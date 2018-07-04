<div id="seat-map-<?=$bID?>" class="seat-map-wrapper">
    <?php echo $svgMap ?>
    <div class="seat-map-reservations hidden" data-class="<?=$class?>">
        <?php foreach($reservations as $key => $u):?>
            <div id="<?=$key.'_details'?>" class="seat-map-reservation-item"
                 data-uid="<?=$u->getUserId()?>"
                 data-seat="<?=$key?>"
                 data-svgelement="<?=$key?>"
                 data-myseat="<?=($key == $mySeat)?true:false;?>">
                <p><?=$u->getUserName()?></p>
                <p><?=t('Seat')?>: <b><?=$key?></b></p>
            </div>
        <?php endforeach; ?>
    </div>
    <style>
        .seat-map-wrapper svg .<?=$class?> *{
            fill: green;
            cursor: pointer;
        }
        .seat-map-wrapper svg .<?=$class?>.taken *{
            fill: red;
            cursor: pointer;
        }
        .seat-map-wrapper svg .<?=$class?>.my *{
            fill: orange;
            cursor: pointer;
        }
    </style>
</div>


