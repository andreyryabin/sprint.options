<?php

namespace Sprint\Options;

use COption;

class Module
{
    protected static $modulename = 'sprint.options';
    protected static $configCache = [];

    protected static function getDocRoot()
    {
        return rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);
    }

    public static function getPhpInterfaceDir()
    {
        if (is_dir(self::getDocRoot() . '/local/php_interface')) {
            return self::getDocRoot() . '/local/php_interface';
        } else {
            return self::getDocRoot() . '/bitrix/php_interface';
        }
    }

    public static function getModuleDir()
    {
        if (is_file(self::getDocRoot() . '/local/modules/' . self::$modulename . '/include.php')) {
            return self::getDocRoot() . '/local/modules/' . self::$modulename;
        } else {
            return self::getDocRoot() . '/bitrix/modules/' . self::$modulename;
        }
    }

    public static function getDbOption($name, $default = '')
    {
        $val = COption::GetOptionString(self::$modulename, $name, null);

        if (is_null($val)) {
            $opts = self::getOptionsConfig();
            $val = isset($opts[$name]) ? $opts[$name]['DEFAULT'] : $default;
        }

        if (static::isMulti($name)) {
            $val = static::getMultiValue($val);
        }

        return $val;
    }

    public static function isMulti($name)
    {
        $opts = self::getOptionsConfig();
        return $opts[$name]['MULTI'] == 'Y';
    }

    public static function getMultiValue($value)
    {
        if (empty($value)) {
            return [];
        }

        return (array)unserialize($value);
    }

    public static function packValue($value)
    {
        $value = array_filter((array)$value);
        return serialize($value);
    }

    public static function setDbOption($name, $value)
    {
        if (static::isMulti($name)) {
            $value = static::packValue($value);
        }

        if ($value != COption::GetOptionString(self::$modulename, $name, '')) {
            COption::SetOptionString(self::$modulename, $name, $value);
        }
    }

    public static function resetDbOptions()
    {
        $options = self::getOptionsConfig();
        foreach ($options as $name => $opt) {
            COption::RemoveOption(self::$modulename, $name);
        }
    }

    public static function getOptionsConfig()
    {
        if (empty(self::$configCache)) {
            $file = self::getPhpInterfaceDir() . '/sprint.options.php';
            if (is_file($file)) {
                self::$configCache = include $file;
                self::$configCache = (self::$configCache && is_array(self::$configCache)) ? self::$configCache : [];
            }
        }
        return self::$configCache;
    }
}
