
<style>
    th,td{
        padding-left: 3px;
        border: 1px solid #000;
        text-align: left;
        border-collapse: collapse;
        font-size: 10px;
    }
    table{
        border-collapse: collapse;
    }
    h2{
        padding: 0;
        margin: 0;
    }
</style><? $count = 1;?>
<table class="" id="dataTable">
    <thead>
    <tr>
        <th></th>
        <th>Наименование</th>
        <th>Кол-во</th>
        <th>Прибыль <br>(<?=number_format(array_sum($summ),0,',',' ')?>)</th>
    </tr>
    </thead>
    <tbody>
    <? if(!empty($dishes))  foreach ($dishes["summ"] as $key => $val) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$dishes["name"][$key]?></td>
            <td><?=$dishes["counting"][$key]?></td>
            <td><?=number_format($dishes["summ"][$key],0,',',' ')?></td>
        </tr>
        <? $count++;}
    ?>
    <? if(!empty($stuffs)) foreach ($stuffs["summ"] as $key => $val) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$stuffs["name"][$key]?></td>
            <td><?=$stuffs["counting"][$key]?></td>
            <td><?=number_format($stuffs["summ"][$key],0,',',' ')?></td>
        </tr>
        <? $count++;}
    ?>
    <? if(!empty($prods)) foreach ($prods["summ"] as $key => $val) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$prods["name"][$key]?></td>
            <td><?=$prods["counting"][$key]?></td>
            <td><?=number_format($prods["summ"][$key],0,',',' ')?></td>
        </tr>
        <? $count++;}
    ?>
    </tbody>
</table>