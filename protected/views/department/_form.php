<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'department-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model,'name',array('class'=>'span5')); ?>
<?=$form->dropDownList($model,'point_id',$point,array('class'=>'span5','empty'=>'Выберите точку'))?>
<div class="form-group">
	<?
	$this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'color',
			'hidden'=>true, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
	));
	?>
</div>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>


<?php $this->endWidget(); ?>
