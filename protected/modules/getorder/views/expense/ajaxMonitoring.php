<? $cnt = 1; $expense = new Expense()?>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Время заказа</th>
            <th>Стол</th>
            <th>Ответственный</th>
            <th>Сумма</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($model as $value) { $number = $expense->getOrderNumber($value['expense_id']); $realN = $expense->getRealOrderNumber($value['expense_id'])?>
        <?if($number == 0){?>
            <tr class="error">
        <?}elseif($number != 0 && $number != $realN){?>
            <tr class="info">
        <?}else{?>
            <tr class="success">
        <?}?>
            <td><?=$cnt?></td>
            <td><?=$value['order_date']?></td>
            <td><?=$value['table']?></td>
            <td><?=$value['name']?></td>
            <td><?=$expense->getExpenseSum($value['expense_id'],$value['order_date'])?></td>
            <?if($number != 0 && $number != $realN){?>
                <td><?=CHtml::link('<i class="fa fa-print"></i>  Печать',array('/order/expense/printExp?exp='.$value['expense_id']),array('class'=>'btn btnPrint'))?></td>
            <?}?>

        </tr>
        <?$cnt++;}
        ?>
    </tbody>
</table>