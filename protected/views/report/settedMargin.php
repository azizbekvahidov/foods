<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(

            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
                'autoclose'=> true
            )
        )
    );
    ?>
</div><a href="javascript:;" id="show" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<span class="heading-title">Мониторинг установленной наценки</span>
<div id="data"></div>
<script>
    $(document).ready(function(){
        $('#show').click(function(){
            var from = $('#from').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxSettedMargin'); ?>",
                data: "dates="+from,
                success: function(data){
                    $('#data').html(data);
                }
            });
        })

    });

</script>
