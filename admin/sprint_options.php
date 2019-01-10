<?php

/** @noinspection PhpIncludeInspection */
require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php";

\CModule::IncludeModule("sprint.options");

/** @global $APPLICATION \CMain */

global $APPLICATION;
$APPLICATION->SetTitle(GetMessage('SPRINT_OPTIONS_TITLE'));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    CUtil::JSPostUnescape();
}

/** @noinspection PhpIncludeInspection */
require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php";

$optionsConfig = \Sprint\Options\Module::getOptionsConfig();
if ($_SERVER['REQUEST_METHOD'] == "POST" && check_bitrix_sessid()) {
    if (isset($_REQUEST['opts_reset'])) {
        \Sprint\Options\Module::resetDbOptions();
    } elseif (isset($_REQUEST['opts_save'])) {
        foreach ($optionsConfig as $name => $aOption) {
            if (isset($_REQUEST[$name])) {
                \Sprint\Options\Module::setDbOption($name, $_REQUEST[$name]);
            }
        }
    }
}

$tabOptionsTabs = array();
$tabControlTabs = array();

foreach ($optionsConfig as $name => $aOption) {
    $aOption['NAME'] = $name;
    $aOption['VALUE'] = \Sprint\Options\Module::getDbOption($name);

    $aOption['STYLE'] = '';
    if (!empty($aOption['WIDTH'])) {
        $aOption['STYLE'] .= 'width: ' . $aOption['WIDTH'] . 'px;';
    }
    if (!empty($aOption['HEIGHT'])) {
        $aOption['STYLE'] .= 'height: ' . $aOption['HEIGHT'] . 'px;';
    }

    $tabName = (empty($aOption['TAB'])) ? GetMessage('SPRINT_OPTIONS_TAB1') : $aOption['TAB'];
    if (!isset($tabOptionsTabs[$tabName])) {
        $tabIndex = count($tabControlTabs) + 1;
        $tabOptionsTabs[$tabName] = array();
        $tabControlTabs[] = array(
            'DIV' => 'tab' . $tabIndex, 'TAB' => $tabName, 'TITLE' => $tabName,
        );
    }

    $tabOptionsTabs[$tabName][] = $aOption;
}
?>

<? $tabControl1 = new CAdminTabControl("tabControl2", $tabControlTabs); ?>

<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid_menu=1&mid=<?=urlencode($module_id);?>&lang=<?=LANGUAGE_ID;?>">
    <? $tabControl1->Begin(); ?>
    <? foreach ($tabOptionsTabs as $tabOptions): ?>
        <? $tabControl1->BeginNextTab(); ?>
        <? foreach ($tabOptions as $aOption):?>
            <p>
                <?=$aOption['TITLE'];?> <br/>
                <? if ($aOption['MULTI'] == 'Y'):  ?>
                    <? foreach($aOption['VALUE'] as $value): ?>
                        <p><input style="<?=$aOption['STYLE'];?>" type="text" value="<?=$value;?>" name="<?=$aOption['NAME'];?>[]"/></p>
                    <? endforeach;?>
                    <? for($i = 0; $i < 3; $i++): ?>
                        <p><input style="<?=$aOption['STYLE'];?>" type="text" value="" name="<?=$aOption['NAME'];?>[]"/></p>
                    <? endfor; ?>
                <? elseif (!empty($aOption['OPTIONS'])):  ?>
                    <select style="<?=$aOption['STYLE'];?>" name="<?=$aOption['NAME'];?>">
                        <? foreach ($aOption['OPTIONS'] as $optVal => $optText): ?>
                            <option <? if ($aOption['VALUE'] == $optVal): ?>selected="selected"<? endif; ?>value="<?=$optVal;?>"><?=$optText;?></option>
                        <? endforeach ?>
                    </select>
                <? elseif (!empty($aOption['HEIGHT'])): ?>
                    <textarea style="<?=$aOption['STYLE'];?>" name="<?=$aOption['NAME'];?>"><?=$aOption['VALUE'];?></textarea>
                <? else: ?>
                    <input style="<?=$aOption['STYLE'];?>" type="text" value="<?=$aOption['VALUE'];?>" name="<?=$aOption['NAME'];?>"/>
                <? endif ?>
            </p>
        <? endforeach ?>
    <? endforeach ?>
    <? $tabControl1->Buttons(); ?>
    <input class="adm-btn-green" type="submit" name="opts_save" value="<?=GetMessage('SPRINT_OPTIONS_SAVE_BTN');?>">
    <input type="submit" class="adm-btn" name="opts_reset" value="<?=GetMessage('SPRINT_OPTIONS_RESET_BTN');?>">
    <?=bitrix_sessid_post();?>
</form>

<? $tabControl1->End(); ?>

<? /** @noinspection PhpIncludeInspection */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>