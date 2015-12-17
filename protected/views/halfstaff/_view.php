<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('halfstuff_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->halfstuff_id),array('view','id'=>$data->halfstuff_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stuff_type')); ?>:</b>
	<?php echo CHtml::encode($data->stuff_type); ?>
	<br />


</div>