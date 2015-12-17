<? $cnt = 1;?>
<div id="data"></div>
<script>
    $(document).ready(function(){
        /*var table = $('#dataTable').DataTable({
            responsive: true,
            "lengthMenu": [[ -1,10, 25, 50, 100], [ "Все",10, 25, 50, 100]],
            'ajax':"/monitoring/refreshTable"
        });*/
        setInterval(function(){
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('monitoring/refreshTable'); ?>",
                success: function(data){
                    $('#data').html(data);
                }
            });
        },3000);

    });
</script>