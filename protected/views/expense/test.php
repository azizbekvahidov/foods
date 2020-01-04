<? $expense = new Expense(); $allSalary = 0; $waiterCnt = 0; $paidDebtSum = 0; $debtSum = 0?>
<div class="col-lg-12">
    <h2>Прибыль</h2>
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th>Ответственный</th>
                <th>Наличные</th>
                <th>Терминал</th>
                <th>Всего</th>
                <th>Сумма без процента</th>
                <th>Процент официантов <?=$waiterProc = Yii::app()->config->get("waiterSalary")?>% </th>
            </tr>
        </thead>
        <tbody><?
        $allClearSumm = number_format(intval($clearSumm)/100 ,0,',','')*100;
        $costSum = $sum['cost'] - $terminalAll['cost'];
        foreach($empSum['cost'] as $key => $val){
            $clears = number_format(($empSum["clearSum"][$key]) / 100, 0, ',', '') * 100;
            $empSalary = number_format((($empSum["check"][$key] == 1) ? $clears/100*$waiterProc : 0)/100, 0, ',', '') * 100;
            if($empSum["check"][$key] == 1 && $val != 0){
                $waiterCnt++;
            }
            if($val != 0){?>
            <tr>
                <td><?=$key?></td>
                <td><?=number_format($val - $terminal["cost"][$key],0,',',' ')?></td>
                <td><?=number_format($terminal["cost"][$key],0,',',' ')?></td>
                <td><?=number_format($val,0,',',' ')?></td>
                <td><?=number_format($clears,0,',',' ')?></td>
                <td><?=number_format($empSalary,0,',',' ')?></td>
            </tr>
            <? $allSalary = $allSalary + $empSalary;}?>
        <?}
        ?>
            <tr>
                <th>Итоги</th>
                <th><?=number_format($costSum,0,',',' ')?></th>
                <td><?=number_format($terminalAll["cost"],0,',',' ')?></td>
                <th><?=number_format($sum['cost'],0,',',' ')?></th>
                <td><?=number_format($allClearSumm,0,',',' ')?></td>
                <td><?=number_format($allSalary,0,',',' ')?></td>
            </tr>

            <tr>
                <th colspan="">Расходы </th>
                <td colspan=""></td>
                <td colspan="5"></td>
            </tr>
            <? foreach ($cost as $val){?>
                <tr>
                    <td colspan=""><?=$val["comment"]?></td>
                    <td colspan=""><?=number_format($val["summ"]*(-1),0,',',' ')?> <a href="javascript:;" class="removeCost" onclick="removeCost(<?=$val["cost_id"]?>)" style="float: right"><i class="glyphicon glyphicon-remove"></i></a></td>
                    <td colspan="5"></td>
                </tr>
            <?$costSum = $costSum - $val["summ"];}?>
            <tr>
                <th colspan="">Итого</th>
                <th colspan=""><?=number_format($costSum,0,',',' ')?></th>
                <td colspan="5"></td>
            </tr>

        </tbody>
    </table>
    <h2>Долги</h2>
    <table class="table table-bordered" >
        <tr>
            <th colspan="">Комментарий</th>
            <td colspan="">Сумма</td>
        </tr>
        <? foreach ($debt as $val){?>
            <tr>
                <td colspan=""><?=$val["comment"]?></td>
                <td colspan=""><?=number_format($val["expSum"]-$val["debtPayed"],0,',',' ')?> </td>
            </tr>
            <?$debtSum = $debtSum + $val["expSum"]-$val["debtPayed"];}?>
        <tr>
            <th colspan="">Итого</th>
            <th colspan=""><?=number_format($debtSum,0,',',' ')?></th>
        </tr>
    </table>
    <h2>Оплаченные долги</h2>
    <table class="table table-bordered" >
        <tr>
            <th colspan="">Комментарий</th>
            <th colspan="">дата</th>
            <td colspan="">Сумма</td>
        </tr>
        <? foreach ($paidDebt as $val){?>
            <tr>
                <td colspan=""><?=$val["comment"]?></td>
                <td><?=date("d.m.Y",strtotime($val["d_date"]))?></td>
                <td colspan=""><?=number_format($val["expSum"]-$val["debtPayed"],0,',',' ')?> </td>
            </tr>
            <?$paidDebtSum = $paidDebtSum + $val["expSum"]-$val["debtPayed"];}?>
        <tr>
            <th colspan="2">Итого</th>
            <th colspan=""><?=number_format($paidDebtSum,0,',',' ')?></th>
        </tr>
    </table>
</div>
<?
    $to = date("Y-m-d",strtotime($to) - 3600);
    $from = date("Y-m-d",strtotime($from) - 3600);
?>
<div class="col-lg-12">
    <h2>Прибыль отделов</h2>
    <table class="table table-bordered" id="depSum">
        <thead>
        <tr>
            <th>Отдел</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>

        <? $allDepSum = 0;
        foreach($department as $val){ $depSum = $expense->getDepIncome($val["department_id"],$from,$to);?>
            <tr>
                <td><?=$val["name"]?></td>
                <td><?=number_format($depSum,0,',',' ')?></td>
            </tr>
        <? $allDepSum = $allDepSum + $depSum;}?>
            <tr>
                <th>Всего</th>
                <th><?=number_format($allDepSum,0,',',' ')?></th>
            </tr>
        </tbody>

    </table>
</div>
<script>
    $('#exportDep').click(function(){
        $('#depSum').table2excel({
            name: "Excel Document Name"
        });
    });
</script>

<script>
    $.fn.setSum = function(){
        $(this).on('click',function() {
            var cell = $(this);
            var sum = cell.parent().parent().children("td:nth-child(4)").text();
            var name = cell.parent().parent().children("td:nth-child(1)").text();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/regSalary'); ?>",
                data: "name=" + name + "&sum=" + sum,
                success: function (data) {
                    $("#show").click();
                }
            });
        });
        return this;

    };

    function removeCost(id){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('expense/removeCost'); ?>",
            data: "id=" + id,
            success: function (data) {
                $("#show").click();
            }
        });
    }


</script>