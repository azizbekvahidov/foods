<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => 'Установка пропорций' ,
        'headerIcon' => 'icon- fa fa-plus-circle',
        'headerButtons' => array(
        	array(
            	'class' => 'bootstrap.widgets.TbButtonGroup',
            	'type' => 'success',
            	// '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            	'buttons' => $this->menu
            )
        )        
    )
);?>
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
		    'block'=>false, // display a larger alert block?
		    'fade'=>true, // use transitions?
		    'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
		    'alerts'=>array( // configurations per alert type
		        'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		        'danger'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), //success, info, warning, error or danger
		    ),
		));
		?>
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
            'htmlOptions'=>array(
                          'class'=>'form-inline',
                        )
        )); ?>
<div class="form-group span12">
            <div>
                <div style="width: 50%; float: left;" >
                    <h3>Пропорция продуктов</h3>
                    <? foreach($this->chosenProduct as $key => $val){
                        ?><div class="form-group">
                            <label><?=$val?></label> 
                            <input class="form-control span-2" type="text" name="prod[<?=$key?>]" />
                        </div><br />
                    <?}?>
                </div>
            </div>
  </div>
            <div class="clear"></div>
            <div class="form-actions span9">
            	<?php $this->widget('bootstrap.widgets.TbButton', array(
            			'buttonType'=>'submit',
                        'id'=>'saveButton',
            			'type'=>'primary',
            			'label'=>'Сохранить',
            		)); ?>
            </div>
        <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>