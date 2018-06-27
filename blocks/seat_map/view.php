<div id="seat-map-<?=$bID?>" class="seat-map-wrapper">
    <?php echo $svgMap ?>
    <div class="seat-map-reservations hidden">
        <?php foreach($reservations as $key => $u):?>
            <div id="<?=$prefix.$key.'_details'?>" class="seat-map-reservation-item"
                 data-uid="<?=$u->getUserId()?>"
                 data-seat="<?=$key?>"
                 data-svgelement="<?=$prefix.$key?>"
                 data-myseat="<?=($key == $mySeat)?true:false;?>">
                <p><?=$u->getUserName()?></p>
                <p><?=t('Seat')?>: <b><?=$key?></b></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>


