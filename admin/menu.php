<?
global $APPLICATION;

include(__DIR__ .'/../locale/ru.php');

if ($APPLICATION->GetGroupRight("sprint.options") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_content",
        "section" => "Sprint",
        "sort" => 50,
        "text" => GetMessage('SPRINT_OPTIONS_MENU_ITEM'),
        "icon" => "sys_menu_icon",
        "page_icon" => "sys_page_icon",
        "items_id" => "sprint_options",
        "items" => array(
            array(
                "text" => GetMessage('SPRINT_OPTIONS_MENU_ITEM'),
                "url" => "sprint_options.php?lang=" . LANGUAGE_ID,
            ),

        )
    );

    return $aMenu;
}

return false;
