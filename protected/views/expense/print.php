<!--<div ><?  $expense = new Expense()?><?php $this->widget('application.extensions.print.printWidget', array(
    'printedElement'=>'#datas-'.$empId,
    'htmlOptions'=>array('id'=>'print')
));
?></div>-->
<? foreach($empId as $key => $val){

    $model = Expense::model()->with('order')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND t.kind = :kind',array(':dates'=>$dates,':empId'=>$val,':kind'=>0));
    $counting = 1; $count = 0;
    ?>
    <? if(!empty($model)){?>
        <h3><?=Employee::model()->findByPk($val)->name?></h3>
<table class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Дата и время</th>
        <th>Стол №</th>
        <th>Сумма счета</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?foreach ($model as $value) {?>
        <tr>
            <td><?=$counting?></td>
            <td><?=$value->order_date?></td>
            <td><?=$value->table?></td>
            <td><?=$expense->getExpenseSum($value->expense_id,$value->order_date)?></td>
            <td><?//=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view&id='.$value->employee_id.'&order_date='.$value->order_date))?></td>
        </tr>
        <? $count = $count + $expense->getExpenseSum($value->expense_id,$value->order_date)?>
        <?$counting++;}    ?>
    <tr>
        <th colspan="3">Сумма</th>
        <th><?=$count?></th>
        <th></th>
    </tr>

    </tbody>
</table>
        <?}?>
<?}?>