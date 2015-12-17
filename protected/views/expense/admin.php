<?php
/* @var $this ExpenseController */
/* @var $model Expense */

$this->breadcrumbs=array(
	'Expenses'=>array('index'),
	'Manage',
);

$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');
$this->menu=array(
	array('label'=>'Expense','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#expense-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Manage Expenses',
        'headerIcon' => 'icon- fa fa-tasks',
        'headerButtons' => array(
            array(
                'class' => 'bootstrap.widgets.TbButtonGroup',
                'type' => 'success',
                // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                //'buttons' => $this->menu
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
		?>

<?php echo CHtml::beginForm(array('export')); ?>
<table class="items table table-striped table-hover" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Дата и время</th>
            <th>Ответственный</th>
            <th>Стол №</th>
            <th>Статус</th>
            <th></th>
        </tr>
    </thead>
    <tbody><? $count = 1;?>
        <? foreach($newModel as $value){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->order_date?></td>
            <td><?=$value->getRelated('employee')->name?></td>
            <td><?=$value->table?></td>
            <td><?=$value->status? "Открыт" : "Закрыт" ?></td>
            <td>
                <?=CHtml::link('<i class="icon-eye-open"></i>',array('#'),array('class'=>'view'))?>
                <?=CHtml::link('<i class="icon-pencil"></i>',array('expense/update&id='.$value->expense_id),array('class'=>'update'))?>
                <?=CHtml::link('<i class="icon-trash"></i>',array('expense/delete&id='.$value->expense_id),array('class'=>'delete'))?>
            </td>
        </tr>
        <script>
            jQuery('#dataTable a.view').on('click','',function(){
        		data=$(this).attr("href").split("|")
        		$("#myModalHeader").html('<?=$value->order_date?>');
    			$("#myModalBody").load("/index.php?r=expense/view&id=&asModal=true&order_date=<?=$value->order_date?>");
        		$("#myModal").modal();
        		return false;
        	});
            
        </script>
        <? $count++;}?>
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
    </tbody>
</table>

<?php echo CHtml::endForm(); ?>


<script>
    
    $(document).ready(function() {
        $('#qdataTable').DataTable({
                responsive: true,
                "order": [[ 3, "desc" ]]               
        });
    });
 </script>
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
