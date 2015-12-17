<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'provider-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>100)); ?><br />
<?php echo CHtml::dropDownList('product','',CHtml::listData(Products::model()->findAll(),'product_id','name'),array('empty'=>'--выберите продукт--','class'=>'span2 left all_product listbox','id'=>'all_product')); ?>
    <div class="form-group">
        <table id="structList" class="table table-striped table-hover ">
            <thead>
                <tr>
                    <th style="text-align:center;">Название продукта</th>
                    <th style="text-align:center;">Удалить</th>
                </tr>
            </thead>
            <tbody>
                <tr style="display: none;"></tr>
                <? if(!empty($chosenProduct)){
                    ?>
                    <? foreach($chosenProduct as $key => $val){?>
                        <tr>
                            <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$val->getRelated('products')->product_id ?>' /><?=$val->getRelated('products')->name?></td>
                            <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
                        </tr>
                    <?}?> 
                <?}?>
                
            </tbody>
        </table>
    </div>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<script>
        $(document).on("click", ".deleteRow", function() {
            var temp_id = $(this).parent().parent().children('td:first-child').children('input').val(); 
            $(this).parent().parent().remove();
        });
        $("#all_product").chosen({
            no_results_text: "Oops, nothing found!",
        }).change(function(){
            var prodValue = $(this).val();
            var prodText = $(this).children('option:selected').text();
            if(prodValue != ''){
                $('#structList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
            }
        }); 
</script>
<?php $this->endWidget(); ?>
