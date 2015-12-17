<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('realize_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->realize_id),array('view','id'=>$data->realize_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('faktura_id')); ?>:</b>
	<?php echo CHtml::encode($data->faktura_id); ?>
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