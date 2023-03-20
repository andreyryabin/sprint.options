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
        <?php foreach ($tab->getOptions() as $option) { ?>
            <tr id="tr_<?= $option->getName() ?>">
                <?php if ($option->isWide()) { ?>
                    <td id="td_<?= $option->getName() ?>" colspan="2" class="adm-detail-content-cell-wide">
                        <?= $option->render(); ?>
                    </td>
                <?php } else { ?>
                    <td class="adm-detail-content-cell-l adm-detail-valign-top" style="width: 40%">
                        <?php if ($option->getHint()) { ?>
                            <img title="<?= $option->getHint() ?>" src="/bitrix/js/main/core/images/hint.gif" alt="hint">
                        <?php } ?>
                        <?= $option->getTitle(); ?>:
                    </td>
                    <td id="td_<?= $option->getName() ?>" class="adm-detail-content-cell-r" style="width: 60%">
                        <?= $option->render(); ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    <?php } ?>
    <?php $tabControl1->Buttons(); ?>
    <input class="adm-btn-green" type="submit" name="opts_save" value="<?= GetMessage('SPRINT_OPTIONS_SAVE_BTN'); ?>">
    <input type="submit" class="adm-btn" name="opts_reset" value="<?= GetMessage('SPRINT_OPTIONS_RESET_BTN'); ?>">
    <?= bitrix_sessid_post(); ?>
    <?php $tabControl1->End(); ?>
</form>
