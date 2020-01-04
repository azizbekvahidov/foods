<? $dish = new Dishes(); $stuff = new Halfstaff(); $prod = new Products(); ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Время</th>
            <th>Действие</th>
            <th colspan="2">Детали</th>
        </tr>
    </thead>
    <tbody>
        <? foreach($model as $value){
            $tempArray = explode("*",$value["archive_message"]);
            $result = array();
            if(!empty($tempArray[1])){
                $temp = explode("=>",$tempArray[1]);
                $order = explode(",",$temp[1]);
                foreach ($order as $val) {
                    if(!empty($val)){
                        $cnt = explode(":",$val);
                        if($temp[0] == "dish")
                            $result[$dish->getName($cnt["0"])] = $cnt[1];
                        if($temp[0] == "stuff")
                            $result[$stuff->getName($cnt["0"])] = $cnt[1];
                        if($temp[0] == "prod")
                            $result[$prod->getName($cnt["0"])] = $cnt[1];
                    }
                }
            }
            if(!empty($tempArray[2])){
                $temp = explode("=>",$tempArray[2]);
                $order = explode(",",$temp[1]);
                foreach ($order as $val) {
                    if(!empty($val)){
                        $cnt = explode(":",$val);
                        if($temp[0] == "dish")
                            $result[$dish->getName($cnt["0"])] = $cnt[1];
                        if($temp[0] == "stuff")
                            $result[$stuff->getName($cnt["0"])] = $cnt[1];
                        if($temp[0] == "prod")
                            $result[$prod->getName($cnt["0"])] = $cnt[1];
                    }
                }
            }
            if(!empty($tempArray[3])){
                $temp = explode("=>",$tempArray[3]);
                $order = explode(",",$temp[1]);
                foreach ($order as $val) {
                    if(!empty($val)){
                        $cnt = explode(":",$val);
                        if($temp[0] == "dish")
                            $result[$dish->getName($cnt["0"])] = $cnt[1];
                        if($temp[0] == "stuff")
                            $result[$stuff->getName($cnt["0"])] = $cnt[1];
                        if($temp[0] == "prod")
                            $result[$prod->getName($cnt["0"])] = $cnt[1];
                    }
                }
            }
            ?>
            <tr>
                <td><?=$value["archive_date"]?></td>
                <td>
                    <?
                        switch ($value["archive_action"]){
                            case "create":
                                echo "Добавление";
                                break;
                            case "update":
                                echo "Изменение";
                                break;
                            case "print":
                                echo "Печать";
                                break;
                            case "removeFromOrder":
                                echo "Удалено из счета";
                                break;
                        }
                    ?>
                </td>
                <td>
                        <? foreach ($result as $key => $value){?>
                            <ul>
                                <li><?=$key?> - <?=$value?></li>
                            </ul>
                        <?}?>
                </td>
            </tr>
        <?}?>
    </tbody>
</table>