<? foreach($model as $key => $val){?>
    <tr>
        <td style='text-align:center;'>
                <input type='text' style='display: none;' name='product_group_id[<?=$id?>][]' value='<?=$key?>' /><?=$val?>
        </td>
        <td style='text-align:center;'><input class='span1' type='text' name='product_group_id[<?=$id?>][count][]' /></td>
        <td style='text-align:center;'><input class='span1' type='text' name='product_group_id[<?=$id?>][price][]' /></td>
        <td style='text-align:center;'><input type="radio" class="checking" name="check[<?=$id?>]" value="<?=$key?>"></td>
        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
    </tr>
<?}?>