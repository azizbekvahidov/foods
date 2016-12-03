<div class="btn-group pull-right" role="group" aria-label="...">
    <a href="/orderPoint/create" class="btn btn-default btn-success">Добавить</a>
    <a href="/orderPoint/admin" class="btn btn-default btn-success">Администрирование</a>
</div>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Администрирование точек',
        'headerIcon' => 'icon- fa fa-tasks',
        'headerButtons' => array(
            array(
                'class' => 'bootstrap.widgets.TbButtonGroup',
                'type' => 'success',
                // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'buttons' => $this->menu
            ),
        )
    )
);?>
<table class="items table table-striped table-hover dataTable table-bordered" id="dataTable">
    <thead>
    <tr>
        <th>№</th>
        <th>#</th>
        <th>Название</th>
        <th>Логин</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? $count = 1; foreach($model as $val){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$val['point_id']?></td>
            <td><?=$val['name']?></td>
            <td><?=$val['login']?></td>
            <td>
                <?=CHtml::link('<i class="icon-pencil"></i>',array('update?id='.$val['point_id']),array('class'=>'update'))?>
                <?=CHtml::link('<i class="icon-trash"></i>',array('delete?id='.$val['point_id']),array('class'=>'delete'))?>
            </td>
        </tr>
        <? $count++;}?>

    </tbody>
</table>
<script>
    jQuery(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            "lengthMenu": [[ -1,10, 25, 50, 100,], [ "Все",10, 25, 50, 100,]]
        });
    });
    jQuery(document).on('click','#dataTable a.view',function(){
        data=$(this).attr("href").split("|")
        $("#myModalHeader").html(data[1]);
        $("#myModalBody").load("/dishes/view?id="+data[0]+"&asModal=true");
        $("#myModal").modal();
        return false;
    });
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
<?php $this->endWidget(); ?>