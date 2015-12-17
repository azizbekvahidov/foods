<?$cnt = 1; $prod = new Products();?>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'employee-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>цена</th>
        </tr>
    </thead>
    <tbody>
    <?foreach($model as $val){
        if($prod->getCostPrice($val['product_id'],$dates) == 0){?>
        <tr class="error">
        <?}else{?>
        <tr>
        <?}?>
            <td><?=$cnt?></td>
            <td><?=$val['name']?></td>
            <td><input type="text" name="prod[<?=$val['product_id']?>]" value="<?=$prod->getCostPrice($val['product_id'],$dates)?>" class="span2" /></td>
        </tr>
    <?$cnt++;}?>
    </tbody>
</table>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
	)); ?>
</div>

<?php $this->endWidget(); ?>