<?$cnt = 1; $expense = new Expense();
$depRealize = new DepFaktura(); $sumCostPrice = 0; $sumPrice = 0; $beforeSumCostPrice = 0; $beforeSumPrice = 0; $balance = new Balance();
$sumRealized = 0; $sumInRealized = 0; $sumInExp = 0;$startCount1 = 0; $startCount = 0; $endCount = 0; $curEndCount = 0; $sumFaktCost =0;?>
<style>
    td,th{
        font-size: 12px;
    }
    .green{
        color: green;
    }
    .red{
        color: red;
    }
    .rait{
        padding: 0 8px!important;
    }
    .view{
        float: right;
    }
    .table-hover tbody tr:hover>td, .table-hover tbody tr:hover>th {
        background-color: #D9F2F5;
    }
    .modal{
        width: 90%!important;
        left: 24% !important;
        top:0%!important;
    }
    .modal-body{
        max-height: 100%!important;
    }
</style>
<table class="table table-bordered table-hover" id="dataTable">
    <thead>
    <tr>
        <th></th>
        <th>Наимен. Отдела</th>
        <th>Выручка</th>
    </tr>
    </thead>
    <tbody>
    <?foreach ($model as $val) {
        $price = $expense->depIncome($val['department_id'],$from,$till);
        $sumPrice = $sumPrice + $price;


        ?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$val['name']?></td>
            <td><?=number_format($price,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|price',array('class'=>'view'))?></td>

        </tr>
        <?$cnt++;}
?>
    </tbody>
    <tfoot>
    <tr>
        <th></th>
        <th>Итого</th>
        <th><?=number_format($sumPrice,0,',',' ')?></th>
        <!--<th></th>-->
    </tr>
    </tfoot>
</table>
<script>
    $(document).ready(function(){
        $("#dataTable a.view").click(function(){
            data=$(this).attr("href").split("|")
            $("#myModalHeader").html(data[1]);
            if(data[2] != '') {
                $("#myModalBody").load("/report/ajaxDetail?depId=" + data[0] + "&key=" + data[2] + '&dates=<?=$from?>&till=<?=$till?>');
            }
            else{
                $("#myModalBody").load("/depStorage/allStorage?depId=" + data[0] + '&dates=<?=$from?>&till=<?=$till?>');
            }
            $("#myModal").modal();
            return false;
        });
    });
    jQuery(document).on('click','#dataTable a.view',function(){

    });
</script>

<?php  $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>

    <h4 id="myModalHeader">Modal header</h4>
</div>

<div class="modal-body" id="myModalBody">
    <p>One fine body...</p>
</div>

<div class="modal-footer">
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'выйти',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
</div>

<?php  $this->endWidget(); ?>
