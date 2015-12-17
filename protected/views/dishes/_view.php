<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('dish_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->dish_id),array('view','id'=>$data->dish_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />



</div>