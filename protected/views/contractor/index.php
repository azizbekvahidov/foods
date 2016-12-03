<?php
/* @var $this ContractorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Contractors',
);

$this->menu=array(
	array('label'=>'Create Contractor', 'url'=>array('create')),
	array('label'=>'Manage Contractor', 'url'=>array('admin')),
);
?>

<h1>Contractors</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
