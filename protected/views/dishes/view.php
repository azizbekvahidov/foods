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
        <? $sum_prod = 0; $products = new Products(); $dates = date('Y-m-d');
            if(!empty($dishProd['prod']))
                foreach($dishProd['prod'] as $key => $val){
                    $price = $products->getCostPrice($key,$dates);?>
                     <tr>
                        <td><?=$products->getName($key)?></td>
                        <td><?=$val?></td>
                        <td><?=$products->getMeasure($key)?></td>
                        <td><?=$price*$val?></td>
                    </tr>
                    <? $sum_prod = $sum_prod + $price*$val."<br />";
                }
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

            if(!empty($dishProd['stuff'])){
                foreach($dishProd['stuff'] as $keys => $value){

                    $price = $stuff->getCostPrice($keys,$dates)

                    ?>
                    <tr>
                        <td><?=$stuff->getName($keys)?></td>
                        <td><?=$value?></td>
                        <td><?=$stuff->getMeasure($keys)?></td>
                        <td><?=number_format( $price*$value, 2 )?></td>
                    </tr>
                    <? $DishSumm = $DishSumm + $price*$value;
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
                <? if($dishProd['count'] != null){?>
                    <th><?=$dishProd['count']?></th>
                <?} else{?>
                    <th>Не указано</th>
                <?}?>
            </tr>
            <tr>
                <th>Себестоимость блюда</th>
                <? if($dishProd['count'] != null){?>
                    <th><?=number_format( ($sum_prod+$DishSumm)/$dishProd['count'], 2 )?></th>
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