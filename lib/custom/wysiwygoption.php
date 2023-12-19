<?php

declare(strict_types=1);

namespace Sprint\Options\Custom;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CFileMan;
use Sprint\Options\Builder\Option;

class WysiwygOption extends Option
{
    private string $height = '100';

    /**
     * @return string
     * @throws LoaderException
     */
    public function render(): string
    {
        if (!Loader::includeModule('fileman')) {
            ShowError('Модуль "Управление структурой" не установлен');
            return '';
        }

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

    /**
     * @param string $height
     * @return WysiwygOption
     */
    public function setHeight(string $height): WysiwygOption
    {
        $this->height = $height;

        return $this;
    }
}
