<?php

namespace Sprint\Options\Builder;

class Page
{
    /**
     * @var Tab[]
     */
    private array  $tabs  = [];
    private string $title = 'page';

    public function setTitle(string $title): Page
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
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
        $this->tabs[] = (new Tab())
            ->setTitle($title)
            ->setDescription($params['DESCRIPTION'] ?? '');
        return $this;
    }

    public function addCustomTab(Tab $tab): Page
    {
        $this->tabs[] = $tab;
        return $this;
    }

    public function addOption(string $name, array $params = []): Page
    {
        if (!empty($params['TAB'])) {
            $this->getTabByTitle($params['TAB'])->addOption($name, $params);
        } else {
            $this->getLastTab()->addOption($name, $params);
        }
        return $this;
    }

    public function addCustomOption(Option $option): Page
    {
        $this->getLastTab()->addCustomOption($option);

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
