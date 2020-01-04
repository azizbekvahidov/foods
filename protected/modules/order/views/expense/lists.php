<? $prices = new Prices(); $dates = date('Y-m-d')?>
<div id="<?=$count?>">
    <div class="row">
    <? foreach($newModel1 as $val){ ?>
      <div class="col-xs-6 col-md-2">
        <div id="dish_<?=$val->getRelated('dish')->dish_id?>" class="thumbnail plus">
          <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$value->name?>" />
          <span class="texts">
            <?=$val->getRelated('dish')->name?>
          </span>
          <div><?=$prices->getPrice($val->just_id,$val->mType,1,$dates);?></div>
        </div>
      </div>
    <?}?>
    <? foreach($newModel2 as $val){?>
      <div class="col-xs-6 col-md-2">
        <div id="product_<?=$val->getRelated('products')->product_id?>" class="thumbnail plus">
          <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$value->name?>" />
          <span class="texts">
            <?=$val->getRelated('products')->name?>
          </span>
            <div><?=$prices->getPrice($val->just_id,$val->mType,3,$dates)?></div>
        </div>
      </div>
    <?}?>
    <? foreach($newModel3 as $val){?>
      <div class="col-xs-6 col-md-2">
        <div id="stuff_<?=$val->getRelated('stuff')->halfstuff_id?>" class="thumbnail plus ">
          <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$value->name?>" />
          <span class="texts">
            <?=$val->getRelated('stuff')->name?>
          </span>
            <div><?=$prices->getPrice($val->just_id,$val->mType,2,$dates)?></div>
        </div>
      </div>
    <?}?>
    </div>
    
</div>
<script>
    $('.plus').on('click', function () {
      $("#submitBtn").removeAttr('disabled');
      var identifies = $(this).children('span').text();
      var thisId = $(this).attr('id');
      //count = parseInt($(this).data('click')) || 0;
      //count=count+1;
      //console.log($(this).data('click'));
      //$(this).data('click',count);
      if($('#order tr.'+thisId).exists()){
        var types = str_split(thisId,1);
          console.log(types[1])
        var count = $('#order tr.'+thisId).children("td.cnt").children('input').val();
        count = parseFloat(count)+1;
          //$('#order tr.'+thisId).children("td:first-child").children('input').val(types[1]);
          $('#order tr.'+thisId).children("td.cnt").children('input').val(count);
          $('#order tr.'+thisId).children("td.cnt").children('span').text(count);
        //$('#order tr.'+thisId).html("<td><button type='button' class='removed'><i class='fa fa-times'></i></button></td><td>"+identifies + "</td> <td>" + count+"</td>");
      }    
      else{
        var types = str_split(thisId,1);
          $('#order').append("<tr class="+thisId+">\
                                    <td >\
                                        <a type='button' class='removed' href='#'>\
                                            <i class='fa fa-times'></i>\
                                        </a>\
                                        <input style='display:none' name='id[]' value='"+thisId+"' />\
                                    </td>\
                                    <td>"+identifies+"</td>\
                                    <td>"+$(this).children('div').text()+"</td>\
                                    <td class='cnt'>\
                                        <input name='count[]' style='display:none' value='1' />\
                                        <a type='button' class='pluss ' href='#'>\
                                            <i class='fa fa-plus'></i>\
                                        </a>\
                                        <span>" +1+"</span>\
                                        <a type='button' class='minus ' href='#'>\
                                            <i class='fa fa-minus'></i>\
                                        </a>\
                                    </td>\
                                </tr>");
      }
        getSum();
    });
</script>