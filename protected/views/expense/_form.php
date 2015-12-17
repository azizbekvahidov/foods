<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'expense-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<? $curDateTime =  date('Y-m-d H:i:s');?>
<?php echo $form->errorSummary($model); ?>
<?php echo $form->dropDownList($model,'employee_id',CHtml::listData(Employee::model()->findAll(),'employee_id','name'),array('class'=>'')); ?>
<?php echo $form->textFieldRow($model,'order_date',array('class'=>'','value'=>$curDateTime,'style'=>'display:none')); ?>
<?php echo $form->dropDownList($model,'table',array(1,2,3,4,5,6,7,8,9))?>
<?php echo $form->textFieldRow($model,'status',array('class'=>'','value'=>1,'style'=>'display:none')); ?>
<br /><br />

<table>
    <table class="table table-hover table-bordered" data-click-to-select="true">
        <thead>
        <tr>
            <th data-checkbox="true" style="text-align: center;">Название</th>
            <th style="text-align: center;">Цена</th>
            <th style="text-align: center;">Количество</th>
        </tr>
        </thead>
        <tbody>
        <? foreach(Dishtype::model()->findAll() as $val){?>
            <tr id="<?=$val['name']?>">
                <th colspan="3"><?=$val['name']?></th>
            </tr>
            <? if(!empty($dishModel))?>
            <? foreach($dishModel as $value){
                if($value->getRelated('dishType')->name == $val->name){?>
                    <tr>
                        <td><input value="1" hidden="" /><input class="checking" type="checkbox" name="1[id][]" value="<?=$value->getRelated('dish')->dish_id?>" /> <?=$value->getRelated('dish')->name?></td>
                        <td><?=$value->getRelated('dish')->price?></td>
                        <td style="text-align: center;" class="span3"></td>
                    </tr>
                <?}
            }?>
            <? if(!empty($stuffModel))?>
            <? foreach($stuffModel as $value){
                if($value->getRelated('dishType')->name == $val->name){?>
                    <tr>
                        <td><input value="2" hidden="" /><input class="checking" type="checkbox" name="2[id][]" value="<?=$value->getRelated('halfstuff')->halfstuff_id?>" /> <?=$value->getRelated('halfstuff')->name?></td>
                        <td><?=$value->getRelated('halfstuff')->price?></td>
                        <td style="text-align: center;" class="span3"></td>
                    </tr>
                <?}
            }?>
            <? if(!empty($prodModel))?>
            <? foreach($prodModel as $value){
                if($value->getRelated('dishType')->name == $val->name){?>
                    <tr>
                        <td><input value="3" hidden="" /><input class="checking" type="checkbox" name="3[id][]" value="<?=$value->getRelated('products')->product_id?>" /> <?=$value->getRelated('products')->name?></td>
                        <td><?=$value->getRelated('products')->price?></td>
                        <td style="text-align: center;" class="span3"></td>
                    </tr>
                <?}
            }?>
        <?}?>

        <script>
            $('.checking').click(function(){
                var type = $(this).parent().children('input:first').val();
                if($(this).prop('checked')){
                    $(this).parent().parent().children('td:last').append('<input name='+type+'[count][] type="text" />');
                }
                else{
                    $(this).parent().parent().children('td:last').children('input').remove();
                }

            });
        </script>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</table>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
    )); ?>
</div>

<?php $this->endWidget(); ?>
