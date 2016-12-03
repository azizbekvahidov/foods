<style>
    .head-title{
        position: absolute;
        right: 150px;
    }
    #department{
        display: none;
    }
</style>
<div class="input-prepend span3">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(

            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div>
<div class="span3">
    <h3 class="head-title"></h3>
    <label class="radio">
        <input type="radio" class="types" name="types" value="0">
        Остатки основного склада
    </label>
    <label class="radio">
        <input type="radio" class="types" name="types" value="1">
        Остатки по отделам
    </label>
    <div id="department"><?=CHtml::dropDownList('depId','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'Выберите отдел'))?><br>
        <label class=""><input type="radio" class="pType" name="pType" value="1"> Продукты</label>
        <label class=""><input type="radio" class="pType" name="pType" value="2"> Загатовки</label>
    </div>
</div>
<div class="col-sm-12" style="margin-top: 20px">
    <input type="text" name="prodId" id="prodId" /> &nbsp;
    <input type="text" name="prodCount" id="prodCount"> &nbsp;
</div>
<divclass="span12">
<form id="form">
    <table class="table table-bordered" id="dataTable">
        <thead>
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Кол-во
                <input  type="button" value="Сохранить" id="submit" class="btn btn-success pull-right"></th>
        </tr>
        </thead>
        <tbody  id="data" >
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
</form>
</div>
<script>
    var dates,
        pType,
        pId,
        pVal,
        dVal = 0,
        tVal;
    $(document).ready(function(){

        $("#from").change(function(){
            dates = $(this).val();
            $(".head-title").html('Изменение Остаток на вечер "'+dates+'"го');
            $('#data').html('');
        });
        $('.types').click(function(){
            tVal = $(this).val();
            if(tVal == 0){
                $("#department").attr('style','display:none');
                $('#data').html('');
            }
            else{
                $("#department").attr('style','display:block');
                $('#data').html('');
                $('#depId').change(function(){
                    dVal = $(this).val();
                });
            }
        });
        $('.pType').click(function(){
            pType = $(this).val();
        });
        $('#submit').click(function(){
            var datas = $('#form').serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('settings/changeBalance'); ?>",
                data: "types="+tVal+'&depId='+dVal+'&dates='+dates+'&'+datas,
                success: function(data){

                }
            });
            $('#data').html('');
        });
        document.onkeyup = function (e) {
            e = e || window.event;
            if (e.keyCode === 13) {
                change();
            }
            // Отменяем действие браузера
            return false;
        }
    });
    function change(){
        if($('#prodId').is(':focus')){
            console.log('id');
            $('#prodCount').focus();
        }
        else{
            console.log('count');
            submit();
        }
    }

    function submit(){
        pId = $("#prodId").val();
        pVal = $("#prodCount").val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('settings/ajaxChangeBalance'); ?>",
            data: "types="+tVal+'&depId='+dVal+'&dates='+dates+'&pId='+pId+'&pVal='+pVal+'&pType='+pType,
            success: function(data){
                $('#data').prepend(data);
            }
        });
        $("#prodId").val('');
        $("#prodCount").val('');
        $('#prodId').focus();
    }
</script>