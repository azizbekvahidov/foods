<? $count = 1;?>
<table class="items table table-striped table-hover dataTable no-footer">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th>Начальное сальдо</th>
            <th>Приход</th>
            <th>Расход</th>
            <th>Конечное сальдо</th>
        </tr>
    </thead>
    <tbody>
        <? foreach($model as $value){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('products')->name?></td>
            <td><?=number_format( $value->startCount, 2 )?></td>
            <td><?=number_format( $inProducts[$value->prod_id], 2 )?></td>
            <td><?=number_format( $outProducts[$value->prod_id], 2 )?></td>
            <td><?=number_format( $endProducts[$value->prod_id], 2 )?></td>
        </tr>
        <? $count++;}?>
    </tbody>
</table>
