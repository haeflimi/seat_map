<?php defined('C5_EXECUTE') or die("Access Denied.");
$al = Loader::helper('concrete/asset_library'); ?>
<div class="ccm-ui seat-map" data-bid="<?= $bID ?>">
    <fieldset>
        <div class="form-group">
            <label class="control-label">
                <?= t('Choose a .svg File to use as Seat Map') ?>
            </label>
            <div class="file-selector">
                <?=$al->file('fID', 'fID', t('Select .svg File'), $fID);?>
            </div>
            <p class="help-block">
                <?=t('Do this for it to work')?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?= t('Seat Object Class') ?>
            </label>
            <?=$form->text('class', $class) ?>
            <p class="help-block">
                <?=t('Define a unique class name to identify .svg Objects representing seats.')?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?= t('Allowed Group') ?>
            </label>
            <?=$form->select('gID', $allGroups, $gID);?>
            <p class="help-block">
                <?=t('Only users in this Group are allowed to claim a Seat.')?>
            </p>
        </div>
    </fieldset>
</div>
