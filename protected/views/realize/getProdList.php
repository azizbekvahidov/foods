<tr>
    <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$model->product_id?>' /><?=$model->name?></td>
    <td style='text-align:center;'><input class='span1' type='text' name='count[]' /> <?=$model->getRelated('measure')->name?></td>
    <td style='text-align:center;'><input class='span1' type='text' name='price[]' /></td>
    <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
</tr>