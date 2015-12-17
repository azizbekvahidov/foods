<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('provider_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->provider_id),array('view','id'=>$data->provider_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>