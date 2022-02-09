<?php

namespace Sprint\Options;

use Sprint\Options\Builder\Builder;
use Sprint\Options\Exception\OptionNotFoundException;

class Module
{
    protected static $modulename = 'sprint.options';
    /**
     * @var Builder
     */
    protected static $configBuilder;

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

    public static function getDbOption(string $name, $default = '')
    {
        try {
            return self::getConfigBuilder()->getOption($name)->getValue();
        } catch (OptionNotFoundException $e) {
            return $default;
        }
    }

    public static function setDbOption(string $name, $value)
    {
        try {
            self::getConfigBuilder()->getOption($name)->setValue($value);
        } catch (OptionNotFoundException $e) {
        }
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
}
