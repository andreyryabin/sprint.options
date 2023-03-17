<?php

namespace Sprint\Options\Builder;

use Sprint\Options\Custom\SelectOption;
use Sprint\Options\Custom\StringOption;
use Sprint\Options\Custom\TextareaOption;

class Tab
{
    /**
     * @var Option[]
     */
    private array  $options     = [];
    private string $title       = 'title';
    private string $description = '';

    public function addOption(string $name, array $params = []): Tab
    {
        if (isset($params['OPTIONS'])) {
            $this->options[] = (new SelectOption($name))
                ->setOptions($params['OPTIONS'])
                ->setWidth($params['WIDTH'] ?? '')
                ->setTitle($params['TITLE'] ?? $name)
                ->setDefault($params['DEFAULT'] ?? '');
        } elseif (isset($params['HEIGHT'])) {
            $this->options[] = (new TextareaOption($name))
                ->setHeight($params['HEIGHT'])
                ->setWidth($params['WIDTH'] ?? '')
                ->setTitle($params['TITLE'] ?? $name)
                ->setDefault($params['DEFAULT'] ?? '');
        } else {
            $this->options[] = (new StringOption($name))
                ->setMulty($params['MULTI'] == 'Y')
                ->setWidth($params['WIDTH'] ?? '')
                ->setTitle($params['TITLE'] ?? $name)
                ->setDefault($params['DEFAULT'] ?? '');
        }

        return $this;
    }

    public function addCustomOption(Option $option): Tab
    {
        $this->options[] = $option;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setTitle(string $title): Tab
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): Tab
    {
        $this->description = $description;
        return $this;
    }
}
