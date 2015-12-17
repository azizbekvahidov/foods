<?php 
if(!empty($lists))
echo CHtml::dropDownList('childtype_id','',$lists,array('empty'=>'--Выберите тип--','class'=>'','id'=>'childtype')); ?>&nbsp; &nbsp;
<script>
$("#childtype").change(function(){
        
        var typeId = $(this).val();
        
        $.ajax({
           type: "POST",
           url: "<?php echo Yii::app()->createUrl('menu/struct'); ?>",
           data: "type_id="+typeId+'&mType='+<?=$mType?>,
           success: function(data){
             $('#data').html(data);
            }
        });
    });
</script>