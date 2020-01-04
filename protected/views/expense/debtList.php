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
<span class="heading-title">Долги</span>
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
                url: "<?php echo Yii::app()->createUrl('expense/ajaxDeptList'); ?>",
                data: "from="+from+"&till="+till,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>


<script>
    jQuery(document).on('click','#dataTable a.debt-close',function() {
        if(!confirm('Вы уверены, что этот счет оплачен')) return false;
        var th = this,
            afterDelete = function(){};
        jQuery(this).parent().parent().remove()
        jQuery.ajax({
            type: 'POST',
            url: jQuery(this).attr('href'),
            success: function(data) {
                //jQuery('#dataTable').yiiGridView('update');
                afterDelete(th, true, data);
            },
            error: function(XHR) {
                return afterDelete(th, false, XHR);
            }
        });
        return false;
    });
    jQuery(document).on('click','#dataTable a.debt-close-just',function() {
        if(!confirm('Вы уверены?')) return false;
        var th = this,
            afterDelete = function(){};
        jQuery(this).parent().parent().remove();
        jQuery.ajax({
            type: 'POST',
            url: jQuery(this).attr('href'),
            success: function(data) {
                //jQuery('#dataTable').yiiGridView('update');
                afterDelete(th, true, data);
            },
            error: function(XHR) {
                return afterDelete(th, false, XHR);
            }
        });
        return false;
    });
    jQuery(document).on('click','.term-debt-close',function() {
        console.log($(this).attr('id'));
        $('#expIdFSum').val($(this).attr('id'));
    });

    jQuery(document).on('click','.paid-debt',function() {
        console.log($(this).attr('id'));
        $('#expIdPiaid').val($(this).attr('id'));
    });
    jQuery(document).on('clikc','#savePaidDebt', function () {

    });
    jQuery(document).on('click','#saveTerm',function() {
        var th = this,
            afterDelete = function(){};
        var expId = $('#expIdFSum').val(),
            term = $("#termSum").val();
        var child = document.getElementById(expId).parentElement.parentElement;
        var parent = child.parentElement;
        parent.removeChild(child);
        $.ajax({
            type: 'GET',
            url: '/expense/debtClose',
            data: 'id=' + expId,
            success: function (data) {
                //jQuery('#dataTable').yiiGridView('update');
            },
            error: function (XHR) {
                return afterDelete(th, false, XHR);
            }
        });
        $.ajax({
            type: 'POST',
            url: '/monitoring/closeTerm',
            data: 'id='+expId+"&term="+term,
            success: function(data) {
                $("#termSum").val('');
                $("#expIdFSum").val('');
                $('#modal-sm').modal('hide');
                //jQuery('#dataTable').yiiGridView('update');
            },
            error: function(XHR) {
                return afterDelete(th, false, XHR);
            }
        });
        return false;
    });
</script>
<script>
    $(document).on('click','td',function(){
        $('tr').css("background-color","white");
        $(this).parent().css("background-color","#D9F2F5");
    });

</script>