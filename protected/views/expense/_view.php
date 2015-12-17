<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('expense_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->expense_id),array('view','id'=>$data->expense_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order_date')); ?>:</b>
	<?php echo CHtml::encode($data->order_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_id')); ?>:</b>
	<?php echo CHtml::encode($data->employee_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('table')); ?>:</b>
	<?php echo CHtml::encode($data->table); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>