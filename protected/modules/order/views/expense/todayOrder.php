<? $count = 1; $expense = new Expense(); $curPercent = 0; $summaP = 0; $summa = 0;?>
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Дата и время</th>
        <th>Ответственный</th>
        <th>Сумма</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach( $model as $value){?>
        <? if($value->getRelated('employee')->check_percent == 1)
            $curPercent = $percent;
        else
            $curPercent = 0;
            
            $temp = $expense->getExpenseSum($value->expense_id,$dates);
        ?>

        <tr>

            <td><span style="display: none"><?=$value->expense_id?></span><?=$count?></td>
            <td><?=$value->order_date?></td>
            <td><?=$value->getRelated('employee')->name?></td>

            <td><?=number_format($temp/100*$curPercent + $temp,0,'.',','); $summaP = $summaP + $temp/100*$curPercent + $temp?></td>
            <td>
                <?=CHtml::link('Отказ',array('/orders/orderRefuse?id='.$value->expense_id),array('id'=>'update','target'=>'blank'))?>
            </td>
        </tr>
        <? $count++; } ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="3">Общая сумма</th>
        <th colspan="2"><?=$summaP?></th>
    </tr>
    </tfoot>
</table>
<style>
    .modal-dialog{
        width: auto!important;
        margin: 0!important;
    }
</style>