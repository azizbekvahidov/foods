<? if(!empty($model))
foreach($model as $key => $value){?>
    <tr>
        <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$key?>' /><?=$value?></td>
        <td style='text-align:center;' id="<?=$key?>"><input type='text' name='count[]' /></td>
        <td style='text-align:center;'><button type="button" class="btn" id="ingridient">Выбрать ингридиент</button></td>
        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
    </tr>
<? }?>
<script>
    $(document).on('click','#ingridient',function(){
        var data = $(this).parent().parent().children("td:first-child").children("input").val();
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('inexpense/Struct'); ?>",
            data: "data="+data,
            success: function(data){
                $('#modalBody').html(data);
            }
        });
    });

</script>