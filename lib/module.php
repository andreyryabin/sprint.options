<?php

namespace Sprint\Options;

use Sprint\Options\Builder\Builder;
use Sprint\Options\Exception\OptionNotFoundException;

class Module
{
    protected static string   $modulename    = 'sprint.options';
    protected static ?Builder $configBuilder = null;
    protected static array    $optionsMap    = [];

    protected static function getDocRoot(): string
    {
        return rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);
    }

    public static function getPhpInterfaceDir(): string
    {
        if (is_dir(self::getDocRoot() . '/local/php_interface')) {
            return self::getDocRoot() . '/local/php_interface';
        } else {
            return self::getDocRoot() . '/bitrix/php_interface';
        }
    }

    public static function getModuleDir(): string
    {
        if (is_file(self::getDocRoot() . '/local/modules/' . self::$modulename . '/include.php')) {
            return self::getDocRoot() . '/local/modules/' . self::$modulename;
        } else {
            return self::getDocRoot() . '/bitrix/modules/' . self::$modulename;
        }
    }

    /**
     * @throws OptionNotFoundException
     */
    public static function getOptionValue($name)
    {
        if (empty(self::$optionsMap)) {
            self::$optionsMap = [];
            foreach (self::getConfigBuilder()->getPages() as $page) {
                foreach ($page->getTabs() as $tab) {
                    foreach ($tab->getOptions() as $option) {
                        self::$optionsMap[$option->getName()] = $option;
                    }
                }
            }
        }

        if (isset(self::$optionsMap[$name])) {
            return self::$optionsMap[$name]->getValue();
        }
        throw new OptionNotFoundException("Option \"$name\" not found");
    }

    public static function getConfigBuilder(): Builder
    {
        if (!is_null(self::$configBuilder)) {
            return self::$configBuilder;
        }

        self::$configBuilder = new Builder();

        $file = self::getPhpInterfaceDir() . '/sprint.options.php';
        if (is_file($file)) {
            $builder = include $file;

            if ($builder instanceof Builder) {
                self::$configBuilder = $builder;
            } elseif (is_array($builder)) {
                foreach ($builder as $name => $params) {
                    self::$configBuilder->addOption($name, $params);
                }
            }
        }

        return self::$configBuilder;
    }

    public static function getModuleName(): string
    {
        return self::$modulename;
    }
}
