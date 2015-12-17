<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'dishtype-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Поля с <span class="required">*</span> объязательны.</p>

	<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>50)); ?><br />
<label>Родитель</label>
<?php echo $form->dropDownList($model,'parent',CHtml::listData(Dishtype::model()->findAll(),'type_id','name'),array('empty'=>'--Выберите тип--','class'=>'span5','maxlength'=>50))?>


<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
