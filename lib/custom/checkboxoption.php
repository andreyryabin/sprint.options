<?php

declare(strict_types=1);

namespace Sprint\Options\Custom;

use Sprint\Options\Builder\Option;

class CheckboxOption extends Option
{
    /**
     * @return string
     */
    public function render(): string
    {
        return '<input name="' . $this->getName() . '" value="0" type="hidden"/>
        <input name="' . $this->getName() . '" value="1" ' . $this->checked($this->getValue()) . ' type="checkbox"/>';
    }

    /**
     * @param false|string|null $value
     * @return string
     */
    private function checked($value): string
    {
        return $value === '1' ? 'checked' : '';
    }
}
