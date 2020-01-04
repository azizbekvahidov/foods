
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>
</div> &nbsp; <button type="button" id="export" class="btn btn-success">Экспорт</button>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
        </tr>
    </thead>
    <tbody>
<?
$product = new Products();
$stuff = new Halfstaff();

foreach ($department as $val) {
    $count = 1;?>
    <tr>
        <th colspan="2" style="text-align: center"><?=$val->name?></th>
    </tr>
    <?$prodList = $product->getProdName($val->department_id);
    $prod = $stuff->getStuffProdName($val->department_id);
    $stuffList = $stuff->getStuffName($val->department_id);?>
    <tr>
        <td colspan="2">Продукты</td>
    </tr>
    <?foreach ($prod + $prodList as $key => $value) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value?></td>
        </tr>
    <?$count++; }?>
    <tr>
        <td colspan="2">Полуфабрикаты</td>
    </tr>
    <?foreach ($stuffList as $key => $value) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value?></td>
        </tr>
        <?$count++; }

}

?>
    </tbody>
</table>
<script>
    $('#export').click(function(){
        $('#dataTable').table2excel({
            name: "Excel Document Name"
        });
    });
</script>