<?$cnt = 1;?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>cnt</th>
            <th>order_date</th>
            <th>employee</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($model as $val) {?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$val->order_date?></td>
            <td><?=$val->getRelated('employee')->name?></td>
            <td></td>
        </tr>
    <?  $cnt++;
    }
    ?>
    </tbody>
</table>