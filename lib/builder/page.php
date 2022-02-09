<?php

namespace Sprint\Options\Builder;

class Page
{
    /**
     * @var Tab[]
     */
    private $tabs = [];
    private $params;

    public function __construct(string $title, array $params)
    {
        $this->params = array_merge([
            'TITLE' => '',
        ], $params);

        $this->params['TITLE'] = $title;
    }

    public function getTitle(): string
    {
        return $this->params['TITLE'];
    }

    /**
     * @return Tab[]
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    public function addTab(string $title, array $params = []): Page
    {
        $this->tabs[] = new Tab($title, $params);
        return $this;
    }

    public function getLastTab(): Tab
    {
        if (empty($this->tabs)) {
            $this->addTab(GetMessage('SPRINT_OPTIONS_DEFAULT_TAB_TITLE'));
        }

        return end($this->tabs);
    }

    public function getTabByTitle($title): Tab
    {
        $title = $title ? (string)$title : GetMessage('SPRINT_OPTIONS_DEFAULT_TAB_TITLE');

        foreach ($this->tabs as $tab) {
            if ($tab->getTitle() == $title) {
                return $tab;
            }
        }
        $this->addTab($title);
        return end($this->tabs);
    }

    public function getTabControl(): array
    {
        $tabControlTabs = [];

        foreach ($this->getTabs() as $index => $tab) {
            $tabControlTabs[] = [
                'DIV'   => 'tab' . $index,
                'TAB'   => $tab->getTitle(),
                'TITLE' => $tab->getDescription(),
            ];
        }

        return $tabControlTabs;
    }
}
