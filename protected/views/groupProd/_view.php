<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('groupProd_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->groupProd_id),array('view','id'=>$data->groupProd_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>