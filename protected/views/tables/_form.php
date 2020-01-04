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
<div class="form-group">
    <?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>100)); ?><br />
</div>
<div class="form-group">
    <?php echo $form->textFieldRow($model,'price',array('class'=>'span5','maxlength'=>100)); ?><br />
</div>



<div class="form-actions span9">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
		)); ?>
</div>
<?php $this->endWidget(); ?>
