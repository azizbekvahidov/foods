<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" >
	<meta name="language" content="en"/>

	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css"/>-->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/metisMenu.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/chosen.css" rel="stylesheet">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/chosen.jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/sb-admin-2.css"/>
</head>

<body>
    <div id="page">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <!-- /.navbar-header -->
            <div class="navbar-header">
                <?= CHtml::link(CHtml::encode(Yii::app()->user->getName()),array('site/index'),array('icon'=>'fa fa-sign-out fa-fw', 'class'=>'navbar-brand'));?>
            </div>
            <ul class="nav navbar-top-links navbar-right">

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <?php $this->widget('zii.widgets.CMenu',array(
                        'encodeLabel' => false,
                        'htmlOptions'=>array(
                            'class'=>'dropdown-menu dropdown-user'
                        ),
            			'items'=>array(
            				array('label'=>'<i class="fa fa-sign-out fa-fw"></i>Войти', 'url'=>array('/delivery/default/login'), 'visible'=>Yii::app()->user->isGuest),
            				array('label'=>'<i class="fa fa-sign-out fa-fw"></i>Выйти ('.Yii::app()->user->name.')','url'=>array('/delivery/default/logout'), 'visible'=>!Yii::app()->user->isGuest)
            			),
            		)); ?>
                </li>
                <!-- /.dropdown -->
            </ul>
           
        </nav>
        <div >
        	
        	<?php if(isset($this->breadcrumbs)):?>
        		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
        			'links'=>$this->breadcrumbs,
        		)); ?><!-- breadcrumbs -->
        	<?php endif?>
        
        	<?php echo $content; ?>
        
        	
        
        	<!--<div id="footer">
        		Copyright &copy; <?php echo date('Y'); ?> by Azizbek.<br/>
        		Все права защищены.<br/>
        		<?php echo Yii::powered(); ?>
        	</div><!-- footer -->
        </div>
</div><!-- page -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/metisMenu.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sb-admin-2.js"></script>

</body>
</html>
