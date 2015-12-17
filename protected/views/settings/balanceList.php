<? $cnt = 1;?>

<form id="forms">
    <?  if($check['CurEndCount'] == null){ ?>
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th></th>
                <th>Название</th>
                <th>Факт. кол-во
                    <button style="float: right" type="button" class="btn btn-success" id="submit">Сохранить</button></th>
            </tr>
        </thead>
        <tbody>
        <? if($types == 0){?>
            <?foreach ($model as $val) {?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$val['Pname']?> <input type="text" name="prod_id[]" style="display: none" value="<?=$val['prod_id']?>"></td>
                <td><input type="text" name="count[]"> <?=$val['Mname']?></td>
            </tr>
            <?$cnt++;}
            ?>
        <?}?>
        <? if($types == 1){?>
            <?foreach ($model as $val) {?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$val['Pname']?> <input type="text" name="prod_id[]" style="display: none" value="<?=$val['prod_id']?>"></td>
                    <td><input type="text" name="pcount[]"> <?=$val['Mname']?></td>
                </tr>
                <?$cnt++;}
            ?>
            <?foreach ($model0 as $val) {?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$val['Pname']?> <input type="text" name="stuff_id[]" style="display: none" value="<?=$val['prod_id']?>"></td>
                    <td><input type="text" name="hcount[]"> <?=$val['Mname']?></td>
                </tr>
                <?$cnt++;}
            ?>
        <?}?>
        </tbody>
    </table>
    <?} else{?>
        <div>Данные на эту дату уже существует</div>
    <?}?>
</form>
<script>
    jQuery(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            "lengthMenu": [[ -1,10, 25, 50, 100,], [ "Все",10, 25, 50, 100,]]
        });
    });
    $(document).ready(function(){
        $('#submit').click(function(){
            var vals = $('#forms').serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('settings/ajaxbalance'); ?>",
                data: vals+'&types=<?=$types?>&depId=<?=$depId?>&dates=<?=$dates?>',
                success: function(){
                    $('#data').html('');
                }
            });
        })
    });
</script>