<?$cnt = 1; $expense = new Expense();
$depRealize = new DepFaktura(); $sumCostPrice = 0; $sumPrice = 0; $beforeSumCostPrice = 0; $beforeSumPrice = 0; $balance = new Balance();
$sumRealized = 0; $sumInRealized = 0; $sumInExp = 0; $startCount = 0; $endCount = 0; $curEndCount = 0; $sumFaktCost =0;?>
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
            <th>Остаток на начало</th>
            <th>Получено со склада</th>
            <th>Внутр прих</th>
            <th>Внутр расх</th>
            <th>План расход</th>
            <th>Факт. расход</th>
            <th>Остаток план</th>
            <th>Остаток Факт.</th>
            <th>Выручка</th>
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
            $costPrice = $expense->getDepCost($val['department_id'],$from,$till);
            $realized = $depRealize->getDepRealizesSumm($from,$till,$val['department_id']);
            $inRealized = $depRealize->getDepInRealizesSumm($from,$till,$val['department_id']);
            $inexp = $depRealize->getDepInExp($from,$till,$val['department_id']);
            $price = $expense->getDepIncome($val['department_id'],$from,$till);
            $tempBalance = $balance->getDepBalanceSumm($from,$till,$val['department_id']);
            //$beforeTempBalance = $balance->getDepBalanceSumm($beforeDates,$val['department_id']);
            //$beforeCostPrice = $expense->getDepCost($val['department_id'],$beforeDates);
            //$beforePrice = $expense->getDepIncome($val['department_id'],$beforeDates);
            $sumCostPrice = $sumCostPrice + $costPrice ;
            $sumPrice = $sumPrice + $price;
            $startCount = $startCount + $tempBalance[0];
            $endCount = $endCount + $tempBalance[1];
            $curEndCount = $curEndCount + $tempBalance[2];
            $sumRealized = $sumRealized + $realized ;
            $sumInRealized = $sumInRealized + $inRealized;
            $sumInExp = $sumInExp + $inexp;
            //$beforeSumCostPrice = $beforeSumCostPrice + $beforeCostPrice;
            //$beforeSumPrice = $beforeSumPrice + $beforePrice;
            $factCostPrice = $tempBalance[4]+$realized+$inRealized-$inexp-$tempBalance[2];
            $sumFaktCost = $sumFaktCost + $factCostPrice;


            ?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$val['name']?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|',array('class'=>'view'))?></td>
                <td><?=number_format($tempBalance[0],0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|begin',array('class'=>'view'))?></td>
                <td><?=number_format($realized,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|realize',array('class'=>'view'))?></td>
                <td><?=number_format($inRealized,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|inRealize',array('class'=>'view'))?></td>
                <td><?=number_format($inexp,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|inExp',array('class'=>'view'))?></td>
                <td><?=number_format($costPrice,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|costPrice',array('class'=>'view'))?></td>
                <td><?=number_format($factCostPrice,0,',',' ')?></td>
                <td><?=number_format($tempBalance[1],0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|end',array('class'=>'view'))?></td>
                <td><?=number_format($tempBalance[2],0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|curEnd',array('class'=>'view'))?></td>
                <td><?=number_format($price,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>',$val['department_id'].'|'.$val['name'].'|price',array('class'=>'view'))?></td>
                <?if($price != 0){
                    if($costPrice == 0){?>
                        <td><?=number_format(0,0,',',' ')?></td>
                    <?}else{?>
                        <td><?=number_format($price*100/($costPrice),0,',',' ')?></td>
                    <?} if($factCostPrice == 0){?>
                        <td><?=number_format(0,0,',',' ')?></td>
                    <?}else{?>
                        <td><?=number_format($price*100/($factCostPrice),0,',',' ')?></td>
                    <?}?>
                <?}else{?>
                    <td>0</td>
                    <td>0</td>
                <?}?>
                <td><?=number_format($factCostPrice/2,0,',',' ')?></td>
                <td><?=number_format($price-$factCostPrice,0,',',' ')?></td>
                <td><?=number_format(($price-$factCostPrice)-$factCostPrice/2,0,',',' ')?></td>
                <!--<td>
                <?//if(($price-$costPrice)-$costPrice/2 > ($beforePrice-$beforeCostPrice)-$beforeCostPrice/2){?>
                    <span class="green"><i class="fa fa-caret-up"></i></span>
                <?//}elseif(($price-$costPrice)-$costPrice/2 < ($beforePrice-$beforeCostPrice)-$beforeCostPrice/2){?>
                    <span class="red"><i class="fa fa-caret-down"></i></span>
                <?//}else{}?>
            </td>-->
            </tr>
            <?$cnt++;}

        $expRealize = $balance->getExpBalance($from,$till);
        $sumCostPrice = $sumCostPrice + $expRealize;
        $sumRealized = $sumRealized + $expRealize;?>
        <tr>
            <td><?=$cnt++?></td>
            <td>Внутренний расход</td>
            <td></td>
            <td><?=number_format($expRealize,0,',',' ')?> <?=CHtml::link('<i class="icon-eye-open"></i>','0|Внутренний расход|other',array('class'=>'view'))?></td>
            <td></td>
            <td></td>
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
            <th><?=number_format($sumInRealized,0,',',' ')?></th>
            <th><?=number_format($sumInExp,0,',',' ')?></th>
            <th><?=number_format($sumCostPrice,0,',',' ')?></th>
            <th><?=number_format($startCount+$sumRealized-$curEndCount,0,',',' ')?></th>
            <th><?=number_format($endCount,0,',',' ')?></th>
            <th><?=number_format($curEndCount,0,',',' ')?></th>
            <th><?=number_format($sumPrice,0,',',' ')?></th>
            <th><?=number_format($sumPrice*100/($sumCostPrice),0,',',' ')?></th>
            <th><?=number_format($sumPrice*100/($startCount+$sumRealized-$curEndCount),0,',',' ')?></th>
            <th><?=number_format($sumFaktCost/2,0,',',' ')?></th>
            <th><?=number_format($sumPrice-$sumFaktCost,0,',',' ')?></th>
            <th><?=number_format(($sumPrice-$sumFaktCost)-$sumFaktCost/2,0,',',' ')?></th>
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
