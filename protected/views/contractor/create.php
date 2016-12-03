
    <div class="btn-group pull-right" role="group" aria-label="...">
        <a href="/contractor/admin" class="btn btn-default btn-success">Администрирование</a>
    </div>

    <?php $box = $this->beginWidget(
        'bootstrap.widgets.TbBox',
        array(
            'title' => 'Добавить Контагента',
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

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
    <?php $this->endWidget(); ?>