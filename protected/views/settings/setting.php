<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'employee-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<br>
<div class="form-group">
    <label class="col-sm-2" for="">Наименование заведение</label>
    <div class="col-sm-2"><input type="text" name="setting[name]" value="<?=Yii::app()->config->get("name")?>" class="form-control"></div>
</div>
<div class="form-group">
    <label class="col-sm-2" for="">Процент</label>
    <div class="col-sm-2"><input type="text" name="setting[percent]" value="<?=Yii::app()->config->get("percent")?>" class="form-control"></div>
</div>
<div class="form-group">
    <label class="col-sm-2" for="">Язык принтера</label>
    <div class="col-sm-2"><input type="text" name="setting[printerLang]" value="<?=Yii::app()->config->get("printerLang")?>" class="form-control"></div>
</div>
<div class="form-group">
    <label class="col-sm-2" for="">Процент официантов</label>
    <div class="col-sm-2"><input type="text" name="setting[waiterSalary]" value="<?=Yii::app()->config->get("waiterSalary")?>" class="form-control"></div>
</div>
<div class="form-group">
    <label class="col-sm-2" for="">Интерфейс принтера</label>
    <div class="col-sm-2"><input type="text" name="setting[printer_interface]" value="<?=Yii::app()->config->get("printer_interface")?>" class="form-control"></div>
</div>

<div class="form-group">
    <div class="col-sm-2"></div>
    <div class="checkbox col-sm-2">

        <label >
            <input type="checkbox" id="banket" name="setting[banket]" value="1" <?=(Yii::app()->config->get("banket") == "0") ? "" : "checked"?>  >Банкет
        </label>
    </div>
</div>

<div class="form-group <?=(Yii::app()->config->get("banket") == "0") ? "hidden": ""?>" id="banket_percent" >
    <label class="col-sm-2" for="">Процент банкета</label>
    <div class="col-sm-2"><input type="text" name="setting[banket_percent]" value="<?=Yii::app()->config->get("banket_percent")?>" class="form-control"></div>
</div>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>$model->isNewRecord ? 'Сохранить' : 'Сохранить',
	)); ?>
</div>

<?php $this->endWidget(); ?>
<script>
    $(document).on("click","#banket", function () {
        var n = $( "#banket:checked" ).length;
        if(n != 0){
            $("#banket_percent").removeClass("hidden");
        }
        else{
            $("#banket_percent").addClass("hidden");
        }
    })
</script>
