<style>
    label{
        display: inline;
    }
    li{
        list-style: none;
        margin: 2px 0 2px 0;
    }
    .form-horizontal .controls{
        margin-left: 45px;
    }
    .checkbox{
        padding-left: 5px;
    }
</style>
<form id="checkForm" class="span3 form-horizontal">
        <div class="controls">
            <label class="checkbox"><?=CHtml::checkBox('',false,array('id'=>'checkAll'))?> Все</label>
        </div>
        <? foreach ($model as $val) {?>
            <div class="controls">
                <label class="checkbox"><?=CHtml::checkBox('empId[]',false,array('class'=>'empId','value'=>$val->employee_id))?> <?=$val->name?></label>
            </div>
        <?}?>
</form>
<div class="span3 pull-right">
    <?=CHtml::link('Сформировать печатную форму',array('print'),array('class'=>' btn pull-right','target'=>'target','id'=>'printBtn'))?>
</div>
<script>

    $('.empId').change(function(){
        var data = $('#checkForm').serialize();
        $('#printBtn').attr('href','/expense/print?'+data)
    })
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
        var data = $('#checkForm').serialize();
        $('#printBtn').attr('href','/expense/print?'+data)
    });
</script>