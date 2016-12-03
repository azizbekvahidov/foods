<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'inexpense-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	// 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<?php echo $form->errorSummary($model); ?>
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(

            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?></div>
<?php echo $form->dropDownList($model,'department_id',CHtml::listData(Department::model()->findAll(),'department_id','name'),array('empty'=>'--Выберите отдел--','class'=>'span3')); ?> &nbsp;  &nbsp; 
<?php echo CHtml::dropDownList('stuff','',CHtml::listData(Halfstaff::model()->findAll(),'halfstuff_id','name'),array('empty'=>'--Выберите полуфабрикат--','class'=>'span3'))?>

  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js" type="text/javascript"></script>
<div id="data">
    <table id="structList" class="table table-striped table-hover ">
        <thead>
            <tr>
                <th style="text-align:center;">Название продукта</th>
                <th style="text-align:center;">Количество</th>
                <th></th>
                <th style="text-align:center;">Удалить</th>
            </tr>
        </thead>
        <tbody>
            <tr style="display: none;"></tr>
        </tbody>
    </table>
</div>
<script>
    $(document).on("click", ".deleteRow", function() {
        var temp_id = $(this).parent().parent().children('td:first-child').children('input').val(); 
        $(this).parent().parent().remove();
    });
    $('#Inexpense_department_id').change(function(){
        var depId = $(this).val(); 
        $.ajax({
           type: "POST",
           url: "<?php echo Yii::app()->createUrl('inexpense/listStuff'); ?>",
           data: "depId="+depId,
           success: function(data){
            $('#structList tbody').html(data);
            }
        });
    });
    $('#stuff').chosen({
        no_results_text: "Oops, nothing found!",
    }).change(function(){
        var prodValue = $(this).val();
        var prodText = $(this).children('option:selected').text();
        if(prodValue != ''){
            $('#structList tr:last').after("\
                <tr>\
                    <td style='text-align:center;'><input type='text' style='display: none;' name='product_id[]' value='"+prodValue+"' />"+prodText+"</td>\
                    <td style='text-align:center;'><input type='text'  name='count[]' /></td>\
                    <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a>\
                </tr>\
            ");
        }
    })
</script>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
        <button type="button" class="btn info" style="float: right;" id="excel">Расчитать затраты</button>
</div>
<script>

    $('#excel').click(function(){
        var data = $('#inexpense-form').serialize();
        $.ajax({
           type: "GET",
           url: "<?php echo Yii::app()->createUrl('inexpense/ProdList'); ?>",
           data: "data="+data+'&excel',
           success: function(data){
            $('#myModalBody').html(data);
           }
        });
    });
    $(document).on('click','#ok',function(){
        var prodValue = $("#productsVal").val(),
            prodId = $("#productsVal").attr('name'),
            stuffValue = $("#stuffId").val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('inexpense/count'); ?>",
            data: "prodVal="+prodValue+'&stuffId='+stuffValue+'&prodId='+prodId,
            success: function(data){
                $('#'+stuffValue).children('input').val(data);
                //$('#modalBody').html(data);
            }
        });

    });
    jQuery(document).on('click','#excel',function(){
		$("#myModalHeader").html('Расчитанные данные');
		$("#myModal").modal();
		return false;
	});

    jQuery(document).on('click','#ingridient',function(){
        $("#modalHeader").html('Выбрать ингридиент');
        $("#modal").modal();
        return false;
    });
    
</script>
<?php $this->endWidget(); ?>
<?php  $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4 id="myModalHeader">Modal header</h4>
    </div>

    <div class="modal-body" id="myModalBody">
        <p>One fine body...</p>
    </div>

    <div class="modal-footer">
        <?php  $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Close',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
        <button type="button" class="btn btn-success" id="export">Экспорт</button>
    </div>

<?php  $this->endWidget(); ?>

<script>
    $('#export').click(function(){
        $('#dataTable').table2excel({
            name: "Excel Document Name"
        }); 
    });
</script>
<?php  $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'modal')
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modalHeader">Modal header</h4>
</div>

<div class="modal-body" id="modalBody">
    <p>One fine body...</p>
</div>

<div class="modal-footer">
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'Close',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
    <?php  $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => 'Ok',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal','id'=>'ok','class'=>"btn btn-success"),
        )
    ); ?>
    <!--<button type="button" class="btn btn-success" id="ok">Ok</button>-->
</div>

<?php  $this->endWidget(); ?>
