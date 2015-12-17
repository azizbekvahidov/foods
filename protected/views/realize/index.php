<?php
/* @var $this RealizeController */
/* @var $dataProvider CActiveDataProvider */


$menu=array();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'_menu.php');
/*$this->menu=array(
	array('label'=>'Realize','url'=>array('index'),'icon'=>'fa fa-list-alt', 'items' => $menu)	
);*/

Yii::app()->clientScript->registerScript('search', "
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('realize-grid', {
			data: $(this).serialize()
		});
		return false;
	});
");

Yii::app()->clientScript->registerScript('refreshGridView', "
	// automatically refresh grid on 5 seconds
	//setInterval(\"$.fn.yiiGridView.update('realize-grid')\",5000);
");

?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Архив продуктов' ,
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

  
    <table class="table table-striped table-bordered table-hover" id="dataTable">
        <thead>
            <tr>
                <th>Название продукта</th>
                <th>Поставщик</th>
                <th>Количество</th>
                <th>Дата реализации</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            <? foreach($newGroupModel as $row){
                $tempCount = 0;
                $tempPrice = 0;
                $tempId = 0;
                $tempSumm = 0;
                foreach($row->getRelated('realizes') as $value){
                    $tempCount = $tempCount + $value['count'];
                    $tempPrice = $tempPrice + $value['price'];
                    if($row['prod_id'] == $value['prod_id']){
                        $tempId = $value['prod_id'];
                        $tempSumm = $value['count']*$value['price'];
                    }
                }
                ?><!--
                <tr>
                    <td><?//=$row->getRelated('group')->name;?></td>
                    <td><?//=$row->getRelated('fakture')->getRelated('provider')->name?> </td>
                    <td><?//=$tempCount?></td>
                    <td><?//=$row->getRelated('fakture')->realize_date?></td>
                    <td><?//=(int)($tempSumm/$tempCount) ?></td>
                </tr> -->               
            <?}?>
            <? foreach($newModel as $row){?>
                <tr>
                    <td><?=$row->getRelated('products')->name;?></td>
                    <td><?=$row->getRelated('fakture')->getRelated('provider')->name?> </td>
                    <td><?=$row['attributes']['count']?></td>
                    <td><?=$row->getRelated('fakture')->realize_date?></td>
                    <td><?=$row['attributes']['price']?></td>
                </tr>
            <?}?>
        </tbody>
    </table>

    <?php /*echo CHtml::beginForm(array('export')); ?>
    <?php $this->widget('bootstrap.widgets.TbGridView',array(
    	'id'=>'realize-grid',
    	'dataProvider'=>$model->search(),
    	'filter'=>$model,
    	'type' => 'striped hover', //bordered condensed
    	'columns'=>array(
    		array(
    	        'name'=> 'realize_id',
    	        'value' => '($data->realize_id)',
    	        'headerHtmlOptions' => array('style' => 'text-align:center;'),
    	    ),
    		
    		array(
    	        'name'=> 'faktura_id',
    	        'value' => '($data->faktura_id)',
    	        'headerHtmlOptions' => array('style' => 'text-align:center;'),
    	    ),
    		
    		array(
    	        'name'=> 'prod_id',
    	        'value' => '($data->prod_id)',
    	        'headerHtmlOptions' => array('style' => 'text-align:center;'),
    	    ),
    		
    		array(
    	        'name'=> 'price',
    	        'value' => '($data->price)',
    	        'headerHtmlOptions' => array('style' => 'text-align:center;'),
    	    ),
    		
    		array(
    	        'name'=> 'count',
    	        'value' => '($data->count)',
    	        'headerHtmlOptions' => array('style' => 'text-align:center;'),
    	    ),
    		
    
    		/*
    		//Contoh
    		array(
    	        'header' => 'Level',
    	        'name'=> 'ref_level_id',
    	        'type'=>'raw',
    	        'value' => '($data->Level->name)',
    	        // 'value' => '($data->status)?"on":"off"',
    	        // 'value' => '@Admin::model()->findByPk($data->createdBy)->username',
    	    ),
    		array(
    			'class'=>'bootstrap.widgets.TbButtonColumn',
    			'template'=>'{view}',
    			'buttons'=>array
                (
                    'view' => array
                    (    
                    	'url' => '$data->realize_id."|".$data->realize_id',              
                    	'click' => 'function(){
                    		data=$(this).attr("href").split("|")
                    		$("#myModalHeader").html(data[1]);
    	        			$("#myModalBody").load("'.$this->createUrl('view').'&id="+data[0]+"&asModal=true");
                    		$("#myModal").modal();
                    		return false;
                    	}',
                    ),
                                )
    		),
    	),
    )); */?>
    <!--
    <select name="fileType" style="width:150px;">
    	<option value="Excel5">EXCEL 5 (xls)</option>
    	<option value="Excel2007">EXCEL 2007 (xlsx)</option>
    	<option value="HTML">HTML</option>
    	<option value="PDF">PDF</option>
    	<option value="WORD">WORD (docx)</option>
    </select>
    <br>-->
    
    <?php /*
    $this->widget('bootstrap.widgets.TbButton', array(
    	'buttonType'=>'submit', 'icon'=>'fa fa-print','label'=>'Export', 'type'=> 'primary'));*/
    ?>
    <?php //echo CHtml::endForm(); ?>
    
 <script>
    
    $(document).ready(function() {
        $('#dataTable').DataTable({
                responsive: true,
                "order": [[ 3, "desc" ]]               
        });
    });
 </script>
<?php $this->endWidget(); ?>

