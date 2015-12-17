<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'faktura-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    // 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?><style>
    td,th{
        text-align: center!important;
    }
</style>
    <div class="form-group">

        <?= CHtml::dropDownList('list','',$List,array('empty' => '--Выберите заявку--'))?>&nbsp; &nbsp;
<?= CHtml::dropDownList('products','',CHtml::listData(Products::model()->findAll(),'product_id','name'),array('empty' => '--Выберите продукт--','id'=>'product'))?>&nbsp; &nbsp;

        <div id="data"></div>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'id'=>'submitBtn',
            'type'=>'primary',
            'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
            'disabled'=>'disabled'
        )); ?>
    </div>
    <script>
        $(document).on("click", ".deleteRow", function() {
            $(this).parent().parent().remove();
        });
        $("#product").chosen({
            no_results_text: "Уупс, Ничего не найдено!"
        }).change(function(){
            var prodValue = $(this).val();
            var cnt = $('#dataTable tr:last td:first').text();
            if(prodValue != ''){

                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('faktura/ajaxSetReqList'); ?>",
                    data: 'id='+prodValue+'&cnt='+cnt,
                    success: function(data){
                        $('#dataTable tr:last').after(data);
                    }
                });
            }
        });
        $("#list").chosen({
            no_results_text: "Уупс, Ничего не найдено!"
        }).change(function(){
            $('#submitBtn').removeAttr('disabled').removeClass('disabled');
        });
        $("#list").change(function(){
            var list = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('faktura/ajaxSetRequest'); ?>",
                data: 'listId='+list,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
        jQuery(document).ready(function() {
            $('#dataTable').DataTable({
                responsive: true,
                "lengthMenu": [[ -1,10, 25, 50, 100,], [ "Все",10, 25, 50, 100,]]
            });
        });
    </script>
<?php $this->endWidget(); ?>