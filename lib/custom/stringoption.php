<?php

namespace Sprint\Options\Custom;

use Sprint\Options\Builder\Option;

class StringOption extends Option
{
    private bool   $multy = false;
    private string $width = '400';

    public function isMulti(): bool
    {
        return $this->multy;
    }

    public function setMulty(bool $multy): StringOption
    {
        $this->multy = $multy;
        return $this;
    }

    public function setWidth(string $width): StringOption
    {
        $this->width = $width;
        return $this;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function getValue()
    {
        $value = parent::getValue();
        if ($this->isMulti()) {
            if (empty($value)) {
                return [];
            }
            //defaultValue
            if (is_array($value)) {
                return $value;
            }
            return (array)unserialize($value);
        }
        return $value;
    }

    public function setValue($value)
    {
        if ($this->isMulti()) {
            $value = array_filter((array)$value);
            $value = serialize($value);
        }

        parent::setValue($value);
    }

    public function render(): string
    {
        if ($this->isMulti()) {
            return $this->renderMulti();
        }

        return $this->renderSimple();
    }

    protected function getStyle(): string
    {
        $width = $this->getWidth();
        $style = '';
        if ($width) {
            $style .= 'width: ' . $width . 'px; ';
        }
        if ($style) {
            $style = 'style="' . $style . '"';
        }
        return $style;
    }

    private function renderSimple(): string
    {
        return '<input ' . $this->getStyle() . ' type="text" value="' . $this->getValue() . '" name="' . $this->getName() . '"/>';
    }

    private function renderMulti(): string
    {
        $html = '';
        foreach ($this->getValue() as $value) {
            $html .= '<input ' . $this->getStyle() . ' type="text" value="' . $value . '" name="' . $this->getName() . '[]"/><br/>';
        }
        $html .= str_repeat('<input ' . $this->getStyle() . ' type="text" value="" name="' . $this->getName() . '[]"/><br/>', 3);
        return $html;
    }
}
