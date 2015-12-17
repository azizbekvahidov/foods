
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Заказы по датам' ,
        'headerIcon' => 'icon- fa fa-list-ol',
        'headerButtons' => array(
            array(
                'class' => 'bootstrap.widgets.TbButtonGroup',
                'type' => 'success',
                // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                //'buttons' => $this->menu
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

<? $count = 1; $expense = new MBalance();?>
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Дата и время</th>
            <th>Выручка с процентом обслуживания</th>
            <th>Выручка без процентом обслуживания</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach( $newModel as $value){ ?>
            <tr>
                <td><?=$count?></td>
                <td><?=date('Y-m-d',strtotime($value->order_date))?></td>
                <td><?=number_format($expense->getProcProceeds(date('Y-m-d',strtotime($value->order_date))),0,'.',',')?></td>
                <td><?=number_format($expense->getProceeds(date('Y-m-d',strtotime($value->order_date))),0,'.',',')?></td>
                <td><?=CHtml::link('<i class="fa fa-eye fa-fw"></i>  Просмотр',array('expense/todayOrder?order_date='.date('Y-m-d',strtotime($value->order_date))))?></td>
            </tr>
        <? $count++; } ?>
    </tbody>
</table>

<?php  $this->endWidget(); ?>
