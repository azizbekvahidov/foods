
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'dishes-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<h2>Заявка на <?=date('Y-m-d',strtotime($dates)+86400)?> <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'id'=>'createButton',
        'type'=>'primary',
        'label'=>'Сохранить',
    )); ?> </h2>
    <div>
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th></th>
                    <th>Наименование</th>
                    <th>план на завтра</th>
                </tr>
            </thead>
            <tbody>
                <?foreach($dish as $val){?>
                <tr>
                    <td></td>
                    <td><?=$val['name']?></td>
                    <td><input type="text" value="<?=$dCount[$val['just_id']]?>" name="dish[<?=$val['just_id']?>]"> <div class="hidden"><?=$dCount[$val['just_id']]?></div></td>
                </tr>
                <?}?>
                <?foreach($stuff as $val){?>
                    <tr>
                        <td></td>
                        <td><?=$val['name']?></td>
                        <td><input type="text" value="<?=$sCount[$val['just_id']]?>" name="stuff[<?=$val['just_id']?>]"><div class="hidden"><?=$sCount[$val['just_id']]?></div></td>
                    </tr>
                <?}?>
                <?foreach($prod as $val){?>
                    <tr>
                        <td></td>
                        <td><?=$val['name']?></td>
                        <td><input type="text" value="<?=$pCount[$val['just_id']]?>" name="prod[<?=$val['just_id']?>]"> <div class="hidden"><?=$pCount[$val['just_id']]?></div></td>
                    </tr>
                <?}?>
            </tbody>
        </table>
    </div>

<?php $this->endWidget(); ?>
<script>
    jQuery(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 2, "desc" ]],
            "paging": false,
            responsive: true,
            "lengthMenu": [[ -1,10, 25, 50, 100,], [ "Все",10, 25, 50, 100,]]
        });
    });
</script>