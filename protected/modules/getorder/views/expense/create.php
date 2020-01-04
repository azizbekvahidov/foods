<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'expense-form',
    'type'=>'inline',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); $menu = new Menu(); ?>

	<?php echo $form->errorSummary($model); ?>
<style>
    .modal {
    }
    @media (max-width: 768px) {
        .thumbnail {
            font-size: 11px;
        }
    }
    .plus {
        cursor: pointer;
    }

    .thumbnail {
        font-size: 11px;
    }

    .sidebar {
        width: 16%;
        position: fixed;
    }

    .right-sidebar {
        z-index: 1;
        position: absolute;
        width: 25%;
        margin-top: 51px;
    }

    #page-wrapper {
        margin: 0 25% 0 16%;
    }

    .thumbnail {
        position: relative;
    }

    .texts {
        position: absolute;
        top: 3px;

    }

    #expense-form {
        margin-top: 0px;
    }

    .cnt {
        cursor: pointer;
    }

    .liStyle {
        list-style: none;
        border: none !important;
    }

    #dataTable td, th {
        padding: 4px !important;
    }

    #dataTable .btn {
        padding: 0;
    }

    #menuList a {
        padding: 4px 4px;
    }

    .topHead {
        z-index: 1000;
        margin-top: -45px;
    }

    #summ {
        color: red;
        font-weight: bold;
    }

</style>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <!-- /.navbar-header -->
    <div class="navbar-header">
        <?= CHtml::link($user['name'],array('site/index'),array('icon'=>'fa fa-sign-out fa-fw', 'class'=>'navbar-brand'));?>
    </div>

</nav>
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
        <div class="col-xs-12 topHead">
            <div class="col-xs-3">

                <?php echo Chtml::button('Мои заказы',array('type'=>'button','class'=>'btn btn-info pull-right','id'=>'orders'))?>
            </div>
            <div class="col-xs-2">
                <?php echo $form->dropDownList($model,'table',$table,array('class'=>'form-control'))?> &nbsp; &nbsp;

            </div>
            <!--<div class="col-xs-3">
                <?php echo $form->dropDownList($model,'employee_id',CHtml::listData(Employee::model()->findAll(),'employee_id','name'),array('class'=>'form-control'))?>
            </div>-->
            <div class="col-xs-3">
                <?=CHtml::dropDownList('menulist','',$menu->getMenuList())?>
            </div>
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
                        <th>Цена</th>
                        <th>кол.</th>
                    </tr>
                </thead>
                <tbody id="order">

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Итого</td>
                        <td colspan="2" id="summ">0</td>
                    </tr>
                </tfoot>

            </table>
        </div>
        <div class="text-center col-xs-12">
            <label class="checkbox inline"><input type="checkbox" name="Expense[debt]" value="1" id="Expense_debt"  />Долг</label>
            <?php //echo $form->checkBoxRow($model,'debt',array('class'=>'form-control'))?>
        </div>
        <?echo $form->textField($model,'comment',array('style'=>'display:none'))?>
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
    }


    $(document).ready(function(){
        $("#menulist").chosen({
            no_results_text: "Ничего не найдено"
        }).change(function(){
            $("#submitBtn").removeAttr('disabled');
            var texts = $(this).find('option:selected').text();
            var thisId = $(this).val();
            var temps = str_split(texts,1);
            //count = parseInt($(this).data('click')) || 0;
            //count=count+1;
            console.log(temps);
            if($('#order tr.'+thisId).exists()){
                var types = str_split(thisId,1);
                var count = $('#order tr.'+thisId).children("td.cnt").children('input').val();
                count = parseFloat(count)+1;
                $('#order tr.'+thisId).children("td:first-child").children('input').val(types[1]);
                $('#order tr.'+thisId).children("td.cnt").children('input').val(count);
                $('#order tr.'+thisId).children("td.cnt").children('span').text(count);
                //$('#order tr.'+thisId).html("<td><button type='button' class='removed'><i class='fa fa-times'></i></button></td><td>"+identifies + "</td> <td>" + count+"</td>");
            }
            else{
                var types = str_split(thisId,1);
                    $('#order').append("<tr class="+thisId+">\
                                <td >\
                                    <a type='button' class='removed btn'>\
                                        <i class='fa fa-times'></i>\
                                    </a>\
                                    <input style='display:none' name='"+types[0]+"[id][]' value='"+types[1]+"' />\
                                </td>\
                                <td>"+temps[0]+"</td>\
                                <td>"+temps[1]+"</td>\
                                <td class='cnt'>\
                                    <input name='"+types[0]+"[count][]' style='display:none' value='1' />\
                                    <a type='button' class='pluss btn'>\
                                        <i class='fa fa-plus'></i>\
                                    </a>\
                                    <span>" +1+"</span>\
                                    <a type='button' class='minus btn'>\
                                        <i class='fa fa-minus'></i>\
                                    </a>\
                                </td>\
                            </tr>");
            }
            getSum();
        });
        ion.sound({
            sounds: [
                {name: "bell_ring"},
                {name: "door_bell"}
            ],
            path: "/src/sounds/",
            preload: true,
            volume: 1.0
        });

    });


    $('#submitBtn').attr('disabled','disabled');

    $('#orders').click(function(){
        $("#ModalsHeader").html("Текущие заказы");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('expense/todayOrder'); ?>",
            data: 'user=<??>'
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
            url: "<?php echo Yii::app()->createUrl('expense/create'); ?>",
            data: 'user=<?=$user['employee_id']?>&'+data
        });
        $('#order').children('tr').remove();
        $('#Expense_debt').removeAttr('checked');
        $("#Expense_comment").val('');
            $(this).attr('disabled','disabled');
        getSum();
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
            $(this).attr('checked','checked');
            return true;
        }
    });

    $(document).on("click", '.types' ,function(){
        var thisId = $(this).parent().attr('id');
        $.ajax({
           type: "POST",
           url: "<?php echo Yii::app()->createUrl('expense/lists'); ?>",
           data: "id="+thisId,
           success: function(data){
             $('#data').html(data);
           }
        });
    });

    jQuery.fn.exists = function() {
       return $(this).length;
    };

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
        count = parseFloat(count)-1;
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
        getSum();
    });

    $(document).on("click", ".pluss", function() {
        var count = $(this).parent().parent().children("td.cnt").children('input').val();
        count = parseFloat(count)+1;
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
        getSum();
    });

    $(document).on("click", ".removed", function() {
        $(this).parent().parent().remove();
        if($("#order tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
        getSum();
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
        getSum();
    });

    document.onkeyup = function (e) {
    	e = e || window.event;
    	if (e.keyCode === 13) {
    		$("#ok").click();
            $("#comment").click();
    	}
    	// Отменяем действие браузера
        getSum();
    	return false;
    };

    $('.all').click(function(){
        $("#order").empty();
        if($("#order tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
        getSum();
    });

    function getSum(){
        var summ = 0;
        $('#dataTable tbody tr').each(function(indx){
            summ += parseFloat($(this).children('td:nth-child(4)').text())*parseInt($(this).children('td:nth-child(3)').text());
            //sum += $(this).children('td:nth-child(3)').text();
        });
        $('#summ').text(summ);
    }

    function getPrice(id,mType,type,orderDate){
        var res;
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('expense/lists'); ?>",
            data: "id="+id+"&mType="+mType+"&type="+type+"&orderDate="+orderDate,
            success: function(data){
                return data;
            }
        });
    }



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


