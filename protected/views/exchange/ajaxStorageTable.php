
<table class="">
    <thead>
    <tr>
        <th>Название</th>
        <th>тек. кол-во</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach($prod['name'] as $key => $val){
        if($prod['id'][$key] != 0){?>
            <tr class="<?=$key?>">
                <td><?=$val?></td>
                <td class="count"><?=number_format($prod['id'][$key],2,',','')?></td>
                <td><input type="text" class="depCount" name="prod[<?=$key?>]" /></td>
            </tr>
        <? }}?>
    </tbody>
</table>