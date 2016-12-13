
<table class="table table-bordered span3" id="dataTable">
    <thead>
        <tr>
            <th>Ответсвенный</th>
            <th>Сумма</th>
            <th></th>
        </tr>
    </thead>
    <tbody><?
    foreach($empSum as $key => $val){
        if($val != 0){?>
        <tr>
            <td><?=$key?></td> 
            <td><?=number_format($val,0,',',' ')?></td>
            <td><?=number_format(round($empPerSum[$key]/100)*100,0,',',' ')?></td>
        </tr>
        <?}?>
    <?}
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Общая сумма</th>
            <th><? echo number_format($sum,0,',',' ')?></th>
            <th><? echo number_format(round($sumPer/100)*100,0,',',' ')?></th>
        </tr>
    </tfoot>
</table>
