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
        <th>Оплаченная дата</th>
        <th>Ответственный за заказ</th>
        <th>Стол №</th>
        <th>Процент обслуживания</th>
        <th>Сумма счета</th>
        <th>Комментарий</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach( $model as $value){
        $procent = new Percent();
        $percent = $procent->getPercent(date('Y-m-d',strtotime($value->getRelated('expense')->order_date)));
        ?>
        <? if($value->getRelated('expense')->getRelated('employee')->check_percent == 1)
            $curPercent = $percent;
        else
            $curPercent = 0;

        $temp = $value->getRelated('expense')->expSum;
        ?>

        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('expense')->order_date?></td>
            <td><?=$value->d_date?></td>
            <td><?=$value->getRelated('expense')->getRelated('employee')->name?></td>
            <td><?=$value->getRelated('expense')->table?></td>
            <td><?=$curPercent?></td>
            <td><?=number_format($temp,0,'.',','); $summa = $summa + $temp?></td>
            <td><?=$value->getRelated('expense')->comment?></td>
            <td>
                <?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view?id='.$value->getRelated('expense')->employee_id.'&order_date='.$value->getRelated('expense')->order_date))?>
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