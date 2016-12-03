<? $func = new Expense();?>
<style>
    .order{
        margin-left: 10px;
    }
</style>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Наименование блюда</th>
            <th>действия</th>

        </tr>
    </thead>
    <tbody>
        <?foreach ($model as $value) { $temp = count($value->getRelated('order')); $number = $func->getOrderNumber($value->expense_id);?>
        <?if($temp != $number){?>
        <tr>
            <td style="text-align: center" colspan="2"><h5><?=$value->getRelated('mType')->name?> - (<?=$value->getRelated('employee')->name?>)</h5></td>
        </tr>
            <?foreach ($value->getRelated('order') as $val) {?>
            <tr class="order" id="<?=$val->expense_id?>-<?=$val->order_id?>">
                <td><?=$val->getRelated('dish')->name?></td>
                <td>
                    <? if($val->status == 0){?>
                        <button class="btn btn-info begin" type="button">Принял</button>
                    <? }elseif($val->status == 1){?>
                        <button class="btn btn-danger end" type="button">Выполнил</button>
                    <? }elseif($val->status == 2){?>
                        <span class="ok badge badge-success"><i class="fa fa-check"></i> Готово</span>
                    <? }?>
                </td>
            </tr>
            <?}?>
        <?}?>
        <?}?>
    </tbody>
</table>
<script>
    $('.begin').click(function(){
        var orderId = $(this).parent().parent().attr('id');
        $.ajax({
            type: "GET",
            data: 'id='+orderId+'&empId='+<?=Yii::app()->user->getId()?>,
            url: "<?=Yii::app()->createUrl('/cooking/default/begin'); ?>"
        });
    });
    $('.end').click(function(){
        var orderId = $(this).parent().parent().attr('id');
        $.ajax({
            type: "GET",
            data: 'id='+orderId+'&empId='+<?=Yii::app()->user->getId()?>,
            url: "<?=Yii::app()->createUrl('/cooking/default/end'); ?>"
        });
    });
</script>