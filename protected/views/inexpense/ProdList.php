
<table class="table-bordered table-striped" id="dataTable">
    <thead>
        <tr>
            <th>Название продукта</th>
            <th>количество</th>
        </tr>
    </thead>
    <tbody>
        <? foreach($model as $key => $value){
            foreach($value as $val){?>
            <tr>
                <td colspan="2"><h4><?=$val->name?></h4></td>
            </tr>
            <? foreach($val->getRelated('stuffStruct') as $values){?>
            <tr>
                <td><?=$values->getRelated('Struct')->name?></td>
                <td><?=number_format(($values->amount/$val->count)*$count[$key],'4',',','')?> <?=$values->getRelated('Struct')->getRelated('measure')->name?></td>
            </tr>
            <? }?>
        <?  }
           }?>
    </tbody>
</table>
