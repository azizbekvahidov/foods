<? $cnt = 1; $prices = new Prices(); $summ = 0?><meta name="viewport" content="width=device-width, initial-scale=1.0">


<style>
    *{
        font-family: Arial, Tahoma;
    }
    h6,h3{
        margin: 5px 0;
    }
    th,td{
        text-align: left;
        font-size: 10px;
        /*border:1px solid #000;*/
    }
    table{
        margin-bottom: 5px;
        margin-top: 5px;
        width: 90%;
        margin-left: 10px;
        border-collapse: collapse;
    }
    .dashedtable{
        border-top: 1px dashed #000;
        border-bottom: 1px dashed #000;
    }
    .right{
        text-align: right;
    }
    table td:first-child,table th:first-child{
        width: 50%;
    }
    table td:nth-child(2),table th:nth-child(2){
        width: 30%;
    }
    table td:last-child,table th:last-child{
        width: 20%;
    }
    .text-center{
        text-align: center;
    }
    .empty th, .empty td{
        font-size:4px;
    }
    .result{
        font-size:26px;
        text-align: center;
    }
</style>

<h3 class="text-center"><?=Yii::app()->config->get("name")?></h3>
<h6 class="text-center"><?=date('d.m.Y')?><br><?=date("H:i:s")?></h6>
<table class="">
        <tr>
            <th colspan="1">Открыт <?=$expense['order_date']?></th>
            <th class="right" colspan="2">Счет № <?=$expense['expense_id']?></th>
        </tr>
        <tr>
            <th colspan="1">Официант <?=$expense['name']?></th>
            <th class="right" colspan="2">Стол № <?=$expense['Tname']?></th>
        </tr>
</table>
<table class="dashedtable">
        <tr class="empty">
            <th>&nbsp; </th>
            <th class="right">&nbsp; </th>
            <th class="right">&nbsp; </th>
        </tr>
        <tr>
            <th>Наименование</th>
            <th class="right">Кол-во</th>
            <th class="right">Сумма</th>
        </tr>
        <tr class="empty">
            <th> &nbsp;</th>
            <th class="right">&nbsp; </th>
            <th class="right">&nbsp; </th>
        </tr>
</table>
<table style="border-bottom: 1px dashed #000; border-collapse: separate;">
    <tbody>
    <?if(!empty($model))
        foreach ($model->getRelated('order') as $value) { $price = $prices->getPrice($value->just_id,$model->mType,$value->type,$model->order_date)?>
            <?if($value->count != 0){?>
                <tr>
                    <td><?=$value->getRelated('dish')->name?></td>
                    <td class="right"><?=$value->count?></td>
                    <td class="right"><?=$price*$value->count; $summ = $summ + $price*$value->count?></td>
                </tr>
            <?}?>
            <?$cnt++;}
    ?>
    <?if(!empty($model2))
        foreach ($model2->getRelated('order') as $value) { $price = $prices->getPrice($value->just_id,$model2->mType,$value->type,$model2->order_date)?>
            <?if($value->count != 0){?>
                <tr>
                    <td><?=$value->getRelated('halfstuff')->name?></td>
                    <td class="right"><?=$value->count?></td>
                    <td class="right"><?=$price*$value->count; $summ = $summ + $price*$value->count?></td>
                </tr>
            <?}?>
            <?$cnt++;}
    ?>
    <?if(!empty($model3))
        foreach ($model3->getRelated('order') as $value) { $price = $prices->getPrice($value->just_id,$model3->mType,$value->type,$model3->order_date)?>
            <?if($value->count != 0){?>
                <tr>
                    <td><?=$value->getRelated('products')->name?></td>
                    <td class="right"><?=$value->count?></td>
                    <td class="right"><?=$price*$value->count; $summ = $summ + $price*$value->count?></td>
                </tr>
            <?}?>
            <?$cnt++;}
    ?>
    <tr class="empty">
        <th> &nbsp;</th>
        <th class="right">&nbsp; </th>
        <th class="right">&nbsp; </th>
    </tr>
    </tbody>
</table>
    <table>
    <tr>
        <th colspan="1">Сумма : </th>
        <th></th>
        <th class="right" colspan="2"><?=number_format(($summ)/100,0,',','')*100?></th>
    </tr>
    <?if($check != 0){
        if($expense["banket"] == 1){  $banket = Yii::app()->config->get("banket_percent");?>
            <tr>
                <th colspan="1"> Обслуживание банкет</th>
                <th class="right" colspan="2"><?=number_format(($summ/100*$banket)/100,0,',','')*100?></th>
            </tr>
        </table>
            <div class="result">
                Итог : <?=number_format(($summ/100*$banket + $summ)/100,0,',','')*100?>
            </div>
        <?} else{?>
            <tr>
                <th colspan="1"> Обслуживание </th>
                <th class="right" colspan="2"><?=number_format(($summ/100*$percent)/100,0,',','')*100?></th>
            </tr>
        </table>
            <div class="result">
                Итог : <?=number_format(($summ/100*$percent + $summ)/100,0,',','')*100?>
            </div>
        <?}?>
    <?} else{?>

</table>
<?}?>