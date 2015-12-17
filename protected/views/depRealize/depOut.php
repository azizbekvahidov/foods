<? $count = 1; $realize = new DepRealize(); $summ = 0?>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Название продукта</th>
            <th>Количество</th>
            <th>Отдел</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($model as $value) {
        foreach ($value->getRelated('realizedProd') as $val) {?>
            <tr>
                <td><?=$count?></td>
                <td><?=$val->getRelated('product')->name?></td>
                <td><?=$val->count?></td>
                <td><?=$value->getrelated('department')->name?></td>
                <td><?=number_format($realize->getRealized($val->prod_id,$dates),0,'.',',')?></td>
                <td><?=number_format($sum = $val->count*$realize->getRealized($val->prod_id,$dates),0,'.',','); $summ = $summ + $sum?></td>
            </tr>
        <?$count++;}
    }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">Общая сумма</th>
            <th><?=number_format($summ,0,'.',',')?></th>
        </tr>
    </tfoot>
</table>