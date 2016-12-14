<div style="float: right;">
    <?//=CHtml::link('Просмотреть остатки',array(),array('class'=>'btn btn-default'))?>
</div>

<? $function = new Functions(); $count = 1; $prod = new Products(); $test = 0; $stuff = new Halfstaff(); $summEnd = 0;
$summ = 0; $sumStart = 0; $sumIn = 0; $sumInDep = 0; $sumOutDep = 0; $sumToStuff = 0; $sumOut = 0; $summFact = 0;?>

<table class=" table table-bordered table-hover" >
        <tr>
            <th></th>
            <th>Название</th>
            <th>Начальное сальдо</th>
            <th>Получено со склада</th>
            <th>Внутр прих</th>
            <th>Внутр расх</th>
            <th>План расход</th>
            <th>Факт. расход</th>
            <th>Расход на загатовки</th>
            <th>Остаток план</th>
            <th>Остаток Факт.</th>
            <th>Разница</th>
            <th>Сумма</th>
        </tr>
    <tbody>

    <?  if(!empty($model)){?>
        <tr>
            <th colspan="10">Продукты</th>
        </tr>
    <? foreach($model as $value){?>
        <? if($value->startCount != 0 || $inProduct[$value->prod_id] != 0 || $outProduct[$value->prod_id] != 0 || $outStuffProd[$value->prod_id] != 0 || $depIn[$value->prod_id] != 0 || $depOut[$value->prod_id] != 0 || $value->endCount !=0 || $value->CurEndCount !=0){?>
                <? $factOutProd = $value->startCount + $inProduct[$value->prod_id] + $depIn[$value->prod_id] - $depOut[$value->prod_id] - $value->CurEndCount?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('products')->name?></td>
            <td><?=number_format( $value->startCount,2,',','')?></td>
            <td><?=number_format( $inProduct[$value->prod_id],2,',','')?></td>
            <td><?=number_format( $depIn[$value->prod_id],2,',','')?></td>
            <td><?=number_format( $depOut[$value->prod_id],2,',','')?></td>
            <td><?=number_format( $outProduct[$value->prod_id],2,',','')?></td>
            <td><?=number_format( $factOutProd,2,',','')?></td>
            <td><?=number_format( $outStuffProd[$value->prod_id],2,',','')?></td>
            <td><?=number_format( $value->endCount,2,',','')?></td>
            <td><?=number_format( $value->CurEndCount,2,',','')?></td>
            <td><?=number_format( $value->endCount-$value->CurEndCount,2,',','')?></td>
            <td><?=number_format( ($value->endCount-$value->CurEndCount)*$prod->getCostPrice($value->prod_id,$value->b_date),0,',','');
                $summ = $summ + $value->endCount*$prod->getCostPrice($value->prod_id,$value->b_date);
                $test = $test +($value->endCount-$value->CurEndCount)*$prod->getCostPrice($value->prod_id,$value->b_date);
                $summEnd = $summEnd + $value->CurEndCount*$prod->getCostPrice($value->prod_id,$value->b_date);
                $summFact = $summFact + $factOutProd*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumIn = $sumIn + $inProduct[$value->prod_id]*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumInDep = $sumInDep + $depIn[$value->prod_id]*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumOutDep = $sumOutDep + $depOut[$value->prod_id]*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumOut = $sumOut + $outProduct[$value->prod_id]*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumToStuff = $sumToStuff + $outStuffProd[$value->prod_id]*$prod->getCostPrice($value->prod_id,$value->b_date);
                $sumStart = $sumStart + $value->startCount*$prod->getCostPrice($value->prod_id,date('Y-m-d',strtotime($value->b_date)-86400))?></td>

        </tr>
        <?$count++; }?>
        <? }}?><tr>
        <th colspan="2">Сумма</th>
        <th colspan=""><?=number_format($sumStart,0,',',' ')?></th>
        <th colspan=""><?=number_format($sumIn,0,',',' ')?></th>
        <th colspan=""><?=number_format($sumInDep,0,',',' ')?></th>
        <th colspan=""><?=number_format($sumOutDep,0,',',' ')?></th>
        <th colspan=""><?=number_format($sumOut,0,',',' ')?></th>
        <th colspan=""><?=number_format($summFact,0,',',' ')?></th>
        <th colspan=""><?=number_format($sumToStuff,0,',',' ')?></th>
        <th><?=number_format($summ,0,',',' ')?></th>
        <th colspan="2"><?//=number_format($summEnd,0,',',' ')?></th>
        <th><?//=$test?></th>
        <?$summ1 = $summ;
        $summEnd1 = $summEnd;
        $summFact1 = $summFact;
        $sumIn1 = $sumIn;
        $sumInDep1 = $sumInDep;
        $sumOutDep1 = $sumOutDep;
        $sumOut1 = $sumOut;
        $sumToStuff1 = $sumToStuff;
        $sumStart1 = $sumStart;
        $test1 = $test?>
    </tr>
    <?  if(!empty($curStuff)){?>
        <tr>
            <th colspan="10">Прлуфабрикаты </th>
        </tr>
    <?;foreach($curStuff as $value){ ?>
            <? //if(number_format( $value->startCount,2) != 0 || number_format( $inProduct[$value->prod_id],2) != 0 || number_format( $outProduct[$value->prod_id],2) != 0){?>
            <? if($value['startCount'] != 0 || $instuff[$value['prod_id']] != 0 || $outStuff[$value['prod_id']] != 0 || $depStuffIn[$value['prod_id']] != 0 || $depStuffOut[$value['prod_id']] != 0 || $value['endCount'] != 0 ){?>
                <? $factOutStuff = $value['startCount'] + $instuff[$value['prod_id']] + $depStuffIn[$value['prod_id']] - $depStuffOut[$value['prod_id']] - $value['CurEndCount']?>
            <tr>
                <td><?=$count?></td>
                <td><?=$value['name']?></td>
                <td><?=number_format( $value['startCount'],2,',','')?></td>
                <td><?=number_format( $instuff[$value['prod_id']],2,',','')?></td>
                <td><?=number_format( $depStuffIn[$value['prod_id']],2,',','')?></td>
                <td><?=number_format( $depStuffOut[$value['prod_id']],2,',','')?></td>
                <td><?=number_format( $outDishStuff[$value['prod_id']],2,',','')?></td>
                <td><?=number_format( $factOutStuff,2,',','')?></td>
                <td><?=number_format( $outStuff[$value['prod_id']],2,',','')?></td>
                <td><?=number_format( $value['endCount'],2,',','')?></td>
                <td><?=number_format( $value['CurEndCount'],2,',','')?></td>
                <td><?=number_format( $value['endCount']-$value['CurEndCount'],2,',','')?></td>
                <td><?=number_format( ($value['endCount']-$value['CurEndCount'])*$stuff->getCostPrice($value['prod_id'],$value['b_date']),0,',','');
                    $summ = $summ + $value['endCount']*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $summEnd = $summEnd + $value['CurEndCount']*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $summFact = $summFact + $factOutStuff*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumIn = $sumIn + ($instuff[$value['prod_id']])*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumInDep = $sumInDep + $depStuffIn[$value['prod_id']]*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumOutDep = $sumOutDep + $depStuffOut[$value['prod_id']]*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumOut = $sumOut + $outDishStuff[$value['prod_id']]*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumToStuff = $sumToStuff + $outStuff[$value['prod_id']]*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
                    $sumStart = $sumStart + $value['startCount']*$stuff->getCostPrice($value['prod_id'],date('Y-m-d',strtotime($value['b_date'])-86400));
                    $test = $test + ($value['endCount']-$value['CurEndCount'])*$stuff->getCostPrice($value['prod_id'],$value['b_date']);
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
            <th colspan=""><?=number_format($sumStart-$sumStart1,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumIn-$sumIn1,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumInDep-$sumInDep1,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumOutDep-$sumOutDep1,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumOut-$sumOut1,0,',',' ')?></th>
            <th colspan=""><?=number_format($summFact-$summFact1,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumToStuff-$sumToStuff1,0,',',' ')?></th>
            <th><?=number_format($summ-$summ1,0,',',' ')?></th>
            <th colspan="2"><?//=number_format($summEnd,0,',',' ')?></th>
            <th><?//=$test?></th>
        </tr>
        <tr>
            <th colspan="2">Общая сумма</th>
            <th colspan=""><?=number_format($sumStart,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumIn,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumInDep,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumOutDep,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumOut,0,',',' ')?></th>
            <th colspan=""><?=number_format($summFact,0,',',' ')?></th>
            <th colspan=""><?=number_format($sumToStuff,0,',',' ')?></th>
            <th><?=number_format($summ,0,',',' ')?></th>
            <th colspan="2"><?//=number_format($summEnd,0,',',' ')?></th>
            <th><?//=$test?></th>
        </tr>
    </tfoot>
</table>