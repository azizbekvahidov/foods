<?php

/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<script src="/js/jquery.printPage.js"></script>

<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/bootstrap3.css" rel="stylesheet">
<style>
    #addBtn{
        position: absolute;
        top: 4px;
        right: 15px;
        font-size: 15px;
        font-weight: bold;
        color: black;
    }
</style>

<style>
    .removed{
        width: 100%;
        font-size: 140%;
        text-align: center;
        cursor: pointer;
    }
    .closedBtns{
        margin: 0 auto;
        width: 200px;
    }
    @media (max-width: 768px) {
        .thumbnail {
            font-size: 50%;
        }
    }
    .plus {
        cursor: pointer;
    }

    .thumbnail {
        font-size: 150%;
    }

    .sidebar {
        width: 16%;
        height: 100vh;
    }
    .right-sidebar {
        height: 100vh;
        z-index: 1;
        position: absolute;
        width: 27%;
    }

    #page-wrappers {
        height: 100vh;
        overflow-y: scroll;
        margin: 0 27% 0 16%;
        padding: 15px;
    }
    .fa{
        font-size: 100%;
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


    #dataTable .btn {
        padding: 0;
    }

    #menuList a {
        padding: 10px 4px;
        font-size: 14px;
    }
    .hideBlock{
        padding: 10px;
        background: #ccc;
        position: absolute;
        width: 100%;
        display: none;
    }
    .changed a, .changed a:hover {
        background-color: #99ff28;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        color: #000!important;
    }
    .nav-tabs>li>a:hover{
        color: #000!important;
    }
    .change a, .change a:hover {
        background-color: #ff585a;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        color: #fff!important;
    }
    li.active.changed a{
        background-color: #29b407!important;
    }
    li.active.change a{
        background-color: #a34648!important;
    }
    .tab-content{
        z-index: 1;
    }
    #debtComment{
        z-index: 9999;
    }
    #termSum{
        z-index: 9999;
    }
</style>

<div id="createDiv">
        <? $menu = new Menu(); ?>
        <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 0;">
            <li role="presentation" class="active success"><a href="#" aria-controls="#" role="tab" data-toggle="tab">&nbsp;</a></li>
        </ul>
        <div class="clearfix"></div>
        <button type="button" class="btn" id="addBtn">+</button>

        <input id="tempPrice" type="text" class="hidden" />
        <div style="position: relative; top: 0">
            <div class="navbar-default sidebar" style="margin-top: 0;" role="navigation; overflow-x: scroll;">
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
            <div id="page-wrappers">
                <div class="tab-panels" id="data">

                </div>
            </div>
            <div class="navbar-default right-sidebar" style="right: 0; top: 0;">
                <div style="position: relative">
                    <div id="debtComment" class="hideBlock" >
                        <div class="form-group">
                            <textarea name="" id="textDebtComment" class="form-control" rows="5" placeholder="Комментарий"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="textDebtPaid" placeholder="Оплаченная часть долга">
                        </div>
                        <div class="form-group">
                            <button type="button" id="addDebt" class="btn btn-danger">Закрыть в долг</button>
                        </div>
                    </div>
                    <div id="termSum" class="hideBlock" >
                        <div class="form-group">
                            <input name="" id="terminalSum" class="form-control" rows="5" placeholder="Сумма" />
                        </div>
                        <div class="form-group">
                            <button type="button" id="closeTerm" class="btn btn-danger">Закрыть терминалом</button>
                        </div>
                    </div>
                    <div class="tab-content">
                    </div>
                </div>
            </div>
        </div>


    <script>
        var globExpId = 0;
        var cntObj;
        var cntVal;
        $(".btnPrint").printPage();
        $(".expCheck").printPage();

        function addTab(id = 0){
            if(id == 0) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('getorder/expense/createExp'); ?>",
                    success: function (data) {
                        $(".nav-tabs li").removeClass("active");
                        $(".tab-content div").removeClass("active");
                        var expId = data;
                        var strTab = '';
                        globExpId = expId;
                        var expIdTxt = '#' + expId;
                        strTab = '<li role="presentation" class="active changed"><a href="' + expIdTxt + '" aria-controls="' + expId + '" role="tab" data-toggle="tab">' + expId + '</a></li>';
                        getOrder(expId);

                        $(".nav-tabs").append(strTab);
                    }

                });
            }
            else{
                $(".nav-tabs li").removeClass("active");
                var expId = id;
                var strTab = '';
                globExpId = expId;
                var expIdTxt = '#' + expId;
                strTab = '<li role="presentation" class="active changed"><a href="' + expIdTxt + '" aria-controls="' + expId + '" role="tab" data-toggle="tab">' + expId + '</a></li>';
                getOrder(expId);

                $(".nav-tabs").append(strTab);
            }
        }

        function getOrder(expId){
            $.ajax({
                type: "POST",
                data: 'id='+expId,
                url: "<?php echo Yii::app()->createUrl('getorder/expense/orders'); ?>",
                success: function(data) {
                    var strTabContent = '';
                    $(".tab-content div").removeClass("active");
                    strTabContent = '<div role="tabpanel" class="tab-pane active" id="'+expId+'">' +
                                        '<form class="expense-form">' +
                                            '<div style="height: 85vh; overflow-y: scroll; ">' +
                                                '<table class="table table-bordered table-fixed dataTable" >' +
                                                    data+
                                                '</table>' +
                                            '</div>'+
                                            '<table style="position: fixed; bottom: 0;">'+
                                                '<tr>'+
                                                    '<td>'+
                                                        '<div class="form-group submitDiv col-xs-9 ">'+
                                                            '<button class="btn btn-success submitBtn" type="button">Добавить</button>' +
                                                            '<a href="getorder/expense/printExpCheck?exp='+ expId+'" class="btn btn-default btnPrint pull-right"><i class="glyphicon glyphicon-print"></i>  Печать </a>'+
                                                        '</div>' +
                                                        '<div class="form-group col-xs-9">' +
                                                        '    <label class="checkbox-inline">' +
                                                        '      <input class="checkDebt" type="checkbox"> Долг' +
                                                        '    </label>' +
                                                        '    <label class="checkbox-inline">' +
                                                        '      <input class="checkTerm" type="checkbox"> Терминал' +
                                                        '    </label>' +
                                                            '<button class="btn btn-danger pull-right" type="button" id="closeExp"  >Закрыть</button>' +
                                                        '</div>'+
                                                    '</td>'+
                                                '</tr>'+
                                            '</table>' +
                                        '</form>' +
                                    '</div>';
                    $(".tab-content").append(strTabContent);
                }
            });
        }
        $(document).on("click","#addBtn", function () {
            addTab();

        });
        $(document).on('click','#addDebt',function () {
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var discount = $(".tab-content .active .dataTable .discount").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('getorder/expense/closeExp'); ?>",
                data: "paid=debt&id="+globExpId+"&sum="+expSum+"&check=0&text="+$("#textDebtComment").val()+"&discount="+discount+"&paidDebt="+$("#textDebtPaid").val(),
                success: function(){
                    $(".nav-tabs li.active").remove();
                    $("#"+globExpId).remove();
                    closeExp();
                }
            });
        });

        $(document).on('click','#closeTerm',function () {
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var discount = $(".tab-content .active .dataTable .discount").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('getorder/expense/closeExp'); ?>",
                data: "paid=term&id="+globExpId+"&sum="+expSum+"&check=0&text="+$("#terminalSum").val()+"&discount="+discount,
                success: function(){
                    $(".nav-tabs li.active").remove();
                    $("#"+globExpId).remove();
                    closeExp();

                }
            });
        });

        $(document).on('click','#closeExp',function () {
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var discount = $(".tab-content .active .dataTable .discount").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('getorder/expense/closeExp'); ?>",
                data: "paid=cash&id="+globExpId+"&sum="+expSum+"&check=0&discount="+discount,
                success: function(){
                    $(".nav-tabs li.active").remove();
                    $("#"+globExpId).remove();
                    closeExp();
                }
            });
        });

        function closeExp(){
            $("#debtComment").slideUp("slow");
            $("#textDebtComment").val("");
            $("#termSum").slideUp("slow");
            $("#terminalSum").val("");
            globExpId = 0;
            $(".nav-tabs li:first-child").addClass("active");
        }

        $(document).on('click','.nav-tabs li', function () {
             var id = $('.tab-content .active').attr('id');
             globExpId = id;

            $("#debtComment").slideUp("slow");
            $("#textDebtComment").val("");
            $(".checkDebt").prop( "checked", false );
            $("#termSum").slideUp("slow");
            $("#terminalSum").val("");
            $(".checkTerm").prop( "checked", false );
        });

        $(document).on('click','.checkDebt', function () {
            if($(this).is(":checked")) {
                $("#debtComment").slideDown("slow");
                $("#termSum").slideUp("slow");
                $(".checkTerm").prop( "checked", false );
                $("#closeExp").addClass("hidden");
            }
            else{
                $("#debtComment").slideUp("slow");
                $("#closeExp").removeClass("hidden");
            }
        });

        $(document).on('click','.checkTerm', function () {
            if($(this).is(":checked")) {
                $("#termSum").slideDown("slow");
                $("#debtComment").slideUp("slow");
                $(".checkDebt").prop( "checked", false );
                $("#closeExp").addClass("hidden");
            }
            else{
                $("#termSum").slideUp("slow");
                $("#closeExp").removeClass("hidden");
            }
        });

        $(document).on("click", '.types' ,function(){
            var thisId = $(this).parent().attr('id');
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('getorder/expense/lists'); ?>",
                data: "id="+thisId,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });

        $(document).on('click','.plus', function () {
            $(".nav-tabs li.active").removeClass("changed");
            $(".nav-tabs li.active").addClass("change");
            var identifies = $(this).children('span').text();
            var thisId = $(this).attr('id');

            if($('.tab-content .active .order tr.'+thisId).exists()){
                var types = str_split(thisId,1);
                var count = $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('input').val();
                count = parseFloat(count)+1;
                $('.tab-content .active .order tr.'+thisId).children("td:first-child").children('input').val(thisId);
                $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('input').val(count);
                $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('span').text(count);
            }
            else{
                var types = str_split(thisId,1);
                $('.tab-content .active .order').append("<tr class="+thisId+">\
                                <td class='removed  glyphicon glyphicon-remove'>\
                                    <input style='display:none' name='id[]' value='"+thisId+"' />\
                                </td>\
                                <td>"+identifies+"</td>\
                                <td>"+$(this).children('div').text()+"</td>\
                                <td class='cnt'>\
                                    <input name='count[]' style='display:none' value='1' />\
                                    <a type='button' class='pluss btn hide'>\
										<input name='' style='display:none' value='0'>\
                                        <i class='fa fa-plus'></i>\
                                    </a>\
                                    <span>" +1+"</span>\
                                    <a type='button' class='minus btn hide'>\
                                        <i class='fa fa-minus'></i>\
                                    </a>\
                                </td>\
                            </tr>");
            }
            getSum();
        });

        $(document).on("click",".removed", function () {
            $(".nav-tabs li.active").removeClass("changed");
            $(".nav-tabs li.active").addClass("change");
            var id = $(this).parent().attr('class');
            $(this).parent().remove();
            // removeFromOrder(id,0);

            getSum();
        });

        $(document).on("click",'.tab-content .active .submitBtn', function(){
            var data = $(".tab-content .active .expense-form").serialize();
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var res = $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('getorder/expense/create'); ?>",
                data: data+"&table=0&employee_id="+<?=Yii::app()->user->getId()?>+"&expenseId="+ globExpId+"&peoples=0"+"&expSum="+expSum+"&check=0"+"&banket=0",
                success: function(){
                    $(".nav-tabs li.active").removeClass("change");
                    $(".nav-tabs li.active").addClass("changed");
                }
            });
            console.log(res);
            $('.tab-content .active .dataTable order').children('tr').remove();
            //$('#Expense_debt').removeAttr('checked');
            //$("#Expense_comment").val('');
            getSum();
        });

        $(document).on("click", ".cnt", function() {
            var count = $(this).children('input').val();
            cntObj = $(this);
            cntVal = count;
            var thisClass = $(this).parent().parent().attr('class');
            $("#myModal").modal();
            return false;
        });



        function printCheck(expSum){
            var data = $("#orderForm").serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('orders/printCheck'); ?>",
                data: data + "&expSum=" + $("#summ").text() + "&expId="+$("#expId").val(),
                success: function (data) {
                    getSum();
                }
            });
        }


        $.fn.cntChange = function () {
            $(this).on('click',function() {
                var id = $(this).attr("id");
                $(".nav-tabs li.active").removeClass("changed");
                $(".nav-tabs li.active").addClass("change");
                switch (id){
                    case "plusOne":
                        cntObj.children("span").text(parseFloat(cntObj.children("span").text()) + 0.1);
                        cntObj.children("input").val(parseFloat(cntObj.children("input").val()) + 0.1);
                        break;
                    case "plusHalf":
                        cntObj.children("span").text(parseFloat(cntObj.children("span").text()) + 0.5);
                        cntObj.children("input").val(parseFloat(cntObj.children("input").val()) + 0.5);
                        break;

                    case "minusHalf":
                        if($("#action").val() == "update"){

                                cntObj.children("span").text(parseFloat(cntObj.children("span").text()) - 0.5);
                                cntObj.children("input").val(parseFloat(cntObj.children("input").val()) - 0.5);
                        }
                        else if($("#action").val() == "create"){
                            cntObj.children("span").text(parseFloat(cntObj.children("span").text()) - 0.5);
                            cntObj.children("input").val(parseFloat(cntObj.children("input").val()) - 0.5);
                        }
                        break;
                    case "minusOne":
                        if($("#action").val() == "update"){

                                console.log("change");
                                cntObj.children("span").text(parseFloat(cntObj.children("span").text()) - 0.1);
                                cntObj.children("input").val(parseFloat(cntObj.children("input").val()) - 0.1);
                            }
                        else if($("#action").val() == "create"){
                            cntObj.children("span").text(parseFloat(cntObj.children("span").text()) - 0.1);
                            cntObj.children("input").val(parseFloat(cntObj.children("input").val()) - 0.1);
                        }
                        break;

                }
                getSum();
            });
            return this;
        };

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

        function getSum(){
            var summ = 0;
            $('.tab-content .active .dataTable tbody tr').each(function(indx){
                var temp = parseFloat($(this).children('td:nth-child(4)').text())*parseInt($(this).children('td:nth-child(3)').text());
                summ += temp
                //sum += $(this).children('td:nth-child(3)').text();
            });
            $('.tab-content .active .dataTable .summ').text(summ);
        }


        jQuery.fn.exists = function() {
            return $(this).length;
        }

        $(document).ready(function(){

            $(".cntPlus").cntChange();
        });
    </script>

    <?
    foreach ($expModel as $item) {?>
        <script>
            addTab(<?=$item["expense_id"]?>);
        </script>
    <?}
    ?>
    <!--/*****      --------------Modal windows------------     ******/-->

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id="myModalBody">
                <div class="row">
                    <div class="col-xs-3 col-md-2">
                        <a href="#" class="thumbnail cntPlus" id="plusOne">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">+0.1</h1>
                        </a>
                    </div>
                    <div class="col-xs-3 col-md-2">
                        <a href="#" class="thumbnail cntPlus" id="plusHalf">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">+0.5</h1>
                        </a>
                    </div>
                    <div class="col-xs-3 col-md-2" >
                        <a href="#" class="thumbnail cntPlus" id="minusHalf">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">-0.5</h1>
                        </a>
                    </div>
                    <div class="col-xs-3 col-md-2" >
                        <a href="#" class="thumbnail cntPlus" id="minusOne">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">-0.1</h1>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="modalOk" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

