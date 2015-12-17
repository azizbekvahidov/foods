<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'dishes-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
    <label>С   <?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'name' => 'from',

            'options' => array(
                'format' => 'yyyy-mm-dd',
                'language' => 'ru',
                'autoclose'=> true
            )
        )
    );
    ?>По <?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'name' => 'to',

            'options' => array(
                'format' => 'yyyy-mm-dd',
                'language' => 'ru',
                'autoclose'=> true
            )
        )
    );
    ?>   <button id="update" class="btn btn-success">Обновить</button>
    </label>
<?php $this->endWidget(); ?>