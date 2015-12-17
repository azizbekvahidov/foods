
<div class="form-group">
    <span>
        <?php echo CHtml::dropDownList('type_id','',CHtml::listData(Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>0)),'type_id','name'),array('empty'=>'--Выберите раздел--','class'=>'','id'=>'type')); ?>&nbsp; &nbsp;
    </span>
    <span id="child"></span>
</div>
<div id="data">

</div>
<script>
    var ID;
    var trId;
    var optionData = '';


    $("#type").change(function(){

        var typeId = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('menu/checkParent'); ?>",
            data: "type_id="+typeId+'&mType='+<?=$mType?>,
            success: function(data){
                $('#child').html(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('menu/struct'); ?>",
            data: "type_id="+typeId+'&mType='+<?=$mType?>,
            success: function(data){
                $('#data').html(data);
            }
        });
    });

    /*$("#type").chosen({
     no_results_text: "Oops, nothing found!",
     }).change(function(){
     trId = $(this).val();
     trVal = $(this).children('option:selected').text();
     ID = '#'+trVal;

     });*/

</script>