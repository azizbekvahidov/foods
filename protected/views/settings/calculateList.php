<? $dishes = new Dishes(); $measure = new Measurement(); $prod = new Products(); $stuff = new Halfstaff(); $count = 1; $dates = date('Y-m-d')?><br>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>

<script src="/js/jquery.printPage.js"></script>
<?=CHtml::link('<i class="fa fa-print"></i>  Печать',array('/settings/ajaxPrintCalculate?id='.$id),array('class'=>'btn btnPrint'))?> &nbsp;
<button class="btn btn-success" id="export">Экспорт в excel</button><br><br>
<table class="table" id="dataTable">
    <tr>
        <th colspan="3"><h2>Блюда</h2></th>
    </tr>
    <tr>
    <?foreach ($result['dish'] as $keys => $val) { $cnt = 1; $summ = 0;?>
        <?if(($count%4)+1 == 0){?>
            </tr>
        <?}?>
        <td>
            <h4><?=$count?>. <?=$dishes->getName($keys)?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Наименование</th>
                        <th>Кол-во</th>
                        <th>цена</th>
                    </tr>
                </thead>
                <tbody>
                <?if(!empty($val['prod']))
                    foreach($val['prod'] as $key => $value){?>
                        <tr>
                            <td><?=$cnt?></td>
                            <td><?=$prod->getName($key)?></td>
                            <td><?=number_format($value,2,',',' ')?> <?=$measure->getMeasure($key,'prod')?></td>
                            <td>
                                <?$summ = $summ + $prod->getCostPrice($key,$dates)*$value?>
                                <?=number_format($prod->getCostPrice($key,$dates)*$value,0,',',' ')?>
                            </td>
                        </tr>
                    <? $cnt++;}?>
                <?if(!empty($val['stuff']))
                    foreach($val['stuff'] as $key => $value){
                        ?>
                        <tr>
                            <td><?=$cnt?></td>
                            <td><?=$stuff->getName($key)?></td>
                            <td><?=number_format($value,2,',',' ')?> <?=$measure->getMeasure($key,'stuff')?></td>
                            <td>
                                <?$summ = $summ + $stuff->getCostPrice($key,$dates)*$value?>
                                <?=number_format($stuff->getCostPrice($key,$dates)*$value,0,',',' ')?>
                            </td>
                        </tr>
                    <? $cnt++;}?>
                    <tr>
                        <td colspan="3">Порций</td>
                        <td><?=$val['count']?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Сумма</th>
                        <th><?=number_format($summ/$val['count'],'0',',',' ')?></th>
                    </tr>
                </tfoot>
            </table>
        </td>
        <?if($count%3 == 0){?>
            <tr>
        <?}?>
    <?$count++;}?>
    </tr>
    <?if(!empty($result['stuff'])){?>
    <?$count = 1;?>
    <tr>
        <th colspan="3"><h2>Загатовки</h2></th>
    </tr>
    <tr>
    <?foreach ($result['stuff'] as $keys => $val) { $cnt = 1; $summ = 0;?>
        <?if(!empty($val)){?>
            <?if(($count%4)+1 == 0){?>
                </tr>
            <?}?>
            <td>
                <h4><?=$count?>. <?=$stuff->getName($keys)?></h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Наименование</th>
                            <th>Кол-во</th>
                            <th>цена</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?if(!empty($val['prod']))
                        foreach($val['prod'] as $key => $value){?>
                            <tr>
                                <td><?=$cnt?></td>
                                <td><?=$prod->getName($key)?></td>
                                <td><?=number_format($value,2,',',' ')?> <?=$measure->getMeasure($key,'prod')?></td>
                                <td>
                                    <?$summ = $summ + $prod->getCostPrice($key,$dates)*$value?>
                                    <?=number_format($prod->getCostPrice($key,$dates)*$value,0,',',' ')?>
                                </td>
                            </tr>
                        <? $cnt++;}?>
                    <?if(!empty($val['stuff']))
                        foreach($val['stuff'] as $key => $value){?>
                            <tr>
                                <td><?=$cnt?></td>
                                <td><?=$stuff->getName($key)?></td>
                                <td><?=number_format($value,2,',',' ')?> <?=$measure->getMeasure($key,'stuff')?></td>
                                <td>
                                    <?$summ = $summ + $stuff->getCostPrice($key,$dates)*$value?>
                                    <?=number_format($stuff->getCostPrice($key,$dates)*$value,0,',',' ')?>
                                </td>
                            </tr>
                        <? $cnt++;}?>
                        <tr>
                            <td colspan="3">Порций</td>
                            <td><?=$val['count']?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Сумма</th>
                            <th><?=number_format(($summ == 0) ? 0 : $summ/$val['count'],'0',',',' ')?></th>
                        </tr>
                    </tfoot>
                </table>
            </td>
            <?if($count%3 == 0){?>
                <tr>
            <?}?>
        <?$count++;}?>
    <?}?>
    </tr>
    <?}?>
</table>

<script>
    $(document).ready(function(){
        $(".btnPrint").printPage();
    });
    $('#export').click(function(){
        $('#dataTable').table2excel({
            name: "Excel Document Name"
        });
    });
</script>