<?php

namespace Sprint\Options\Builder;

use Sprint\Options\Exception\OptionNotFoundException;
use Sprint\Options\Exception\PageNotFoundException;

class Builder
{
    /**
     * @var Page[]
     */
    private $pages = [];
    /**
     * @var Option[]
     */
    private $options = [];
    private $params;

    public function __construct(array $params = [])
    {
        $this->params = array_merge([
            'TITLE'       => GetMessage('SPRINT_OPTIONS_DEFAULT_TITLE'),
            'PARENT_MENU' => 'global_menu_content',
            'SORT'        => 50,
        ], $params);
    }

    public function addPage(string $title, array $params = []): Builder
    {
        $this->pages[] = new Page($title, $params);
        return $this;
    }

    public function addTab(string $title, array $params = []): Builder
    {
        $this->getLastPage()->addTab($title, $params);
        return $this;
    }

    public function addOption(string $name, array $params = []): Builder
    {
        if (!isset($this->options[$name])) {
            $this->options[$name] = new Option($name, $params);
            if (!empty($params['TAB'])) {
                $this->getLastPage()->getTabByTitle($params['TAB'])->addField($name);
            } else {
                $this->getLastPage()->getLastTab()->addField($name);
            }
        }
        return $this;
    }

    public function getFirstPage()
    {
        if (empty($this->pages)) {
            $this->addPage(GetMessage('SPRINT_OPTIONS_DEFAULT_PAGE_TITLE'));
        }

        return reset($this->pages);
    }

    public function getLastPage()
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
        return $this->params['TITLE'];
    }

    public function setTitle(string $title): Builder
    {
        $this->params['TITLE'] = $title;
        return $this;
    }

    public function getSort()
    {
        return $this->params['SORT'];
    }

    public function setSort(int $sort): Builder
    {
        $this->params['SORT'] = $sort;
        return $this;
    }

    public function getParentMenu()
    {
        return $this->params['PARENT_MENU'];
    }

    public function setParentMenu(string $parentMenu): Builder
    {
        $this->params['PARENT_MENU'] = $parentMenu;
        return $this;
    }

    /**
     * @param string $name
     *
     * @throws OptionNotFoundException
     * @return Option
     */
    public function getOption(string $name): Option
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        throw new OptionNotFoundException();
    }

    /**
     * @param int $id
     *
     * @throws PageNotFoundException
     * @return Page
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

    /**
     * @return Option[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @throws OptionNotFoundException
     */
    public function resetValuesOnPage(Page $page)
    {
        foreach ($page->getTabs() as $tab) {
            foreach ($tab->getFields() as $optionName) {
                $this->getOption($optionName)->resetValue();
            }
        }
    }

    /**
     * @throws OptionNotFoundException
     */
    public function setValuesOnPage(Page $page, array $postdata)
    {
        foreach ($page->getTabs() as $tab) {
            foreach ($tab->getFields() as $optionName) {
                if (isset($postdata[$optionName])) {
                    $this->getOption($optionName)->setValue($postdata[$optionName]);
                }
            }
        }
    }
}
