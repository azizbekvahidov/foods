<? $cnt = 1;?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Цена (сум)</th>
            <th>Сумма (сум)</th>
            <th>% от всей суммы прихода</th>
            <th>% от всей суммы прихода поставщика</th>
        </tr>
    </thead>
    <tbody>
    <?foreach ($model as $val) { $sums = $val['count']*$val['price'];?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$val['name']?></td>
            <td><?=number_format($val['count'],1,',',' ')?> <?=$val['mName']?></td>
            <td><?=number_format($val['price'],0,',',' ')?></td>
            <td><?=number_format($sums,0,',',' ')?></td>
            <td><?=number_format($sums*100/$allSumm,0,',',' ')?>%</td>
            <td><?=number_format($sums*100/$summ["summ"],0,',',' ')?>%</td>
        </tr>
    <?$cnt++;}?>
    </tbody>
</table>