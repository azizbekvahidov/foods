<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<?php $this->pageTitle = 'Регистрация нового пользователя';
    $this->pageDescription = 'Регистрация нового пользователя Advert.uz';
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-registration-form',
    'htmlOptions'=>array('class'=>"form-horizontal",),
    
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>
    <fieldset>
    <h4>Регистрация пользователя</h4>

	<p class="note">Поля с <span class="required">*</span> должны быть заполнены.</p>

	<?php echo $form->errorSummary($model); ?>
    
    

	<div class="form-group">
		<?php echo $form->labelEx($model,'Имя пользователя', array('label' =>'Имя пользователя', "class"=>"col-md-4 control-label", "for"=>"username")); ?>
		<div class="col-md-5">
        <?php echo $form->textField($model,'username',array("id"=>"username", "class"=>"form-control input-md" )); ?>
		
        <?php echo $form->error($model,'username'); ?>
        </div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'Пароль',array('label' =>'Пароль',"class"=>"col-md-4 control-label", "for"=>"password")); ?>
		<div class="col-md-5">
        <?php echo $form->passwordField($model,'password', array("id"=>"password", "class"=>"form-control input-md" )); ?>
		
        <?php echo $form->error($model,'password'); ?>
        </div>
	</div>
    
    <div class="form-group">
		<?php echo $form->labelEx($model,'Повторный пароль', array('label' =>'Повторный пароль',"class"=>"col-md-4 control-label", "for"=>"repassword")); ?>
        <div class="col-md-5">
        <?php echo $form->passwordField($model,'repassword', array("id"=>"repassword", "class"=>"form-control input-md" )); ?>
		
        <?php echo $form->error($model,'repassword'); ?>
        </div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'E-MAIL', array("class"=>"col-md-4 control-label", "for"=>"email")); ?>
		<div class="col-md-5">
        <?php echo $form->textField($model,'email', array("id"=>"email", "class"=>"form-control input-md" )); ?>
		
        <?php echo $form->error($model,'email'); ?>
        </div>
	</div>
    <?php if(CCaptcha::checkRequirements()):?>
    <div class="form-group">
    
		<?php echo $form->labelEx($model,'Текст в изображении', array('label' =>'Текст в изображении',"class"=>"col-md-4 control-label", "for"=>"captcha")); ?>
		<div class="col-md-6">
        <?php $this->widget('CCaptcha');?>
        <?php echo $form->textField($model,'captcha', array("class"=>"form-control input-md" )); ?>
		<div class="help-block">Введите текст с изображения</div>
        <?php echo $form->error($model,'captcha'); ?>
        </div>
	</div>
    <?php endif;?>

	<div class="form-group">
		
		<?php echo $form->hiddenField($model,'role', array('value'=>'guest')); ?>
		<?php echo $form->error($model,'role'); ?>
            
                    <?php echo $form->hiddenField($model, "IsActive", array('value'=>'0')); ?>
                    <?php echo CHtml::hiddenField("step1"); ?>
		
	</div>
    


	<div class="form-group">
        <label class="col-md-4 control-label" for="register"></label>
        <div class="col-md-4">
        <?php echo CHtml::button('Далее',array("id"=>"regUser", "name"=>"regUser", "class"=>"btn btn-info", "onClick"=>"RegUserClick();")); ?>
        </div>
	</div>
    </fieldset>

<?php $this->endWidget(); ?>

</div><!-- form -->