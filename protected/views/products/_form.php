<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'products-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>100)); ?><br />
<div class="span9">
<div class="span3"><?php echo $form->dropDownListRow($model,'measure_id',CHtml::listData(Measurement::model()->findAll(),'measure_id','name'),array('class'=>'span2')); ?></div>
<div class="span3"><?php echo $form->dropDownListRow($model,'department_id',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'выберите отдел')); ?></div>
<!--<label for="">Группа</label>-->
<? //echo $form->dropDownList($model,'groupProd_id',CHtml::listData(GroupProd::model()->findAll(),'groupProd_id','name'),array('empty' => '--Выберите группу--','class'=>'span2'))?>
</div>

<div class="form-actions span9">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
