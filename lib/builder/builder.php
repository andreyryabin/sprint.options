<?php

namespace Sprint\Options\Builder;

use Sprint\Options\Custom\SelectOption;
use Sprint\Options\Custom\StringOption;
use Sprint\Options\Custom\TextareaOption;
use Sprint\Options\Exception\OptionNotFoundException;
use Sprint\Options\Exception\PageNotFoundException;

class Builder
{
    /**
     * @var Page[]
     */
    private array $pages = [];
    /**
     * @var Option[]
     */
    private array $options = [];
    private array $params;

    public function __construct(array $params = [])
    {
        $this->params = array_merge([
            'TITLE'       => GetMessage('SPRINT_OPTIONS_DEFAULT_TITLE'),
            'PARENT_MENU' => 'global_menu_content',
            'SORT'        => 50,
            'ICON'        => 'sys_menu_icon',
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

    /**
     * @param string $name
     * @param array  $params
     *
     * @return $this
     */
    public function addOption(string $name, array $params = []): Builder
    {
        if (!isset($this->options[$name])) {
            if (isset($params['OPTIONS'])) {
                $this->options[$name] = (new SelectOption($name))
                    ->setOptions($params['OPTIONS'])
                    ->setWidth($params['WIDTH'] ?? '')
                    ->setTitle($params['TITLE'] ?? $name)
                    ->setDefault($params['DEFAULT'] ?? '');
            } elseif (isset($params['HEIGHT'])) {
                $this->options[$name] = (new TextareaOption($name))
                    ->setHeight($params['HEIGHT'])
                    ->setWidth($params['WIDTH'] ?? '')
                    ->setTitle($params['TITLE'] ?? $name)
                    ->setDefault($params['DEFAULT'] ?? '');
            } else {
                $this->options[$name] = (new StringOption($name))
                    ->setMulty($params['MULTI'] == 'Y')
                    ->setWidth($params['WIDTH'] ?? '')
                    ->setTitle($params['TITLE'] ?? $name)
                    ->setDefault($params['DEFAULT'] ?? '');
            }
            if (!empty($params['TAB'])) {
                $this->getLastPage()->getTabByTitle($params['TAB'])->addField($name);
            } else {
                $this->getLastPage()->getLastTab()->addField($name);
            }
        }
        return $this;
    }

    public function addCustom(Option $option): Builder
    {
        $name = $option->getName();

        $this->options[$name] = $option;

        $this->getLastPage()->getLastTab()->addField($name);

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

    public function setIcon(string $icon): Builder
    {
        $this->params['ICON'] = $icon;
        return $this;
    }

    public function getIcon()
    {
        return $this->params['ICON'];
    }

    /**
     * @throws OptionNotFoundException
     */
    public function getOption(string $name): Option
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        throw new OptionNotFoundException();
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
