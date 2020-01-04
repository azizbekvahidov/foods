<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'name' => 'dates',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div>
<?=CHtml::dropDownList('department_id','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('id'=>'department'))?>
&nbsp; <a  href="javascript:;" id="show" class="btn">Показать</a>
&nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
<span class="heading-title">Все показатели отделов</span>
<div id="data">

</div>
<script>
    $(document).ready(function(){

        $('#show').click(function(){
            var dates = $("#dates").val();
            var depId = $('#department').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('depStorage/allStorage'); ?>",
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

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>