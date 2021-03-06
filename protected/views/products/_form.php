<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'products-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>
<div class="form-group">
    <?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>100)); ?><br />
</div>

<div class="form-group" >
    <?php echo $form->dropDownListRow($model,'measure_id',CHtml::listData(Measurement::model()->findAll(),'measure_id','name'),array('class'=>'span2')); ?>
</div>


<div class="form-actions span9">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
		)); ?>
</div>
<script>
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();
    $('#Products_name').keyup(function() {
        var value = $(this).val();
        delay(function(){
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('products/ajaxCheckProd'); ?>",
                data: 'value='+value,
                success: function(data){
                    if(data == 1){
                        alert("Такой продукт существует");
                        $("#Products_name").val('');
                    }
                }
            });
        }, 500 );
    });
</script>

<?php $this->endWidget(); ?>
