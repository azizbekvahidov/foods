    <? $cnt = 1;
    foreach ($tables as $val) {?>
        <div class="col-md-2">
            <?if(!empty($curTables))
                foreach ($curTables as $keys => $value) {
                    if($value['table'] == $val['table_num']){
                        if($value['tables'] == $table){?>
                            <a class="modalTableBtn actived table-<?=$val['table_num']?>" href="#"><?=$val['table_num']?></a>
                        <?} else{?>
                            <a class="modalTableBtn disabled table-<?=$val['table_num']?>" href="#"><?=$val['table_num']?></a>
                    <?  }
                    }
                    else{?>
                        <a class="modalTableBtn table-<?=$val['table_num']?>" href="#"><?=$val['table_num']?></a>
                    <?}
                }?>
        </div>
        <?$cnt++;
    }
    ?>