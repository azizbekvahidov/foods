<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('dep_realize_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->dep_realize_id),array('view','id'=>$data->dep_realize_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dep_faktura_id')); ?>:</b>
	<?php echo CHtml::encode($data->dep_faktura_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prod_id')); ?>:</b>
	<?php echo CHtml::encode($data->prod_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />


</div>