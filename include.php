<?php

use Sprint\Options\Exception\OptionNotFoundException;
use Sprint\Options\Module;

include(__DIR__ . '/locale/ru.php');

if (!function_exists('sprint_options_get')) {
    function sprint_options_get($name, $default = '')
    {
        try {
            return Module::getOptionValue($name);
        } catch (OptionNotFoundException $e) {
        }

        return $default;
    }
}
