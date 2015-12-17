<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('storage_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->storage_id),array('view','id'=>$data->storage_id)); ?>
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


</div>