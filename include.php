<?php

include(__DIR__ .'/loader.php');
include(__DIR__ .'/locale/ru.php');

if (!function_exists('sprint_options_get')){
    function sprint_options_get($name, $default = ''){
        return \Sprint\Options\Module::getDbOption($name, $default);
    }
}
