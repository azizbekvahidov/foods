<? $count = 1;?>
<table class="table table-bordered" id="dataTable">
    <thead>
    <tr>
        <th>#</th>
        <th>Название продукта</th>
        <th>Количество</th>
        <th>Отдел</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($model as $value) {
        foreach ($value->getRelated('inorder') as $val) {?>
            <tr>
                <td><?=$count?></td>
                <td><?=$val->getRelated('stuffs')->name?></td>
                <td><?=$depId[$value->department_id]?></td>
                <td><?=$val->count?> <?=$val->getRelated('stuffs')->getRelated('halfstuffType')->name?></td>
                <td><?=CHtml::link('<i class="icon-trash"></i>',array('deleteInorder&id='.$val->inorder_id),array('class'=>'delete'))?></td>
            </tr>
            <?$count++;}
    }?>
    </tbody>
</table>
<script>
    jQuery(document).on('click','#dataTable a.delete',function() {
        if(!confirm('Вы уверены, что хотите удалить данный элемент?')) return false;
        var th = this,
            afterDelete = function(){};
        jQuery(this).parent().parent().remove()
        jQuery.ajax({
            type: 'POST',
            url: jQuery(this).attr('href'),
            success: function(data) {
                //jQuery('#dataTable').yiiGridView('update');
                afterDelete(th, true, data);
            },
            error: function(XHR) {
                return afterDelete(th, false, XHR);
            }
        });
        return false;
    });
</script>