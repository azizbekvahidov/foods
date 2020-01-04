<?$prices = new Prices(); $summ = 0;?>

    <thead>
        <tr>
            <th id="all" class=" col-sm-1"><a class="btn all hide">Все</a></th>
            <th id="ordName" class="col-sm-7">Название
                    <input style="display:none" name="action" id="action" value="update">
            </th>
            <th id="ordPrice" class="col-sm-2">Цена</th>
            <th id="ordCount" class="col-sm-2">кол.</th>
        </tr>
    </thead>
    <tbody class="order">
    <?if(!empty($model)){?>
        <?if(!empty($order))?>
            <?foreach ($order as $val) {?>
                <tr class="dish_<?=$val['just_id']?>">
                    <td class=" removed glyphicon glyphicon-remove">
                        <input style="display:none" name="id[]" value="dish_<?=$val['just_id']?>">
                    </td>
                    <td>
                        <?=$val['name']?>
                    </td>
                    <td><?=$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$model['order_date'])?></td>
                    <td class="cnt">
                        <input name="count[]" style="display:none" value="<?=$val['count']?>">
                        <a type="button" class="pluss btn hide">
                            <input name="" style="display:none" value="<?=$val['count']?>">
                            <i class="fa fa-plus"></i>
                        </a>
                        <span><?=$val['count']?></span>
                        <a type="button" class="minus btn hide">
                            <i class="fa fa-minus"></i>
                        </a>
                    </td>
                </tr>
            <? $summ = $summ + $val['count']*$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$model['order_date']);}
            ?>

        <?if(!empty($order2))?>
            <?foreach ($order2 as $val) {?>
                <tr class="stuff_<?=$val['just_id']?>">
                    <td class=" removed glyphicon glyphicon-remove">
                        <input style="display:none" name="id[]" value="stuff_<?=$val['just_id']?>">
                    </td>
                    <td>
                        <?=$val['name']?>
                    </td>
                    <td><?=$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$dates)?></td>
                    <td class="cnt">
                        <input name="count[]" style="display:none" value="<?=$val['count']?>">
                        <a type="button" class="pluss btn hide">
                            <input name="" style="display:none" value="<?=$val['count']?>">
                            <i class="fa fa-plus"></i>
                        </a>
                        <span><?=$val['count']?></span>
                        <a type="button" class="minus btn hide">
                            <i class="fa fa-minus"></i>
                        </a>
                    </td>
                </tr>
            <?$summ = $summ + $val['count']*$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$model['order_date']);}
            ?>

        <?if(!empty($order3))?>
            <?foreach ($order3 as $val) {?>
                <tr class="product_<?=$val['just_id']?>">
                    <td class=" removed glyphicon glyphicon-remove">
                        <input style="display:none" name="id[]" value="product_<?=$val['just_id']?>">
                    </td>
                    <td>
                        <?=$val['name']?>
                    </td>
                    <td><?=$prices->getPrice($val['just_id'],$model['mType'],$val['type'],$dates)?></td>
                    <td class="cnt">
                        <input name="count[]" style="display:none" value="<?=$val['count']?>">
                        <a type="button" class="pluss btn hide">
                            <input name="" style="display:none" value="<?=$val['count']?>">
                            <i class="fa fa-plus"></i>
                        </a>
                        <span><?=$val['count']?></span>
                        <a type="button" class="minus btn hide">
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
            <td colspan="2" class="summ"><?=number_format($summ/100,0,',','')*100?></td>
        </tr>
        <tr>
            <td class="text-center" colspan="4">
                <input type="text" class="form-control discount" name="discount" placeholder="Скидка к счету">
            </td>
        </tr>
    </tfoot>

<?if($model["banket"] == 1){?>
    <script>
        $("#banket").attr("checked","checked");
    </script>
<?}else{?>
    <script>
        $("#banket").removeAttr("checked");
    </script>
<?}?>