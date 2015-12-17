<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>$dates,
            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div>
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>$dates,
            'name' => 'to',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div> &nbsp;
<button type="button" id="show" class="btn">Показать</button> &nbsp;
<button type="button" id="export" class="btn btn-success">Экспорт</button>
<span class="heading-title">Выручка</span>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>
</div>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var dates = '<?=$dates?>',
            depId;
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('expense/test'); ?>",
            data: "dates="+dates,
            success: function(data){
                $('#data').html(data);
            }
        });
        $('#show').click(function(){
            var from = $('#from').val(),
            to = $('#to').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/test'); ?>",
                data: "from="+from+"&to="+to,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>
<script>
    $('#export').click(function(){
        $('#dataTable').table2excel({
            name: "Excel Document Name"
        });
    });
</script>