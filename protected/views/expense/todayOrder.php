<? $count = 1; $expense = new Expense(); $curPercent = 0; $summaP = 0; $summa = 0;?>
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Дата и время</th>
        <th>Ответственный за заказ</th>
        <th>Стол №</th>
	    <th>Процент обслуживания</th>
        <th>Сумма счета</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach( $newModel as $value){?>
	    <? if($value["check_percent"] == 1)
		    $curPercent = $percent;
	    else
		    $curPercent = 0;

        $temp = $expense->getExpenseSum($value["expense_id"],$value["order_date"]);
	    ?>

        <tr>
            <td><?=$count?></td>
            <td><?=$value["order_date"]?></td>
            <td><?=$value["name"]?></td>
            <td><?=($value["table"] != 0 ) ? $value["Tname"] : "На вынос"?></td>
	        <td><?=$curPercent?></td>
            <td><?=number_format($temp,0,',',' '); $summa = $summa + $temp?></td>
            <td><?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view?id='.$value["employee_id"].'&order_date='.$value["order_date"]))?></td>
        </tr>
        <? $count++; } ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">Общая сумма</th>
            <th colspan="2"><?=number_format($summa,0,',',' ')?></th>
        </tr>
    </tfoot>
</table>