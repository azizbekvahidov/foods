<?$cnt = 1; ?>
<style>
    thead{
        background-color:white;
    }
    .tempTr{
        display: none;
    }
</style>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Сумма (<?=number_format($model2['summ'],0,',',' ')?> сум)</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($model as $val) {?>
        <tr class="trHead" id="<?=$val['provider_id']?>">
            <td><?=$cnt?></td>
            <td><?=$val['name']?></td>
            <td><?=number_format($val['summ'],0,',',' ')?></td>
        </tr>
        <tr class="tempTr">
            <td colspan="3"></td>
        </tr>
    <?$cnt++;}?>
    </tbody>
</table>
<script>
    $(function(){
        $(".trHead").click(function(){
            var provId = $(this).attr('id');
            $(this).next('tr').toggle(function(){
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('faktura/provProdList'); ?>",
                    data: 'provId='+provId+'&from=<?=$from?>&to=<?=$to?>',
                    success: function(data){
                        $('#'+provId).next('tr').children('td').html(data);
                    }

                });
            });
        });
    })
</script>