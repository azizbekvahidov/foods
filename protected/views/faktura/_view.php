<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('faktura_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->faktura_id),array('view','id'=>$data->faktura_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('realize_date')); ?>:</b>
	<?php echo CHtml::encode($data->realize_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_id')); ?>:</b>
	<?php echo CHtml::encode($data->provider_id); ?>
	<br />


</div>