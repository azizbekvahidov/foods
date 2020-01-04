<script src="/js/jquery.printPage.js"></script>
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
    @media (max-width: 768px)
        .thumbnail {
            font-size: 13px;
        }
        .plus{
            cursor: pointer;
        }.thumbnail {
             font-size: 13px;
         }
        .sidebar{
            width: 20%;
            position: fixed;
        }
        .right-sidebar{
            z-index: 1;
            position: absolute;
            width: 25%;
            margin-top: 51px;
        }
        #page-wrapper{
            margin: 0 25% 0 20%;
        }
        .thumbnail{
            position: relative;
        }
        .texts{
            position: absolute;
            top: 3px;

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
        #dataTable td,th,a{
            padding: 4px!important  ;
            font-size: 13px!important;
        }
        #dataTable .btn{
            padding: 0;
        }
        #menuList a{
            padding: 4px 4px;
        }
        .topHead{
            z-index: 1000;
            margin-top: -45px;
        }
        #summ{
            color: red;
            font-weight: bold;
        }
        #Psumm{
            color: blue;
            font-weight: bold;
        }
        .closeBtn{
            right: -245px;
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
    <div class="col-xs-12 topHead">
        <div class="col-xs-3">

            <?php echo Chtml::button('Мои заказы',array('type'=>'button','class'=>'btn btn-info pull-right','id'=>'orders'))?>
        </div><!--
            <div class="col-xs-2">
                <?php /*echo $form->dropDownList($model,'table',array(1,2,3,4,5,6,7,8,9),array('class'=>'form-control'))*/?> &nbsp; &nbsp;

            </div>-->
        <div class="col-xs-3 hide">
            <?php echo $form->dropDownList($model,'employee_id',CHtml::listData(Employee::model()->findAll('status = 0 and role = 2'),'employee_id','name'),array('class'=>'form-control'))?>
        </div>
        <div class="col-xs-4 hide">
            <?=CHtml::dropDownList('menulist','',$menu->getMenuList())?>
        </div>
        <div class="closeBtn col-xs-2 hide">
            <a href="/settings/countDay" class="btn btn-danger ">Закрыть кассу</a>
        </div>
        <div class=" col-xs-2">
            <button class="btn btn-danger" id="refuseBtn" type="button">Отказ счета</button>
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
                <th><a href="#" class=" all">Все</a></th>
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
            <tr class="hide">
                <td colspan="2"></td>
                <td colspan="2" id="Psumm">0</td>
            </tr>
            </tfoot>

        </table>
    </div>
    <div class="text-center col-xs-12">
        <!--<label class="checkbox inline"><input type="checkbox" name="Expense[debt]" value="1" id="Expense_debt"  />Боходир ака</label>-->
        <?php echo $form->checkBoxRow($model,'debt',array('class'=>'form-control'))?>
    </div>
    <?echo $form->textField($model,'comment',array('style'=>'display:none'))?>
    <input type="text" class='hide' name='Expense[empId]' value="0" id='Expense_empId'>
    <input type="text" class='hide' name='Expense[contr]' value="0" id='Expense_contr'>
    <div class="form-actions text-center col-xs-5 ">
        <button class="btn btn-success" id="submitBtn" type="button"><?=$model->isNewRecord ? 'Наличка' : 'Сохранить'?></button>

    </div>
    <div class="form-actions text-center col-xs-4 hide">
        <button class="btn btn-info" id="closeBtn" type="button"><?=$model->isNewRecord ? 'Закрыть' : 'Сохранить'?></button>

    </div>
    <div class="form-actions text-center col-xs-5 ">
        <button class="btn btn-info" id="closeTerm" type="button" data-toggle="modal" data-target="#modal-sm">Терминал</button>
    </div>
    <div class=" text-center col-xs-4 hide">
        <button class="btn btn-info" id="Expense_prepaid" type="button" data-toggle="modal" data-target="#ModalPrepaid">Аванс</button>
    </div>
    <div class="text-center col-xs-4 hide">
        <button class="btn btn-info" id="Expense_prepaid_term" type="button" data-toggle="modal" data-target="#ModalPrepaid">Аванс терминалом</button>
    </div>
</div>
<a class="hide" id="btnPrint"></a>
<script>
var counts = [],
    temps,
    count,
    expId = 0;
var prepaidSum = 0;
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
$(document).ready(function(){
    $("#btnPrint").printPage();
    $("#menulist").chosen({
        no_results_text: "Ничего не найдено"
    }).change(function(){
        $("#submitBtn").removeAttr('disabled');
        var texts = $(this).find('option:selected').text();
        var thisId = $(this).val();
        var temps = str_split(texts,1);
        //count = parseInt($(this).data('click')) || 0;
        //count=count+1;
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
            var types = str_split(thisId,1);-
                $('#order').append("<tr class="+thisId+">\
                                <td >\
                                    <a type='button' class='removed' href='#'>\
                                        <i class='fa fa-times'></i>\
                                    </a>\
                                    <input style='display:none' name='id[]' value='"+thisId+"' />\
                                </td>\
                                <td>"+temps[0]+"</td>\
                                <td>"+temps[1]+"</td>\
                                <td class='cnt'>\
                                    <input name='count[]' style='display:none' value='1' />\
                                    <a type='button' class='pluss' href='#'>\
                                        <i class='fa fa-plus'></i>\
                                    </a>\
                                    <span>" +1+"</span>\
                                    <a type='button' class='minus' href='#'>\
                                        <i class='fa fa-minus'></i>\
                                    </a>\
                                </td>\
                            </tr>");
        }
        getSum();
    });
});
$(document).on('click','.brnPrint',function(){

});
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
var prepaid = false;

$(document).on('click','#submitBtn',function(){
    if (confirm('Оплатить счет?')) {
        var expSum = $("#summ").text();
        var datas = $("#expense-form").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('order/expense/addExp'); ?>",
            data: datas + "&expSum=" + expSum + "&prepaid=" + prepaid + "&prepaidSum=" + prepaidSum + "&prepStatus=" + prepaidStatus,
            success: function (expenseId) {
                expId = expenseId;
                console.log(expId);
                if(closeTerminal == false) {
                    $("#btnPrint").attr("href", "/monitoring/printCheck?exp=" + expId);
                    $("#closeBtn").click();
                    $("#btnPrint").click();
                    printCheck(datas,expId,expSum);
                }
                if(closeTerminal == true){
                    var term = $("#termSum").val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('monitoring/closeTerm'); ?>",
                        data: 'id=' + expId + '&term=' + term,
                        success: function (data) {
                            $("#termSum").val('');
                            $("#expIdFSum").val('');
                            $('#modal-sm').modal('hide');
                            $("#btnPrint").attr("href", "/monitoring/printCheck?exp=" + expId);
                            $("#closeBtn").click();
                            $("#btnPrint").click();
                            printCheck(datas,expId,expSum);
                        }
                    });
                }
            }
        });
    }
});
$('#closeBtn').click(function(){
    $('#order').children('tr').remove();
    $('#Expense_debt').removeAttr('checked');
    $("#Expense_comment").val('');
    $("#Expense_empId").val('');
    $("#Expense_contr").val('');
    prepaid = false;
    prepaidStatus = 0;
    prepaidSum = 0;
    $("#prepaidModalBody textarea").val("");
    $("#prepaidModalBody input").val("");
    getSum();
});
var closeTerminal = false;
$(document).on('click','#saveTerm',function(){
    closeTerminal = true;
    $("#submitBtn").click();

});

function printCheck(data,expId,expSum){
    $.ajax({
        type: "POST",
        url: "<?php echo Yii::app()->createUrl('order/expense/printCheck'); ?>",
        data: data + "&expSum=" + expSum + "&prepaid=" + prepaid + "&prepaidSum=" + prepaidSum + "&prepStatus=" + prepaidStatus+"&expId="+expId,
        success: function (data) {
                $('#order').children('tr').remove();
                $('#Expense_debt').removeAttr('checked');
                $("#Expense_comment").val('');
                $("#Expense_empId").val('');
                $("#Expense_contr").val('');
                closeTerminal = false;
                getSum();
            }
    });
}

$(document).on("click", "#comment", function() {
    var thisValue = $("#ModalBody").children('input').val();
    $("#Expense_comment").val(thisValue);
    $("#Expense_empId").val($("#employee").val());
    $("#Expense_contr").val($("#contractor").val());
});
$(document).on('click','#Expense_debt',function(){

    if($('#Expense_debt').attr('checked') == 'checked') {
        //$("#Expense_comment").val("Боходир ака");
        $("#ModalHeader").html("Комментприй для долга");
        $("#Modal").modal();
        $(this).attr('checked','checked');
        return true;
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

$(document).on("click","#prepaidComment", function(){
    var thisValue = $("#prepaidModalBody").children('textarea').val();
    var sum = $("#prepaidModalBody").children('input').val();
    prepaidSum = sum;
    prepaid = true;
    $("#Expense_comment").val(thisValue);
    $("#submitBtn").click();
    $("#closeBtn").click();


})

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
var prepaidStatus = 0;
$(document).on("click","#Expense_prepaid", function(){
    $("#ModalHeader").html("Комментприй для предоплаты");
    $("#ModalPrepaid").modal();
    return true;
});
$(document).on("click","#Expense_prepaid_term", function(){
    prepaidStatus = 1;
    $("#ModalHeader").html("Комментприй для предоплаты");
    $("#ModalPrepaid").modal();
    return true;
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
    getSum();
});
$(document).on("click","#refuseBtn", function(){
    $("#modalRefuse").modal("show");
});
$(document).on("click","#saveRefuse", function () {
    var refuse = $("#refuse").val();
    $.ajax({
        type: "POST",
        url: "<?php echo Yii::app()->createUrl('order/expense/refuse'); ?>",
        data: 'refuse=' + refuse,
        success: function () {
            $("#refuse").val("");
            $("#modalRefuse").modal("hide");
        }
    });
});

function getSum(){
    var summ = 0;
    $('#dataTable tbody tr').each(function(indx){
        summ += parseFloat($(this).children('td:nth-child(4)').text())*parseInt($(this).children('td:nth-child(3)').text());
        //sum += $(this).children('td:nth-child(3)').text();
    });
    $('#summ').text(Math.round(summ / 100) * 100);
    $('#Psumm').text(Math.round((summ+(summ/10)) / 100) * 100);
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

    <input class='span2 form-control' type='text' name='' value='' />
    <?=CHtml::dropDownList('employee','',CHtml::listData(Employee::model()->findAll('status != 1'),'employee_id','name'),array('empty'=>'выберите сотрудника'))?>
    <?//=CHtml::dropDownList('contractor','',CHtml::listData(Contractor::model()->findAll('status != 1'),'contractor_id','name'),array('empty'=>'выберите контрагента'))?>
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
    array('id' => 'ModalPrepaid')
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="ModalHeader">Аванс</h4>
</div>

<div class="modal-body" id="prepaidModalBody">

    <textarea placeholder="комментарий" class='span3 form-control' type='text' name='' value='' ></textarea>
    <br/>
    <input class='span3 form-control' placeholder="сумма аванса" type='text' name='' value=''/>
</div>

<div class="modal-footer">
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'Ok',
            'url' => '#',
            'htmlOptions' => array('id'=>'prepaidComment','data-dismiss' => 'modal','class'=>'btn btn-success'),
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

<div class="modal fade bs-example-modal-sm" id="modalRefuse" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Отказ счета</h4>
        </div>
        <div class="modal-content">
                <input type="text" class="form-control" id="refuse" placeholder="счет отказа">
        </div>
        <div class="modal-footer">
            <button type="button" id="saveRefuse" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-sm" id="modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">сумма терминал</h4>
        </div>
        <div class="modal-content">
            <input type="text" value="" id="expIdFSum" style="display: none">
            <input type="number" id="termSum" class="form-control"/>
        </div>
        <div class="modal-footer">
            <button type="button" id="saveTerm" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        </div>
    </div>
</div>
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


