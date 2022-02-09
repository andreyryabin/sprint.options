<?php
global $APPLICATION;

use Sprint\Options\Module;

if (!CModule::IncludeModule('sprint.options')) {
    return false;
}

if ($APPLICATION->GetGroupRight('sprint.options') == 'D') {
    return false;
}

$builder = Module::getConfigBuilder();

$items = [];
foreach ($builder->getPages() as $index => $page) {
    $items[] = [
        "text" => $page->getTitle(),
        "url"  => "sprint_options.php?lang=" . LANGUAGE_ID . "&showpage=" . ($index + 1),
    ];
}

$aMenu = [
    "parent_menu" => "global_menu_content",
    "section"     => "Sprint",
    "sort"        => $builder->getSort(),
    "text"        => $builder->getTitle(),
    "icon"        => "sys_menu_icon",
    "page_icon"   => "sys_page_icon",
    "items_id"    => "sprint_options",
    "items"       => $items,
    "more_url"    => [
        "sprint_options.php?lang=" . LANGUAGE_ID,
    ],
];

return $aMenu;
