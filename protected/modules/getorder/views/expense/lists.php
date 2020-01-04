<? $prices = new Prices(); $dates = date('Y-m-d')?>
<div id="<?=$count?>">
    <div class="row">
    <? foreach($newModel1 as $val){ ?>
      <div class="col-xs-6 col-md-2">
        <div id="dish_<?=$val["dish_id"]?>" class="thumbnail plus">
          <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$val["name"]?>" />
          <span class="texts">
            <?=$val["name"]?>
          </span>
          <div><?=$prices->getPrice($val["just_id"],$val["mType"],1,$dates);?></div>
        </div>
      </div>
    <?}?>
    <? foreach($newModel2 as $val){ ?>
      <div class="col-xs-6 col-md-2">
        <div id="stuff_<?=$val["halfstuff_id"]?>" class="thumbnail plus">
          <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$val["name"]?>" />
          <span class="texts">
            <?=$val["name"]?>
          </span>
            <div><?=$prices->getPrice($val["just_id"],$val["mType"],2,$dates)?></div>
        </div>
      </div>
    <?}?>
    <? foreach($newModel3 as $val){?>
      <div class="col-xs-6 col-md-2">
        <div id="product_<?=$val["product_id"]?>" class="thumbnail plus ">
          <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$val["name"]?>" />
          <span class="texts">
            <?=$val["name"]?>
          </span>
            <div><?=$prices->getPrice($val["just_id"],$val["mType"],3,$dates)?></div>
        </div>
      </div>
    <?}?>
    </div>

</div>
<script>
</script>