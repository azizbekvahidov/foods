<? $count = 1; $expense = new Expense(); $curPercent = 0; $summaP = 0; $summa = 0;?>
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Дата и время</th>
        <th>Ответственный за заказ</th>
        <th>Стол №</th>
        <th>Сумма</th>
        <th>Процент обслуживания</th>
        <th>Сумма счета</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach( $model as $value){?>
        <? if($value->getRelated('employee')->check_percent == 1)
            $curPercent = $percent;
        else
            $curPercent = 0;
        ?>

        <tr>
            <td><?=$count?></td>
            <td><?=$value->order_date?></td>
            <td><?=$value->getRelated('employee')->name?></td>
            <td><?=$value->table?></td>
            <td><?=number_format($expense->getExpenseSum($value->expense_id),0,'.',','); $summa = $summa + $expense->getExpenseSum($value->expense_id)?></td>
            <td><?=$curPercent?></td>
            <td><?=number_format($expense->getExpenseSum($value->expense_id)/100*$curPercent + $expense->getExpenseSum($value->expense_id),0,'.',','); $summaP = $summaP + $expense->getExpenseSum($value->expense_id)/100*$curPercent + $expense->getExpenseSum($value->expense_id)?></td>
            <td>
                <?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view&id='.$value->employee_id.'&order_date='.$value->order_date))?> |
                <?=CHtml::link('<i class="fa fa-pencil fa-fw"></i>  Редактировать',array('expense/update&id='.$value->expense_id))?>
            </td>
        </tr>
        <? $count++; } ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4">Общая сумма</th>
        <th colspan="2"><?=$summa?></th>
        <th colspan="2"><?=$summaP?></th>
    </tr>
    </tfoot>
</table>