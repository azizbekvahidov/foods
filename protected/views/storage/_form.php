<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'storage-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<?php echo $form->datepickerRow($model,'curDate',
								array(
					                'options' => array(
					                    'language' => 'id',
					                    'format' => 'yyyy-mm-dd', 
					                    'weekStart'=> 1,
					                    'autoclose'=>'true',
					                    'keyboardNavigation'=>true,
					                ), 
                                    'value'=>"2015-03-02",
					            ),
					            array(
					                'prepend' => '<i class="icon-calendar"></i>'
					            )
			);; ?>
<?php // echo $form->dropDownListRow($model,'prod_id',CHtml::listData(Products::model()->findAll(),'product_id','name'),array('class'=>'','id'=>'products')); ?>
<?php// echo $form->textFieldRow($model,'curCount',array('class'=>'span5')); ?>
<div class="form-group">
    <table id="prodList" class="table table-striped table-bordered table-hover ">
        <thead>
            <tr>
                <th style="text-align:center;">Название продукта</th>
                <th style="text-align:center;">Количество</th>
                <th style="text-align:center;">Цена</th>
            </tr>
        </thead>
        <tbody>
            <tr style="display: none;"></tr>
            <? if(!$curModel){?>
            <? foreach($products as $key => $value){?>
                <tr>
                    <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$value->product_id?>' /><?=$value->name?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='count[]'  /> &nbsp; <?=$value->getRelated('measure')->name?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='price[]'  /></td>
                    <!--<td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>-->
                </tr>
            <?}?>
            <? } else{?>
            <? foreach($curModel as $key => $value){?>
                <tr>
                    <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$value->prod_id?>' /><?=$value->getRelated('product')->name?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='count[]' value="<?=$value->curCount?>" /> &nbsp; <?=$value->getRelated('product')->getRelated('measure')->name?></td>
                    <td style='text-align:center;'><input class='span1' type='text' name='price[]' value="<?=$value->price?>" /></td>
                </tr>
            <?}?>
            <?}?>
        </tbody>
    </table>
</div>
<script>
    $('#submitBtn').attr('disabled','disabled');
    $(document).on("click", ".deleteRow", function() {
        $(this).parent().parent().remove();
    });
    
    $("#products").chosen({
                    no_results_text: "Oops, nothing found!",
                }).change(function(){
                    var prodValue = $(this).val();
                    var prodText = $(this).children('option:selected').text();
                    if(prodValue != ''){
                        $('#prodList tr:last').after("\
                            <tr>\
                                <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                                <td style='text-align:center;'><input class='span1' type='text' name='count[]' /></td>\
                                <td style='text-align:center;'><input class='span1' type='text' name='price[]' /></td>\
                                <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                            </tr>\
                        ");
                    }
                }); 
                
</script>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
