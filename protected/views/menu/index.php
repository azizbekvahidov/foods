<?php
/* @var $this MenuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Меню',
);

$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');
$this->menu=array(
	array('label'=>'Меню','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);


?>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Список меню' ,
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
		?>

<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php echo CHtml::beginForm(array('export')); ?>
<? $count = 1;?>
<div class="span5">
    <h3>Блюда</h3>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th></th>
                <th style="text-align: center;">Название</th>
                <th style="text-align: center;">Цена</th>
            </tr>
        </thead>
        <tbody>
            <?  foreach($dishModel as $key => $value){?>
            <tr>
                <th colspan="3"><?=$value['name']?></th>
            </tr>
                <? foreach($value->getRelated('menu') as $k => $val){?>
                <tr>
                    <td><?=$count?></td>
                    <td><?=$val->getRelated('dish')->name?></td>
                    <td><?=$val->getRelated('dish')->price?></td>
                </tr>
                <? $count++; }?>
            <?}?>
        </tbody>
        <tfoot>
        
        </tfoot>
    </table>    
    </div>
    <? $count = 1;?>
    <div class="span5">
    <h3>Продукты</h3>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th></th>
                <th style="text-align: center;">Название</th>
                <th style="text-align: center;">Цена</th>
            </tr>
        </thead>
        <tbody>
            <?  foreach($prodModel as $key => $value){?>
            <tr>
                <th colspan="3"><?=$value['name']?></th>
            </tr>
                <? foreach($value->getRelated('menu') as $k => $val){?>
                <tr>
                    <td><?=$count?></td>
                    <td><?=$val->getRelated('products')->name?></td>
                    <td><?=$val->getRelated('products')->price?></td>
                </tr>
                <? $count++; }?>
            <?}?>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>
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
