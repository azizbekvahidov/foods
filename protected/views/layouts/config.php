<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>Настройка MostbyteCafe</title>

    <!-- Bootstrap -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/loading-bar.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/custom.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/own.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/gentella/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/gentella/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/gentella/fastclick.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/gentella/loading-bar.min.js"></script>
    <!-- Custom Theme Scripts -->
<!--    <script src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/js/gentella/custom.min.js"></script>-->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/gentella/active.js"></script>



</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
        <?php echo $content; ?>

        </div>
    </div>





</body>
</html>
