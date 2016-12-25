<? $expense = new Expense();?>
<h2>Выручка</h2>

<table class="table table-bordered span3" id="dataTable">
    <thead>
        <tr>
            <th>Ответсвенный</th>
            <th>Сумма</th>
            <th></th>
        </tr>
    </thead>
    <tbody><?
    foreach($empSum['cost'] as $key => $val){
        if($val != 0){?>
        <tr>
            <td><?=$key?></td> 
            <td><?=number_format($val,0,',',' ')?></td>
            <td><?=number_format(round($empPerSum['cost'][$key]/100)*100,0,',',' ')?></td>
        </tr>
        <?}?>
    <?}
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Общая сумма</th>
            <th><? echo number_format($sum['cost'],0,',',' ')?></th>
            <th><? echo number_format(round($sumPer['cost']/100)*100,0,',',' ')?></th>
        </tr>
    </tfoot>
</table>

<h2>Торты</h2>

<table class="table table-bordered span3" id="dataTable">
    <thead>
    <tr>
        <th>Ответсвенный</th>
        <th>Сумма</th>
        <th></th>
    </tr>
    </thead>
    <tbody><?
    foreach($empSum['cont'] as $key => $val){
        if($val != 0){?>
            <tr>
                <td><?=$key?></td>
                <td><?=number_format($val,0,',',' ')?></td>
                <td><?=number_format(round($empPerSum['cont'][$key]/100)*100,0,',',' ')?></td>
            </tr>
        <?}?>
    <?}
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th>Общая сумма</th>
        <th><? echo number_format($sum['cont'],0,',',' ')?></th>
        <th><? echo number_format(round($sumPer['cont']/100)*100,0,',',' ')?></th>
    </tr>
    </tfoot>
</table>

<h2>Долг персонала</h2>

<table class="table table-bordered span3" id="dataTable">
    <thead>
    <tr>
        <th>Ответсвенный</th>
        <th>Сумма</th>
        <th></th>
    </tr>
    </thead>
    <tbody><?
    foreach($empSum['empdebt'] as $key => $val){
        if($val != 0){?>
            <tr>
                <td><?=$key?></td>
                <td><?=number_format($val,0,',',' ')?></td>
                <td><?=number_format(round($empPerSum['empdebt'][$key]/100)*100,0,',',' ')?></td>
            </tr>
        <?}?>
    <?}
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th>Общая сумма</th>
        <th><? echo number_format($sum['empdebt'],0,',',' ')?></th>
        <th><? echo number_format(round($sumPer['empdebt']/100)*100,0,',',' ')?></th>
    </tr>
    </tfoot>
</table>

<h2>Другие долги</h2>

<table class="table table-bordered span3" id="dataTable">
    <thead>
    <tr>
        <th>Ответсвенный</th>
        <th>Сумма</th>
        <th></th>
    </tr>
    </thead>
    <tbody><?
    foreach($empSum['debt'] as $key => $val){
        if($val != 0){?>
            <tr>
                <td><?=$key?></td>
                <td><?=number_format($val,0,',',' ')?></td>
                <td><?=number_format(round($empPerSum['debt'][$key]/100)*100,0,',',' ')?></td>
            </tr>
        <?}?>
    <?}
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th>Общая сумма</th>
        <th><? echo number_format($sum['debt'],0,',',' ')?></th>
        <th><? echo number_format(round($sumPer['debt']/100)*100,0,',',' ')?></th>
    </tr>
    </tfoot>
</table>
