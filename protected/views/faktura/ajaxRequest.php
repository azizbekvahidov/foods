
<? $product = new Products(); $price = $product->getCostPrice($model['product_id'],$dates); ?>
<tr>
    <td><a href='javascript:;' class = 'deleteRow'><i class='fa fa-times '></i></a> <?=$model['Pname']?> (<?=$model['Mname']?>)</td>
    <? foreach ($depId as $val) {?>
        <td><input class='span1' type='text' name='request[<?=$val['department_id']?>][<?=$model['product_id']?>][count]' /></td>
    <?}
    ?>
    <td><input class='span1' type='text' name='request[0][<?=$model['product_id']?>][count]' /></td>
</tr>