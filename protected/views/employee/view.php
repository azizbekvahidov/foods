<?php
/* @var $this EmployeeController */
/* @var $model Employee */

$this->breadcrumbs=array(
	'Employees'=>array('index'),
	$model->name,
);

$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');


$menu2=array(
	array('label'=>'Employee','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);

if(!isset($_GET['asModal'])){
?>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'View Employees #'.$model->employee_id,
        'headerIcon' => 'icon- fa fa-eye',
        'headerButtons' => array(
            array(
                'class' => 'bootstrap.widgets.TbButtonGroup',
                'type' => 'success',
                // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'buttons' => $menu2
            ),
        ) 
    )
);?>
<?php
}
?>

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
		?>		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>	
        <style>
            .row{
                margin: 0;
                            
            }
            .modal{
                left: 50%;
z-index: 1050;
            }
        </style>

<table id="dataTable" class="table table-hover table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Дата заказа</th>
            <th>Стол №</th>
            <th>Статус</th>
            <th>Просмотр</th>
        </tr>
    </thead>
    <tbody>
        <? $count = 1;?>        
        <? foreach($viewModel as $value){?>

            <tr>           
                <td><?=$count?></td>
                <td><?=$value['order_date']?></td> 
                <td><?=$value['table']?></td>
                <? if($value['status'] == 1){?>
                <td>Открыт</td>
                <? } if($value['status'] == 0){?>
                <td>Закрыт</td>
                <?}?>
                <td><?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/view&id='.$value['employee_id'].'&order_date='.$value['order_date']))?></td>
            </tr>
        <? $count++; }?>
    </tbody>
</table>
<script>
    
        $('#dataTable').DataTable({
                responsive: true,
                "order": [[ 1, "desc" ]]               
        });
    
 </script>
<?php
if(!isset($_GET['asModal'])){
	$this->endWidget();}
?>