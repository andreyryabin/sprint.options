<?php

declare(strict_types = 1);

namespace Sprint\Options\Custom;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CFileMan;
use Sprint\Options\Builder\Option;

class WysiwygOption extends Option
{
    private string $height = '100';

    public function render(): string
    {
        try {
            if (Loader::includeModule('fileman')) {
                ob_start();

                CFileMan::AddHTMLEditorFrame(
                    $this->getName(),
                    $this->getValue(),
                    'html',
                    'html',
                    ['height' => $this->height]
                );

                return ob_get_clean();
            }
        } catch (LoaderException $e) {
            return $this->showError($e->getMessage());
        }

        return $this->showError(GetMessage('SPRINT_OPTIONS_ERR_FILEMAN'));
    }

    protected function showError($message): string
    {
        return '<span class="errortext">' . nl2br($message) . '</span>';
    }

    public function setHeight(string $height): WysiwygOption
    {
        $this->height = $height;

        return $this;
    }
}
