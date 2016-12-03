<? $emp = new Employee()?>
<div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span><?
    $this->widget(
        'bootstrap.widgets.TbDatePicker',
        array(
            'value'=>$dates,
            'name' => 'from',
            'options' => array(
                'language' => 'ru',
                'format' => 'yyyy-mm-dd',
            )
        )
    );
    ?>
</div>
<select name="" id="empId">
    <? foreach ($emp->getActiveEmpList() as $val) {?>
        <option value="<?= $val['employee_id'] ?>"><?=$val['name']?></option>
    <?}
    ?>
</select>
<a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
<span class="heading-title">Мониторинг пробитых заказов</span>
<div id="data"></div>
<script>
    $(document).ready(function(){
        var from,
            empId;
        $('#view').click(function(){
            from = $('#from').val();
            empId = $('#empId').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxEmpExpense'); ?>",
                data: "from="+from+"&empId="+empId,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>


<script>
    $(document).on('click','td',function(){
        $('tr').css("background-color","white");
        $(this).parent().css("background-color","#D9F2F5");
    });

</script>