<?$cnt = 1; $expense = new Expense();
$depRealize = new DepFaktura(); $sumCostPrice = 0; $sumPrice = 0; $beforeSumCostPrice = 0; $beforeSumPrice = 0; $balance = new Balance();
$sumRealized = 0; $startCount = 0; $endCount = 0; $curEndCount = 0;?>
<style>
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
        width: 100%;
        left: 19%;
    }
</style>
<table class="table table-bordered table-hover" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Наимен. Отдела</th>
            <th>Остаток на начало</th>
            <th>Получено со склада</th>
            <th>План расход</th>
            <th>Факт. расход</th>
            <th>Остаток план</th>
            <th>Остаток Факт.</th>
            <th style="padding: 8px 16px!important;">Выручка</th>
            <th>% от план.</th>
            <th>% от факт.</th>
            <th>наценка план</th>
            <th>наценка факт</th>
            <th>отклон (+,-)</th>
           <!-- <th>тренд ср.стат.</th>-->
        </tr>
    </thead>
    <tbody>
    <?foreach ($model as $val) {
        //$beforeDates = date('Y-m-d',strtotime($dates)-86400);
        $costPrice = $expense->getDepCost($val['department_id'],$dates);
        $realized = $depRealize->getDepRealizesSumm($dates,$val['department_id']);
        $price = $expense->getDepIncome($val['department_id'],$dates);
        $tempBalance = $balance->getDepBalanceSumm($dates,$val['department_id']);
        //$beforeTempBalance = $balance->getDepBalanceSumm($beforeDates,$val['department_id']);
        //$beforeCostPrice = $expense->getDepCost($val['department_id'],$beforeDates);
        //$beforePrice = $expense->getDepIncome($val['department_id'],$beforeDates);
        $sumCostPrice = $sumCostPrice + $costPrice ;
        $sumPrice = $sumPrice + $price;
        $startCount = $startCount + $tempBalance[0];
        $endCount = $endCount + $tempBalance[1];
        $curEndCount = $curEndCount + $tempBalance[2];
        $sumRealized = $sumRealized + $realized ;
        //$beforeSumCostPrice = $beforeSumCostPrice + $beforeCostPrice;
        //$beforeSumPrice = $beforeSumPrice + $beforePrice;
        ?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$val['name']?></td>
            <td><?=number_format($tempBalance[0],0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|begin',array('class'=>'view'))?></td>
            <td><?=number_format($realized,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|realize',array('class'=>'view'))?></td>
            <td><?=number_format($costPrice,0,',',' ')?></td>
            <td><?=number_format($tempBalance[0]+$realized-$tempBalance[2],0,',',' ')?></td>
            <td><?=number_format($tempBalance[1],0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|end',array('class'=>'view'))?></td>
            <td><?=number_format($tempBalance[2],0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|curEnd',array('class'=>'view'))?></td>
            <td><?=number_format($price,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|price',array('class'=>'view'))?></td>
            <?if($price != 0){?>
            <td><?=number_format($price*100/($costPrice),0,',',' ')?></td>
            <td><?=number_format($price*100/($tempBalance[0]+$realized-$tempBalance[2]),0,',',' ')?></td>
            <?}else{?>
                <td>0</td>
                <td>0</td>
            <?}?>
            <td><?=number_format($costPrice/2,0,',',' ')?></td>
            <td><?=number_format($price-$costPrice,0,',',' ')?></td>
            <td><?=number_format(($price-$costPrice)-$costPrice/2,0,',',' ')?></td>
            <!--<td>
                <?//if(($price-$costPrice)-$costPrice/2 > ($beforePrice-$beforeCostPrice)-$beforeCostPrice/2){?>
                    <span class="green"><i class="fa fa-caret-up"></i></span>
                <?//}elseif(($price-$costPrice)-$costPrice/2 < ($beforePrice-$beforeCostPrice)-$beforeCostPrice/2){?>
                    <span class="red"><i class="fa fa-caret-down"></i></span>
                <?//}else{}?>
            </td>-->
        </tr>
    <?$cnt++;}

    $expRealize =$balance->getExpBalance($dates);
    $sumCostPrice = $sumCostPrice + $expRealize;
    $sumRealized = $sumRealized + $expRealize;?>
        <tr>
            <td><?=$cnt++?></td>
            <td>Внутренний расход</td>
            <td></td>
            <td><?=number_format($expRealize,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>','0|Внутренний расход|other',array('class'=>'view'))?></td>
            <td><?=number_format($expRealize,0,',',' ')?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Итого</th>
            <th><?=number_format($startCount,0,',',' ')?> </th>
            <th><?=number_format($sumRealized,0,',',' ')?></th>
            <th><?=number_format($sumCostPrice,0,',',' ')?></th>
            <th><?=number_format($startCount+$sumRealized-$curEndCount,0,',',' ')?></th>
            <th><?=number_format($endCount,0,',',' ')?></th>
            <th><?=number_format($curEndCount,0,',',' ')?></th>
            <th><?=number_format($sumPrice,0,',',' ')?></th>
            <th><?=number_format($sumPrice*100/($sumCostPrice),0,',',' ')?></th>
            <th><?=number_format($sumPrice*100/($startCount+$sumRealized-$curEndCount),0,',',' ')?></th>
            <th><?=number_format($sumCostPrice/2,0,',',' ')?></th>
            <th><?=number_format($sumPrice-$sumCostPrice,0,',',' ')?></th>
            <th><?=number_format(($sumPrice-$sumCostPrice)-$sumCostPrice/2,0,',',' ')?></th>
            <!--<th></th>-->
        </tr>
    </tfoot>
</table>
<script>
    jQuery(document).on('click','#dataTable a.view',function(){
        data=$(this).attr("href").split("|")
        $("#myModalHeader").html(data[1]);
        $("#myModalBody").load("/report/ajaxDetail?depId="+data[0]+"&key="+data[2]+'&dates=<?=$dates?>');
        $("#myModal").modal();
        return false;
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