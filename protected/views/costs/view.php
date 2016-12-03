<?php
/* @var $this CostsController */
/* @var $model Costs */

$this->breadcrumbs=array(
	'Costs'=>array('index'),
	$model->cost_id,
);

$this->menu=array(
	array('label'=>'List Costs', 'url'=>array('index')),
	array('label'=>'Create Costs', 'url'=>array('create')),
	array('label'=>'Update Costs', 'url'=>array('update', 'id'=>$model->cost_id)),
	array('label'=>'Delete Costs', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cost_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Costs', 'url'=>array('admin')),
);
?>

<h1>View Costs #<?php echo $model->cost_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'cost_id',
		'comment',
		'user_id',
		'cost_date',
		'summ',
		'contractor_id',
	),
)); ?>
