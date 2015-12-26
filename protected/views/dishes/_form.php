<div id="dishCreate">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    	'id'=>'dishes-form',
    	// Please note: When you enable ajax validation, make sure the corresponding
    	// controller action is handling ajax validation correctly.
    	// There is a call to performAjaxValidation() commented in generated controller code.
    	// See class documentation of CActiveForm for details on this.
    	'enableAjaxValidation'=>false,
    	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    )); ?>
    
    	<p class="note">Поля с <span class="required">*</span> Объязательны.</p>
    <div>
    	<?php echo $form->errorSummary($model); ?>
<div class="form-group">
        <?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>100)); ?><br />
        <div class="span4">
            <?php echo $form->textFieldRow($model,'count',array('class'=>'span3','maxlength'=>100)); ?>
        </div>
    </div>
    <div class="span10" >
        <div class="span3" >
            <h3>Продукты</h3>
            <?php echo $form->dropDownList($model,'dishStruct',$prodList,array('class'=>'span2 left all_product listbox','id'=>'all_product','empty'=>'--Выберите продукт--'));?>
            
        </div>
        <div class="span3" >
            <h3>Полуфабрикаты</h3>
            <?php echo $form->dropDownList($model,'halfstuff',$stuffList,array('class'=>'span2 left all_halfstuff listbox','id'=>'all_halfstuff','empty'=>'--Выберите полуфабрикат--')); ?>
            
       </div>
       <div class="form-group">
        <table id="structList" class="table table-striped table-hover ">
            <thead>
                <tr>
                    <th style="text-align:center;">Название продукта</th>
                    <th style="text-align:center;">Пропорция</th>
                    <th style="text-align:center;">Удалить</th>
                </tr>
            </thead>
            <tbody>
                <tr style="display: none;"></tr>
                <? if($chosenProd != ''){?>
                    <? foreach($chosenProd as $key => $val){
                        $struct = $val->getRelated('Struct');?>
                        <tr>
                            <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='<?=$val['product_id']?>' /><?=$val['name']?></td>
                            <td style='text-align:center;'><input class='span1' type='text' name='prod[]' value="<?=$struct[0]['amount']?>" /></td>
                            <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
                        </tr>
                    <?}?> 
                <?}?>

                <? if($chosenStuff != ''){?>
                    <? foreach($chosenStuff as $key => $val){
                        $struct = $val->getRelated('Struct');?>
                        <tr>
                            <td style='text-align:center;'><input type='text' style='display: none;' name='stuff_id[]' value='<?=$val['halfstuff_id']?>' /><?=$val['name']?></td>
                            <td style='text-align:center;'><input class='span1' type='text' name='stuff[]' value="<?=$struct[0]['amount']?>" /></td>
                            <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>
                        </tr>
                    <?}?> 
                <?}?>
            </tbody>
        </table>
    </div>
       
       <script>
       $(document).on("click", ".deleteRow", function() {
            var temp_id = $(this).parent().parent().children('td:first-child').children('input').val(); 
            $(this).parent().parent().remove();
        });
        $("#all_product").chosen({
            no_results_text: "Oops, nothing found!",
        }).change(function(){
            var prodValue = $(this).val();
            var prodText = $(this).children('option:selected').text();
            if(prodValue != ''){
                $('#structList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                        <td style='text-align:center;'><input class='span1' type='text' name='prod[]' /></td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
            }
        }); 
        $("#all_halfstuff").chosen({
            no_results_text: "Oops, nothing found!",
        }).change(function(){
            var stuffValue = $(this).val();
            var stuffText = $(this).children('option:selected').text();
            if(stuffValue != ''){
                $('#structList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='stuff_id[]' value='"+stuffValue+"' />"+stuffText+"</td>\
                        <td style='text-align:center;'><input class='span1' type='text' name='stuff[]' /></td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
            }
        }); 
        $("#all_dishes").chosen({
            no_results_text: "Oops, nothing found!",
        }).change(function(){
            var dishValue = $(this).val();
            var dishText = $(this).children('option:selected').text();
            if(dishValue != ''){
                $('#structList tr:last').after("\
                    <tr>\
                        <td style='text-align:center;'><input type='text' style='display: none;' name='dish_id[]' value='"+dishValue+"' />"+dishText+"</td>\
                        <td style='text-align:center;'><input class='span1' type='text' name='dish[]' /></td>\
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                    </tr>\
                ");
            }
        }); 
      </script>
      
    </div>
</div>
    <div class="form-actions span9">
    	<?php $this->widget('bootstrap.widgets.TbButton', array(
    			'buttonType'=>'submit',
                'id'=>'createButton',
    			'type'=>'primary',
    			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
    		)); ?>
    </div>
    
    <?php $this->endWidget(); ?>
</div>
  
<?php
Yii::app()->clientScript->registerScript("multiselect", "

     
    $('form').submit(function() {
        
        //$('.all_product option').prop('selected',false);
        //$('').prop('selected','selected');
        //$('.all_halfstuff option').prop('selected',false);
        //$('.all_halfstuff option').prop('selected','selected');
        
//        alert($(this).serialize());
    });
");
?>
