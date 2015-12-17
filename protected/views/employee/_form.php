<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'employee-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<style>
    #department{
        display: none;
    }
</style>

	<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model,'name',array('class'=>'span3','maxlength'=>100)); ?>
<?php echo $form->textFieldRow($model,'login',array('class'=>'span3','maxlength'=>100)); ?>
<label for="Employee_password">Пароль</label>
<?php echo CHtml::passwordField('Employee[password]','',array('class'=>'span3','maxlength'=>100))?>
<?php echo $form->dropDownListRow($model,'role',array(0=>'Повар',1=>'Официант',2=>'Администратор'),array('class'=>'span3','empty'=>'Выберите роль'))?>
<span id="department">
<?php echo $form->dropDownListRow($model,'depId',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('class'=>'span3','empty'=>'Выберите отдел'))?>
</span>
<?php echo $form->checkBoxRow($model,'check_percent')?>


<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
		)); ?>
</div>
<script>
    $(document).ready(function(){
        $('#Employee_role').change(function(){
            if($(this).val() == 0){
                $('#department').show();
            }
            else{
                $('#department').hide();
                $('#Employee_depId').val(0);
            }
        });

    });
</script>
<?php $this->endWidget(); ?>
