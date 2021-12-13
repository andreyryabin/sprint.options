<?php

use Sprint\Options\Module;

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php";

global $APPLICATION;
if (!CModule::IncludeModule('sprint.options')) {
    echo 'need to install module sprint.options';
    return false;
}
if ($APPLICATION->GetGroupRight('sprint.options') == 'D') {
    echo 'access denied to module sprint.options';
    return false;
}
global $APPLICATION;
$APPLICATION->SetTitle(GetMessage('SPRINT_OPTIONS_TITLE'));

require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php";

$actionUrl = $APPLICATION->GetCurPage() . '?' . http_build_query(['lang' => LANGUAGE_ID,]);

$optionsConfig = Module::getOptionsConfig();
if ($_SERVER['REQUEST_METHOD'] == "POST" && check_bitrix_sessid()) {
    CUtil::JSPostUnescape();
    if (isset($_REQUEST['opts_reset'])) {
        Module::resetDbOptions();
    } elseif (isset($_REQUEST['opts_save'])) {
        foreach ($optionsConfig as $name => $aOption) {
            if (isset($_REQUEST[$name])) {
                Module::setDbOption($name, $_REQUEST[$name]);
            }
        }
    }

    LocalRedirect($actionUrl);
}

$tabOptionsTabs = [];
$tabControlTabs = [];

foreach ($optionsConfig as $name => $aOption) {
    $aOption['NAME'] = $name;
    $aOption['VALUE'] = Module::getDbOption($name);

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
        $tabOptionsTabs[$tabName] = [];
        $tabControlTabs[] = [
            'DIV'   => 'tab' . $tabIndex,
            'TAB'   => $tabName,
            'TITLE' => $tabName,
        ];
    }

    $tabOptionsTabs[$tabName][] = $aOption;
}
$tabControl1 = new CAdminTabControl("tabControl2", $tabControlTabs);

?>
    <form method="post" action="<?= $actionUrl ?>">
        <?php $tabControl1->Begin(); ?>
        <?php foreach ($tabOptionsTabs as $tabOptions) { ?>
            <?php $tabControl1->BeginNextTab(); ?>
            <?php foreach ($tabOptions as $aOption) { ?>
                <div style="margin-bottom: 10px">
                    <?= $aOption['TITLE']; ?> <br/>
                    <?php if ($aOption['MULTI'] == 'Y') { ?>
                        <?php foreach ($aOption['VALUE'] as $value) { ?>
                            <p><input style="<?= $aOption['STYLE']; ?>" type="text" value="<?= $value; ?>" name="<?= $aOption['NAME']; ?>[]"/></p>
                        <?php } ?>
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                            <p><input style="<?= $aOption['STYLE']; ?>" type="text" value="" name="<?= $aOption['NAME']; ?>[]"/></p>
                        <?php } ?>
                    <?php } elseif (!empty($aOption['OPTIONS'])) { ?>
                        <select style="<?= $aOption['STYLE']; ?>" name="<?= $aOption['NAME']; ?>">
                            <?php foreach ($aOption['OPTIONS'] as $optVal => $optText) { ?>
                                <option <?php if ($aOption['VALUE'] == $optVal){ ?>selected="selected" <?php } ?>value="<?= $optVal; ?>"><?= $optText; ?></option>
                            <?php } ?>
                        </select>
                    <?php } elseif (!empty($aOption['HEIGHT'])) { ?>
                        <textarea style="<?= $aOption['STYLE']; ?>" name="<?= $aOption['NAME']; ?>"><?= $aOption['VALUE']; ?></textarea>
                    <?php } else { ?>
                        <input style="<?= $aOption['STYLE']; ?>" type="text" value="<?= $aOption['VALUE']; ?>" name="<?= $aOption['NAME']; ?>"/>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
        <?php $tabControl1->Buttons(); ?>
        <input class="adm-btn-green" type="submit" name="opts_save" value="<?= GetMessage('SPRINT_OPTIONS_SAVE_BTN'); ?>">
        <input type="submit" class="adm-btn" name="opts_reset" value="<?= GetMessage('SPRINT_OPTIONS_RESET_BTN'); ?>">
        <?= bitrix_sessid_post(); ?>
        <?php $tabControl1->End(); ?>
    </form>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
