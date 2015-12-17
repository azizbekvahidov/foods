<? foreach($products as $value){?>
<tr>
    <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$value->getRelated('products')->product_id?>' /><?=$value->getRelated('products')->name?></td>
    <td style='text-align:center;'><input class='span1' type='text' name='count[]' /> <?=$value->getRelated('products')->getRelated('measure')->name?></td>
    <td style='text-align:center;'><input class='span1' type='text' name='price[]' /></td>
    <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
</tr>
<? }?>