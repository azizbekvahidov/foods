<? $cnt = 1; $prices = new Prices(); $summ = 0?><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/css/bootstrap3.css" rel="stylesheet">

<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th colspan="3">Счет № <ins><?=$expense->expense_id?></ins></th>
            <th colspan="2">Стол № <ins><?=$expense->table?></ins></th>
        </tr>
        <tr>
            <th colspan="5">Официант <ins><?=$expense->getRelated('employee')->name?></ins></th>

        </tr>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Цена</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?if(!empty($model))
        foreach ($model->getRelated('order') as $value) { $price = $prices->getPrice($value->just_id,$model->mType,$value->type,$model->order_date)?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('dish')->name?></td>
                <td><?=$value->count?></td>
                <td><?=$price?></td>
                <td><?=$price*$value->count; $summ = $summ + $price*$value->count?></td>
            </tr>
        <?$cnt++;}
    ?>
    <?if(!empty($model2))
        foreach ($model2->getRelated('order') as $value) { $price = $prices->getPrice($value->just_id,$model2->mType,$value->type,$model2->order_date)?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('halfstuff')->name?></td>
                <td><?=$value->count?></td>
                <td><?=$price?></td>
                <td><?=$price*$value->count; $summ = $summ + $price*$value->count?></td>
            </tr>
        <?$cnt++;}
    ?>
    <?if(!empty($model3))
        foreach ($model3->getRelated('order') as $value) { $price = $prices->getPrice($value->just_id,$model3->mType,$value->type,$model3->order_date)?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('products')->name?></td>
                <td><?=$value->count?></td>
                <td><?=$price?></td>
                <td><?=$price*$value->count; $summ = $summ + $price*$value->count?></td>
            </tr>
        <?$cnt++;}
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Итого : </th>
            <th><?=$summ?> Сум</th>
        </tr>
    </tfoot>
</table>