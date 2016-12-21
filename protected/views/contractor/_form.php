<?php
/* @var $this ContractorController */
/* @var $model Contractor */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contractor-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

    <div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class=" buttons form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',array('class'=>'btn btn-default')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->