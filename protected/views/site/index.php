<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<h1><?php echo "Кафе (".CHtml::encode(Yii::app()->name).")"; ?></h1>
<div style="text-align: center">
<label>Выберите месяц</label> <?
$this->widget(
    'bootstrap.widgets.TbDatePicker',
    array(
        'name' => 'monthPicker',

        'options' => array(
            'format' => 'yyyy-m',
            'startView'=> 1,
            'minViewMode'=> 1,
            'language' => 'ru',
            'autoclose'=> true
        )
    )
);
?></div>
<script>
    $(document).ready(function(){
        var dates = '<?=date("Y-n")?>'
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('site/proceed'); ?>",
            data: "dates="+dates,
            success: function(data){
                $('#data').html(data);
            }
        });
    })
    $(document).on('change','#monthPicker',function(){
        var dates = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('site/proceed'); ?>",
            data: "dates="+dates,
            success: function(data){
                $('#data').html(data);
            }
        });
    })
</script>
<div id="data"></div>
<div style="text-align: center;  position: absolute; top: 50px; right: 30px;">
            <? if(!empty($coockieModel)){
				if($coockieModel['coockie_end'] == null ){?>
					<?=CHtml::link('Расчитать отделы',array('storage/endDepBalance'),array('class'=>'btn btn-large btn-danger span2'))?>
					<?=CHtml::link('Расчитать склад',array('storage/end?sess=coockie'),array('class'=>'btn btn-large btn-danger span2'))?>
				<? }else{}

            } elseif(empty($coockieModel)){
			if($beforeCoockie['coockie_end'] == null){?>
                <?=CHtml::link('Расчитать отделы<br>(за вчера)',array('storage/beforeEndDepBalance?dates='.$beforeCoockie['coockie_date']),array('class'=>'btn btn-large btn-danger span2'))?>
                <?=CHtml::link('Расчитать склад<br>(за вчера)',array('storage/beforeEnd?sess=coockie&dates='.$beforeCoockie['coockie_date']),array('class'=>'btn btn-large btn-danger span2'))?>
            <? }else{?>
                <?=CHtml::link('Начать день',array('storage/start?sess=coockie'),array('class'=>'btn btn-large btn-success span2'))?>
            <?}}?>
        </div>