<? $count = 1; $realize = new DepRealize(); $summ = 0; $products = new Products()?>
<table class="table table-bordered" id="dataTable">
    <thead>
    <tr>
        <th></th>
        <th>Название продукта</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Сумма</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($outProduct as $key => $val) {?>
            <tr>
                <td><?=$count?></td>
                <td><?=$products->getName($key)?></td>
                <td><?=$val?></td>
                <td><?=number_format($realize->getRealized($key,$dates),0,'.',',')?></td>
                <td><?=number_format($sum = $val*$realize->getRealized($key,$dates),0,'.',','); $summ = $summ + $sum?></td>
            </tr>
            <?$count++;
    }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4">Общая сумма</th>
        <th><?=number_format($summ,0,'.',',')?></th>
    </tr>
    </tfoot>
</table>