<?php
/* @var $this ContractorController */
/* @var $data Contractor */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('contractor_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->contractor_id), array('view', 'id'=>$data->contractor_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>