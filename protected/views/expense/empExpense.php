<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>$dates,
            'name' => 'dates',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div>
<?=CHtml::dropDownList('employee','',CHtml::listData(Employee::model()->findAll('status = 0 and role >= 1'),'employee_id','name'))?>
<button type="button" id="export" class="btn ">Показать</button>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var dates,
            empId;
        $('#export').click(function(){
            dates = $('#dates').val();
            empId = $('#employee').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/ajaxEmpExpense'); ?>",
                data: "dates="+dates+"&empId="+empId,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>