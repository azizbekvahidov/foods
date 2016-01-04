<div class="span3" >
    <h3>Продукты</h3>
    <?php echo CHtml::dropDownList('dishStruct','',$prodList,array('class'=>'span2 left all_product listbox','id'=>'all_product','empty'=>'--Выберите продукт--'));?>

</div>
<div class="span3" >
    <h3>Полуфабрикаты</h3>
    <?php echo CHtml::dropDownList('halfstuff','',$stuffList,array('class'=>'span2 left all_halfstuff listbox','id'=>'all_halfstuff','empty'=>'--Выберите полуфабрикат--')); ?>

</div>
<script>
    $("#all_product").chosen({
        no_results_text: "Oops, nothing found!",
    }).change(function(){
        var prodValue = $(this).val();
        var prodText = $(this).children('option:selected').text();
        if(prodValue != ''){
            $('#structList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                        <td style='text-align:center;'><input class='span1' type='text' name='prod[]' /></td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
        }
    });
    $("#all_halfstuff").chosen({
        no_results_text: "Oops, nothing found!",
    }).change(function(){
        var stuffValue = $(this).val();
        var stuffText = $(this).children('option:selected').text();
        if(stuffValue != ''){
            $('#structList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='stuff_id[]' value='"+stuffValue+"' />"+stuffText+"</td>\
                        <td style='text-align:center;'><input class='span1' type='text' name='stuff[]' /></td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
        }
    });
</script>