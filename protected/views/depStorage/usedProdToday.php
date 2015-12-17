
<?
    $prodType = array(
        '1'=>'Блюда',
        '2'=>'Полуфабрикаты',
    );
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>
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
    ?></div>
    <?=CHtml::dropDownList('department_id','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'Выберите отдел'))?>
&nbsp; &nbsp;
    <?=CHtml::dropDownList('prod_type','',$prodType)?>&nbsp; &nbsp;
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<a href="javascript:;"  id="export" class="btn btn-success" style="  margin-top: -11px; margin-left: 10px;">Экспорт</a>
<div id="data">
    
</div>
<script>
    $(document).ready(function(){
        
        
        $('#view').click(function(){
            var depId = $("#department_id").val(),
                dates = $("#dates").val(),
                prodType = $("#prod_type").val();
            $.ajax({
               type: "POST",
               url: "<?php echo Yii::app()->createUrl('DepStorage/usedProdLists'); ?>",
               data: 'department_id='+depId+"&dates="+dates+"&prod_type="+prodType,
               success: function(data){
                 $('#data').html(data);
                }
            });
        });
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "Excel Document Name"
            });
        });
    });
</script>