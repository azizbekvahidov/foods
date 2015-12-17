<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'halfstaff-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Поля с <span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>
<div class="form-group">
    <?php echo $form->textFieldRow($model,'name',array('class'=>'span5')); ?><br />
<div class="span3"><?php echo $form->dropDownListRow($model,'stuff_type',CHtml::listData(Measurement::model()->findAll(),'measure_id','name')); ?></div>
    <div class="span3"><?php echo $form->textFieldRow($model,'count',array()); ?></div>
<div class="span3"><?php echo $form->dropDownListRow($model,'department_id',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'Выбрать отдел','class'=>'span3','maxlength'=>100)); ?></div>
        <div >
            <div class="">
                <div class="span3">
                    <h3>Продукты</h3>
                    <?php echo $form->listBox($model,'stuffStruct',CHtml::listData(Products::model()->findAll(),'product_id','name'),array('class'=>'span2 left all_options listbox','style'=>'height:200px!important','id'=>'all_product')); ?>
                </div>
                <div class="span3">
                    <h3>Полуфабрикаты</h3>
                    <?php echo $form->listBox($model,'stuffStruct',CHtml::listData(Halfstaff::model()->findAll(),'halfstuff_id','name'),array('class'=>'span2 left all_options listbox','style'=>'height:200px!important','id'=>'all_stuff')); ?>
                </div>
                <div class="form-group">
                    <table id="prodList" class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <th style="text-align:center;">Название продукта</th>
                                <th style="text-align:center;">Пропорция</th>
                                <th style="text-align:center;">Удалить</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="display: none;"></tr>
                            <? if($chosenProduct != ''){?>
                                <? foreach($chosenProduct as $key => $val){
                                    $struct = $val->getRelated('stuffStruct');?>
                                    <tr>
                                        <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$val['product_id']?>' /><?=$val['name']?></td>
                                        <td style='text-align:center;'><input class='span1' type='text' name='prod[]' value="<?=$struct[0]['amount']?>" /></td>
                                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
                                    </tr>
                                <?}?> 
                            <?}?>
                            <? if($chosenStuff != ''){?>
                                <? foreach($chosenStuff as $key => $val){
                                    $struct = $val[0]->getRelated('stuff'); ?>
                                    <tr>
                                        <td style='text-align:center;'><input type='text' style='display: none;' name='stuff_id[]' value='<?=$val[0]['prod_id']?>' /><?=$struct['name']?></td>
                                        <td style='text-align:center;'><input class='span1' type='text' name='stuff[]' value="<?=$val[0]['amount']?>" /></td>
                                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
                                    </tr>
                                <?}?> 
                            <?}
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
                $('#prodList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                        <td style='text-align:center;'><input class='span1' type='text' name='prod[]' /></td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
            }
        }); 
        $("#all_stuff").chosen({
            no_results_text: "Oops, nothing found!",
        }).change(function(){
            var prodValue = $(this).val();
            var prodText = $(this).children('option:selected').text();
            if(prodValue != ''){
                $('#prodList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='stuff_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                        <td style='text-align:center;'><input class='span1' type='text' name='stuff[]' /></td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
            }
        }); 
      </script>
      
   <div class="clear"></div>
</div>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>
<?php
Yii::app()->clientScript->registerScript("multiselect", "");
?>
<?php $this->endWidget(); ?>
