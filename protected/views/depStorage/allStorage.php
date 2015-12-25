<div style="float: right;">
    <?//=CHtml::link('Просмотреть остатки',array(),array('class'=>'btn btn-default'))?>
</div>

<? $count = 1; $prod = new Products(); $stuff = new Halfstaff(); $summ = 0; $sumStart = 0; $sumIn = 0; $sumOut = 0;?>

<table class=" table table-bordered table-striped table-hover" >
        <tr>
            <th></th>
            <th>Название</th>
            <th>Начальное сальдо</th>
            <th>Приход</th>
            <th>Расход</th>
            <th>Расход на загатовки</th>
            <th colspan="2" style="text-align: center; width: 100px;">Перемещение
                <table>
                    <tr style="border-top: 1px solid #ccc;">
                        <th style="text-align: center; border: 0; background: none;">Приход</th>
                        <th style="text-align: center; background: none;">Расход</th>
                    </tr>
                </table>
            </th>
            <th>Конечное сальдо</th>
            <th>Сумма</th>
        </tr>
    <tbody >

    <?  if(!empty($model)){?>
        <tr>
            <th colspan="9">Продукты</th>
        </tr>
    <? foreach($model as $value){?>
        <? if($value->startCount != 0 || $inProduct[$value->prod_id] != 0 || $outProduct[$value->prod_id] != 0 || $outStuffProd[$value->prod_id] != 0 || $depIn[$value->prod_id] != 0 || $depOut[$value->prod_id] != 0 || $value->endCount !=0 ){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('products')->name?></td>
            <td><?=number_format( $value->startCount,2,',','')?></td>
            <td><?=number_format( $inProduct[$value->prod_id],2,',','')?></td>
            <td><?=number_format( $outProduct[$value->prod_id],2,',','')?></td>
            <td><?=number_format( $outStuffProd[$value->prod_id],2,',','')?></td>        
            <th style="text-align: center;"><?=number_format( $depIn[$value->prod_id],2,',','')?></th>
            <th style="text-align: center;"><?=number_format( $depOut[$value->prod_id],2,',','')?></th>
            </td>
            <td>
                <?=number_format( $value->endCount,2,',','')?>
            </td>
            <td><?=number_format( $value->endCount*$prod->getCostPrice($value->prod_id,$value->b_date),0,',','');
                $summ = $summ + $value->endCount*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumIn = $sumIn + ($inProduct[$value->prod_id] + $depIn[$value->prod_id])*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumOut = $sumOut + ($outProduct[$value->prod_id]+$depOut[$value->prod_id]+$outStuffProd[$value->prod_id])*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumStart = $sumStart + $value->startCount*$prod->getCostPrice($value->prod_id,$value->b_date)?></td>

        </tr>
        <?$count++; }?>
        <? }}?>
    <?  if(!empty($curStuff)){?>
        <tr>
            <th colspan="9">Прлуфабрикаты</th>
        </tr>
    <?;foreach($curStuff as $value){ ?>
            <? //if(number_format( $value->startCount,2) != 0 || number_format( $inProduct[$value->prod_id],2) != 0 || number_format( $outProduct[$value->prod_id],2) != 0){?>
            <? if($value['startCount'] != 0 || $instuff[$value['prod_id']] != 0 || $outStuff[$value['prod_id']] != 0 || $depStuffIn[$value['prod_id']] != 0 || $depStuffOut[$value['prod_id']] != 0 || $value['endCount'] != 0 ){?>
            <tr>
                <td><?=$count?></td>
                <td><?=$value['name']?></td>
                <td><?=number_format( $value['startCount'],2,',','')?></td>
                <td><?=number_format( $instuff[$value['prod_id']],2,',','')?></td>
                <td><?=number_format( $outStuff[$value['prod_id']],2,',','')?></td>
                <td><?=number_format(0,2,',','')?></td>
                <th style="text-align: center;"><?=number_format( $depStuffIn[$value['prod_id']],2,',','')?></th>
                <th style="text-align: center;"><?=number_format( $depStuffOut[$value['prod_id']],2,',','')?></th>
                <td>
                    <?=number_format( $value['endCount'],2,',','')?>
                </td>
                <td><?=number_format( $value['endCount']*$stuff->getCostPrice($value['prod_id'],$value['b_date']),0,',','');
                    $summ = $summ + $value['endCount']*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumIn = $sumIn + ($instuff[$value['prod_id']]+$depStuffIn[$value['prod_id']])*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumOut = $sumOut + ($outStuff[$value['prod_id']]+$depStuffOut[$value['prod_id']])*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumStart = $sumStart + $value['startCount']*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    ?></td>
            </tr>
        <?$count++; }?>
            <?
            //}
        }}?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Сумма</th>
            <th colspan="4"><?=number_format($sumStart,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumIn,0,',',' ')?></th>
            <th colspan="2"><?=number_format($sumOut,0,',',' ')?></th>
            <th><?=number_format($summ,0,',',' ')?></th>
        </tr>
    </tfoot>
</table>