<? if(!empty($model))
    foreach($model as $key => $value){?>
        <tr>
            <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$value->dish_id?>' /><?=$value->name?></td>
            <td style='text-align:center;'><input type='text' name='count[]' /></td>
            <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
        </tr>
    <? }?>