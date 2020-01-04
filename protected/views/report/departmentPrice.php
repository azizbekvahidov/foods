
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
            )
        )
    );
    ?>
</div>
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>$dates,
            'name' => 'till',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div>
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<span class="heading-title">Доход отделов</span>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var from,
            till;
        $('#view').click(function(){
            from = $('#from').val();
            till = $('#till').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxDepartmentPrice'); ?>",
                data: "from="+from+"&till="+till,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>


<script>
    $(document).on('click','td',function(){
        $('tr').css("background-color","white");
        $(this).parent().css("background-color","#D9F2F5");
    });

</script>