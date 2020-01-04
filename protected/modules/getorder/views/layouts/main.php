<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" >
	<meta name="language" content="en"/><meta name="viewport" content="width=device-width, initial-scale=no">

	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/print.css" media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/ie.css" media="screen, projection">
	<![endif]-->

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/orders/jquery-1.8.3.js"></script>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/bootstrap3.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/metisMenu.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/chosen.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/keyboard.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/helping.css " />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/vKey.css " />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/orders/chosen.jquery.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/orders/keyboard.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/orders/mainKeyboard.js" type="text/javascript"></script>

</head>

<body class="wood">
    <div id="page">

        	<?php echo $content; ?>


        </div>
</div><!-- page -->

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap3.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/orders/jQuerySession.js"></script>

</body>
</html>
