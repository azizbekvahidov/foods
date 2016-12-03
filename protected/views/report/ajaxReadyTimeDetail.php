<? $func = new Functions()?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Наименование</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?foreach ($model as $val) {?>
            <tr>
                <td><?=$val['name']?></td>
                <? $time = $func->getRefuseTimes(1,$val['just_id'],$dates)?>
                <? for($i = 0; $i < 5; $i++){?>
                    <td><?=(!empty($time[$i]['dates'])) ? (date('H',$time[$i]['dates'])-6).":".date('i:s',$time[$i]['dates']) : 0?></td>
                <?}?>
            </tr>
        <?}
        ?>
        <?foreach ($model2 as $val) {?>
            <tr>
                <td><?=$val['name']?></td>
                <? $time = $func->getRefuseTimes(2,$val['just_id'],$dates)?>
                <? for($i = 0; $i < 5; $i++){?>
                    <td><?=(!empty($time[$i]['dates'])) ? (date('H',$time[$i]['dates'])-6).":".date('i:s',$time[$i]['dates']) : 0?></td>
                <?}?>
            </tr>
        <?}
        ?>
        <?foreach ($model3 as $val) {?>
            <tr>
                <td><?=$val['name']?></td>
                <? $time = $func->getRefuseTimes(3,$val['just_id'],$dates)?>
                <? for($i = 0; $i < 5; $i++){?>
                    <td><?=(!empty($time[$i]['dates'])) ? (date('H',$time[$i]['dates'])-6).":".date('i:s',$time[$i]['dates']) : 0?></td>
                <?}?>
            </tr>
        <?}
        ?>
    </tbody>
</table>