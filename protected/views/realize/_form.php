<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'realize-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); $products = new Products()?>

	<p class="note">Поля с <span class="required">*</span> Обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

<div class="form-group">
    <?= CHtml::dropDownList('provider','',$provList,array('empty' => '--Выберите поставщика--','id'=>'provider'))?>&nbsp; &nbsp;
    <?= CHtml::dropDownList('products','',$prodList,array('empty' => '--Выберите продукт--','id'=>'product'))?>&nbsp; &nbsp;
    <!--
    <?php echo $form->textFieldRow($model,'prod_id',array('class'=>'span5')); ?>
    <?php echo $form->textFieldRow($model,'price',array('class'=>'span5')); ?>
    <?php echo $form->textFieldRow($model,'count',array('class'=>'span5')); ?>-->
</div><br /><br />
<div class="form-group">
    <table id="prodList" class="table table-striped table-hover ">
        <thead>
            <tr>
                <th style="text-align:center;">Название продукта</th>
                <th style="text-align:center;">Количество</th>
                <th style="text-align:center;">Цена</th>
                <th style="text-align:center;">Удалить</th>
            </tr>
        </thead>
        <tbody>
            <tr style="display: none;"></tr>
        </tbody>
    </table>
</div>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
            'id'=>'submitBtn',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>
<script>
    $('#submitBtn').attr('disabled','disabled');
    $(document).on("click", ".deleteRow", function() {
        $(this).parent().parent().remove();
    });
    $("#product").chosen({
                    no_results_text: "Oops, nothing found!",
                }).change(function(){
                    var prodValue = $(this).val();
                    var prodText = $(this).children('option:selected').text();
                    if(prodValue != ''){

                            $.ajax({
                                type: "POST",
                                url: "<?php echo Yii::app()->createUrl('realize/getProdList'); ?>",
                                data: 'id='+prodValue,
                                success: function(data){
                                    $('#prodList tr:last').after(data);
                                }
                            });
                            /*"\
                            <tr>\
                                <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                                <td style='text-align:center;'><input class='span1' type='text' name='count[]' /> <?//=$products->getMeasure()?></td>\
                                <td style='text-align:center;'><input class='span1' type='text' name='price[]' /></td>\
                                <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                            </tr>\
                    "*/
                    }
                }); 
    $("#provider").chosen({
                    no_results_text: "Oops, nothing found!",
                }).change(function(){
                    if($("#provider").val()){
                            $('#submitBtn').removeAttr('disabled');
                        }
                        else{
                            $('#submitBtn').attr('disabled','disabled');
                        }
                   // alert($(this).val());
                   var depId = $(this).val();
                    $.ajax({
                       type: "POST",
                       url: "<?php echo Yii::app()->createUrl('realize/prodList'); ?>",
                       data: 'provider_id='+depId,
                       success: function(data){

                         $('#prodList tr:last').after(data);
                        }
                    });
                });

    $(document).ready(function(){
        
        
        $('#provider').change(function(){
            
        });
    });      
</script>
<?php $this->endWidget(); ?>
