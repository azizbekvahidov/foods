<div class="col-sm-6">
    <table class="table table-bordered col-sm-6">
        <thead>
            <tr>
                <th colspan="4">Получено</th>
            </tr>
            <tr>
                <th>От кого</th>
                <th>Наименование</th>
                <th>Кол-во</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody>
            <?foreach ($model as $val) {?>
            <tr>
                <td><?=$val['Cname']?></td>
                <td><?=$val['Pname']?></td>
                <td><?=$val['count']?></td>
                <td><?=$val['exchange_date']?></td>
            </tr>
            <?}
            ?>
        </tbody>
    </table>
</div>
<div class="col-sm-6">
    <table class="table table-bordered col-sm-6">
        <thead>
        <tr>
            <th colspan="4">Отдано</th>
        </tr>
        <tr>
            <th>Кому</th>
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        <?foreach ($model2 as $val) {?>
            <tr>
                <td><?=$val['Cname']?></td>
                <td><?=$val['Pname']?></td>
                <td><?=$val['count']?></td>
                <td><?=$val['exchange_date']?></td>
            </tr>
        <?}
        ?>
        </tbody>
    </table>
</div>