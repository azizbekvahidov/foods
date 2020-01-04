<? $count = 1; $expense = new Expense(); $curPercent = 0; $summaP = 0; $summa = 0;?>
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Счет №</th>
        <th>Дата и время</th>
        <th>Стол</th>
        <!--<th>Сумма</th>
        <th></th>-->
    </tr>
    </thead>
    <tbody>
    <? foreach( $model as $value){?>
        <? if($value->getRelated('employee')->check_percent == 1)
            $curPercent = $percent;
        else
            $curPercent = 0;
            
            $temp = $expense->getExpenseSum($value->expense_id,$dates);
        ?>

        <tr>

            <td><span style="display: none"><?=$value->expense_id?></span><?=$count?></td>
            <td><?=$value->expense_id?></td>
            <td><?=$value->order_date?></td>
            <td><?=$value->table?></td>
            <!--<td><?=number_format($temp/100*$curPercent + $temp,0,'.',','); $summaP = $summaP + $temp/100*$curPercent + $temp?></td>-->
            <!--<td>
                <a style="padding: 3px 5px;" href="/expense/printExpCheck?exp=<?=$value->expense_id?>" type="button" name="button" class="btn btn-info expCheck" data-dismiss="modal">Напечатать</a>
            </td>-->
        </tr>
        <? $count++; } ?>
    </tbody>
    <tfoot>
    <!--<tr>
        <th colspan="4">Общая сумма</th>
        <th colspan="2"><?=$summaP?></th>
        <th></th>
    </tr>-->
    </tfoot>
</table>
<style>
    .modal-dialog{
        margin: 0 auto!important;
    }
</style>