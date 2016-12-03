<style>
    .modal{
        left: 50%!important;
        bottom: auto;
        overflow: visible!important;
        overflow-y: visible!important;
    }
    #dish_chosen{
        width: 200px!important;
    }
    .modal-body{
        height: 350px;
    }
</style>
<form id="form">
    <div class="input-prepend">
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
    <? $product = new Products(); $stuff = new Halfstaff(); $dish = new Dishes();
        echo CHtml::dropDownList('department','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'Выберите отдел')); 
    ?> &nbsp; 
    <? $product = new Products(); $stuff = new Halfstaff(); 
        echo CHtml::dropDownList('products','',$product->getUseProdList(),array('empty'=>'Выберите продукт'));
    ?> &nbsp; 
    <?
        echo CHtml::dropDownList('halfstuff','',$stuff->getUseStuffList(),array('empty'=>'Выберите заготовку'));
    ?> &nbsp;
    <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#myModal">
        Выбрать блюдо
    </button>
        <button type="button" class="save btn btn-success pull-right">Сохранить</button>
    <br /><br />
    <div class="span6">
        <table class="table ">
            <tbody id="data">
            </tbody>
        </table>
    </div>
    <div class="span6">
        <textarea name="comment" id="comment" class="form-control" placeholder="Комментарий"></textarea>
    </div>
</form>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form id="modalForm" >
                    <div class="span2">
                        <?=CHtml::dropDownList('dish','',$dish->getUseDishList(),array('empty'=>'выберите блюдо','style'=>'width:200px!important'))?>
                    </div>
                    <div class="span2">
                        <input type="text" name="count" class="form-control"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="countBtn">Расчитать</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click','.remove',function(){
            $(this).parent().parent().remove(); 
    });
    $(function(){
        $('.save').click(function(){
            datas = $('#form').serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('off/saveList'); ?>",
                data: datas,
                success: function(){
                    $('#data').html('');
                    $('#comment').text('');
                }
            });
        });
        
        $("#products").chosen({
            no_results_text: "Уупс, Ничего не найдено!"
        }).change(function(){
            var prodValue = $(this).val();
            var prodText = $("#products option:selected").text();
            if(prodValue != ''){
                $('#data').append("\
                    <tr>\
                        <td class='span3'>\
                            <input name='prod[id][]' hidden='' value='"+prodValue+"'  />\
                            "+prodText+"\
                        </td>\
                        <td class='span2'>\
                            <input name='prod[count][]' placeholder='Кол-во' class='form-control'  />\
                        </td>\
                        <td>\
                            <a href='javascript:;' class='remove'><i class='icon-trash'></i></a>\
                        </td>\
                    </tr>\
                ");
            }
        });
        
        $("#halfstuff").chosen({
            no_results_text: "Уупс, Ничего не найдено!"
        }).change(function(){
            var prodValue = $(this).val();
            var prodText = $("#halfstuff option:selected").text();
            if(prodValue != ''){
                $('#data').append("\
                    <tr>\
                        <td class='span3'>\
                            <input name='stuff[id][]' hidden='' value='"+prodValue+"'  />\
                            "+prodText+"\
                        </td>\
                        <td class='span2'>\
                            <input name='stuff[count][]' placeholder='Кол-во' class='form-control'  />\
                        </td>\
                        <td>\
                            <a href='javascript:;' class='remove'><i class='icon-trash'></i></a>\
                        </td>\
                    </tr>\
                ");
            }
        });
        $('#dish').chosen({
            no_results_text: "Уупс, Ничего не найдено!"
        });

    });
    $(document).on('click','#countBtn',function(){
        datas = $('#modalForm').serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('off/getList'); ?>",
            data: datas,
            success: function(data){
                $('#data').append(data);

            }
        });
    });
</script>