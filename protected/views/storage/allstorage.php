
<? $count = 1;?>
<table class="items table table-striped table-hover dataTable no-footer" >

        <tr>
            <th></th>
            <th>Название</th>
            <th>Начальное сальдо</th>
            <th>Приход</th>
            <th>Расход</th>
            <th>Внутренний расход</th>
            <th>План. кон. сальдо</th>
            <th>Факт. кон. сальдо</th>
            <th>Разница</th>
        </tr>
    <tbody>
        <? foreach($model as $value){?>
        <? //if($value->startCount != 0 || $inProducts[$value->prod_id] != 0 || $outProducts[$value->prod_id] != 0 || $inOutProducts[$value->prod_id] != 0 ){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('products')->name?></td>
            <td><?=number_format( $value->startCount, 2,',','' )?></td>
            <td><?=number_format( $inProducts[$value->prod_id], 2,',','' )?></td>
            <td><?=number_format( $outProducts[$value->prod_id], 2,',','' )?></td>
            <td><?=number_format( $inOutProducts[$value->prod_id], 2,',','' )?></td>
            <td><?=number_format( $value->endCount, 2,',','' )?></td>
            <td><?=number_format( $value->CurEndCount, 2,',','' )?></td>
            <td><?=number_format( $value->endCount-$value->CurEndCount, 2,',','' )?></td>
        </tr>
        <?// }?>
        <? $count++;}?>
    </tbody>
</table>