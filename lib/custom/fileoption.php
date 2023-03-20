<?php

namespace Sprint\Options\Custom;

use CFile;
use CIBlock;
use Sprint\Options\Builder\Option;
use Sprint\Options\Module;

class FileOption extends Option
{
    public const IMAGES = 'I';
    public const FILES  = 'A';
    private int    $maxCount         = 1;
    private string $allowUpload      = 'I';
    private bool   $allowEdit        = true;
    private bool   $allowDescription = true;

    public function render(): string
    {
        if ($this->maxCount == 1) {
            $name = $this->getName();
            $value = $this->getValue();
        } else {
            $name = $this->getName() . '[n#IND#]';
            $value = [];
            foreach ($this->getValue() as $fileId) {
                $value[$this->getName() . "[$fileId]"] = $fileId;
            }
        }

        return $this->renderInput($name, $value);
    }

    public function setAllowFiles(int $maxCount = 0): FileOption
    {
        $this->allowUpload = self::FILES;
        $this->maxCount = $maxCount;
        return $this;
    }

    public function setAllowImages(int $maxCount = 0): FileOption
    {
        $this->allowUpload = self::IMAGES;
        $this->maxCount = $maxCount;
        return $this;
    }

    public function getValue()
    {
        $value = parent::getValue();
        if ($this->maxCount == 1) {
            return $value;
        }
        return ($value) ? explode(',', $value) : [];
    }

    public function setValue($value)
    {
        if ($this->maxCount == 1) {
            $fileId = $this->setValueFromRequest();
            if (empty($fileId)) {
                $this->resetValue();
            }
            parent::setValue($fileId);
            return;
        }

        $fileIds = $this->setMultiValueFromRequest();
        $fileIds = array_filter($fileIds);

        if (empty($fileIds)) {
            $this->resetValue();
        }

        parent::setValue(implode(',', $fileIds));
    }

    protected function setValueFromRequest(): int
    {
        $name = $this->getName();
        $value = array_key_exists($name, $_FILES) ? $_FILES[$name] : $_REQUEST[$name];

        $del = $_REQUEST[$name . '_del'] === 'Y';
        $descr = $_REQUEST[$name . '_descr'] ?? '';

        return $this->saveFile($value, $del, $descr);
    }

    protected function setMultiValueFromRequest(): array
    {
        $name = $this->getName();
        $multiValue = array_key_exists($name, $_FILES) ? $_FILES[$name] : $_REQUEST[$name];

        $fileIds = [];
        foreach ($multiValue as $key => $value) {
            $del = $_REQUEST[$name . '_del'][$key] === 'Y';
            $descr = $_REQUEST[$name . '_descr'][$key] ?? '';

            $fileIds[] = $this->saveFile($value, $del, $descr);
        }
        return $fileIds;
    }

    protected function saveFile($data, $del, $descr): int
    {
        if (is_numeric($data) && !$del) {
            CFile::UpdateDesc($data, $descr);
            return $data;
        }

        if (is_numeric($data) && $del) {
            CFile::Delete($data);
            return 0;
        }

        $file = CIBlock::makeFileArray($data, $del, $descr);
        $fileId = CFile::SaveFile($file, Module::getModuleName());

        return is_numeric($fileId) ? $fileId : 0;
    }

    protected function renderInput($name, $value): string
    {
        return \Bitrix\Main\UI\FileInput::createInstance([
            "name"        => $name,
            "id"          => $name . mt_rand(99, 99999),
            "description" => $this->allowDescription,
            "edit"        => $this->allowEdit,
            "upload"      => true,
            "allowUpload" => $this->allowUpload,
            "medialib"    => true,
            "fileDialog"  => true,
            "cloud"       => false,
            "delete"      => true,
            "maxCount"    => $this->maxCount,
        ])->show($value);
    }

    public function setAllowEdit(bool $allowEdit): FileOption
    {
        $this->allowEdit = $allowEdit;
        return $this;
    }

    public function setAllowDescription(bool $allowDescription): FileOption
    {
        $this->allowDescription = $allowDescription;
        return $this;
    }

    public function isAllowEdit(): bool
    {
        return $this->allowEdit;
    }

    public function isAllowDescription(): bool
    {
        return $this->allowDescription;
    }
}
