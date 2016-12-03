<? $dish = new Dishes(); $stuff = new Halfstaff(); $prod = new Products(); $cnt = 1;?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Кол-во</th>
        </tr>
    </thead>
    <tbody>
        <?foreach ($model as $val) {?>
        <tr>
            <td><?=$cnt?></td>
            <td><?
                if($val['type'] == 1){
                    echo $dish->getName($val['just_id']);
                }
                if($val['type'] == 2){
                    echo $stuff->getName($val['just_id']);
                }
                if($val['type'] == 3){
                    echo $prod->getName($val['just_id']);
                }
                ?>
            </td>
            <td><?=$val['count']?></td>
        </tr>
        <?$cnt++;}?>
        <?foreach ($model1 as $val) {?>
        <tr>
            <td><?=$cnt?></td>
            <td><?
                if($val['type'] == 1){
                    echo $dish->getName($val['just_id']);
                }
                if($val['type'] == 2){
                    echo $stuff->getName($val['just_id']);
                }
                if($val['type'] == 3){
                    echo $prod->getName($val['just_id']);
                }
                ?>
            </td>
            <td><?=$val['count']?></td>
        </tr>
        <?$cnt++;}?>
        <?foreach ($model2 as $val) {?>
        <tr>
            <td><?=$cnt?></td>
            <td><?
                if($val['type'] == 1){
                    echo $dish->getName($val['just_id']);
                }
                if($val['type'] == 2){
                    echo $stuff->getName($val['just_id']);
                }
                if($val['type'] == 3){
                    echo $prod->getName($val['just_id']);
                }
                ?>
            </td>
            <td><?=$val['count']?></td>
        </tr>
        <?$cnt++;}
        ?>
    </tbody>
</table>