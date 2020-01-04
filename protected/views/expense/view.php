<?php
/* @var $this ExpenseController */
/* @var $model Expense */



if(!isset($_GET['asModal'])){
?>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'View Expenses #'.$dishModel[0]->expense_id,
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
        
          <? $count = 1; $summ = 0; $curPercent = 0; $prices = new Prices();?>
    <table class="table table-hover table-bordered">
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
            <? foreach($dishModel as $value){?>
                <? foreach($value->getRelated('order') as $key => $val){
				$price = $prices->getPrice($val->just_id,$value->mType,$val->type,$value->order_date)?>
                <tr>                
                    <td><?=$count?></td>            
                    <td><?=$val->getRelated('dish')->name?></td>
                    <td><?=$val->count?></td>
                    <td><?=number_format($price,0,'.',',')?></td>
                    <td><?=number_format($val->count*$price,0,'.',','); $summ = $summ + $val->count*$price?></td>
                </tr>                                                    
                <? $count++; }?>
	            <? if($value->getRelated('employee')->check_percent == 1)
		            $curPercent = $percent;
		            ?>
            <?}?>
            <? foreach($stuffModel as $value){?>
                <? foreach($value->getRelated('order') as $key => $val){
				$price = $prices->getPrice($val->just_id,$value->mType,$val->type,$value->order_date)?>
                <tr>
                    <td><?=$count?></td>
                    <td><?=$val->getRelated('halfstuff')->name?></td>
                    <td><?=$val->count?></td>
                    <td><?=number_format($price,0,'.',',')?></td>
                    <td><?=number_format($val->count*$price,0,'.',','); $summ = $summ + $val->count*$price?></td>
                </tr>
                <? $count++; }?>
	            <? if($value->getRelated('employee')->check_percent == 1)
		            $curPercent = $percent;
	            ?>
            <?}?>
            <? foreach($prodModel as $value){?>
                <? foreach($value->getRelated('order') as $key => $val){
				$price = $prices->getPrice($val->just_id,$value->mType,$val->type,$value->order_date)?>
                <tr>
                    <td><?=$count?></td>
                    <td><?=$val->getRelated('products')->name?></td>
                    <td><?=$val->count?></td>
                    <td><?=number_format($price,0,'.',',')?></td>
                    <td><?=number_format($val->count*$price,0,'.',','); $summ = $summ + $val->count*$price?></td>
                </tr>
                <? $count++; }?>
	            <? if($value->getRelated('employee')->check_percent == 1)
		            $curPercent = $percent;
	            ?>
            <?}?>
        </tbody>
        <tfoot>
	        <tr>
		        <th colspan="4">Сумма без процентов</th>
		        <th><?=number_format($summ,0,'.',',')?></th>
	        </tr>
	        <tr>
		        <th colspan="4">Процент на обслуживания</th>
		        <th><?=$curPercent,0?></th>
	        </tr>
            <tr>
                <th colspan="4">Общая сумма</th>
                <th><?=number_format($summ/100*$curPercent+$summ,0,'.',',');?></th>
            </tr>
        </tfoot>                
    </table>

<?php
if(!isset($_GET['asModal'])){
	$this->endWidget();}
?>