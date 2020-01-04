<?$prices = new Prices(); $summ = 0;?>


<thead>
<tr>
    <th id="all" class=" col-sm-1"><a class="btn all">Все</a></th>
    <th id="ordName" class="col-sm-7">Название
        <?if(!empty($model)){?>
            <input style="display:none" name="action" id="action" value="update">
        <?}else{?>
            <input style="display:none" name="action" id="action" value="create">
        <?}?>
    </th>
    <th id="ordPrice" class="col-sm-2">Цена</th>
    <th id="ordCount" class="col-sm-2">кол.</th>
</tr>
</thead>
<tbody id="order">
<?if(!empty($model)){?>
    <?if(!empty($order))?>
    <?foreach ($order as $val) {?>
        <tr class="dish_<?=$val['just_id']?>">
            <td class="removed fa fa-times">
                <input style="display:none" name="id[]" value="dish_<?=$val['just_id']?>">
            </td>
            <td>
                <?=$val['name']?>
            </td>
            <td><?=$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$model['order_date'])?></td>
            <td class="cnt">
                <input name="count[]" style="display:none" value="<?=$val['count']?>">
                <a type="button" class="pluss btn hide">
                    <i class="fa fa-plus"></i>
                </a>
                <span><?=$val['count']?></span>
                <a type="button" class="minus btn ">
                    <i class="fa fa-minus"></i>
                </a>
            </td>
        </tr>
        <? $summ = $summ + $val['count']*$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$model['order_date']);}
    ?>

    <?if(!empty($order2))?>
    <?foreach ($order2 as $val) {?>
        <tr class="stuff_<?=$val['just_id']?>">
            <td class="removed fa fa-times">
                <input style="display:none" name="id[]" value="stuff_<?=$val['just_id']?>">
            </td>
            <td>
                <?=$val['name']?>
            </td>
            <td><?=$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$dates)?></td>
            <td class="cnt">
                <input name="count[]" style="display:none" value="<?=$val['count']?>">
                <a type="button" class="pluss btn hide">
                    <i class="fa fa-plus"></i>
                </a>
                <span><?=$val['count']?></span>
                <a type="button" class="minus btn ">
                    <i class="fa fa-minus"></i>
                </a>
            </td>
        </tr>
        <?$summ = $summ + $val['count']*$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$model['order_date']);}
    ?>

    <?if(!empty($order3))?>
    <?foreach ($order3 as $val) {?>
        <tr class="product_<?=$val['just_id']?>">
            <td class="removed fa fa-times">
                <input style="display:none" name="id[]" value="product_<?=$val['just_id']?>">
            </td>
            <td>
                <?=$val['name']?>
            </td>
            <td><?=$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$dates)?></td>
            <td class="cnt">
                <input name="count[]" style="display:none" value="<?=$val['count']?>">
                <a type="button" class="pluss btn hide">
                    <i class="fa fa-plus"></i>
                </a>
                <span><?=$val['count']?></span>
                <a type="button" class="minus btn ">
                    <i class="fa fa-minus"></i>
                </a>
            </td>
        </tr>
        <? $summ = $summ + $val['count']*$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$dates);}?>
<?}?>
</tbody>
<tfoot>
<tr>
    <td colspan="2">Итого</td>
    <td colspan="2" id="summ"><?=number_format($summ/100,0,',','')*100?></td>
</tr>
</tfoot>

<script>

</script>
