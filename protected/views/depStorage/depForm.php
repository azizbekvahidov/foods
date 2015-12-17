<br/><?//=CHtml::dropDownList('','',CHtml::listData(Products::model()->findAll(),'product_id','name'),array('empty'=>'выберите продукт','id'=>'products'))?>
<?//=CHtml::dropDownList('','',CHtml::listData(Halfstaff::model()->findAll(),'halfstuff_id','name'),array('empty'=>'выберите продукт','id'=>'halfstuff'))?>
<table id="prodList" class="table table-striped table-bordered table-hover ">
    <thead>
        <tr>
            <th style="text-align:center;">Название продукта</th>
            <th style="text-align:center;">Количество</th>
            <!--<th style="text-align:center;"></th>-->
        </tr>
    </thead>
    <tbody>
        <? if(empty($curProdModel) && empty($curStuffModel)){?>
            <? if(!empty($products)){?>
            <? foreach($products as $key => $value){?>
                <tr>
                    <td style='text-align:center;'><input type='text' style='display: none;' name='prod[product_id][]' value='<?=$key?>' /><?=$value?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='prod[count][]'  /></td>
                    <!--<td style='text-align:center;'><a href="javascript:;" class="deleteRow"><i class="icon-trash "></i></a></td>-->

                </tr>
            <?}?>
            <?} if(!empty($stuffs)){?>
            <? foreach($stuffs as $key => $value){?>
                <tr>
                    <td style='text-align:center;'><input type='text' style='display: none;' name='stuff[stuff_id][]' value='<?=$key?>' /><?=$value?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='stuff[count][]'  /> &nbsp;
                    <!--<td style='text-align:center;'><a href="javascript:;" class="deleteRow"><i class="icon-trash "></i></a></td>-->

                </tr>
            <?} }?>

            <? } else{?>
        <? if(!empty($curProdModel)){?>
            <? foreach($curProdModel as $key => $value){
                ?>
                <tr>
                    <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$value->prod_id?>' /><?=$value->getRelated('product')->name?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='count[]' value="<?=$value->curCount?>" /> &nbsp; <?=$value->getRelated('product')->getRelated('measure')->name?></td>

                    <!--<td style='text-align:center;'><input class='span1' type='text' name='price[]' value="<?=$value->price?>" /></td>-->
                </tr>
            <?}?>
        <? }  if(!empty($curStuffModel)){?>
            <? foreach($curStuffModel as $key => $value){ ?>
                <tr>
                    <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$value->prod_id?>' /><?=$value->getRelated('stuff')->name?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='count[]' value="<?=$value->curCount?>" /> &nbsp; <?=$value->getRelated('stuff')->getRelated('halfstuffType')->name?></td>

                    <!--<td style='text-align:center;'><input class='span1' type='text' name='price[]' value="<?=$value->price?>" /></td>-->
                </tr>
            <?}?>
        <? } ?>
        <?}?>
    </tbody>
</table>
<script>
        $('.deleteRow').on('click',function () {
            $(this).parent().parent().remove();
        });

    $('#prodList').DataTable({
            responsive: true,      
            "lengthMenu": [[-1], ["Все"]]       
    });
    $("#products").chosen({
        no_results_text: "Oops, nothing found!"
    }).change(function(){
        var prodValue = $(this).val();
        var prodText = $(this).children('option:selected').text();
        if(prodValue != ''){
            $('#prodList tr:last').after("\
                            <tr>\
                                <td style='text-align:center;'><input type='text' style='display: none;' name='prod[product_id][]' value='"+prodValue+"' />"+prodText+"</td>\
                                <td style='text-align:center;'><input class='span1' type='text' name='prod[count][]' /></td>\
                                <td style='text-align:center;'><a href='javascript:;' class='deleteRow'><i class='icon-trash '></i></a></td>\
                            </tr>\
                        ");
            $('.deleteRow').on('click',function () {
                $(this).parent().parent().remove();
            });
        }
    });

    $("#halfstuff").chosen({
        no_results_text: "Oops, nothing found!"
    }).change(function(){
        var prodValue = $(this).val();
        var prodText = $(this).children('option:selected').text();
        if(prodValue != ''){
            $('#prodList tr:last').after("\
                            <tr>\
                                <td style='text-align:center;'><input type='text' style='display: none;' name='stuff[product_id][]' value='"+prodValue+"' />"+prodText+"</td>\
                                <td style='text-align:center;'><input class='span1' type='text' name='stuff[count][]' /></td>\
                                <td style='text-align:center;'><a href='javascript:;' class='deleteRow'><i class='icon-trash '></i></a></td>\
                            </tr>\
                        ");
            $('.deleteRow').on('click',function () {
                $(this).parent().parent().remove();
            });
        }
    });
</script>