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
<?=CHtml::dropDownList('dep','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'Выберите отдел'))?>
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a><br>
<span class="heading-title">Время приготовления заказов</span>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var from,
            to,
            dep;
        $('#view').click(function(){
            from = $('#from').val();
            to = $('#to').val();
            dep = $('#dep').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxReadyTime'); ?>",
                data: "from="+from+'&to='+to+'&depId='+dep,
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