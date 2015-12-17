<?php
/* @var $this HalfstaffController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Полуфабрикаты',
);

$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');
$this->menu=array(
	array('label'=>'Полуфабрикаты','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('halfstaff-grid', {
			data: $(this).serialize()
		});
		return false;
	});
");

Yii::app()->clientScript->registerScript('refreshGridView', "
	// automatically refresh grid on 5 seconds
	//setInterval(\"$.fn.yiiGridView.update('halfstaff-grid')\",5000);
");

?>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Лист полуфабрикатов' ,
        'headerIcon' => 'icon- fa fa-list-ol',
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
<?php /** $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); **/ ?>
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
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
		?><p>
	 Вы можете ввести операторы сравнения  (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b>
	или <b>=</b>) в начале каждого из ваших значений поиска, чтобы указать, как сравнение должно быть сделано.
</p>

<?php echo CHtml::beginForm(array('export')); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>
    <style>
        .modal{
            left: 50%;
            z-index: 1050;
            bottom: 60px;
        }
    </style>
<table class="items table table-striped table-hover dataTable table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <? $count = 1; foreach($newModel as $val){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$val->name?></td>
            <td><?=CHtml::link('<i class="icon-eye-open"></i>',$val->halfstuff_id.'|'.$val->name,array('class'=>'view'))?></td>
        </tr>
    <? $count++;}?>
    
    </tbody>
</table>
<script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                    responsive: true,      
                    "lengthMenu": [[ -1,10, 25, 50, 100,], [ "Все",10, 25, 50, 100,]]       
            });
        });
        jQuery(document).on('click','#dataTable a.view',function(){
    		data=$(this).attr("href").split("|")
    		$("#myModalHeader").html(data[1]);
    		$("#myModalBody").load("/halfstaff/view&id="+data[0]+"&asModal=true");
    		$("#myModal").modal();
    		return false;
    	});
        
    </script>
<?php echo CHtml::endForm(); ?>
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
