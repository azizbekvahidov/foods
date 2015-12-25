<? $dishes = new Dishes(); $measure = new Measurement(); $prod = new Products(); $stuff = new Halfstaff(); $count = 1; $dates = date('Y-m-d')?><br>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>

<script src="/js/jquery.printPage.js"></script>
<?=CHtml::link('<i class="fa fa-print"></i>  Печать',array('/settings/ajaxPrintCalculate?id='.$id),array('class'=>'btn btnPrint'))?> &nbsp;
<button class="btn btn-success" id="export">Экспорт в excel</button><br><br>
<table class="table" id="dataTable">
    <tr>
    <?foreach ($model as $val) { $cnt = 1; $summ = 0;?>
        <?if(($count%4)+1 == 0){?>
            </tr>
        <?}?>
        <td>
            <h4><?=$count?>. <?=$val['name']?></h4>
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
                <?foreach($dishes->getProd($val['dish_id']) as $key => $value){?>
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
                <?foreach($dishes->getStuff($val['dish_id']) as $key => $value){?>
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
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Сумма</th>
                        <th><?=number_format($summ,'0',',',' ')?></th>
                    </tr>
                </tfoot>
            </table>
        </td>
        <?if($count%3 == 0){?>
            <tr>
        <?}?>
    <?$count++;}?>
    </tr>
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