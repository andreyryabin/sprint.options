<?php
global $APPLICATION;

if (!CModule::IncludeModule('sprint.options')) {
    return false;
}

if ($APPLICATION->GetGroupRight('sprint.options') == 'D') {
    return false;
}

include(__DIR__ . '/../locale/ru.php');

$aMenu = [
    "parent_menu" => "global_menu_content",
    "section"     => "Sprint",
    "sort"        => 50,
    "text"        => GetMessage('SPRINT_OPTIONS_MENU_ITEM'),
    "icon"        => "sys_menu_icon",
    "page_icon"   => "sys_page_icon",
    "items_id"    => "sprint_options",
    "items"       => [
        [
            "text" => GetMessage('SPRINT_OPTIONS_MENU_ITEM'),
            "url"  => "sprint_options.php?lang=" . LANGUAGE_ID,
        ],

    ],
];

return $aMenu;
