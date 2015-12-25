<? $dishes = new Dishes(); $measure = new Measurement(); $prod = new Products(); $stuff = new Halfstaff(); $count = 1; $dates = date('Y-m-d')?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="/css/bootstrap3.css" rel="stylesheet">
<style>
    td,th{
        padding: 0 8px!important;
        font-size: 11px;
    }
</style>
<table class="table" id="dataTable">
    <tr>
        <?foreach ($model as $val) { $cnt = 1; $summ = 0;?>
        <?if(($count%4)+1 == 0){?>
    </tr>
    <?}?>
    <td>
        <h4><?=$count?>. <?=$val['name']?></h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>Наименование</th>
                <th>Кол-во</th>
                <th>цена</th>
            </tr>
            </thead>
            <?foreach($dishes->getProd($val['dish_id']) as $key => $value){?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$prod->getName($key)?></td>
                    <td><?=number_format($value,2,',',' ')?> <?=$measure->getMeasure($key,'prod')?></td>
                    <td>
                        <?$summ = $summ + $prod->getCostPrice($key,$dates)*$value?>
                        <?=number_format($prod->getCostPrice($key,$dates)*$value,0,',',' ')?>
                    </td>
                </tr>
                <? $cnt++;}?>
            <?foreach($dishes->getStuff($val['dish_id']) as $key => $value){?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$stuff->getName($key)?></td>
                    <td><?=number_format($value,2,',',' ')?> <?=$measure->getMeasure($key,'stuff')?></td>
                    <td>
                        <?$summ = $summ + $stuff->getCostPrice($key,$dates)*$value?>
                        <?=number_format($stuff->getCostPrice($key,$dates)*$value,0,',',' ')?>
                    </td>
                </tr>
                <? $cnt++;}?>
        </table>
    </td>
    <?if($count%3 == 0){?>
    <tr>
        <?}?>
        <?$count++;}?>
    </tr>
</table>