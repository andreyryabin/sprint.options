<?php

namespace Sprint\Options\Custom;

use Sprint\Options\Builder\Option;

class SelectOption extends Option
{
    private string $width   = '';
    private array  $options = [];

    public function setWidth(string $width): SelectOption
    {
        $this->width = $width;
        return $this;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function setOptions(array $options): SelectOption
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function render(): string
    {
        $value = $this->getValue();

        $html = '<select ' . $this->getStyle() . ' name="' . $this->getName() . '">';
        foreach ($this->getOptions() as $optVal => $optText) {
            $selected = ($value == $optVal ? 'selected="selected" ' : '');
            $html .= '<option value="' . $optVal . '" ' . $selected . '>' . $optText . '</option>';
        }
        $html .= '</select>';

        return $html;
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
}
