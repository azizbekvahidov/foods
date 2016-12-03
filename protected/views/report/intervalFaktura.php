С
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(

            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?></div>
По
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(

            'name' => 'to',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?></div>
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<a href="javascript:;" id="export" class="btn" style="  margin-top: -11px; margin-left: 10px;">Экспорт</a>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var from,
            to;
        $('#view').click(function(){
            from = $('#from').val();
            to = $('#to').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxIntervalFaktura'); ?>",
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
            name: "Цены на период"
        });
    });
</script>