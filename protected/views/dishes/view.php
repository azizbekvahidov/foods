<?php
/* @var $this DishesController */
/* @var $model Dishes */

$this->breadcrumbs=array(
	'Блюда'=>array('index'),
	$model->name,
);

$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');


$menu2=array(
	array('label'=>'Блюда','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);

if(!isset($_GET['asModal'])){
?>
    <?php $box = $this->beginWidget(
        'bootstrap.widgets.TbBox',
        array(
            'title' => 'Блюда #'.$model->dish_id,
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
        <? $sum_prod = 0; $prodPrice = new Products(); $dates = date('Y-m-d');
            foreach($this->dish_product->getRelated('products') as $key => $val){
                 foreach($this->dish_product->getRelated('dishStruct') as $value){
                    $price = $prodPrice->getCostPrice($val->product_id,$dates);
                    if($val->product_id == $value->prod_id){
                        $amount = $value->amount;
                    }
                }
                ?>
                 <tr>
                    <td><?=$val->name?></td>
                    <td><?=$amount?></td>
                    <td><?=$val->getRelated('measure')->name?></td>           
                    <td><?=$price*$amount?></td>
                </tr> 
            <? $sum_prod = $sum_prod + $price*$amount."<br />";}
            ?>
        
        </tbody>
        <tfoot>
            <tr>
                <th>Сумма</th>
                <th></th>
                <th></th>
                <th><?=number_format( $sum_prod, 2 )?></th>
            </tr>
        </tfoot>
   </table>
</div>
<div class="">
    <label>Полуфабрикаты</label>
    <table class="table table-bordered">
        <thead>
            <th>название</th>
            <th>кол</th>
            <th>ед.изм</th>
            <th>цена</th>
        </thead>
        <tbody>
        <? 
                    $sum = array();
                    $DishSumm = 0;
                    $stuff = new Halfstaff();
                    
            if($HalfstuffProd){
                foreach($HalfstuffProd->getRelated('halfstuff') as $keys => $value){
                    
                    $price = $stuff->getCostPrice($value->getRelated('Structs')->halfstuff_id,$dates)
                    
                ?>
                <tr>
                    <td><?=$value->getRelated('Structs')->name?></td>
                    <td><?=$value->amount?></td>
                    <td><?=$value->getRelated('Structs')->getRelated('halfstuffType')->name?></td>
                    <td><?=number_format( $price*$value->amount, 2 )?></td>
                </tr>
            <? $DishSumm = $DishSumm + $price*$value->amount;
                }
            }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Сумма</th>
                <th></th>
                <th></th>
                <th><?=number_format( $DishSumm, 2 )?></th>
            </tr>
        </tfoot>
   </table>
   <table class="table table-bordered">
    <tbody>
        <tr>
            <th>Количество порций</th>
            <? if($this->dish_product['count'] != null){?>
            <th><?=$this->dish_product['count']?></th>
            <?} else{?>
            <th>Не указано</th>
            <?}?>
        </tr>
        <tr>
            <th>Себестоимость блюда</th>
            <? if($this->dish_product['count'] != null){?>
            <th><?=number_format( ($sum_prod+$DishSumm)/$this->dish_product['count'], 2 )?></th>
            <?} else{?>
            <th><?=number_format( ($sum_prod+$DishSumm)/1, 2 )?></th>
            <?}?>
        </tr>

    </tbody>
   </table>
   
        <?=CHtml::link('Редактировать <i class="icon-pencil"></i>',array('/dishes/update?id='.$id),array('target'=>'_blank','class'=>'btn btn-success edit'));?>
</div>
<?php
if(!isset($_GET['asModal'])){
	$this->endWidget();}
?>