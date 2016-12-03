<?php
/* @var $this CostsController */
/* @var $model Costs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'costs-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textField($model,'comment',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'summ'); ?>
		<?php echo $form->textField($model,'summ',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'summ'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'contractor_id'); ?>
		<?php echo $form->dropDownList($model,'contractor_id',CHtml::listData(Contractor::model()->findAll(),'contractor_id','name'),array('empty'=>'выберите контрагента')); ?>
		<?php echo $form->error($model,'contractor_id'); ?>
	</div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'employee_id'); ?>
        <?php echo $form->dropDownList($model,'employee_id',CHtml::listData(Employee::model()->findAll('status != 1'),'employee_id','name'),array('empty'=>'выберите сотрудника')); ?>
        <?php echo $form->error($model,'employee_id'); ?>
    </div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Save',array('class'=>'btn btn-default')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->