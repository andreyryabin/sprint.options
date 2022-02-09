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
            $option = $builder->getOption($optionName); ?>
            <div style="margin-bottom: 10px">
                <?= $option->getTitle() ?> <br/>
                <?php if ($option->isMulti()) { ?>
                    <?php foreach ($option->getValue() as $value) { ?>
                        <p><input style="<?= $option->getStyle() ?>" type="text" value="<?= $value; ?>" name="<?= $option->getName(); ?>[]"/></p>
                    <?php } ?>
                    <?php for ($i = 0; $i < 3; $i++) { ?>
                        <p><input style="<?= $option->getStyle(); ?>" type="text" value="" name="<?= $option->getName(); ?>[]"/></p>
                    <?php } ?>
                <?php } elseif (!empty($option->getOptions())) { ?>
                    <select style="<?= $option->getStyle(); ?>" name="<?= $option->getName(); ?>">
                        <?php foreach ($option->getOptions() as $optVal => $optText) { ?>
                            <option <?php if ($option->getValue() == $optVal){ ?>selected="selected" <?php } ?>value="<?= $optVal; ?>"><?= $optText; ?></option>
                        <?php } ?>
                    </select>
                <?php } elseif (!empty($option->getHeight())) { ?>
                    <textarea style="<?= $option->getStyle(); ?>" name="<?= $option->getName(); ?>"><?= $option->getValue(); ?></textarea>
                <?php } else { ?>
                    <input style="<?= $option->getStyle(); ?>" type="text" value="<?= $option->getValue(); ?>" name="<?= $option->getName(); ?>"/>
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
