<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('order_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->order_id),array('view','id'=>$data->order_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expense_id')); ?>:</b>
	<?php echo CHtml::encode($data->expense_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('just_id')); ?>:</b>
	<?php echo CHtml::encode($data->just_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('table_id')); ?>:</b>
	<?php echo CHtml::encode($data->table_id); ?>
	<br />


</div>