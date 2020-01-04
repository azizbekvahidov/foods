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
            <td style="font-weight: bold; font-size: 20px"><?=$val['Tname']?></td>
            <td><?=$val["expSum"]?></td>
            <td>
                <?=CHtml::link('<i class="fa fa-print"></i>  Печать',array('/monitoring/printCheck?exp='.$val['expense_id']),array('class'=>'btn btnPrint'))?>
                <?=CHtml::link($val['expense_id'],'#',array('style'=>'display:none','class'=>'expId'))?>
                <button type="button" class="btn btn-danger closeCheck" >Закрыт счет</button>
                <button type="button" class="btn btn-warning closeDebt" >Закрыт счет как долг</button>
                <button type="button" class="btn btn-primary closeTerm" data-toggle="modal" data-target="#modal-sm">Оплата по терминалу</button>
                <div class="col-sm-2">
                    <input type="number" id="<?=$val['expense_id']?>" class="form-control discount" placeholder="Скидка счета" value="<?=$val["discount"]?>">
                </div>
                <?=CHtml::link('<i class="fa fa-eye fa-fw"></i> Отказ',array('orders/orderRefuse?id='.$val['expense_id']))?>
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