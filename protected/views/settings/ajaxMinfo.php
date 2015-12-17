<? if(empty($mInfo)){?>
    <div>
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
            'id'=>'dishes-form',
            'type'=>'horizontal',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>false,
            // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
        )); ?>
        <div>
            <input name="Info[dates]" value="<?=$dates?>" hidden="hidden" style="display: none" />
            <div class="control-group">
                <label class="control-label" for="inputEmail">Выручка за сегодня</label>
                <div class="controls">
                    <input type="number" name="Info[proceed]" id="inputEmail" placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputPassword">Приход за сегодня</label>
                <div class="controls">
                    <input type="number" name="Info[parish]" id="inputPassword" placeholder="">
                </div>
            </div>
        </div>
            <div class="control-group">
                <div class="controls">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType'=>'submit',
                    'id'=>'createButton',
                    'type'=>'primary',
                    'label'=>'Сохранить',
                )); ?>
                </div>
            </div>

        <?php $this->endWidget(); ?>
    </div>
<?} else{?>
    <div style="text-align: center">
        <h1 class="alert alert-success">Данные на эту дату уже введены !!!</h1>
    </div>
    <div class="control-group">
        <h4>Выручка за сегодня - "<?=number_format($mInfo->proceed,0,',',' ')?>" сум</h4>
        <h4>Приход за сегодня - "<?=number_format($mInfo->parish,0,',',' ')?>" сум</h4>
    </div>
<?}?>