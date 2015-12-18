<?= CHtml::dropDownList('depId','',$dep,array('empty' => '--Выберите Отдел--'))?>&nbsp; &nbsp;
<button class="btn" type="button" id="show">Показать</button>
<div id="data"></div>
<script>
    $("#depId").chosen({
        no_results_text: "Уупс, Ничего не найдено!"
    });
    $("#show").click(function(){
        var id = $("#depId").val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('settings/calculateList'); ?>",
            data: 'id='+id,
            success: function(data){
                $('#data').html(data);
            }
        });
    });
</script>