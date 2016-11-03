<?php
$module_id = "sprint.options";

global $APPLICATION;
$MODULE_RIGHT = $APPLICATION->GetGroupRight($module_id);
if (!($MODULE_RIGHT >= "R")) {
    $APPLICATION->AuthForm("ACCESS_DENIED");
}

CModule::IncludeModule($module_id);


?>


<p>Документация к модулю: <br/>
    <a target="_blank" href="https://bitbucket.org/andrey_ryabin/sprint.options/">https://bitbucket.org/andrey_ryabin/sprint.options/</a>
</p>
