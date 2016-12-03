<div class="btn-group pull-right" role="group" aria-label="...">
    <a href="/orderPoint/create" class="btn btn-default btn-success">Добавить</a>
    <a href="/orderPoint/admin" class="btn btn-default btn-success">Администрирование</a>
</div>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Создание точек',
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
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'dishes-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<form class="col-sm-5">
    <div class="form-group">
        <label for="exampleInputEmail1">Наименование</label>
        <input type="text" name="point[name]" class="form-control" placeholder="Наименование">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Логин</label>
        <input type="text" name="point[login]" class="form-control" placeholder="Логин">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Пароль</label>
        <input type="password" name="point[password]" class="form-control" placeholder="Пароль">
    </div>
    <button type="submit" class="btn btn-default">Сохранить</button>
</form>

    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>