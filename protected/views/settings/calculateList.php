<? $dishes = new Dishes(); $measure = new Measurement(); $prod = new Products(); $stuff = new Halfstaff(); $count = 1;?>
<table class="table">
    <tr>
    <?foreach ($model as $val) { $cnt = 1?>
        <?if(($count%4)+1 == 0){?>
            </tr>
        <?}?>
        <td>
            <h4><?=$count?>. <?=$val['name']?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Наименование</th>
                        <th>Кол-во</th>
                    </tr>
                </thead>
            <?foreach($dishes->getProd($val['dish_id']) as $key => $value){?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$prod->getName($key)?></td>
                    <td><?=$value?></td>
                </tr>
            <? $cnt++;}?>
            </table>
        </td>
        <?if($count%3 == 0){?>
            <tr>
        <?}?>
    <?$count++;}?>
    </tr>
</table>