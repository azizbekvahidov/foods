<? $cnt = 1; $prod = new Products(); $stuff = new Halfstaff(); $price = new Prices(); $dish = new Dishes(); $sum = 0;?>
<style>

    thead{
        background-color:white;
    }
</style>
<table id="dataTable" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Сумма (сум) </th>
        </tr>
    </thead>
    <tbody>
        <?if(!empty($model)){
            foreach ($model as $val) {
                if($val['count']!=0){?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$val['name']?></td>
                    <td><?=number_format($val['count'],2,',','')?> <?=$val['Mname']?></td>
                    <?if($key == 'price'){?>
                        <td><?=number_format($price->getPrice($val['prod_id'],$val['mType'],$val['type'],$dates)*$val['count'],0,',',' ')?></td>
                        <? $sum = $sum + $price->getPrice($val['prod_id'],$val['mType'],$val['type'],$dates)*$val['count']?>
                    <?}else{?>
                        <td><?=number_format($prod->getCostPrice($val['prod_id'],$dates)*$val['count'],0,',',' ')?></td>
                        <? $sum = $sum + $prod->getCostPrice($val['prod_id'],$dates)*$val['count']?>
                    <?}?>
                </tr>
            <?  $cnt++;}
            }
        }if(!empty($model2)){
            foreach ($model2 as $val) {
                if($val['count']!=0){?>
                    <tr>
                        <td><?=$cnt?></td>
                        <td><?=$val['name']?></td>
                        <td><?=number_format($val['count'],2,',','')?> <?=$val['Mname']?></td>
                        <?if($key == 'price'){?>
                            <td><?=number_format($price->getPrice($val['prod_id'],$val['mType'],$val['type'],$dates)*$val['count'],0,',',' ')?></td>
                            <? $sum = $sum + $price->getPrice($val['prod_id'],$val['mType'],$val['type'],$dates)*$val['count']?>
                        <?}else{?>
                            <td><?=number_format($stuff->getCostPrice($val['prod_id'],$dates)*$val['count'],0,',',' ')?></td>
                            <? $sum = $sum + $stuff->getCostPrice($val['prod_id'],$dates)*$val['count']?>
                        <?}?>
                    </tr>
                    <?  $cnt++;}
            }
        }if(!empty($model3)){
            foreach ($model3 as $val) {
                if($val['count']!=0){?>
                    <tr>
                        <td><?=$cnt?></td>
                        <td><?=$val['name']?></td>
                        <td><?=number_format($val['count'],2,',','')?> <?=$val['Mname']?></td>
                        <?if($key == 'price'){?>
                            <td><?=number_format($price->getPrice($val['prod_id'],$val['mType'],$val['type'],$dates)*$val['count'],0,',',' ')?></td>
                            <? $sum = $sum + $price->getPrice($val['prod_id'],$val['mType'],$val['type'],$dates)*$val['count']?>
                        <?}else{?>
                            <td><?=number_format($dish->getCostPrice($val['prod_id'],$dates)*$val['count'],0,',',' ')?></td>
                            <? $sum = $sum + $dish->getCostPrice($val['prod_id'],$dates)*$val['count']?>
                        <?}?>
                    </tr>
                    <?  $cnt++;}
            }
        }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Итого</th>
            <th><?=number_format($sum,0,',',' ')?></th>
        </tr>
    </tfoot>
</table>
<div id="bottom_anchor"></div>
<script>
    function moveScroll(){
        var scroll = $(window).scrollTop();
        var anchor_top = $("#dataTable").offset().top;
        var anchor_bottom = $("#bottom_anchor").offset().top;
        if (scroll>anchor_top && scroll<anchor_bottom) {
            clone_table = $("#clone");
            if(clone_table.length == 0){
                clone_table = $("#dataTable").clone();
                clone_table.attr('id', 'clone');
                clone_table.css({position:'fixed',
                    'pointer-events': 'none',
                    top:0});
                clone_table.width($("#dataTable").width());
                $("#content").append(clone_table);
                $("#clone").css({visibility:'hidden'});
                $("#clone thead").css({'visibility':'visible','pointer-events':'auto'});
            }
        } else {
            $("#clone").remove();
        }
    }
    $(window).scroll(moveScroll);

</script>