<? $prod = new Products(); $stuff = new Halfstaff();
 if(!empty($model['prod'])){
    foreach ($model['prod'] as $key => $val) {?>
        <tr>
            <td class='span3'>
                <input name='prod[id][]' hidden='' value='<?=$key?>'  />
                <?=$prod->getName($key)?>
            </td>
            <td class='span2'>
                <input name='prod[count][]' placeholder='Кол-во' value="<?=$val/$model['count']*$count?>" class='form-control'  />
            </td>
            <td>
                <a href='javascript:;' class='remove'><i class='icon-trash'></i></a>
            </td>
        </tr>
    <?}
}
if(!empty($model['stuff'])){
    foreach ($model['stuff'] as $key => $val) {?>
        <tr>
            <td class='span3'>
                <input name='stuff[id][]' hidden='' value='<?=$key?>'  />
                <?=$stuff->getName($key)?>
            </td>
            <td class='span2'>
                <input name='stuff[count][]' placeholder='Кол-во' value="<?=$val/$model['count']*$count?>" class='form-control'  />
            </td>
            <td>
                <a href='javascript:;' class='remove'><i class='icon-trash'></i></a>
            </td>
        </tr>
    <?}
}?>
