<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'realize_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'faktura_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'prod_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'price',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'count',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
