<? $cnt = 1; $product = new Products(); $faktura = new Faktura()?><meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="/css/bootstrap3.css" rel="stylesheet">
<style>
    *{
        font-size: 12px!important;
    }
    td{
        padding: 0!important;
    }
    .center{
        text-align: center;
    }
</style>
<table id="dataTable" class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Наименование</th>
        <th>Кол-во</th>
        <th>Цена</th>
        <th>Сумма</th>
        <? foreach ($dep as $key => $val) {
            $sum[$key] = 0;?>
            <th><?=$val?></th>
        <?}?>
        <th>Прочие</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($model as $val) {
        $countSum = $faktura->getReqSumCount($val['prod_id'],$id);
        $price = $product->getCostPrice($val['prod_id'],$dates);

        $summ = $summ + $countSum*$price; ?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$val['Pname']?> (<?=$val['Mname']?>)</td>
            <td class="center"><?=number_format($countSum,2,',',' ')?></td>
            <td class="center"><?=number_format($price,0,',',' ')?></td>
            <td class="center"><?=number_format($countSum*$price,0,',',' ')?></td>
            <? foreach ($dep as $key => $value) {
                $count = $faktura->getReqCount($val['request_id'],$key,$val['prod_id']);
                $sum[$key] = $sum[$key] + $count*$price?>
                <td class="center"><?=number_format($count,2,',',' ')?></td>
            <?}?>
            <?$sum[0] = $sum[0] + $faktura->getReqCount($val['request_id'],0,$val['prod_id'])*$price?>
            <td class="center"><?=number_format($faktura->getReqCount($val['request_id'],0,$val['prod_id']),2,',',' ')?></td>
        </tr>
        <?$cnt++;}?>
    </tbody>
    <tfoot>
    <tr>
        <th></th>
        <th>Итого</th>
        <th class="center"></th>
        <th class="center"></th>
        <th class="center"><?=number_format($summ,0,',','')?></th>
        <? foreach ($dep as $key => $val) {?>
            <th class="center"><?=number_format($sum[$key],0,',','')?></th>
        <?}?>
        <th class="center"><?=number_format($sum[0],0,',','')?></th>
    </tr>
    </tfoot>
</table>