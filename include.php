<?php

use Sprint\Options\Module;

include(__DIR__ . '/locale/ru.php');

if (!function_exists('sprint_options_get')) {
    function sprint_options_get($name, $default = '')
    {
        return Module::getDbOption($name, $default);
    }
}
