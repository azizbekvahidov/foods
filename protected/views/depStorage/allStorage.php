<div style="float: right;">
    <?//=CHtml::link('Просмотреть остатки',array(),array('class'=>'btn btn-default'))?>
</div>

<? $function = new Functions(); $count = 1; $product = new Products(); $test = 0; $stuff = new Halfstaff(); $summEnd = 0;
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
    <?  if(!empty($prod["start"])){ ?>
        <tr>
            <th colspan="10">Продукты</th>
        </tr>
    <? foreach($prod["start"] as $key => $value){?>
        <? if($value["cnt"] != 0 || $inProduct[$key] != 0 || $outProduct[$key] != 0 || $outStuffProd[$key] != 0 || $depIn[$key] != 0 || $depOut[$key] != 0 || $prod["end"][$key]["cnt"] !=0 || $prod["curEnd"][$key]["cnt"] !=0 || $value['cnt'] != ""){?>
                <? $factOutProd = $value["cnt"]+ $inProduct[$key] + $depIn[$key] - $depOut[$key] - $prod["curEnd"][$key]["cnt"]?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value["name"]?></td>
            <td><?=number_format( $value["cnt"],2,',','')?></td>
            <td><?=number_format( $inProduct[$key],2,',','')?></td>
            <td><?=number_format( $depIn[$key],2,',','')?></td>
            <td><?=number_format( $depOut[$key],2,',','')?></td>
            <td><?=number_format( $outProduct[$key],2,',','')?></td>
            <td><?=number_format( $factOutProd,2,',','')?></td>
            <td><?=number_format( $outStuffProd[$key],2,',','')?></td>
            <td><?=number_format( $prod["end"][$key]["cnt"],2,',','')?></td>
            <td><?=number_format( $prod["curEnd"][$key]["cnt"],2,',','')?></td>
            <td><?=number_format( $prod["end"][$key]["cnt"]-$prod["curEnd"][$key]["cnt"],2,',','')?></td>
            <td><?=number_format( ($prod["end"][$key]["cnt"]-$prod["curEnd"][$key]["cnt"])*$product->getCostPrice($key,$prod["end"][$key]["date"]),0,',','');
                $summ = $summ + $prod["end"][$key]["cnt"]*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $test = $test +($prod["end"][$key]["cnt"]-$prod["curEnd"][$key]["cnt"])*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $summEnd = $summEnd + $prod["curEnd"][$key]["cnt"]*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $summFact = $summFact + $factOutProd*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $sumIn = $sumIn + $inProduct[$key]*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $sumInDep = $sumInDep + $depIn[$key]*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $sumOutDep = $sumOutDep + $depOut[$key]*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $sumOut = $sumOut + $outProduct[$key]*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $sumToStuff = $sumToStuff + $outStuffProd[$key]*$product->getCostPrice($key,$prod["end"][$key]["date"]);
                $sumStart = $sumStart + $value["cnt"]*$product->getCostPrice($key,date('Y-m-d',strtotime($prod["end"][$key]["date"])-86400))?></td>

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

    <?  if(!empty($stuffs["start"])){?>
        <tr>
            <th colspan="10">Полуфабрикаты </th>
        </tr>
    <?;foreach($stuffs["start"] as $key => $value){ ?>
            <? if($value['cnt'] != 0 || $instuff[$key] != 0 || $outStuff[$key] != 0 || $depStuffIn[$key] != 0 || $depStuffOut[$key] != 0 || $stuffs["end"][$key]["cnt"] != 0 || $value['cnt'] != ""){?>
                <? $factOutStuff = $value['cnt'] + $instuff[$key] + $depStuffIn[$key] - $depStuffOut[$key] - $stuffs["curEnd"][$key]["cnt"]?>
            <tr>
                <td><?=$count?></td>
                <td><?=$value['name']?></td>
                <td><?=number_format( $value["cnt"],2,',','')?></td>
                <td><?=number_format( $instuff[$key],2,',','')?></td>
                <td><?=number_format( $depStuffIn[$key],2,',','')?></td>
                <td><?=number_format( $depStuffOut[$key],2,',','')?></td>
                <td><?=number_format( $outDishStuff[$key],2,',','')?></td>
                <td><?=number_format( $factOutStuff,2,',','')?></td>
                <td><?=number_format( $outStuff[$key],2,',','')?></td>
                <td><?=number_format( $stuffs["end"][$key]["cnt"],2,',','')?></td>
                <td><?=number_format( $stuffs["curEnd"][$key]["cnt"],2,',','')?></td>
                <td><?=number_format( $stuffs["end"][$key]["cnt"]-$stuffs["curEnd"][$key]["cnt"],2,',','')?></td>
                <td><?=number_format( ($stuffs["end"][$key]["cnt"]-$stuffs["curEnd"][$key]["cnt"])*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]),0,',','');
                    $summ = $summ + $stuffs["end"][$key]["cnt"]*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $summEnd = $summEnd + $stuffs["curEnd"][$key]["cnt"]*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $summFact = $summFact + $factOutStuff*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $sumIn = $sumIn + ($instuff[$key])*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $sumInDep = $sumInDep + $depStuffIn[$key]*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $sumOutDep = $sumOutDep + $depStuffOut[$key]*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $sumOut = $sumOut + $outDishStuff[$key]*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $sumToStuff = $sumToStuff + $outStuff[$key]*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
                    $sumStart = $sumStart + $value['cnt']*$stuff->getCostPrice($key,date('Y-m-d',strtotime($stuffs["end"][$key]["date"])-86400));
                    $test = $test + ($stuffs["end"][$key]["cnt"]-$stuffs["curEnd"][$key]["cnt"])*$stuff->getCostPrice($key,$stuffs["end"][$key]["date"]);
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