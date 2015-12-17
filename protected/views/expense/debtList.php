<style>
    .btn{
        padding: 0px 12px;
    }
</style>
<? $count = 1; $expense = new Expense(); $curPercent = 0; $summaP = 0; $summa = 0;?>
<table class="table table-hover table-bordered" id="dataTable">
    <thead>
    <tr>
        <th></th>
        <th>Дата и время</th>
        <th>Ответственный за заказ</th>
        <th>Стол №</th>
        <th>Сумма</th>
        <th>Процент обслуживания</th>
        <th>Сумма счета</th>
        <th>Комментарий</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach( $model as $value){
        $procent = new Percent();
        $percent = $procent->getPercent(date('Y-m-d',strtotime($value->order_date)));
        ?>
        <? if($value->getRelated('employee')->check_percent == 1)
            $curPercent = $percent;
        else
            $curPercent = 0;

        $temp = $expense->getExpenseSum($value->expense_id,$value->order_date);
        ?>

        <tr>
            <td><?=$count?></td>
            <td><?=$value->order_date?></td>
            <td><?=$value->getRelated('employee')->name?></td>
            <td><?=$value->table?></td>
            <td><?=number_format($temp,0,'.',','); $summa = $summa + $temp?></td>
            <td><?=$curPercent?></td>
            <td><?=number_format($temp/100*$curPercent + $temp,0,'.',','); $summaP = $summaP + $temp/100*$curPercent + $temp?></td>
            <td><?=$value->comment?></td>
            <td>
                <?=CHtml::link('Оплатить долг',array('expense/debtClose?id='.$value->expense_id),array('class'=>'btn btn-success debt-close'))?>
                <?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view?id='.$value->employee_id.'&order_date='.$value->order_date))?>
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
<script>
    jQuery(document).on('click','#dataTable a.debt-close',function() {
        if(!confirm('Вы уверены, что этот счет оплачен')) return false;
        var th = this,
            afterDelete = function(){};
        jQuery(this).parent().parent().remove()
        jQuery.ajax({
            type: 'POST',
            url: jQuery(this).attr('href'),
            success: function(data) {
                //jQuery('#dataTable').yiiGridView('update');
                afterDelete(th, true, data);
            },
            error: function(XHR) {
                return afterDelete(th, false, XHR);
            }
        });
        return false;
    });
</script>