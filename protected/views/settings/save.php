<tr>
    <td><?=$result['prod_id']?></td>
    <td><?=$result['pName']?></td>
    <td><input style="display: none" type="text" name="prod_id[<?=$result['type']?>][<?=$result['prod_id']?>]" value="<?=$result['prod_id']?>"/><input type="text" name="count[<?=$result['type']?>][<?=$result['prod_id']?>]" value="<?=$result['CurEndCount']?>" /><?=$result['mName']?></td>
</tr>