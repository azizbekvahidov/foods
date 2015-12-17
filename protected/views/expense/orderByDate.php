<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>$dates,
            'name' => 'dates',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?></div>
<button type="button" class="btn" id="show">Показать</button>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var dates,
            depId;
        $('#show').click(function(){
            dates = $('#dates').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/empOrder'); ?>",
                data: "dates="+dates,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>