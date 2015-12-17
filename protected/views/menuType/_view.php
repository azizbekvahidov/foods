<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('menuType_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->menuType_id),array('view','id'=>$data->menuType_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>