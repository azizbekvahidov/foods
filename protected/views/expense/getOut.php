
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>date('Y-m-d'),
            'name' => 'dates',
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
        var dates,
            depId;
        $('#view').click(function(){
            dates = $('#dates').val();
            depId = $('#department').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/Out'); ?>",
                data: "dates="+dates+'&depId='+depId,
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