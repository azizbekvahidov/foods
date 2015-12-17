<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'dep-realize-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<?php echo $form->errorSummary($model); ?>

    <?=CHtml::dropDownList('department','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('id'=>'department','empty' => '--Выберите отдел--',))?><br />
<br />
<div id="data">
    
</div>
<script>
    $(document).ready(function(){
        var depId;
        $('#department').change(function(){
            depId = $(this).val();
            $.ajax({
               type: "POST",
               url: "<?php echo Yii::app()->createUrl('depRealize/todayStorage'); ?>",
               data: "depId="+depId,
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
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
