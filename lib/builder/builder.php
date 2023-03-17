<?php

namespace Sprint\Options\Builder;

use Sprint\Options\Exception\OptionNotFoundException;
use Sprint\Options\Exception\PageNotFoundException;

class Builder
{
    /**
     * @var Page[]
     */
    private array  $pages      = [];
    private string $title;
    private string $parentMenu = 'global_menu_content';
    private int    $sort       = 50;
    private string $icon       = 'sys_menu_icon';

    public function __construct()
    {
        $this->title = GetMessage('SPRINT_OPTIONS_DEFAULT_TITLE');
    }

    public function addPage(string $title): Builder
    {
        $this->pages[] = (new Page())->setTitle($title);
        return $this;
    }

    public function addTab(string $title, array $params = []): Builder
    {
        $this->getLastPage()->addTab($title, $params);
        return $this;
    }

    public function addOption(string $name, array $params = []): Builder
    {
        if (!empty($params['TAB'])) {
            $this->getLastPage()->getTabByTitle($params['TAB'])->addOption($name, $params);
        } else {
            $this->getLastPage()->getLastTab()->addOption($name, $params);
        }
        return $this;
    }

    public function addCustomOption(Option $option): Builder
    {
        $this->getLastPage()->getLastTab()->addCustomOption($option);

        return $this;
    }

    public function addCustomPage(Page $page): Builder
    {
        $this->pages[] = $page;
        return $this;
    }

    public function addCustomTab(Tab $tab): Builder
    {
        $this->getLastPage()->addCustomTab($tab);
        return $this;
    }

    public function getFirstPage(): Page
    {
        if (empty($this->pages)) {
            $this->addPage(GetMessage('SPRINT_OPTIONS_DEFAULT_PAGE_TITLE'));
        }

        return reset($this->pages);
    }

    public function getLastPage(): Page
    {
        if (empty($this->pages)) {
            $this->addPage(GetMessage('SPRINT_OPTIONS_DEFAULT_PAGE_TITLE'));
        }

        return end($this->pages);
    }

    /**
     * @return Page[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Builder
    {
        $this->title = $title;
        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): Builder
    {
        $this->sort = $sort;
        return $this;
    }

    public function getParentMenu(): string
    {
        return $this->parentMenu;
    }

    public function setParentMenu(string $parentMenu): Builder
    {
        $this->parentMenu = $parentMenu;
        return $this;
    }

    public function setIcon(string $icon): Builder
    {
        $this->icon = $icon;
        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @throws PageNotFoundException
     */
    public function getPage(int $id): Page
    {
        foreach ($this->getPages() as $index => $page) {
            if (($index + 1) == $id) {
                return $page;
            }
        }
        throw new PageNotFoundException();
    }

    public function resetValuesOnPage(Page $page)
    {
        foreach ($page->getTabs() as $tab) {
            foreach ($tab->getOptions() as $option) {
                $option->resetValue();
            }
        }
    }

    public function setValuesOnPage(Page $page, array $postdata)
    {
        foreach ($page->getTabs() as $tab) {
            foreach ($tab->getOptions() as $option) {
                if (isset($postdata[$option->getName()])) {
                    $option->setValue($postdata[$option->getName()]);
                }
            }
        }
    }

    /**
     * @throws OptionNotFoundException
     */
    public function getOptionValue($name)
    {
        foreach ($this->getPages() as $page) {
            foreach ($page->getTabs() as $tab) {
                foreach ($tab->getOptions() as $option) {
                    if ($option->getName() == $name) {
                        return $option->getValue();
                    }
                }
            }
        }
        throw new OptionNotFoundException("Option $name not found");
    }
}
