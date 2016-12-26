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
<?=CHtml::dropDownList('cont','',CHtml::listData(Contractor::model()->findAll('status != 1'),'contractor_id','name'),array('class'=>''))?>
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<span class="heading-title">Обмен товаров</span>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var from,
            to,
            dep;
        $('#view').click(function(){
            from = $('#from').val();
            to = $('#to').val();
            dep = $('#cont').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxExchange'); ?>",
                data: "from="+from+'&to='+to+'&cont='+dep,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>