
<table class="items table-bordered table table-striped table-hover dataTable no-footer">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th>Дата</th>
            <th>Количество</th>
        </tr>
    </thead>
    <tbody>
        <? $count = 1; 
        if(!empty($depProdModel))
        foreach($depProdModel as $value){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('product')->name?></td>
            <td><?=$value->curDate?></td>
            <td><?=$value->curCount?> <?=$value->getRelated('product')->getRelated('measure')->name?></td>
        </tr>
        <? $count++; }
        if(!empty($depStuffModel))
        foreach($depStuffModel as $value){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('stuff')->name?></td>
            <td><?=$value->curDate?></td>
            <td><?=$value->curCount?> <?=$value->getRelated('stuff')->getRelated('halfstuffType')->name?></td>
        </tr>
        <? $count++; }
        if(!empty($depDishModel))
        foreach($depDishModel as $value){?>
            <tr>
                <td><?=$count?></td>
                <td><?=$value->getRelated('dish')->name?></td>
                <td><?=$value->curDate?></td>
                <td><?=$value->curCount?> </td>
            </tr>
            <? $count++; }?>

    </tbody>
</table>