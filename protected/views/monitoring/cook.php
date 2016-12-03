<?$cnt = 1;?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>№</th>
            <th>Время заказа</th>
            <th>Официант</th>
            <th>Стол №</th>
            <th>Сумма</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($model as $val) {?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$val['order_date']?></td>
            <td><?=$val['name']?></td>
            <td><?=$val['table']?></td>
            <td>
                <?=CHtml::link('<i class="fa fa-print"></i>  Печать',array('/monitoring/printCheck?exp='.$val['expense_id']),array('class'=>'btn btnPrint'))?>
                <?=CHtml::link($val['expense_id'],'#',array('style'=>'display:none','class'=>'expId'))?>
                <button type="button" class="btn btn-danger closeCheck" >Закрыт счет</button>
                <button type="button" class="btn btn-warning closeDebt" >Закрыт счет как долг</button>
                <button type="button" class="btn btn-primary closeTerm" data-toggle="modal" data-target="#modal-sm">Оплата по терминалу</button>
            </td>
        </tr>
    <?  $cnt++;
    }
    ?>
    </tbody>
</table>
<script>
    $(document).ready(function(){
    });


</script>