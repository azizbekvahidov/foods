<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('dep_storage_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->dep_storage_id),array('view','id'=>$data->dep_storage_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('curDate')); ?>:</b>
	<?php echo CHtml::encode($data->curDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prod_id')); ?>:</b>
	<?php echo CHtml::encode($data->prod_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('curCount')); ?>:</b>
	<?php echo CHtml::encode($data->curCount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('department_id')); ?>:</b>
	<?php echo CHtml::encode($data->department_id); ?>
	<br />


</div>