
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
    ?>
</div>
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<span class="heading-title">Мониторинг доходности отделов</span>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var from;
        $('#view').click(function(){
            from = $('#from').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxDepIncome'); ?>",
                data: "dates="+from,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>