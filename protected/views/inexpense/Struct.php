<?=CHtml::textField('stuff',$model->halfstuff_id,array('style'=>'display:none','id'=>'stuffId'))?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($model->getRelated('stuffStruct') as $value) {?>
            <tr>
                <td><?=CHtml::radioButton('Struct',false,array('class'=>'radioBtn'))?></td>
                <td><?=$value->getRelated('Struct')->name?></td>
                <td><?=CHtml::textField($value->getRelated('Struct')->product_id,'',array('style'=>'display:none','class'=>'products'))?></td>
            </tr>
        <?}?>
    </tbody>
</table>
<script>
    $('.radioBtn').click(function(){
        $(this).parent().parent().parent().children('tr').children("td:last-child").children('input').attr('style','display:none').attr('id','');
        $(this).parent().parent().children("td:last-child").children('input').attr('style','display:block').attr('id','productsVal');
    });
</script>