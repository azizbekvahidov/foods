<? $cnt = 1;?>
<style>
    .modal{
        left:50%!important;
    }
    .modal-content{
        box-shadow: none!important;
        border: none!important;
    }
</style>
<div id="data"></div>
    <script src="/js/jquery.printPage.js"></script>

<script>
    var timer;

    function timers() {
        timer = setInterval(function(){
            refreshTable();
        },3000);
    }
    function refreshTable(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('monitoring/refreshTable'); ?>",
            success: function(data){
                $('#data').html(data);
            }
        });
    }
    $(document).ready(function(){
        $(".btnPrint").printPage();
        refreshTable();
        timers();

    });

    $(document).on("focus",".discount", function () {
        clearTimeout(timer);
    });
    $(document).on("focusout",".discount", function () {
        timers();
    });
    $(document).on('keyup',".discount", function (e) {
        if (e.keyCode == 13) {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('monitoring/setDiscount'); ?>",
                data: 'id='+$(this).attr("id")+"&val="+$(this).val(),
                success: function(data){
                    refreshTable();
                }
            });
            timers();
        }
    });
    $(document).on('click','.closeCheck',function(){
        var id = $(this).parent().children('a.expId').text();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('monitoring/closeExp'); ?>",
            data: 'id='+id,
            success: function(data){
                refreshTable();
            }
        });
    });
    $(document).on('click','.brnPrint',function(){

    });
    $(document).on('click','.closeDebt',function(){
        clearTimeout(timer);
        var id = $(this).parent().children('a.expId').text();
            $("#ModalHeader").html("Комментприй для долга");
            $("#ModalBody > input").val(id);
            $("#Modal").modal();
            return true;
    });
    $(document).on('click','#comment',function(){
        var id =  $("#ModalBody > input").val(),
            text =  $("#ModalBody textarea").val(),
            payed = $("#ModalBody > div > input").val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('monitoring/closeDebt'); ?>",
            data: 'id='+id+'&text='+text+'&payed='+payed,
            success: function(data){
                $("#ModalBody textarea").val('');
                $("#ModalBody input").val('');
                refreshTable();
            }
        });
    });
    $(document).on('click','.closeTerm',function(){
        var id = $(this).parent().children('a.expId').text();
        $("#expIdFSum").val(id);
    });
    $(document).on('click','#saveTerm',function(){
        var id =  $("#expIdFSum").val(),
            term = $("#termSum").val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('monitoring/closeTerm'); ?>",
            data: 'id='+id+'&term='+term,
            success: function(data){
                $("#termSum").val('');
                $("#expIdFSum").val('');
                refreshTable();
                $('#modal-sm').modal('hide');
            }
        });
    });
</script>
    <div class="modal fade bs-example-modal-sm" id="modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">сумма терминал</h4>
            </div>
            <div class="modal-content">
                <input type="text" value="" id="expIdFSum" style="display: none">
                <input type="number" id="termSum" class="form-control"/>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveTerm" class="btn btn-primary">Сохранить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
<?php $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'Modal')
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4 id="ModalHeader">Modal header</h4>
    </div>

    <div class="modal-body" id="ModalBody">
        <input type="text" value="" style="display: none ">
        <div class="form-group">
            <textarea class=' form-control' placeholder="Комментарий к долгу"  ></textarea>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" placeholder="Оплаченная часть" />
        </div>
    </div>

    <div class="modal-footer">
        <?php  $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Ok',
                'url' => '#',
                'htmlOptions' => array('id'=>'comment','data-dismiss' => 'modal','class'=>'btn btn-success'),
            )
        ); ?>
        <?php  $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Отмена',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

<?php  $this->endWidget(); ?>