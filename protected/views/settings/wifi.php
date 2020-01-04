<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'employee-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


<label >Код от Wi-Fi</label>
<?php echo CHtml::textField('wifi',$model["setting_value"],array('class'=>'span3')); ?>


<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
	)); ?>
</div>

<?php $this->endWidget(); ?>
