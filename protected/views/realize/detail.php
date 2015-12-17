<?php
/* @var $this RealizeController */
/* @var $model Realize */


$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');


$menu2=array(
	array('label'=>'Realize','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);


?>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Приход по дате #',
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
);
?>

		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>
<table class="table table-striped table-bordered table-hover" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Дата</th>
            <th>Сумма</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? $summ = 0; $counter = 1;
        foreach($Products as $key => $row){?>
            <tr>
                <td><?=$counter?></td>
                <td><?=$row?></td>
                <td><?=number_format($summa[$key],0,'.',',')?></td>
                <td><?=CHtml::link('<i class="fa fa-eye fa-fw"></i> Просмотреть',array('realize/view?currentDate='.$row)); $summ = $summ + $summa[$key] ?></td>
            </tr>
        <? $counter++;}?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Сумма прихода</th>
            <th><?=number_format($summ,0,'.',',')?></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<script>
    
    $(document).ready(function() {
        $('#dataTable').DataTable({
                responsive: true,
                "order": [[ 3, "desc" ]]               
        });
    });
 </script>
<?php

	$this->endWidget();
?>