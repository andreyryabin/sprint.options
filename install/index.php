<?php

class sprint_options extends CModule
{
    var $MODULE_ID           = "sprint.options";
    var $MODULE_NAME;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $MODULE_GROUP_RIGHTS = "Y";

    function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        include(__DIR__ . '/../locale/ru.php');

        $this->MODULE_NAME = GetMessage("SPRINT_OPTIONS_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("SPRINT_OPTIONS_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = GetMessage("SPRINT_OPTIONS_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("SPRINT_OPTIONS_PARTNER_URI");
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");

        if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface")) {
            CopyDirFiles(__DIR__ . "/php_interface", $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface", false);
        } else {
            CopyDirFiles(__DIR__ . "/php_interface", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface", false);
        }
    }

    function DoUninstall()
    {
        //launch upgrade when reinstalled module
        DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        UnRegisterModule($this->MODULE_ID);
    }
}
