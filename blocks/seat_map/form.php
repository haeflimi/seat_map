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
                <?=t('Only users in this Group are allowed to claim a Seat and Searchable in the Search Field')?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?=$form->checkbox('showSearch', '1', ($showSearch == '1')?1:0 );?>
                <?= t('Show Search') ?>

            </label>
            <p class="help-block">
                <?=t('Display a Search field that highlights results in the Map and on the List.')?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?=$form->checkbox('showList', '1', ($showList == '1')?1:0 );?>
                <?= t('Show List') ?>
            </label>
            <p class="help-block">
                <?=t('Display a Participant list alongside the Seatmap.')?>
            </p>
        </div>
    </fieldset>
</div>
