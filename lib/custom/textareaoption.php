<?php

namespace Sprint\Options\Custom;

use Sprint\Options\Builder\Option;

class TextareaOption extends Option
{
    private string $width  = '600';
    private string $height = '100';

    public function setWidth(string $width): TextareaOption
    {
        $this->width = $width;
        return $this;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function setHeight(string $height): TextareaOption
    {
        $this->height = $height;
        return $this;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function render(): string
    {
        return '<textarea ' . $this->getStyle() . ' name="' . $this->getName() . '">' . $this->getValue() . '</textarea>';
    }

    protected function getStyle(): string
    {
        $width = $this->getWidth();
        $height = $this->getHeight();

        $style = '';
        if ($width) {
            $style .= 'width: ' . $width . 'px; ';
        }
        if ($height) {
            $style .= 'height: ' . $height . 'px; ';
        }
        if ($style) {
            $style = 'style="' . $style . '"';
        }
        return $style;
    }
}
