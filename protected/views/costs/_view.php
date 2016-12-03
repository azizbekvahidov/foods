<?php
/* @var $this CostsController */
/* @var $data Costs */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cost_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cost_id), array('view', 'id'=>$data->cost_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cost_date')); ?>:</b>
	<?php echo CHtml::encode($data->cost_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('summ')); ?>:</b>
	<?php echo CHtml::encode($data->summ); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contractor_id')); ?>:</b>
	<?php echo CHtml::encode($data->contractor_id); ?>
	<br />


</div>