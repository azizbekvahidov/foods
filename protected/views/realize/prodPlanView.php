<? $prod = new Products(); $stuff = new Halfstaff(); $function = new Functions(); $sumPlan = 0; $sumFact = 0;?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>
<button class="btn btn-success" id="export">Экспорт в excel</button><br><br>
<table class="table table-bordered table-condensed" id="dataTable">
    <thead>
        <tr>
            <th>Наименование</th>
            <th>Планирование</th>
            <th>Фактически</th>
            <th>Разница</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($department as $value) {
            $outProdSum = $function->multToSumProd($outProd[$value['department_id']],$dates);
            $sumPlan = $sumPlan + $outProdSum;
            //$outStuffSum = $function->multToSumStuff($outStuff[$value['department_id']],$dates);
            $curEndProdSum = $function->multToSumProd($curEndProd[$value['department_id']],$dates);
            $sumFact = $sumFact + $curEndProdSum;
            //$curEndStuffSum = $function->multToSumStuff($curEndStuff[$value['department_id']],$dates)?>
        <tr >
            <th><?=$value['name']?></th>
            <th><?=number_format($outProdSum,0,',',' ')?></th>
            <th><?=number_format($curEndProdSum,0,',',' ')?></th>
            <th><?=number_format(($outProdSum  - $curEndProdSum ),0,',',' ')?></th>
        </tr>
        <tr>
            <td colspan="4">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Наименование</th>
                            <th>Планирование</th>
                            <th>Фактически</th>
                            <th>Разница</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?if(!empty($curEndProd[$value['department_id']])){?>
                        <tr>
                            <th colspan="5" class="text-center">Продукты</th>
                        </tr>
                        <?foreach($curEndProd[$value['department_id']] as $key => $val){
                            if($val != 0 or $outProd[$value['department_id']][$key] != 0){?>
                            <tr>
                                <td><?=$prodName[$key]?></td>
                                <td><?=number_format($outProd[$value['department_id']][$key],2,',','')?></td>
                                <td><?=number_format($val,2,',','')?></td>
                                <td><?=number_format($outProd[$value['department_id']][$key]-$val,2,',','')?></td>
                                <td><?=number_format(($outProd[$value['department_id']][$key]-$val)*$prod->getCostPrice($key,$dates),0,',',' ')?></td>
                            </tr>
                            <?}?>
                        <?}?>
                    <?}/*if(!empty($curEndStuff[$value['department_id']])){?>
                        <tr>
                            <th colspan="5" class="text-center">Полуфабрикаты</th>
                        </tr>
                        <?foreach($curEndStuff[$value['department_id']] as $key => $val){
                            if($val != 0 or $outStuff[$value['department_id']][$key] != 0){?>
                            <tr>
                                <td><?=$stuffName[$value['department_id']][$key]?></td>
                                <td><?=number_format($outStuff[$value['department_id']][$key],2,',','')?></td>
                                <td><?=number_format($val,2,',','')?></td>
                                <td><?=number_format($outStuff[$value['department_id']][$key]-$val,2,',','')?></td>
                                <td><?=number_format(($outStuff[$value['department_id']][$key]-$val)*$stuff->getCostPrice($key,$dates),0,',',' ')?></td>
                            </tr>
                            <?}?>
                        <?}?>
                    <?}*/?>
                    </tbody>
                </table>
            </td>
        </tr>
        <?}?>
        <tr>
            <th>Основной склад</th>
            <th>0</th>
            <th><?=number_format($function->multToSumProd($balanceProd,$dates),0,',',' ')?></th>
            <th><?=number_format(0-$function->multToSumProd($balanceProd,$dates),0,',',' ')?></th>
            <?$sumFact = $sumFact + $function->multToSumProd($balanceProd,$dates)?>
        </tr>
        <tr>
            <td colspan="4">
                <table class="table table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Планирование</th>
                        <th>Фактически</th>
                        <th>Разница</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?foreach ($balanceProd as $key => $val) {
                        if($val != 0){?>
                            <tr>
                                <td><?=$prodName[$key]?></td>
                                <td><?=number_format(0,2,',','')?></td>
                                <td><?=number_format($val,2,',','')?></td>
                                <td><?=number_format(0-$val,2,',','')?></td>
                                <td><?=number_format((0-$val)*$prod->getCostPrice($key,$dates),0,',',' ')?></td>
                            </tr>
                        <?}}
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <th>Итого</th>
            <th><?=number_format($sumPlan,0,',',' ')?></th>
            <th><?=number_format($sumFact,0,',',' ')?></th>
            <th><?=number_format($sumPlan-$sumFact,0,',',' ')?></th>
        </tr>
    </tbody>
</table>
<script>

    $('#export').click(function(){
        $('#dataTable').table2excel({
            name: "Excel Document Name"
        });
    });
</script>