<?php

namespace Sprint\Options\Builder;

use COption;

class Option
{
    private $params;
    private $modulename = 'sprint.options';

    public function __construct(string $name, array $params)
    {
        $this->params = array_merge([
            'NAME'    => '',
            'MULTI'   => '',
            'DEFAULT' => '',
            'TITLE'   => '',
            'HEIGHT'  => '',
            'OPTIONS' => [],
        ], $params);

        if (empty($this->params['TITLE'])) {
            $this->params['TITLE'] = $name;
        }

        $this->params['NAME'] = $name;
    }

    public function getName(): string
    {
        return $this->params['NAME'];
    }

    public function isMulti(): bool
    {
        return ($this->params['MULTI'] == 'Y');
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->params['DEFAULT'];
    }

    public function getTitle(): string
    {
        return $this->params['TITLE'];
    }

    public function getStyle(): string
    {
        $style = '';
        if (!empty($this->params['WIDTH'])) {
            $style .= 'width: ' . $this->params['WIDTH'] . 'px;';
        }
        if (!empty($this->params['HEIGHT'])) {
            $style .= 'height: ' . $this->params['HEIGHT'] . 'px;';
        }
        return $style;
    }

    public function getHeight(): string
    {
        return $this->params['HEIGHT'];
    }

    public function getOptions(): array
    {
        return (array)$this->params['OPTIONS'];
    }

    public function getValue()
    {
        $val = COption::GetOptionString($this->modulename, $this->getName(), $this->getDefaultValue());

        if ($this->isMulti()) {
            if (empty($val)) {
                return [];
            }

            return (array)unserialize($val);
        }

        return $val;
    }

    public function setValue($value)
    {
        if ($this->isMulti()) {
            $value = array_filter((array)$value);
            $value = serialize($value);
        }

        COption::SetOptionString($this->modulename, $this->getName(), (string)$value);
    }

    public function resetValue()
    {
        COption::RemoveOption($this->modulename, $this->getName());
    }
}
