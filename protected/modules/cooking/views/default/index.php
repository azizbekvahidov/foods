
<div id="data"></div>
<script>
    $(document).ready(function () {
        setInterval(function() {
            $.ajax({
                type: "POST",
                url: "<?=Yii::app()->createUrl('/cooking/default/ajaxData'); ?>",
                success: function (data) {
                    $('#data').html(data);
                }
            });
        },1500);
    })
</script>