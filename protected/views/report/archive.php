<div class="form-group col-sm-2">
<input type="text" id="expenseId" class="form-control col-sm-2"> <button id="showBtn" class="btn btn-success">Показать</button>
</div>
<div style="clear: both;"></div>
<div id="data"></div>
<script>
    $(document).ready(function(){
        $('#showBtn').click(function(){
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxArchive'); ?>",
                data: "expId="+$("#expenseId").val(),
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>