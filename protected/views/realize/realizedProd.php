<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>Название</th>
            <th>Количество</th>
            <th>Сумма (<?=number_format(array_sum($prodSumm),0,',',' ')?>)</th>
        </tr>
    </thead>
    <tbody>
    <? $realize = new Realize();
    foreach ($prodModel as $value) {?>
        <? if($prodCount[$value->product_id] != ''){  ?>
        <tr>
            <td><?=$value->name?></td>
            <td><?=$prodCount[$value->product_id]?></td>
            <td><?=number_format($prodSumm[$value->product_id],0,',',' ')?></td>
        </tr>
        <?}?>
    <?}
    ?>
    </tbody>
</table>