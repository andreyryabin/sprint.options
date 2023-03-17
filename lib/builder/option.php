<?php

namespace Sprint\Options\Builder;

use COption;
use Sprint\Options\Module;

abstract class Option
{
    private string $name;
    private string $title = '';
    private        $default;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    abstract function render(): string;

    public function setTitle(string $title): Option
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title ?: $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default): Option
    {
        $this->default = $default;
        return $this;
    }

    public function getValue()
    {
        return COption::GetOptionString(
            Module::getModuleName(),
            $this->getName(),
            $this->getDefault()
        );
    }

    public function setValue($value)
    {
        COption::SetOptionString(
            Module::getModuleName(),
            $this->getName(),
            (string)$value
        );
    }

    public function resetValue()
    {
        COption::RemoveOption(
            Module::getModuleName(),
            $this->getName()
        );
    }
}
