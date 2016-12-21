<?php
/* @var $this ContractorController */
/* @var $model Contractor */


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#contractor-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="btn-group pull-right" role="group" aria-label="...">
    <a href="/contractor/admin" class="btn btn-default btn-success">Администрирование</a>
    <a href="/contractor/create" class="btn btn-default btn-success">Создать</a>
</div>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Администрирование Контагента',
        'headerIcon' => 'icon- fa fa-tasks',
        'headerButtons' => array(
            array(
                'class' => 'bootstrap.widgets.TbButtonGroup',
                'type' => 'success',
                // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'buttons' => $this->menu
            ),
        )
    )
);?>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'contractor-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'contractor_id',
		'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
<?php $this->endWidget(); ?>
