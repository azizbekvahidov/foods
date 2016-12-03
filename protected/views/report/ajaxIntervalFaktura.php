<?$i = $from; $price = new Products();?>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>Наименование</th>
            <? while($i <= $to){?>
            <th><?=$i?></th>
            <?$i = date('Y-m-d',strtotime($i)+86400);}
            ?>
        </tr>
    </thead>
    <tbody>
        <?foreach ($prod as $val) { $j = $from?>
        <tr>
            <td><?=$val['name']?></td>
            <? while($j <= $to){?>
            <td><?=$price->getCostPrice($val['product_id'],$j)?></td>
            <?$j = date('Y-m-d',strtotime($j)+86400);}
            ?>
        </tr>
        <?}
        ?>
    </tbody>
</table>