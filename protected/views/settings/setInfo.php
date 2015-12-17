<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>$dates,
            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
                'autoclose'=> true
            )
        )
    );
    ?>
</div>
<div id="data"></div>
<script>
    $(document).ready(function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('settings/ajaxMInfo'); ?>",
            data: "dates=<?=$dates?>",
            success: function(data){
                $('#data').html(data);
            }
        });
        $('#from').change(function(){
            var dates = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('settings/ajaxMInfo'); ?>",
                data: "dates="+dates,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>