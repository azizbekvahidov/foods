<?php
/* @var $this ProductsController */
/* @var $model Products */


$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');
$this->menu=array(
	array('label'=>'Продукты','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#products-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Администрирование продуктов',
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
);?>		<?php $this->widget('bootstrap.widgets.TbAlert', array(
		    'block'=>false, // display a larger alert block?
		    'fade'=>true, // use transitions?
		    'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
		    'alerts'=>array( // configurations per alert type
		        'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'danger'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		    ),
		));
	$count = 1;	?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>
<table class="items table table-striped table-hover" id="dataTable">
    <thead>
        <tr>
            <th>№</th>
            <th>#</th>
            <th>Название</th>
            <th>Ед. измерения</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach($newModel as $value){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->product_id?></td>
            <td><?=$value->name?></td>
            <td><?=$value->getRelated('measure')->name?></td>
            <td>
                <?=CHtml::link('<i class="icon-pencil"></i>',array('products/update?id='.$value->product_id),array('class'=>'update'))?>
                <?=CHtml::link('<i class="icon-trash"></i>',array('products/delete?id='.$value->product_id),array('class'=>'delete'))?>
            </td>
        </tr>
        <? $count++; }?>
    </tbody>
</table>

<script>
    
    $(document).ready(function() {
        $('#dataTable').DataTable({
                responsive: true,      
                    "lengthMenu": [[ -1,10, 25, 50, 100,], [ "Все",10, 25, 50, 100,]]       
        });
    });
 </script>
 <script>
            jQuery(document).on('click','#dataTable a.delete',function() {
            	if(!confirm('Вы уверены, что хотите удалить данный элемент?')) return false;
            	var th = this,
            		afterDelete = function(){};
                    jQuery(this).parent().parent().remove()
            	jQuery.ajax({
            		type: 'POST',
            		url: jQuery(this).attr('href'),
            		success: function(data) {
            			//jQuery('#dataTable').yiiGridView('update');
            			afterDelete(th, true, data);
            		},
            		error: function(XHR) {
            			return afterDelete(th, false, XHR);
            		}
            	});
            	return false;
            });
        </script>
<?php /*$box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Import Data',
        'htmlOptions' => array('style' => 'width:25%; text-align:center;margin-top:-100px', 'class'=>'pull-right'),
    )
);?>
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'import-admin-form',
		'type' => 'inline',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array(
			'enctype'=>'multipart/form-data',
		),
		'action' => $this->createUrl('import'),  //<- your form action here
	)); ?>
	<?php echo $form->fileFieldRow($model,'fileImport'); ?> 
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'Import',
		'icon'=>'fa fa-download'
	)); ?>
	<br>
	(file type permitted: xls, xlsx, ods only)
	<?php $this->endWidget(); ?>
<?php $this->endWidget(); */?>

<?php $this->endWidget(); ?>
<?php  $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
); ?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4 id="myModalHeader">Modal header</h4>
    </div>
 
    <div class="modal-body" id="myModalBody">
        <p>One fine body...</p>
    </div>
 
    <div class="modal-footer">
        <?php  $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Close',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
 
<?php  $this->endWidget(); ?>
