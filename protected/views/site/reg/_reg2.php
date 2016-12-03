<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<?php $this->pageTitle = 'Регистрация организации';
    $this->pageDescription = 'Регистрация организации';
?>
<div class="form">
    <form method="POST" action="/site/registration2" id="org-exist-form" class="form form-horizontal">
        <?php echo CHtml::dropDownList('orgID', '', Organization::All(), array('empty'=>'-Выберите организацию-', 'value'=>'-1', 'class'=>'form-control')); ?>
         
            <?php echo CHtml::button('Выбрать', array("class"=>"btn btn-info","onClick"=>"RegOrgClick(this);", "id" => "regNewOrg_btn", 'class'=>'form-control')); ?>
        <?php echo CHtml::hiddenField("userid", $userid, array('class'=>'form-control'));?>
    </form>
</div>
<div class="form">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'org-form',
    'htmlOptions'=>array('class'=>"form-horizontal",),
    
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>
    <fieldset>
    <h4>Регистрация данных организации</h4>

	<p class="note">Поля с <span class="required">*</span> должны быть заполнены.</p>

	<?php echo $form->errorSummary($model); ?>
    
    

	<div class="form-group">
		<?php echo $form->labelEx($model,'От кого', array('label'=>'От кого', "class"=>"col-md-4 control-label", "for"=>"username")); ?>
		<div class="col-md-5">
        <?php echo $form->textField($model,'orgName',array("class"=>"form-control input-md" )); ?>
		
        <?php echo $form->error($model,'orgName'); ?>
        </div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'Откуда',array('label'=>'Откуда', "class"=>"col-md-4 control-label", "for"=>"password")); ?>
		<div class="col-md-5">
        <?php echo $form->textField($model,'orgFrom', array("class"=>"form-control input-md" )); ?>
		
        <?php echo $form->error($model,'orgFrom'); ?>
        </div>
	</div>
    
    

	<div class="form-group">
		<?php echo $form->labelEx($model,'Индекс', array('label'=>'Индекс',"class"=>"col-md-4 control-label", "for"=>"email")); ?>
		<div class="col-md-5">
        <?php echo $form->textField($model,'index', array("class"=>"form-control input-md" )); ?>
		
        <?php echo $form->error($model,'index'); ?>
        </div>
	</div>
    <?php if(CCaptcha::checkRequirements()):?>
    <div class="form-group">
    
		<?php echo $form->labelEx($model,'Текст в изображении', array('label'=>'Текст в изображении',"class"=>"col-md-4 control-label", "for"=>"captcha")); ?>
		<div class="col-md-6">
        <?php $this->widget('CCaptcha');?>
        <?php echo $form->textField($model,'captcha', array("class"=>"form-control input-md" )); ?>
		<div class="help-block">Введите текст с изображения</div>
        <?php echo $form->error($model,'captcha'); ?>
        </div>
	</div>
    <?php endif;?>

	<div class="form-group">
		
            <?php echo CHtml::hiddenField("userid", $userid);?>
		
	</div>
    <div class="form-group">
      <label class="col-md-4 control-label" for="announcerule">Соглашаюсь с </label>
      <div class="col-md-4">
      <div class="checkbox">
        <label for="announcerule-0">
          <input type="checkbox" name="announcerule" id="announcerule-0" value="1">
          с правилами
        </label>
    	</div>
      </div>
    </div>


	<div class="form-group">
        <label class="col-md-4 control-label" for="register"></label>
        <div class="col-md-4">
        <?php echo CHtml::button('Регистрация', array("id"=>"regNewOrg_btn", "name"=>"register", "class"=>"btn btn-info", "onClick"=>"RegOrgClick(this);")); ?>
        </div>
	</div>
    </fieldset>

<?php $this->endWidget(); ?>

</div><!-- form -->