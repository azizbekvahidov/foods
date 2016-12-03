<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/sb-admin-2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/metisMenu.min.css"/>
<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Авторизация</h3>
                    </div>
                    <?
                    $this->pageTitle=Yii::app()->name . ' - Login';
                    
                    ?>
                    <div class="form panel-body">
                        <?php $form=$this->beginWidget('CActiveForm', array(
                        	'id'=>'login-form',
                        	'enableClientValidation'=>true,
                        	'clientOptions'=>array(
                        		'validateOnSubmit'=>true,
                        	),
                        )); ?>
                        
                         <fieldset>
                            <div class="form-group">
                            		<?php echo $form->textField($model,'username',array("class"=>"form-control","placeholder"=>"Логин")); ?>
                            		<?php echo $form->error($model,'username'); ?>
                            </div>
                            <div class="form-group">
                            		<?php echo $form->passwordField($model,'password',array("class"=>"form-control","placeholder"=>"Пароль")); ?>
                            		<?php echo $form->error($model,'password'); ?>
                            		
                            </div>
                        		<?php echo CHtml::submitButton('Войти',array('class'=>'btn btn-lg btn-success btn-block')); ?>
                        
                        <?php $this->endWidget(); ?>
                    </div><!-- form -->
            </div>
        </div>
    </div>
</div>
