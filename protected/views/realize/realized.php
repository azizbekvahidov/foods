
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>date('Y-m-d'),
            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?></div>
<style>
</style>
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>date('Y-m-d'),
            'name' => 'to',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?></div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<button type="button" id="export" class="btn btn-success">Экспорт</button>
<div id="data">

</div>
<script>
    $(document).ready(function(){
        var from,
            to,
            depId;
        $('#view').click(function(){
            from = $('#from').val();
            to = $('#to').val();
            depId = $('#department').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('realize/realizedProd'); ?>",
                data: "from="+from+'&to='+to,
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