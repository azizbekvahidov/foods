<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'dep-storage-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<style>
    input[type="text"],.input-append .add-on, .input-prepend .add-on{
        height: 30px!important;
    }
    .input-append, .input-prepend, label{
        display: block!important;
    }
</style>
	<?php echo $form->errorSummary($model); ?>
<div class="span3">
    <?php echo $form->datepickerRow($model,'curDate',
								array(
					                'options' => array(
					                    'language' => 'id',
					                    'format' => 'yyyy-mm-dd', 
					                    'weekStart'=> 1,
					                    'autoclose'=>'true',
					                    'keyboardNavigation'=>true,
					                ), 
					            ),
					            array(
					                'prepend' => '<i class="icon-calendar"></i>'
					            )
			);; ?>
</div>
<div class="span3">
            <label for="DepStorage_department_id">Выберите отдел</label>
<?php echo $form->dropDownList($model,'department_id',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('class'=>'span3','empty'=>'выберите отдел')); ?>

</div>
<br /><br />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>
<div class="form-group" id="data">
    
</div>
<script>
    $(document).ready(function() {
        $('#DepStorage_department_id').change(function(){
            var dates = $(this).val();
            console.log(dates);
            $.ajax({
               type: "POST",
               url: "<?php echo Yii::app()->createUrl('depStorage/depForm'); ?>",
               data: "depId="+dates,
               success: function(data){
                 $('#data').html(data);
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
