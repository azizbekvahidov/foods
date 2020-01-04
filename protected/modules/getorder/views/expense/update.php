<?php  $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'upexpense-form',
    'type'=>'inline',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

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
    .upplus{
        cursor: pointer;
    }
    .sidebar{
        width: 20%;
    }
    .right-sidebar{
        z-index: 1;
        position: absolute;
        width: 25%;
        margin-top: 51px;
    }.thumbnail {
         font-size: 11px;
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
        left: 15px;
    }
    #upexpense-form{
        margin-top: 0px;
    }
    .cnt{
        cursor: pointer;
    }
    .liStyle{
        list-style: none;
        border: none!important;
    }
    #updataTable td,th{
        padding: 4px!important  ;
    }
    #updataTable .btn{
        padding: 0;
    }
    #menuList a{
        padding: 4px 4px;
    }
    .topHead{
        z-index: 1000;
        margin-top: -45px;
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
            Стол
            <?php echo CHtml::dropDownList('table',$table,$tables,array('class'=>'form-control'))?> &nbsp; &nbsp;

        </div>
        <div class="col-xs-3">

            <?php echo CHtml::dropDownList('employee_id',$empId,CHtml::listData(Employee::model()->findAll(),'employee_id','name'),array('class'=>'form-control'))?>
        </div>
        <div class="col-xs-3">
            <label>Долг</label>
            <?php echo CHtml::checkBox('debt',$debt?true:false,array('class'=>'form-control'))?>
        </div>
        <?echo CHtml::textField('comment','',array('style'=>'display:none'))?>
    </div>
    <div class="tab-panels" id="updata">

    </div>

</div>
<div class="navbar-default right-sidebar" style="right: 0; top: 0;">
    <input name="expense_id" style="display:none;" value="<?=$expense_id?>" />
    <div>
        <table class="table table-bordered" id="updataTable">
            <thead>
            <tr>
                <th><a class="btn all">Все</a></th>
                <th>Название</th>
                <th>кол.</th>
            </tr>
            </thead>
            <tbody id="uporder">
            <?if(!empty($updateDish)){?>
                <?foreach($updateDish->getRelated('order') as $val) {?>
                    <tr class="dish_<?=$val->just_id?>">
                        <td >
                            <a type='button' class='removed btn'>
                                <i class='fa fa-times'></i>
                            </a>
                            <input style='display:none' name='dish[id][]' value='<?=$val->just_id?>' />
                        </td>
                        <td><?=$val->getRelated('dish')->name?></td>
                        <td class='cnt'>
                            <input name='dish[count][]' style='display:none' value='<?=$val->count?>' />
                            <span><?=$val->count?></span>
                            <a type='button' class='minus btn'>
                                <i class='fa fa-minus'></i>
                            </a>
                        </td>
                    </tr>
                <?}}?>
            <?if(!empty($updateStuff)){?>
                <?foreach($updateStuff->getRelated('order') as $val) {?>
                    <tr class="stuff_<?=$val->just_id?>">
                        <td >
                            <a type='button' class='removed btn'>
                                <i class='fa fa-times'></i>
                            </a>
                            <input style='display:none' name='stuff[id][]' value='<?=$val->just_id?>' />
                        </td>
                        <td><?=$val->getRelated('halfstuff')->name?></td>
                        <td class='cnt'>
                            <input name='stuff[count][]' style='display:none' value='<?=$val->count?>' />
                            <span><?=$val->count?></span>
                            <a type='button' class='minus btn'>
                                <i class='fa fa-minus'></i>
                            </a>
                        </td>
                    </tr>
                <?}}?>
            <?if(!empty($updateProd)){?>
                <?foreach($updateProd->getRelated('order') as $val) {?>
                    <tr class="product_<?=$val->just_id?>">
                        <td >
                            <a type='button' class='removed btn'>
                                <i class='fa fa-times'></i>
                            </a>
                            <input style='display:none' name='product[id][]' value='<?=$val->just_id?>' />
                        </td>
                        <td><?=$val->getRelated('products')->name?></td>
                        <td class='cnt'>
                            <input name='product[count][]' style='display:none' value='<?=$val->count?>' />
                            <span><?=$val->count?></span>
                            <a type='button' class='minus btn'>
                                <i class='fa fa-minus'></i>
                            </a>
                        </td>
                    </tr>
                <?}}?>
            </tbody>

        </table>
    </div>
    <div class="form-actions text-center col-xs-12 ">
        <button class="btn btn-success" id="upsubmitBtn" type="button"><?=$model->isNewRecord ? 'Добавить' : 'Сохранить'?></button>

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


    $('#uporders').click(function(){
        $("#upmyModalHeader").html("Текущие заказы");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('order/expense/todayOrder'); ?>",
            success: function(data){
                $('#upmyModalBody').html(data);
            }
        });
        $("#upmyModal").modal();
        return false;
    });

    $('#upsubmitBtn').click(function(){
        var data = $("#upexpense-form").serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('order/expense/update?id='.$expense_id); ?>",
                data: data
            });
                window.close()


    });
    $(document).on("click", "#com", function() {
        var thisValue = $("#ModalBody").children('input').val();
        $("#comment").val(thisValue);
    });
    $(document).on('click','#debt',function(){

        if($('#debt').attr('checked') == 'checked') {
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
            url: "<?php echo Yii::app()->createUrl('order/expense/upLists'); ?>",
            data: "id="+thisId,
            success: function(data){
                $('#updata').html(data);
            }
        });
    })

    jQuery.fn.exists = function() {
        return $(this).length;
    }

    $(document).on("click", ".cnt span", function() {
        var count = $(this).children('input').val();
        var thisClass = $(this).parent().parent().attr('class');
        $("up#myModalHeader").html("Укажите количество");
        $('#upmyModalBody').html("<input class='span2 form-control' type='text' name='' value='' />");
        $("#upmyModalBody").children('input').val(count);
        $("#upmyModalBody").children('input').attr('name',thisClass);
        $("#upmyModal").modal();
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
        if($("#uporder tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
    });

    $(document).on("click", ".removed", function() {
        $(this).parent().parent().remove();
        if($("#uporder tr").exists() == 0){
            $('#submitBtn').attr('disabled','disabled');
        }
    });

    $(document).on("click", "#upok", function() {
        var curCount = $("#upmyModalBody").children('input').val();
        var curClass = $("#upmyModalBody").children('input').attr('name');
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
        if($("#uporder tr").exists() == 0){
            $('#upsubmitBtn').attr('disabled','disabled');
        }
    });


    document.onkeyup = function (e) {
        e = e || window.event;
        if (e.keyCode === 13) {
            $("#ok").click();
        }
        // Отменяем действие браузера
        return false;
    }

    $('.all').click(function(){
        $("#uporder").empty();
        if($("#uporder tr").exists() == 0){
            $('#upsubmitBtn').attr('disabled','disabled');
        }
    });


</script>

<?php $this->endWidget(); ?>

<?php $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'upmyModal')
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="upmyModalHeader">Modal header</h4>
</div>

<div class="modal-body" id="upmyModalBody">

</div>

<div class="modal-footer">
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'Ok',
            'url' => '#',
            'htmlOptions' => array('id'=>'upok','data-dismiss' => 'modal','class'=>'btn btn-success'),
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
            'htmlOptions' => array('id'=>'com','data-dismiss' => 'modal','class'=>'btn btn-success'),
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

