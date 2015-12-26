<style>
    thead{
        background-color:white;
    }
    td{
        text-align: center;
    }
</style>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'faktura-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<div class="form-group">
    <?= CHtml::dropDownList('provider','',$provList,array('empty' => '--Выберите поставщика--','id'=>'provider'))?>&nbsp; &nbsp;
    <?= CHtml::dropDownList('products','',$prodList,array('empty' => '--Выберите продукт--','id'=>'product'))?>&nbsp; &nbsp;

</div><br /><br />
<div class="form-group">
    <table id="prodList" class="table table-hover ">
        <thead>
        <tr>
            <th style="text-align:center;">Наименование</th>
            <? foreach ($depId as $val) {?>
                <th style="text-align:center;"><?=$val->name?></th>
            <?}?>
            <th style="text-align:center;">Прочие</th>
        </tr>
        </thead>
        <tbody>
        <tr style="display: none;"></tr>
        </tbody>
    </table>
</div>
<div id="bottom_anchor"></div>
<script>
    function moveScroll(){
        var scroll = $(window).scrollTop();
        var anchor_top = $("#prodList").offset().top;
        var anchor_bottom = $("#bottom_anchor").offset().top;
        if (scroll>anchor_top && scroll<anchor_bottom) {
            clone_table = $("#clone");
            if(clone_table.length == 0){
                clone_table = $("#prodList").clone();
                clone_table.attr('id', 'clone');
                clone_table.css({position:'fixed',
                    'pointer-events': 'none',
                    top:0});
                clone_table.width($("#prodList").width());
                $("#content").append(clone_table);
                $("#clone").css({visibility:'hidden'});
                $("#clone thead").css({'visibility':'visible','pointer-events':'auto'});
            }
        } else {
            $("#clone").remove();
        }
    }
    $(window).scroll(moveScroll);
</script>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'id'=>'submitBtn',
        'type'=>'primary',
        'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
        'disabled'=>'disabled'
    )); ?>
</div>
<script>
    $(document).on("click", ".deleteRow", function() {
        $(this).parent().parent().remove();
    });
    $("#product").chosen({
        no_results_text: "Уупс, Ничего не найдено!"
    }).change(function(){
        var prodValue = $(this).val();
        if(prodValue != ''){

            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('faktura/ajaxRequest'); ?>",
                data: 'id='+prodValue,
                success: function(data){
                    $('#prodList tr:last').after(data);
                }
            });
        }
    });
    $("#provider").chosen({
        no_results_text: "Уупс, Ничего не найдено!"
    }).change(function(){
        $('#submitBtn').removeAttr('disabled').removeClass('disabled');
    });

</script>
<?php $this->endWidget(); ?>