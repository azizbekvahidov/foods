<? $func = new Employee()?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Зарплата</th>
            <th>Задолжность</th>
            <th>Итого</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th colspan="4" class="text-center">Повар</th>
        </tr>
        <?foreach($cook as $val){?>
            <tr>
                <td><?=$val["name"]?></td>
                <td><?=$val["salary"]?></td>
                <td></td>
                <td></td>
            </tr>
        <?}?>
        <tr>
            <th colspan="4" class="text-center">Официанты</th>
        </tr>
        <?foreach($waiter as $val){?>
            <tr>
                <td><?=$val["name"]?></td>
                <td><?=$func->getWaiterSalary()?></td>
                <td></td>
                <td></td>
            </tr>
        <?}?>
    </tbody>
</table>