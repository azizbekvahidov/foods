<div id="<?=$count?>">
    <div class="row">
        <? $prices = new Prices(); foreach($newModel1 as $val){?>
            <div class="col-xs-6 col-md-2">
                <div id="dish_<?=$val->getRelated('dish')->dish_id?>" class="thumbnail upplus">
                    <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$value->name?>" />
          <span class="texts">
            <?=$val->getRelated('dish')->name?>
          </span>
                    <?=$prices->getPrice($val->just_id,$val->mType,1,$dates);?>
                </div>
            </div>
        <?}?>
        <? foreach($newModel2 as $val){?>
            <div class="col-xs-6 col-md-2">
                <div id="product_<?=$val->getRelated('products')->product_id?>" class="thumbnail upplus">
                    <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$value->name?>" />
          <span class="texts">
            <?=$val->getRelated('products')->name?>
          </span>
                    <?=$prices->getPrice($val->just_id,$val->mType,3,$dates);?>
                </div>
            </div>
        <?}?>
        <? foreach($newModel3 as $val){?>
            <div class="col-xs-6 col-md-2">
                <div id="stuff_<?=$val->getRelated('stuff')->halfstuff_id?>" class="thumbnail upplus ">
                    <img class="img-rounded" src="<?php echo Yii::app()->request->baseUrl; ?>/images/dish_bg.jpg" alt="<?=$value->name?>" />
          <span class="texts">
         <?=$val->getRelated('stuff')->name?>
          </span>
                    <?=$prices->getPrice($val->just_id,$val->mType,2,$dates);?>
                </div>
            </div>
        <?}?>
    </div>

</div>
<script>
    $(document).on('click','.upplus',function () {
        console.log('asdasd');
        $("#upsubmitBtn").removeAttr('disabled');
        var identifies = $(this).children('span').text();
        var thisId = $(this).attr('id');
        //count = parseInt($(this).data('click')) || 0;
        //count=count+1;
        //console.log($(this).data('click'));
        //$(this).data('click',count);
        if($('#uporder tr.'+thisId).exists()){
            var types = str_split(thisId,1);
            var count = $('#uporder tr.'+thisId).children("td.cnt").children('input').val();
            count = parseFloat(count)+1;
            $('#uporder tr.'+thisId).children("td:first-child").children('input').val(types[1]);
            $('#uporder tr.'+thisId).children("td.cnt").children('input').val(count);
            $('#uporder tr.'+thisId).children("td.cnt").children('span').text(count);
            //$('#order tr.'+thisId).html("<td><button type='button' class='removed'><i class='fa fa-times'></i></button></td><td>"+identifies + "</td> <td>" + count+"</td>");
        }
        else{
            var types = str_split(thisId,1);
            $('#uporder').append("<tr class="+thisId+">\
                                <td >\
                                    <a type='button' class='removed btn'>\
                                        <i class='fa fa-times'></i>\
                                    </a>\
                                    <input style='display:none' name='"+types[0]+"[id][]' value='"+types[1]+"' />\
                                </td>\
                                <td>"+identifies + "</td>\
                                <td class='cnt'>\
                                    <input name='"+types[0]+"[count][]' style='display:none' value='1' />\
                                    <span>" +1+"</span>\
                                    <a type='button' class='minus btn'>\
                                        <i class='fa fa-minus'></i>\
                                    </a>\
                                </td>\
                            </tr>");
        }
    });
</script>