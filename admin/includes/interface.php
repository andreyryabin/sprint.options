<?php

use Sprint\Options\Exception\PageNotFoundException;
use Sprint\Options\Module;

/** @global $APPLICATION CMain */
global $APPLICATION;

if (!CModule::IncludeModule('sprint.options')) {
    throw new Exception('need to install module sprint.options');
}
if ($APPLICATION->GetGroupRight('sprint.options') == 'D') {
    throw new Exception('access denied to module sprint.options');
}

$builder = Module::getConfigBuilder();

try {
    $builderPage = $builder->getPage((int)($_REQUEST['showpage'] ?? 1));
} catch (PageNotFoundException $e) {
    $builderPage = $builder->getFirstPage();
}

$APPLICATION->SetTitle($builderPage->getTitle());

if ($_SERVER['REQUEST_METHOD'] == "POST" && check_bitrix_sessid()) {
    CUtil::JSPostUnescape();
    if (isset($_REQUEST['opts_reset'])) {
        $builder->resetValuesOnPage($builderPage);
    } elseif (isset($_REQUEST['opts_save'])) {
        $builder->setValuesOnPage($builderPage, $_POST);
    }
}

$tabControl1 = new CAdminTabControl("tabControl2", $builderPage->getTabControl()); ?>
<form method="post" action="">
    <?php $tabControl1->Begin(); ?>
    <?php foreach ($builderPage->getTabs() as $tab) { ?>
        <?php $tabControl1->BeginNextTab(); ?>
        <?php foreach ($tab->getFields() as $optionName) {
            $option = $builder->getOption($optionName);
            ?>
            <tr id="tr_<?= $option->getName() ?>">
                <td class="adm-detail-content-cell-l" style="width: 40%">
                    <?= $option->getTitle(); ?>:
                </td>
                <td class="adm-detail-content-cell-r" style="width: 60%">
                    <?= $option->render(); ?>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>
    <?php $tabControl1->Buttons(); ?>
    <input class="adm-btn-green" type="submit" name="opts_save" value="<?= GetMessage('SPRINT_OPTIONS_SAVE_BTN'); ?>">
    <input type="submit" class="adm-btn" name="opts_reset" value="<?= GetMessage('SPRINT_OPTIONS_RESET_BTN'); ?>">
    <?= bitrix_sessid_post(); ?>
    <?php $tabControl1->End(); ?>
</form>
