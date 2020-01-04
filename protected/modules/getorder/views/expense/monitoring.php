<div id="data"></div>
<script src="/js/jquery.printPage.js"></script>
<script>
    $(document).ready(function(){
        $(".btnPrint").printPage();
        setInterval(function(){
            $.ajax({
                type:"Post",
                url: "<?php echo Yii::app()->createUrl('order/expense/ajaxMonitoring'); ?>",
                success: function(data){
                    $('#data').html(data);
                }
            });
        },2000);
    });
</script>