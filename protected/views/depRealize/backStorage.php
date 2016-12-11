<form action="" method="post">


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
    От <?=CHtml::dropDownList('department','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('id'=>'department','empty' => '--Выберите отдел--',))?>
<br />
<div id="data">

<div >

</div>

</div>
<script>
    $(document).ready(function(){
        var depsId,depId;
        $('#department').change(function(){
            depId = $(this).val();
            var dates = $('#from').val();
            $.ajax({
               type: "POST",
               url: "<?php echo Yii::app()->createUrl('depRealize/ajaxBackStorage'); ?>",
               data: "depId="+depId+"&dates="+dates,
               success: function(data){
                 $('#data').html(data);
               }
            });
        });

    });
</script>
<div class="form-actions ">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Сохранить',
		)); ?>
</div>
</form>
