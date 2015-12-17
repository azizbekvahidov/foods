<style>
    .head-title{
        position: absolute;
        right: 300px;
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
<div id="department"><?=CHtml::dropDownList('depId','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'Выберите отдел'))?></div>
</div>
<div id="data" class="span12"></div>
<script>
    $(document).ready(function(){
        var dates;
        $("#from").change(function(){
            dates = $(this).val();
            $(".head-title").html('Остатки на вечер "'+dates+'"го');
            $('#data').html('');
        });
        $('.types').click(function(){
            var tVal = $(this).val();
            if(tVal == 0){
                $("#department").attr('style','display:none');
                $('#data').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('settings/balanceList'); ?>",
                    data: "types="+tVal+'&dates='+dates,
                    success: function(data){
                        $('#data').html(data);
                    }
                });
            }
            else{
                $("#department").attr('style','display:block');
                $('#data').html('');
                $('#depId').change(function(){
                    var dVal = $(this).val();
                    console.log(dVal);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('settings/balanceList'); ?>",
                        data: "types="+tVal+'&depId='+dVal+'&dates='+dates,
                        success: function(data){
                            $('#data').html(data);
                        }
                    });
                });
            }
        })
    });
</script>