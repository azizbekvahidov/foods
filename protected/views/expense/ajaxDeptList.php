<style>
    .btn {
        padding: 0px 12px;
    }
    .modal{
        left:50%!important;
    }
    .modal-content{
        box-shadow: none!important;
        border: none!important;
    }
</style>
<? $count = 1; $expense = new Expense(); $curPercent = 0; $summaP = 0; $summa = 0; $func = new Functions()?>
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
            <td><?=$func->getDebtorName($value->debtor_type,$value->debtor_id) ?></td>
            <td>
                <?=CHtml::link('Оплатить долг',array('expense/debtClose?id='.$value->expense_id),array('class'=>'btn btn-success debt-close'))?>
                <?=CHtml::link('Оплатить долг по терминалу',array('id='.$value->expense_id),array('class'=>'btn btn-info term-debt-close','data-toggle'=>"modal",'data-target'=>"#modal-sm"))?>
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
    jQuery(document).on('click','.term-debt-close',function() {
        JQuery(this).attr('')
    };
    jQuery(document).on('click','#saveTerm',function() {
        var th = this,
            afterDelete = function(){};
        console.log(jQuery('.term-debt-close').attr('href'));
        jQuery('.term-debt-close').parent().parent().remove();
        jQuery.ajax({
            type: 'POST',
            url: jQuery('.term-debt-close').attr('href'),
            success: function(data) {
                $("#termSum").val('');
                $("#expIdFSum").val('');
                $('#modal-sm').modal('hide');
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
<div class="modal fade bs-example-modal-sm" id="modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">сумма терминал</h4>
        </div>
        <div class="modal-content">
            <input type="text" value="" id="expIdFSum" style="display: none">
            <input type="number" id="termSum" class="form-control"/>
        </div>
        <div class="modal-footer">
            <button type="button" id="saveTerm" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        </div>
    </div>
</div>