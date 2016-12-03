<?php $this->beginContent('//layouts/main'); ?>
<?php $this->breadcrumbs = array();?>
    <div class="row">

    <div class="col-md-2 col-sm-3 leftside">

        <ul id="leftnav" class="nav nav-stacked">

            <li> <a href="#" data-toggle="popover" title="Недвижимость" data-content=""><i class="flaticon-school50"></i>&nbsp;&nbsp;Недвижимость</a></li>
            <li><a href="#" data-toggle="popover" title="Работа" data-content=""><i class="flaticon-business133"></i>&nbsp;&nbsp;Работа</a></li>
            <li><a href="#"  data-toggle="popover" title="Авто" data-content=""><span class="flaticon-car80"></span>&nbsp;&nbsp;Авто</a></li>
            <li><a href="#" data-toggle="popover" title="Услуги" data-content=""><span class="flaticon-tool36"></span>&nbsp;&nbsp;Услуги</a></li>

            <li><a href="#" data-toggle="popover" title="Бытовая электроника" data-content=""><span class="flaticon-pc6"></span>&nbsp;&nbsp;Компьютеры</a></li>
            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="" id="category_btn">еще...</a></li>

        </ul>

        <div class="news">
            <h4>Новости сайта</h4>
            
            <p>
                Вы можете размещать ваши объявления без регистрации
            </p>
        </div>
    </div>

    <!-- Popovers for leftside menu BEGIN-->
    <div style="display: none;">
       
        <?php $this->widget('ext.categories.categories', array('type'=>'onecat', 'catName'=>'Недвижимость'));?>
        <?php $this->widget('ext.categories.categories', array('type'=>'onecat', 'catName'=>'Работа'));?>
        <?php $this->widget('ext.categories.categories', array('type'=>'onecat', 'catName'=>'Услуги'));?>
        <?php $this->widget('ext.categories.categories', array('type'=>'onecat', 'catName'=>'Авто'));?>
        <?php $this->widget('ext.categories.categories', array('type'=>'onecat', 'catName'=>'Бытовая электроника'));?>
    </div>
    <!-- Popovers for leftside menu end-->

    <!-- Popovers Form for view BEGIN-->

    <!--Modal for categories-->
    <!-- Large modal -->


    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Все категории</h4>
                </div>
                <div class="modal-body">
                    <?php $this->widget('ext.categories.categories', array('type'=>'leftmenu')); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>

                </div>
            </div>
        </div>
    </div>
    <!--END modal for categories-->

    <div class="announce" style="display: none;">



    </div>

    <!--Popovers Form for view END-->

    <div class="col-md-10 col-sm-9">
    <div class="row">
    <div class="col-sm-9 col-md-9">

    <div class="row" >
        <div class="col-sm-4">
            <a href="/index.php/announce/create" class="btn btn-success"><span class="fa fa-plus"></span> Добавить новое объявление</a>
        </div>
		<div class="col-sm-8">
	<?php 		$this->widget('zii.widgets.CBreadcrumbs', array(
				'links'=>$this->breadcrumbs,
			));?>
			
		</div>
        <div class="col-sm-12" id="reklama" style="background-color: #66625b;height:100px; margin: 10px;color:#d5d4da; text-align: center;">
            <h2>Реклама</h2>
        </div>
    </div>
		<?php echo $content; ?>
    </div>

    <div class="col-sm-3 col-md-3" style="text-align: right;margin-top:10px;">
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
   <?php $this->endContent(); ?>