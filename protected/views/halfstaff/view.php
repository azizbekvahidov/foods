    <?php
/* @var $this HalfstaffController */
/* @var $model Halfstaff */

$this->breadcrumbs=array(
	'Halfstaff'=>array('index'),
	$model->name,
);

$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');


$menu2=array(
	array('label'=>'Halfstaff','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);

if(!isset($_GET['asModal'])){
?>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'View Halfstaff #'.$model->halfstuff_id,
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
		?>	
<div class="">
    <label>Продукты</label>
    <table class="table table-bordered">
        <thead>
            <th>название</th>
            <th>кол</th>
            <th>ед.изм</th>
            <th>цена</th>
        </thead>
        <tbody>
        <? $sum = 0;
        $prod = new Products();
        $dates = date('Y-m-d');
        if($Products){
            foreach($Products->getRelated('stuffStruct') as $key => $val){

                $struct = $Products->getRelated('stuffStruct');
                $price = $prod->getCostPrice($val->prod_id,$dates)
                ?>
                
              <tr>
                <td><?=$val->getRelated('Struct')->name?></td>
                <td><?=$val->amount?></td>
                <td><?=$val->getRelated('Struct')->getRelated('measure')->name?></td>
                <td><?=number_format( $price*$val->amount, 2 )?></td>
              </tr>  
                            
            <? $sum = $sum + $price*$val->amount;}        
            
        } if($Stuff){
            $stuffSum = 0;
            $stuff = new Halfstaff();
            foreach($Stuff->getRelated('stuffStruct') as $value){
                $price = $stuff->getCostPrice($value->prod_id,$dates);
                //$stuffSum = $countStuff/$value->getRelated('stuff')->count;
                ?> <tr>
                    <td><?=$value->getRelated('stuff')->name?></td>
                    <td><?=$value->amount?></td>
                    <td><?=$value->getRelated('stuff')->getRelated('halfstuffType')->name?></td>
                    <?
                    ?>

                    <td><?=number_format( $price*$value->amount, 2 );$sum = $sum + $price*$value->amount;?></td>
                </tr>
            <? } }?>
        </tbody>
        <tfoot>
            <tr>
                <th>Количество порций</th>
                <th></th>
                <th></th>
                <? if($Products->count != null){?>
                <th><?=$Products->count?></th>
                <?} else{?>
                <th>Не указано</th>
                <?}?>
            </tr>
            <tr>
                <th>Сумма</th>
                <th></th>
                <th></th>
                <? if($Products->count != null ){?>
                <th><?=number_format( $sum/$Products->count, 2 )?></th>
                <?} else{?>
                <th><?=number_format( $sum, 2 )?></th>
                <?}?>
               
            </tr>
        </tfoot>
   </table>
</div>

<?php
if(!isset($_GET['asModal'])){
	$this->endWidget();
}
?>
