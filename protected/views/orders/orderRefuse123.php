<div class="form-group">
    <input type="text" id="expId" value="<?=$expId?>">
    <button class="btn btn-success" id="show">Показать</button>
</div>
<form id="orderForm" action="">
    <table id="dataTable" class="table table-bordered"></table>
</form>
<script>

    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('orders/refuse'); ?>",
            data: 'id='+$("#expId").val(),
            success: function(data){
                $('#dataTable').html(data);
            }
        });
    });
    $(document).on("click", "#show", function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('orders/refuse'); ?>",
            data: 'id='+$("#expId").val(),
            success: function(data){
                $('#dataTable').html(data);
            }
        });
    });

    $(document).on("click", ".minus", function() {
        var count = $(this).parent().parent().children("td.cnt").children('input').val();
        count = parseFloat(count)-1;
        var id = $(this).parent().parent().attr('class');
        if(count > 0){
            $(this).parent().parent().children("td.cnt").children('input').val(count);
            $(this).parent().parent().children("td.cnt").children('span').text(count);
        }
        else{
            $(this).parent().parent().remove();
        }
        removeFromOrder(id,count);
        getSum();
    });

    function getSum(){
        var summ = 0;
        $('#dataTable tbody tr').each(function(indx){
            summ += parseFloat($(this).children('td:nth-child(4)').text())*parseInt($(this).children('td:nth-child(3)').text());
            //sum += $(this).children('td:nth-child(3)').text();
        });
        $('#summ').text(Math.round(summ / 100) * 100);
        $('#Psumm').text(Math.round((summ+(summ/10)) / 100) * 100);
    }

    function removeFromOrder(id,count){
        var expId = $("#expenseId");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('orders/removeFromOrder'); ?>",
            data: "id="+id+'&count='+count+'&expenseId='+$("#expId").val(),
            success: function(data){
                printCheck();
            }
        });
    }

    $(document).on("click", ".removed", function() {
        var id = $(this).parent().attr('class');
        $(this).parent().remove();
        var cnt  = $(this).parent().children("td.cnt").children("input").val();
        removeFromOrder(id,0);
        getSum();
    });

    function printCheck(expSum){
        var data = $("#orderForm").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('orders/printCheck'); ?>",
            data: data + "&expSum=" + $("#summ").text() + "&expId="+$("#expId").val(),
            success: function (data) {
                getSum();
            }
        });
    }

</script>