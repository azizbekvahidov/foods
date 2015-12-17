<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'menu-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<?php echo $form->errorSummary($model); ?>
<?=Chtml::dropDownList('mType','',CHtml::listData(MenuType::model()->findAll(),'mType_id','name'),array('empty'=>'Выберите тип меню'))?>
<div id="listData"></div>
<script>
    $(document).ready(function(){
        $('#mType').change(function(){
            var id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('menu/menuList'); ?>",
                data: "mType="+id,
                success: function(data){
                    $('#listData').html(data);
                }
            });
        });
    });
</script>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
