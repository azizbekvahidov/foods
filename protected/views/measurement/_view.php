<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('measure_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->measure_id),array('view','id'=>$data->measure_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>