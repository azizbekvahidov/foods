<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang dir="ltr">
<head>

    
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
    <link rel="icon" 
      type="image/x-icon" 
      href="/images/favicon.ico" />
    
<link rel="stylesheet" href="/css/bootstrap.min.css"/>
<link rel="stylesheet" href="/css/custom.css"/>
    <link rel="stylesheet" href="/css/customstyle.css"/>
	<link rel="stylesheet" href="/css/style.css"/>
    <!--<link rel="stylesheet" href="/css/sidemenu.css"/>
	<link rel="stylesheet" href="/css/metisMenu.min.css"/>-->
    <link rel="stylesheet" href="/css/font-awesome.min.css"/>
    
    
    
	<script src="/js/jquery.min.js"></script>
    <script src="/js/holder.js"></script>
    <script src="/js/bootstrap.min.js"></script>
     <script src="/js/script.js"></script>
	<script src="/js/metisMenu.min.js"></script>
    <script type='text/javascript' src="assets/js/css3-mediaqueries.js"></script>  
	
    
 
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script type='text/javascript' src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <script type='text/javascript' src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
<![endif]-->
    <?php Yii::app()->clientScript->registerPackage('client'); ?>
    <?php //Yii::app()->getClientScript()->registerCoreScript('client'); ?>
    <?php Yii::app()->getClientScript()->registerCoreScript('form'); ?>
    <?php Yii::app()->getClientScript()->registerCoreScript('simple_gal'); ?>
    <script src="/js/client.js"></script>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    
</head>
<body>
    

    <script type="text/javascript">
    
    $(function () {
          
        
        var popovers = [];
        var currentPopover;
  $('[data-toggle="popover"]').mouseenter(function(){
        currentPopover = this;
        var txt = $(this).text();
        var templ = '<div class="popover" role="tooltip">'+
                            '<div class="arrow"></div>'+
                            '<h3 class="popover-title"></h3>'+
                            '<div class="popover-content"></div>'+
                            '</div>';;
        var cont='';                    
        switch(txt)
        {
            case 'Недвижимость':
                cont= $('#cat2').html();
                   
            break;
            case 'Работа':
                cont= $('#cat1').html();   
            break;
        }
        
        $(this).popover({
            template:templ,
            content: function(){
                popovers.push(currentPopover);
                return cont;
            },
            html:true,
            trigger:'click',
            //delay: { "show": 0, "hide": 10000 },
        });
        
       
    
  });
  $('body').on('click', function (e) {
                $.each(popovers, function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                        //$(this).popover('destroy');
                    }
                });
            });
 
  $('.elmodal').popover({
        
        content: $('.announce').html(),
        
        html:true,
        trigger:'hover',
    });
  
		
	$('[href="#cat"]').click(function(){
		id = $(this).attr('data-id');
		txt= $(this).text();
		$('#category_btn').text(txt);
		$('#category_btn').attr('data-id', id);
	});	
  
  
})
    
    </script>
    <nav class="navbar navbar-default" role="navigation">
    <div class="container">
      <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
          <a class="navbar-brand" href="#" style="">
            <img alt="Brand" src="/images/logo2.png" style="height:50px;margin: -10px 0">
          </a>
          
        </div>
        <div id="navbarCollapse" class="navbar-collapse collapse">
            <ul class="nav navbar-nav" >
                <li>
                    <a href="#">Контакты</a>
                </li>
                <li>
                    <a href="#">Реклама</a>
                </li>
                
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><button href="#" class="btn btn-success" style="width: 100px; height: 30px;padding-top: 5px; margin-top: 10px;">Войти</button></li>
                
            </ul>
            
            <form role="search" class="navbar-form navbar-right navbar-input-group">
                <div class="form-group">
                    
                        <input type="text" placeholder="Что ищем?" class="form-control" />
                        
                    
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp;</button>
            </form>
            
        </div>
        
      </div>
      </div>
    </nav>
        
   

    <div id="content" class="container">
        <div class="row">
        
            <div class="col-md-2 col-sm-3 leftside">
            
                <ul id="leftnav" class="nav nav-stacked">
                    
                    <li><a href="#" data-toggle="popover" title="Недвижимость" data-content="">Недвижимость</a></li>
                    <li><a href="#" data-toggle="popover" title="Работа" data-content="">Работа</a></li>
                    <li><a href="#">Услуги</a></li>
                    <li><a href="#">Авто</a></li>
                    <li><a href="#">Для дома</a></li>
                    <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg">еще</a></li>
                    
                </ul>
                
                <div class="news">
                    <h4>Новости сайта</h4>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing 
                    </p>
                </div>
            </div>
            <!--Modal for categories-->
			<!-- Large modal -->
				

				<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
				  <div class="modal-dialog modal-lg">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Все категории</h4>
					  </div>
					  <div class="modal-body">
						<div class="row">
                            <div class="col-sm-3">
								<ul class="modalcat"><h5><strong>НЕДВИЖИМОСТЬ</strong></h5>
									<li><a href="#cat" data-id="53" data-dismiss="modal">Квартиры</a></li> 
									<li><a href="#cat" data-id="53" data-dismiss="modal">Аренда</a></li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">Комнаты</a> </li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">Дома</a></li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">дачи</a></li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">коттеджи</a> </li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">Земельные участки</a> </li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">Гаражи и машиноместа </a></li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">Коммерческая недвижимость</a> </li>
									<li><a href="#cat" data-id="53" data-dismiss="modal">Недвижимость за рубежом</a></li>
                                </ul> 
								
								<ul class="modalcat"><h5><strong>УСЛУГИ</strong></h5>
								<li>IT, интернет, телеком</li> 
								<li>Деловые услуги </li>
								<li>Красота, здоровье</li>
								<li>Мастер на час</li>
								<li>Оборудование, производство </li>
								<li>Обучение, курсы </li>
								<li>Праздники, мероприятия</li>
								<li>Реклама, полиграфия</li>
								<li>Ремонт и обслуживание техники</li>
								<li>Ремонт, строительство</li>
								<li>Сад, благоустройство </li>
								<li>Транспорт, перевозки </li>
								<li>Уборка</li>
								<li>Фото- и видеосъёмка </li>
								<li>Другое </li>

								</ul>

                            
                            
                            </div>
                            <div class="col-sm-3">
								<ul class="modalcat"><h5><strong>УСЛУГИ</strong></h5>
                                    <li>IT, интернет, телеком</li>
                                    <li>Деловые услуги </li>
                                    <li>Красота, здоровье</li>
                                    <li>Мастер на час</li>
                                    <li>Оборудование, производство </li>
                                    <li>Обучение, курсы </li>
                                    <li>Праздники, мероприятия</li>
                                    <li>Реклама, полиграфия</li>
                                    <li>Ремонт и обслуживание техники</li>
                                    <li>Ремонт, строительство</li>
                                    <li>Сад, благоустройство </li>
                                    <li>Транспорт, перевозки </li>
                                    <li>Уборка</li>
                                    <li>Фото- и видеосъёмка </li>
                                    <li>Другое </li>

                                </ul>
                            </div>
                            <div class="col-sm-3">
								<ul class="modalcat"><h5><strong>РАБОТА</strong></h5>
                                <li>IT, интернет, телеком </li>
                                <li>Автомобильный бизнес</li>
                                <li>Административная работа </li>
                                <li>Банки, инвестиции</li>
                                <li>Бухгалтерия, финансы </li>
                                <li>Высший менеджмент </li>
                                <li>Госслужба, НКО </li>
                                <li>ЖКХ, эксплуатация </li>
                                <li>Искусство, развлечения </li>
                                <li>Консультирование</li>
                                <li>Маркетинг, реклама, PR </li>
                                <li>Медицина, фармацевтика </li>
                                <li>Образование, наука </li>
                                <li>Охрана, безопасность </li>
                                <li>Продажи </li>
                                <li>Производство, сырьё, с/х </li>
                                <li>Страхование </li>
                                <li>Строительство </li>
                                <li>Транспорт, логистика </li>
                                <li>Туризм, рестораны</li>
                                <li>Управление персоналом </li>
                                <li>Фитнес, салоны красоты </li>
                                <li>Юриспруденция </li>
                                <li>Без опыта, студенты</li>
                                </ul>
                            </div>
                            


                            <div class="col-sm-3">
								<ul class="modalcat"><h5><strong>УСЛУГИ</strong></h5>
                                    <li>IT, интернет, телеком</li>
                                    <li>Деловые услуги </li>
                                    <li>Красота, здоровье</li>
                                    <li>Мастер на час</li>
                                    <li>Оборудование, производство </li>
                                    <li>Обучение, курсы </li>
                                    <li>Праздники, мероприятия</li>
                                    <li>Реклама, полиграфия</li>
                                    <li>Ремонт и обслуживание техники</li>
                                    <li>Ремонт, строительство</li>
                                    <li>Сад, благоустройство </li>
                                    <li>Транспорт, перевозки </li>
                                    <li>Уборка</li>
                                    <li>Фото- и видеосъёмка </li>
                                    <li>Другое </li>

                                </ul>
                            </div>
                        </div>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
						
					  </div>
					</div>
				  </div>
				</div>
			<!--END modal for categories-->
            <!-- Popovers for leftside menu BEGIN-->
            <div style="display: none;">
            <div id="cat1">
                
                <div class="col-sm-12" > 
                <ul style="list-style: none;">
                    <li><a href="#">IT, интернет, телеком <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Автомобильный бизнес<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Административная работа <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Банки, инвестиции <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Бухгалтерия, финансы<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Высший менеджмент<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Госслужба, НКО <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">ЖКХ, эксплуатация <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Искусство, развлечения <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Консультированиеv<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Маркетинг, реклама, PR<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Медицина, фармацевтика <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Образование, наука <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Охрана, безопасность<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Продажи</a></li>
                    <li><a href="#">Производство, сырьё, с/х <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Страхование</a></li>
                    <li><a href="#">Строительство <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Транспорт, логистика<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Туризм, рестораны <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Управление персоналом<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Фитнес, салоны красоты<span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Юриспруденция <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Без опыта, студенты<span class="badge pull-right">12</span></a></li>
                    
                    </ul>
                    <a href="#" class="btn btn-xs btn-success">Добавить</a>
                </div>
                
            </div>
            <div id="cat2">
                <div class="col-sm-8"> 
                    <ul style="list-style: none;margin-left:5px;">
                    <li><a href="#">Дома <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Квартиры <span class="badge pull-right">12</span></a></li>
                    <li><a href="#">Земельные участки <span class="badge pull-right">14</span></li>
                    <li><a href="#">Дачи <span class="badge pull-right">2</span></li>
                    <li><a href="#">Ком. недвижимость <span class="badge pull-right">2</span></li>
                    </ul>
                    <a href="http://advert.uz" class="btn btn-xs btn-success">Добавить</a>
                </div>
                <div class="col-sm-4" > 
                <ul style="list-style: none;">
                    <li>Продам</li>
                    <li>Куплю</li>
                    <li>Сдам</li>
                    <li>Поменяю</li>
                    
                    </ul>
                </div>
                
            </div>
            </div>
            <!-- Popovers for leftside menu end-->
            
            <!-- Popovers Form for view BEGIN-->
            
            <div class="announce" style="display: none;">
                
                                            
Navbaxor tuman, Yangiyo'l, Sho'rtepa massividagi 2014 yil qurib bitgazilgan hovli (kottedj) sotiladi. <br/><span class="fa fa-user"></span> Tel:+998944810085 
                    
                
            </div>
            
            <!--Popovers Form for view END-->
            
            <div class="col-md-10 col-sm-9">
                <div class="row">
                    <div class="col-sm-9 col-md-9">
                        
                        <div class="row" >
                            <div class="col-sm-12" id="reklama" style="background-color: #66625b;height:100px; margin: 10px;color:#dad9d9; text-align: center;">
                                <h2>Реклама</h2>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <h4>Добавление объявления </h4>
                            </div>
                            <div class="col-sm-6">
                                
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-8 col-md-9" >
                                   
                                       <form class="form form-stacked">
                                       <div class="form-group">
                                        
                                        <a class="btn btn-success form-control" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="" id="category_btn">Выбрать категорию</a>
                                       </div>
                                       <div class="form-group">
                                        <label class="form-label">Имя</label>
                                        <input  class="form-control" type="text"/>
                                       </div>
                                       <div class="form-group">
                                        <label class="form-label">Имя</label>
                                        <input  class="form-control" type="text"/>
                                       </div>
                                       <div class="form-group">
                                        <label class="form-label">Имя</label>
                                        <input  class="form-control" type="text"/>
                                       </div>
                                       </form> 
                                    
                                </div>
                                 <div class="col-sm-4 col-md-3" id="special">

                                        <div class="media mediabody">
                                                <div class="media-body">
                                                    <a href="#">Lorem Ipsum is simply dummy text of the printing ...</a>
                                                   
                                                </div>
                                        </div>
                                        <div class="media mediabody">
                                                <div class="media-body">
                                                    <a href="#">Lorem Ipsum is simply dummy text of the printing ...</a>
                                                   
                                                </div>
                                        </div>
                                        <div class="media mediabody">
                                                <div class="media-body">
                                                    <a href="#">Lorem Ipsum is simply dummy text of the printing ...</a>
                                                   
                                                </div>
                                        </div>
                                        <div class="media mediabody">
                                                <div class="media-body">
                                                    <a href="#">Lorem Ipsum is simply dummy text of the printing ...</a>
                                                   
                                                </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                    
                    <div class="col-sm-3 col-md-3" style="text-align: right;">
                         <iframe src="http://cbu.uz/ru/section/iframe/USD/RUB/EUR/ffffff/2c80c6" width=200 height=110 scrolling=no frameborder=0></iframe>
                        <div id="ok_group_widget"></div>
                        <script type="text/javascript">
                        !function (d, id, did, st) {
                          var js = d.createElement("script");
                          js.src = "http://connect.ok.ru/connect.js";
                          js.onload = js.onreadystatechange = function () {
                          if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                            if (!this.executed) {
                              this.executed = true;
                              setTimeout(function () {
                                OK.CONNECT.insertGroupWidget(id,did,st);
                              }, 0);
                            }
                          }}
                          d.documentElement.appendChild(js);
                        }(document,"ok_group_widget","52718296236102","{width:200,height:300}");
                        </script>
                    </div>                    
                </div>
                
                
                
                
            </div>
            
        </div>
    
        <div class="row" id="footer">
            <div class="col-md-1"></div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        
                        <ul><h5>Помощь</h5>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Правила размещения объявлений</a></li>
                            
                        </ul>
                    </div>
                    <div class="col-md-4">
                        
                        <ul><h5>Юредическим лицам</h5>
                            <li><a href="#">Реклама</a></li>
                            
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul><h5>О нас</h5>
                        <li><a href="#">Вакансии</a></li>
                        </ul>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    
    

</body>
</html>
