<?php

declare(strict_types = 1);

namespace Sprint\Options\Custom;

use Sprint\Options\Builder\Option;

class CheckboxOption extends Option
{
    public function render(): string
    {
        return '<input name="' . $this->getName() . '" value="0" type="hidden"/>
        <input name="' . $this->getName() . '" value="1" ' . $this->checked() . ' type="checkbox"/>';
    }

    private function checked(): string
    {
        return $this->getValue() === '1' ? 'checked' : '';
    }
}
