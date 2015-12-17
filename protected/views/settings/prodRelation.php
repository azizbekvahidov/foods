<?=CHtml::dropDownList('product','',CHtml::listData(Products::model()->findAll(),'product_id','name'),array('empty'=>'Выберите продукт'))?>
<br>
<br>
<div id="data">

</div>
<script>
    $("#product").chosen({
        no_results_text: "Oops, nothing found!"
    }).change(function(){
        var id = $(this).val();
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('settings/prodRelList'); ?>",
            data: "id="+id,
            success: function(data){
                $('#data').html(data);
            }
        });
    });
</script>