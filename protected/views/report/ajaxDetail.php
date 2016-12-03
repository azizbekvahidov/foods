<? $cnt = 1; $prod = new Products(); $stuff = new Halfstaff(); $price = new Prices(); $dish = new Dishes(); $sum = 0;?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>
<style>

    thead{
        background-color:white;
    }
    .modal{
        z-index: 1050;
    }
</style>

<table id="Detail" class="table table-bordered table-hover">
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
                    <?}elseif($key == 'begin'){?>
                        <td><?=number_format($prod->getCostPrice($val['prod_id'],date('Y-m-d',strtotime($dates)-86400))*$val['count'],0,',',' ')?></td>
                        <? $sum = $sum + $prod->getCostPrice($val['prod_id'],date('Y-m-d',strtotime($dates)-86400))*$val['count']?>
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
                        <?}elseif($key == 'begin'){?>
                            <td><?=number_format($stuff->getCostPrice($val['prod_id'],date('Y-m-d',strtotime($dates)-86400))*$val['count'],0,',',' ')?></td>
                            <? $sum = $sum + $stuff->getCostPrice($val['prod_id'],date('Y-m-d',strtotime($dates)-86400))*$val['count']?>
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
<div id="bottom_an"></div>
<script>
    $('#Detail').DataTable({
        fixedHeader: true,
        responsive: true,
        "lengthMenu": [[10, 25, 50, 100,-1], [ 10, 25, 50, 100,"Все"]]
    });
</script>