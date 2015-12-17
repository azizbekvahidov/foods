
    <?=CHtml::dropDownList('department_id','',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'Выберите отдел'))?>
&nbsp; &nbsp;


<div id="data">
    
</div>
<script>
    $(document).ready(function(){
        
        
        $('#department_id').change(function(){
            var depId = $(this).val();
            $.ajax({
               type: "POST",
               url: "<?php echo Yii::app()->createUrl('DepStorage/viewStorage'); ?>",
               data: 'department_id='+depId,
               success: function(data){
                 $('#data').html(data);
                }
            });
        });
    });
</script>