<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
	<meta name="language" content="en"/>
    <meta name="viewport" content="width=device-width, user-scalable=no">

	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css"/>-->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/metisMenu.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/datatables.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/chosen.css" rel="stylesheet">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/chosen.jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/helping.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap3.css"/>
</head>

<body>
    <div id="page">


        <nav class="navbar-default MainNavbar  navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>-->
                <?= CHtml::link('<span class="fa fa-home"></span>',array('site/index'),array('icon'=>'fa fa-sign-out fa-fw', 'class'=>'navbar-brand'));?>
                <span><?=date('Y-m-d')?></span>
            </div>
            <? $roles = Yii::app()->user->getRole();?>
            <!-- /.navbar-header -->
            <ul class="nav navbar-top-links navbar-right">
                <?if($roles > 1){?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bars fa-fw"></i> Заказы <i class="fa fa-caret-down"></i>
                    </a>
                    <?php $this->widget('bootstrap.widgets.TbMenu',array(
                        'htmlOptions'=>array(
                            'class'=>'dropdown-menu'
                        ),
                        'items'=>array(
                            array('label'=>'Все заказы', 'url'=>array('expense/index')),
                            array('label'=>'Заказы по официантам', 'url'=>array('expense/empExpense')),
                            array('label'=>'Выручка по датам', 'url'=>array('/expense/taken')),
                            array('label'=>'Заказы официантов', 'url'=>array('expense/empOrder')),
                            array('label'=>'Должники','url'=>array('expense/debtList')),
                            array('label'=>'Оплаченные долги','url'=>array('expense/paidDebt')),
                            array('label'=>'Мониторинг','url'=>array('monitoring/index')),
                        ),
                    )); ?>
                </li>
                <li>
                    <?=CHtml::link('<i class="fa fa-list-alt fa-fw"></i> Расход',array('/costs/create'),array('target'=>'_blank'));?>
                </li>
                <?if($roles == 2){?>
                    <li>
                        <?=CHtml::link('<i class="fa fa-list-alt fa-fw"></i> Добавить заказ',array('/order'),array('target'=>'_blank'));?>
                    </li>
                    <li>
                        <?=CHtml::link('<i class="fa fa-list-alt fa-fw"></i> Расходы',array('/costs/crate'),array('target'=>'_blank'));?>
                    </li>
                    <?}?>
                <?}?>
                <?if(Yii::app()->user->getRole() > 2){?>
<!--                <li class="dropdown">-->
<!--                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">-->
<!--                        <i class="fa fa-archive fa-fw"></i> Склад <i class="fa fa-caret-down"></i>-->
<!--                    </a>-->
<!--                    <ul class="dropdown-menu dropdown-user" id="yw1" aria-labelledby="dropdownMenu">-->
<!--	                    <li class="dropdown-submenu">-->
<!--		                    <a tabindex="-1" class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-archive fa-fw"></i> Приход</a>-->
<!--		                    --><?php //$this->widget('bootstrap.widgets.TbMenu',array(
//			                    'htmlOptions'=>array(
//				                    'class'=>'dropdown-menu'
//			                    ),
//			                    'items'=>array(
//				                    array('label'=>'Приход заготовок', 'url'=>array('inexpense/today')),
//				                    array('label'=>'Приход за сегодня', 'url'=>array('realize/today')),
//				                    array('label'=>'Приход по датам','url'=>array('realize/detail')),
//			                    ),
//		                    )); ?>
<!--	                    </li>-->
<!--                        <li class="dropdown-submenu">-->
<!--                            <a tabindex="-1" class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-folder-open fa-fw"></i> Показатели склада</a>-->
<!--                            --><?php //$this->widget('bootstrap.widgets.TbMenu',array(
//                                'htmlOptions'=>array(
//                                    'class'=>'dropdown-menu'
//                                ),
//                                'items'=>array(
//                                    array('label'=>'Начальный остаток склада', 'url'=>array('storage/index')),
//                                    array('label'=>'Показатели склада за сегодня', 'url'=>array('storage/today')),
//                                ),
//                            )); ?>
<!--                        </li>-->
<!--                        <li class="dropdown-submenu">-->
<!--                            <a tabindex="-1" class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-folder-open fa-fw"></i> Показатели отделов</span></a>-->
<!--                            --><?php //$this->widget('bootstrap.widgets.TbMenu',array(
//                                'htmlOptions'=>array(
//                                    'class'=>'dropdown-menu'
//                                ),
//                                'items'=>array(
//	                                array('label'=>'Приход по датам', 'url'=>array('depRealize/getDepOut')),
//	                                array('label'=>'Расход по датам', 'url'=>array('expense/getOut')),
//                                    array('label'=>'Начальный остаток отделов', 'url'=>array('DepStorage/index')),
//                                    array('label'=>'Показатели отделов за сегодня', 'url'=>array('DepStorage/today')),
//                                    array('label'=>'Все показатели отделов','url'=>array('DepStorage/allIn')),
//                                    array('label'=>'Расчеты по отделам','url'=>array('DepStorage/usedProd')),
//                                ),
//                            )); ?>
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-archive fa-fw"></i> Отчеты <i class="fa fa-caret-down"></i>
                    </a>
                    <?php $this->widget('zii.widgets.CMenu',array(
                        'encodeLabel' => false,
                        'htmlOptions'=>array(
                            'class'=>'dropdown-menu dropdown-user'
                        ),
                        'items'=>array(
                            array('label'=>'Приход продуктов', 'url'=>array('/faktura/providerProd')),
                            array('label'=>'Расходы по продуктам','url'=>'/report/prodExp'),
                            array('label'=>'Приход и расход по продуктам','url'=>'/report/allProd'),
                            array('label'=>'Реализованные блюда по отделам','url'=>'/report/depDish'),
                            array('label'=>'Мониторинг доходности блюд','url'=>'/report/dishIncome'),
                            array('label'=>'Мониторинг установленной наценки', 'url'=>array('report/settedMargin')),
                            array('label'=>'Мониторинг доходности отделов', 'url'=>array('report/depIncome')),
                            array('label'=>'Время приготовления заказов', 'url'=>array('report/readyTime')),
                            array('label'=>'Мониторинг пробитых заказов', 'url'=>array('report/empExpense')),
                            array('label'=>'Все показатели склада','url'=>array('storage/allIn')),
                            array('label'=>'Все показатели отделов','url'=>array('depStorage/allIn')),
                            array('label'=>'Наценки', 'url'=>array('dishes/checkMargin')),
														array('label'=>'Расходы денег', 'url'=>array('report/infoReport')),

                        ),
                    )); ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-archive fa-fw"></i> Движения  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user" id="yw1">
                        <li><?=CHtml::link('<i class="fa fa-plus-square"></i> Заявка на приход',array('faktura/request'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-plus-square"></i> Приход по заявке',array('faktura/setRequest'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-plus-square"></i> Приход продуктов на склад',array('realize/create'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-arrows-h"></i> Перемещение продуктов со склада',array('depRealize/create'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-arrows-h"></i> Перемещение продуктов между отделами',array('depRealize/move'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-arrows-h"></i> Перемещение полуфабрикатов между отделами',array('inexpense/move'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-plus-square"></i> Приход заготовок на точку',array('inexpense/create'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-plus-square"></i> Списание продуктов и загатовок',array('off/create'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-minus-square"></i> Внутренний расход',array('expense/kindCreate'));?></li>
												<li><?=CHtml::link('<i class="fa fa-minus-square"></i> Обмен продуктов',array('exchange/create'));?></li>
                        <li><?=CHtml::link('<i class="fa fa-minus-square"></i> Возврат в склад',array('depRealize/backStorage'));?></li>

                    </ul>
                </li>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-archive fa-fw"></i> Справочники  <i class="fa fa-caret-down"></i>
                    </a>
                    <?php $this->widget('zii.widgets.CMenu',array(
                        'encodeLabel' => false,
                        'htmlOptions'=>array(
                            'class'=>'dropdown-menu dropdown-user'
                        ),
                        'items'=>array(
                            array('label'=>'Продукты', 'url'=>array('/products/admin')),
                            //array('label'=>'Группа продукты', 'url'=>array('/groupProd/index')),
                            array('label'=>'Блюда', 'url'=>array('/dishes/admin')),
                            array('label'=>'Полуфабрикаты', 'url'=>array('/halfstaff/admin')),
                            array('label'=>'Отделы кухни','url'=>array('/department/admin')),
                            array('label'=>'Точки','url'=>array('/orderPoint/admin')),
                            array('label'=>'Ед.Измерения', 'url'=>array('/measurement/admin')),
                            array('label'=>'Тип блюд', 'url'=>array('/dishtype/admin')),
                            array('label'=>'Меню', 'url'=>array('/menu/index')),
                            array('label'=>'Контрагент','url'=>array('/contractor/admin')),
                            array('label'=>'Сотрудники', 'url'=>array('/employee/admin')),
                            array('label'=>'Поставщики', 'url'=>array('/provider/admin')),

                        ),
                    )); ?>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-cogs"></i> Настройка <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user" id="yw1">
                        <li><?=Chtml::link('<i class="fa fa-file"></i> Остатки на конец дня',array('settings/setBalance'))?></li>
                        <li><?=Chtml::link('<i class="fa fa-file"></i> Изменять остатки',array('settings/changeBalance'))?></li>
                        <li><?=Chtml::link('<i class="fa fa-file"></i> Выручка и Приход',array('settings/setInfo'))?></li>
                        <li><?=CHtml::link('<i class="fa fa-refresh"></i> Обновить структуру остатков',array('settings/refresh'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-refresh"></i> Удалить дубликаты остатков',array('settings/refresh'));?></a></li>
                        <li><?=CHtml::link('<i class="fa fa-refresh"></i> Обновить вуручку',array('settings/mbalancerefresh'));?></a></li>
	                    <li><?=CHtml::link('<i class="fa fa-cog"></i> Процент на заказы',array('settings/percent'))?></li>
                        <li><?=CHtml::link('<i class="fa fa-cog"></i> Цены на продукты',array('settings/prodPrice'))?></li>
                        <li><?=CHtml::link('<i class="fa fa-file"></i> Архивировать базу',array('settings/dumbDb'))?></li>
                        <li><?=Chtml::link('<i class="fa fa-file"></i> Получить список',array('settings/exportList'))?></li>
                        <li><?=Chtml::link('<i class="fa fa-file"></i> Список калькуляций',array('settings/calculate'))?></li>
                        <li><?=Chtml::link('<i class="fa fa-file"></i> Использование продукта',array('settings/prodRelation'))?></li>
                    </ul>
                </li>
                <?}?>
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
            				array('label'=>'<i class="fa fa-sign-out fa-fw"></i>Войти', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
            				array('label'=>'<i class="fa fa-sign-out fa-fw"></i>Выйти ('.Yii::app()->user->name.')','url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            			),
            		)); ?>
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

                <!-- /.sidebar-collapse -->
            </div>
        </nav>
        <div id="page-wrapper">

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
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/metisMenu.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/raphael-min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sb-admin-2.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/highchart/highcharts.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/highchart/exporting.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/help.js"></script>
<script>
    $(function(){
            $('td').click(function(){
                console.log($(this))
            });
        $(document).ajaxStart(function(){
            preloadShow();
        }).ajaxStop(function(){
            preloadHide();
        })
    });

</script>

</body>
</html>
