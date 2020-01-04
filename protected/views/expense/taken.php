<script src="/js/jquery.printPage.js"></script>
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
            'name' => 'to',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div> &nbsp;
<button type="button" id="show" class="btn">Показать</button> &nbsp;
<a href="javascript:;" type="button" id="printReport" class="btn btn-success">Печать</a>
<span class="heading-title">Выручка</span>
</div>
<div id="data"></div>
<script>

    $(document).ready(function(){
        $("#printReport").printPage();
        var dates = '<?=$dates?>',
            depId;
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('expense/test'); ?>",
            data: "dates="+dates,
            success: function(data){
                $('#data').html(data);
                $("#printReport").attr('href','/expense/printReport?from='+dates+"&to="+dates);
            }
        });
        $('#show').click(function(){
            var from = $('#from').val(),
            to = $('#to').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/test'); ?>",
                data: "from="+from+"&to="+to,
                success: function(data){
                    $('#data').html(data);
                    $("#printReport").attr('href','/expense/printReport?from='+from+"&to="+to);
                }
            });
        });
    });
</script>