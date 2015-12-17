<? $counting = 1; $count = 0; $expense = new Expense(); ?>
<div id="datas-<?=$empId?>">
<table class="table table-bordered dataGrid" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Дата и время</th>
            <th>Стол №</th>
            <th>Сумма</th>
            <th>Процент на обслуживание</th>
            <th>Сумма счета</th>
            <th>
            </th>
        </tr>
    </thead>
    <tbody>
    <? if(!empty($model))?>
        <?foreach ($model as $value) { $curPercent = 0; $percent = new Percent();
            if ( $value->getRelated( 'employee' )->check_percent == 1 ) {
                $curPercent = $percent->getPercent($value->order_date);
            }
        $temp = $expense->getExpenseSum($value->expense_id,$value->order_date);
        ?>
        <tr>
            <td><?=$counting?></td>
            <td><?=$value->order_date?></td>
            <td><?=$value->table?></td>
            <td><?=number_format($temp,0,'.',',')?></td>
            <td><?=$curPercent?></td>
            <td><?=number_format($temp/100*$curPercent + $temp,0,'.',',')?></td>
            <td><?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view?id='.$value->employee_id.'&order_date='.$value->order_date))?></td>
        </tr>
        <? $count = $count + $temp?>
    <?$counting++;}    ?>
        <tr>
            <th colspan="5">Сумма без процентов</th>
            <th><?=number_format($count,0,'.',',')?></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="5">Процент на обслуживание</th>
            <th><?=$curPercent?></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="5">Общая Сумма</th>
            <th><?=number_format(($count/100*$curPercent + $count),0,'.',',')?></th>
            <th></th>
        </tr>

    </tbody>
</table>
</div>