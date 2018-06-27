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
                <?= t('ID Prefix') ?>
            </label>
            <?=$form->text('prefix', $prefix) ?>
            <p class="help-block">
                <?=t('Define a unique prefix that is used to identify Seat representations within in the Seat Map.')?>
            </p>
        </div>
    </fieldset>
</div>
