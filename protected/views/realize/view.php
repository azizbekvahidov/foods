<?php
/* @var $this RealizeController */
/* @var $model Realize */



$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');


$menu2=array(
	array('label'=>'Realize','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);

if(!isset($_GET['asModal'])){
?>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Приход по дате #'.$currentDate,
        'headerIcon' => 'icon- fa fa-eye',
        'headerButtons' => array(
            array(
                'class' => 'bootstrap.widgets.TbButtonGroup',
                'type' => 'success',
                // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                //'buttons' => $menu2
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
		?>	
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th>Количество</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
        <? $summ = 0; $counter = 1;
        foreach($Products as $val){
            $realize = $val->getRelated('realize');

            foreach($realize as $row){
                $prod = $row->getRelated('products');
                $measure = $prod->getRelated('measure'); ?>
                <tr>
                    <td><?=$counter?></td>
                    <td><?=$prod['name']?></td>
                    <td><?=$row['count']?></td>
                    <td><?=number_format($row['price'],0,'.',',')?></td>
                    <td><?=number_format($row['price']*$row['count'],0,'.',',')?></td>
                    <? $summ = $summ + $row['price']*$row['count']; $counter++;?>
                </tr>     
            <?}
        }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Сумма прихода</th>
            <th><?=number_format($summ,0,'.',',')?></th>
        </tr>
    </tfoot>
</table>
<?php
if(!isset($_GET['asModal'])){
	$this->endWidget();}
?>