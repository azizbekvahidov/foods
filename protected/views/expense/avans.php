<style>
    .btn {
        padding: 0px 12px;
    }
    .modal{
        bottom: 300px;
        overflow-y: auto;
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
        <th>Оплаченная сумма</th>
        <th>Сумма счета</th>
        <th>Разница</th>
        <th>Комментарий</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach( $model as $value){
        ?>

        <tr>
            <td><?=$count?></td>
            <td><?=date("Y-m-d H:i:s",$value["prepCreate"])?></td>
            <td><?=$value["prepaidSum"]?></td>
            <td><?=$value["expSum"]?></td>
            <td id="difference"><?=$value["expSum"] - $value["prepaidSum"]?></td>
            <td><?=$value["comment"]?></td>
            <td>
                <?=CHtml::link('Оплатить',array('javascript:;',"id"=>"paid"),array('id'=>$value["expense_id"],'class'=>'btn btn-success prep-close','data-toggle'=>"modal",'data-target'=>"#modal-sm"))?>
                <?=CHtml::link('Оплатить терминалу',array('javascript:;'),array('id'=>$value["expense_id"],'class'=>'btn btn-info term-prep-close','data-toggle'=>"modal",'data-target'=>"#modal-sm"))?>
                <?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view?id='.$value["employee_id"].'&order_date='.$value["order_date"]))?>
            </td>
        </tr>
        <? $count++; } ?>
    </tbody>
</table>
<script>
    var expId = 0;
    var prepStatus = 0;
    jQuery(document).on('click','.term-prep-close',function() {
        prepStatus = 1;
        expId = $(this).attr('id');
        $("#Sum").val($("#difference").text());
    });
    jQuery(document).on('click','.prep-close',function() {
        expId = $(this).attr('id');
        $("#Sum").val($("#difference").text());
    });
    jQuery(document).on('click','#saveTerm',function() {
        $.ajax({
            type: 'POST',
            url: '/expense/paidPrepaid',
            data: 'id='+expId+"&prepStatus="+prepStatus+"&sum="+$("#Sum").val(),
            success: function() {
                $("#Sum").val('');
                $('#modal-sm').modal('hide');
            }
        });
        return false;
    });
</script>
<div class="modal fade bs-example-modal-sm" id="modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Оплата аванса</h4>
        </div>
        <div class="modal-content">
            <input type="number" id="Sum" class="form-control"/>
        </div>
        <div class="modal-footer">
            <button type="button" id="saveTerm" class="btn btn-primary">Оплатить</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        </div>
    </div>
</div>
