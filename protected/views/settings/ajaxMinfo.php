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
                    <input type="number" name="Info[proceed]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputPassword">Приход за сегодня</label>
                <div class="controls">
                    <input type="number" name="Info[parish]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Терминал</label>
                <div class="controls">
                    <input type="number" name="Info[term]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Азиз терминал</label>
                <div class="controls">
                    <input type="number" name="Info[azizTerm]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Торт Шамс</label>
                <div class="controls">
                    <input type="number" name="Info[tortShams]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Мясо</label>
                <div class="controls">
                    <input type="number" name="Info[meat]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Прочие</label>
                <div class="controls">
                    <input type="number" name="Info[other]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Касса</label>
                <div class="controls">
                    <input type="number" name="Info[kassa]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Гос. банк</label>
                <div class="controls">
                    <input type="number" name="Info[gosBank]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Официант</label>
                <div class="controls">
                    <input type="number" name="Info[waitor]"  placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Ген. счет</label>
                <div class="controls">
                    <input type="number" name="Info[genDir]"  placeholder="">
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