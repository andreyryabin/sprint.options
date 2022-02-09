<?php

namespace Sprint\Options\Builder;

class Tab
{
    private $fields = [];
    private $params;

    public function __construct(string $title, array $params)
    {
        $this->params = array_merge([
            'TITLE'       => '',
            'DESCRIPTION' => '',
        ], $params);

        $this->params['TITLE'] = $title;
    }

    public function addField(string $name): Tab
    {
        $this->fields[] = $name;
        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getTitle(): string
    {
        return $this->params['TITLE'];
    }

    public function getDescription(): string
    {
        return $this->params['DESCRIPTION'];
    }
}
