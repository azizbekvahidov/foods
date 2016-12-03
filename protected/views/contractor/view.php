<?php
/* @var $this ContractorController */
/* @var $model Contractor */

$this->breadcrumbs=array(
	'Contractors'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Contractor', 'url'=>array('index')),
	array('label'=>'Create Contractor', 'url'=>array('create')),
	array('label'=>'Update Contractor', 'url'=>array('update', 'id'=>$model->contractor_id)),
	array('label'=>'Delete Contractor', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->contractor_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Contractor', 'url'=>array('admin')),
);
?>

<h1>View Contractor #<?php echo $model->contractor_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'contractor_id',
		'name',
	),
)); ?>
