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
<? $count = 1; $expense = new Expense(); $curPercent = 0; $summaP = 0; $summa = 0; $func = new Functions(); $balance = 0;?>
<table class="table table-hover table-bordered" id="dataTable">
    <thead>
    <tr>
        <th></th>
        <th>Дата и время</th>
        <th>Ответственный за заказ</th>
        <th>Стол №</th>
        <th>Процент обслуживания</th>
        <th>Сумма счета</th>
        <th>Комментарий</th>
        <th>Оплачено</th>
        <th>Остаток</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach( $model as $value){
        $procent = new Percent();
        $percent = $procent->getPercent(date('Y-m-d',strtotime($value["order_date"])));
        ?>
        <? if($value["check_percent"] == 1)
            $curPercent = $percent;
        else
            $curPercent = 0;
        $balance = $balance + ($value["expSum"] - $value["debtPayed"]);
        $temp = $value["expSum"];
        ?>

        <tr>
            <td><?=$count?></td>
            <td><?=$value["order_date"]?></td>
            <td><?=$value["name"]?></td>
            <td><?=$value["table"]?></td>
            <td><?=$curPercent?></td>
            <td><?=number_format($temp,0,'.',','); $summa = $summa + $temp?></td>
            <td><?=$value["comment"]?></td>
            <td><?=$value["debtPayed"]?></td>
            <td><?=$value["expSum"] - $value["debtPayed"]?></td>
            <td>
                <?=CHtml::link('Оплатить долг',array('expense/debtClose?id='.$value["expense_id"]),array('class'=>'btn btn-success debt-close'))?>
                <?=CHtml::link('Оплатить долг по терминалу',array('javascript:;'),array('id'=>$value["expense_id"],'class'=>'btn btn-info term-debt-close','data-toggle'=>"modal",'data-target'=>"#modal-sm"))?>
<!--                --><?//=CHtml::link('Оплатить часть',array('javascript:;'),array('id'=>$value["expense_id"],'class'=>'btn btn-warning paid-debt','data-toggle'=>"modal",'data-target'=>"#paidDebtModal"))?>
                <?=CHtml::link('Закрыть',array('expense/debtCloseJust?id='.$value["expense_id"]),array('class'=>'btn btn-danger debt-close-just'))?>
                <?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view?id='.$value["employee_id"].'&order_date='.$value["order_date"]))?>
            </td>
        </tr>
        <? $count++; } ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5">Общая сумма</th>
        <th colspan="1"><?=$summa?></th>
        <th colspan="2"></th>
        <th colspan="1"><?=$balance?></th>
    </tr>
    </tfoot>
</table>
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

<div class="modal fade bs-example-modal-sm" id="paidDebtModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Оплата части долга</h4>
        </div>
        <div class="modal-content">
            <input type="text" value="" id="expIdPiaid" style="display: none">
            <input type="number" id="paidDebt" class="form-control"/>
        </div>
        <div class="modal-footer">
            <button type="button" id="savePaidDebt" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        </div>
    </div>
</div>