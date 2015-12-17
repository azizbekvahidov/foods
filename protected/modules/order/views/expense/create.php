<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'expense-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>
    <style>
        .modal {
            position: fixed;
            top: 10% ;
            bottom: auto!important;
            left: 50% !important;
            z-index: 1050;
            width: 560px!important;
            margin-left: -280px!important;
            background-color: #ffffff!important;
            border: 1px solid #999!important;
            border: 1px solid rgba(0, 0, 0, 0.3) !important;
            -webkit-border-radius: 6px!important;
            -moz-border-radius: 6px!important;
            border-radius: 6px!important;
            -webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3)!important;
            -moz-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3)!important;
            box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3)!important;
            -webkit-background-clip: padding-box!important;
            -moz-background-clip: padding-box!important;
            background-clip: padding-box!important;
            outline: none!important;
        }
        .plus{
              cursor: pointer;
        }
        .sidebar{
            width: 200px;
        }
        .right-sidebar{
              z-index: 1;
              position: absolute;
              width: 300px;
              margin-top: 51px;
        }
        #page-wrapper{
            margin: 0 300px 0 200px;
        }
        .thumbnail{
            position: relative;
        }   
        .texts{
              position: absolute;
              top: 3px;
              left: 15px;
        }
        #expense-form{
            margin-top: 0px;
        }
        .cnt{
            cursor: pointer;
        }
        .liStyle{
            list-style: none;
            border: none!important;
        }
        #dataTable td,th{
            padding: 4px!important  ;
        }
        #dataTable .btn{
            padding: 0;
        }
        #menuList a{
            padding: 0 4px;
        }

    </style>
<link href="/css/bootstrap3.css" rel="stylesheet">
    <!-- /.navbar-top-links -->
    <div class="navbar-default sidebar" style="margin-top: 0;" role="navigation">
        <div class="sidebar-nav tab-box">
            <ul class="nav nav-pills nav-stacked tab-nav" id="menuList">
            <? foreach($menuModel as $key => $value){
                $subMenu = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>$value->type_id))
                ?>
                <li id="<?=$value->type_id?>">
                    <a href="javascript:;" class="types"><?=$value->name?><span style="float: right;" class="fa fa-angle-right"></span></a>
                    <ul><? foreach($subMenu as $val){?>
                        <li class="liStyle" id="<?=$val->type_id?>">
                            <a href="javascript:;" class="types"><?=$val->name?><span style="float: right;" class="fa fa-angle-right"></span></a>
                        </li>
                    <? }?>
                    </ul>
                </li>
            <? }?>
            
            </ul>
             
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <div id="page-wrapper">
        <div class="col-xs-12">
            <div class="col-xs-3">
                <label>Стол</label>
                <?php echo $form->dropDownList($model,'table',array(1,2,3,4,5,6,7,8,9),array('class'=>'form-control'))?> &nbsp; &nbsp;

            </div>
            <div class="col-xs-3">

                <label>официант</label>
                <?php echo $form->dropDownList($model,'employee_id',CHtml::listData(Employee::model()->findAll(),'employee_id','name'),array('class'=>'form-control'))?>
            </div>
            <div class="col-xs-3">

                <?php echo Chtml::button('Текущие заказы',array('type'=>'button','class'=>'btn btn-info pull-right','id'=>'orders'))?>
            </div>
            <div class="col-xs-3">
                <label>Долг</label>
                <?php echo $form->checkBox($model,'debt',array('class'=>'form-control'))?>
            </div>
            <?echo $form->textField($model,'comment',array('style'=>'display:none'))?>
        </div>
        <div class="tab-panels" id="data">
                    
        </div>
       
    </div>
    <div class="navbar-default right-sidebar" style="right: 0; top: 0;">
        <div>
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th><a class="btn all">Все</a></th>
                        <th>Название</th>
                        <th>кол.</th>
                    </tr>
                </thead>
                <tbody id="order">
                    
                </tbody>
                      
            </table>
        </div>
        <div class="form-actions text-center col-xs-12 ">
            <button class="btn btn-success" id="submitBtn" type="button"><?=$model->isNewRecord ? 'Добавить' : 'Сохранить'?></button>

        </div>
    </div>
    
<script>
var counts = [],
    temps;
    var count;
    function str_split ( str, len ) {
    			
    	str = str.split('_');
    	if ( !len ) { return str; }
    
    	var r = [];
    	for( var k=0;k<(str.length/len); k++ ) {
    		r[k] = '';
    	}
    	for( var k in str ) {
    		r[ Math.floor(k/len) ] += str[k];
    	}
    return r;
    };
    
    $('#submitBtn').attr('disabled','disabled');

    $('#orders').click(function(){
        $("#ModalsHeader").html("Текущие заказы");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('order/expense/todayOrder'); ?>",
            success: function(data){
                $('#ModalsBody').html(data);
            }
        });
        $("#Modals").modal();
        return false;
    });

    $('#submitBtn').click(function(){

            var data = $("#expense-form").serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('order/expense/create'); ?>",
                data: data
            });
            $('#order').children('tr').remove();
        $('#Expense_debt').removeAttr('checked');
        $("#Expense_comment").val('');
            $(this).attr('disabled','disabled');
    });

    $(document).on("click", "#comment", function() {
        var thisValue = $("#ModalBody").children('input').val();
        $("#Expense_comment").val(thisValue);
    });
    $(document).on('click','#Expense_debt',function(){

        if($('#Expense_debt').attr('checked') == 'checked') {
            $("#ModalHeader").html("Комментприй для долга");
            $('#ModalBody').html("<input class='span2 form-control' type='text' name='' value='' />");
            $("#Modal").modal();
            $($this).attr('checked','checked');
            return false;
        }
    })
$(document).on("click", '.types' ,function(){
        var thisId = $(this).parent().attr('id');
        $.ajax({
           type: "POST",
           url: "<?php echo Yii::app()->createUrl('order/expense/lists'); ?>",
           data: "id="+thisId,
           success: function(data){
             $('#data').html(data);
           }
        });
    })
    
    jQuery.fn.exists = function() {
       return $(this).length;
    }
    
    $(document).on("click", ".cnt span", function() {
        var count = $(this).children('input').val();
        var thisClass = $(this).parent().parent().attr('class');
        $("#myModalHeader").html("Укажите количество");
        $('#myModalBody').html("<input class='span2 form-control' type='text' name='' value='' />");
    	$("#myModalBody").children('input').val(count);
    	$("#myModalBody").children('input').attr('name',thisClass);
        $("#myModal").modal();
    	return false;
    });
    
    $(document).on("click", ".minus", function() {
        var count = $(this).parent().parent().children("td.cnt").children('input').val();
        count = parseInt(count)-1;
        if(count > 0){
            $(this).parent().parent().children("td.cnt").children('input').val(count);
            $(this).parent().parent().children("td.cnt").children('span').text(count);
        }
        else{
            $(this).parent().parent().remove();
        }
        if($("#order tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
    });
    
    $(document).on("click", ".removed", function() {
        $(this).parent().parent().remove();
        if($("#order tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
    });    
    
    $(document).on("click", "#ok", function() {
        var curCount = $("#myModalBody").children('input').val();
        var curClass = $("#myModalBody").children('input').attr('name');
        if(curCount != '') {
            curCount = parseFloat(curCount.replace(/,/,'.'));
        }
        else{
            curCount = 0;
        }
        var inputVal = parseFloat($("." + curClass).children("td.cnt").children('input').val());
        var spanVal = parseFloat($("." + curClass).children("td.cnt").children('span').text());
        console.log(curCount);
        $("." + curClass).children("td.cnt").children('input').attr('value',curCount+inputVal);
        $("." + curClass).children("td.cnt").children('span').text(spanVal+curCount);
        if($("#order tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
    });    
    
    document.onkeyup = function (e) {
    	e = e || window.event;
    	if (e.keyCode === 13) {
    		$("#ok").click();
            $("#comment").click();
    	}
    	// Отменяем действие браузера
    	return false;
    }
    
    $('.all').click(function(){
        $("#order").empty();
        if($("#order tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
    });


</script>

<?php $this->endWidget(); ?>

<?php $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
); ?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4 id="myModalHeader">Modal header</h4>
    </div>
 
    <div class="modal-body" id="myModalBody">

    </div>
 
    <div class="modal-footer">
        <?php  $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Ok',
                'url' => '#',
                'htmlOptions' => array('id'=>'ok','data-dismiss' => 'modal','class'=>'btn btn-success'),
            )
        ); ?>
        <?php  $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Отмена',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
 
<?php  $this->endWidget(); ?>

<?php $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'Modal')
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="ModalHeader">Modal header</h4>
</div>

<div class="modal-body" id="ModalBody">

</div>

<div class="modal-footer">
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'Ok',
            'url' => '#',
            'htmlOptions' => array('id'=>'comment','data-dismiss' => 'modal','class'=>'btn btn-success'),
        )
    ); ?>
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'Отмена',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
</div>

<?php  $this->endWidget(); ?>


<?php $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'Modals')
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="ModalsHeader">Modal header</h4>
</div>

<div class="modal-body" id="ModalsBody">

</div>

<div class="modal-footer">
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'Отмена',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
</div>

<?php  $this->endWidget(); ?>


