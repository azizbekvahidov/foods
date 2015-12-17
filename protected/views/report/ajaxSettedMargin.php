<? $cnt = 1; $dish = new Dishes(); $stuff = new Halfstaff(); $prod = new Products();$prices = new Prices();?>
<style>
    .green{
        color: green;
    }
    .red{
        color: red;
    }
    .rait{
        padding: 0 8px!important;
    }
    thead{
        background-color: #ffffff;
    }
</style>
<table class="table table-bordered table-stripped" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Наимен. блюда</th>
            <th>Себестоимость (сум)</th>
            <th>Цена реализации (сум)</th>
            <th>наценка план (%)</th>
            <th>наценка факт (%)</th>
            <th>отклон (+,-)</th>
            <th>тренд ср.стат.</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($model as $val) {
            $beforeDates = date('Y-m-d',strtotime($dates)-86400);
            $costPrice = $dish->getCostPrice($val['just_id'],$dates);
            $price = $prices->getPrice($val['just_id'],$val['mType'],$val['type'],$dates);
            $beforeCostPrice = $dish->getCostPrice($val['just_id'],$beforeDates);
            $beorePrice = $prices->getPrice($val['just_id'],$val['mType'],$val['type'],$beforeDates);
            if($costPrice == 0 ){
                $margin = (($price*100)/1)-100;
            }
            elseif($price == 0){
                $margin = ((1*100)/$costPrice)-100;
            }
            else{
                $margin = (($price*100)/$costPrice)-100;
            }

            if($beforeCostPrice == 0 ){
                $beforeMargin = (($beorePrice*100)/1)-100;
            }
            elseif($beorePrice == 0){
                $beforeMargin = ((1*100)/$beforeCostPrice)-100;
            }
            else{
                $beforeMargin = (($beorePrice*100)/$beforeCostPrice)-100;
            }
            ?>
        <tr>
            <th><?=$cnt?></th>
            <th><?=$val['name']?></th>
            <th><?=number_format($costPrice,0,',',' ')?></th>
            <th><?=number_format($price,0,',',' ')?></th>
            <th>50</th>
            <th><?=number_format($margin,0,',',' ')?></th>
            <th><?=number_format($margin-50,0,',',' ')?></th>
            <th >
                <?if(number_format($margin-50,0,',',' ') == number_format($beforeMargin-50,0,',',' ')){?>

                <?}elseif(number_format($margin-50,0,',',' ') > number_format($beforeMargin-50,0,',',' ')){?>
                    <span class="green"><i class="fa fa-caret-up"></i></span>
                <?}elseif(number_format($margin-50,0,',',' ') < number_format($beforeMargin-50,0,',',' ')){?>
                    <span class="red"><i class="fa fa-caret-down"></i></span>
                <?}?>
            </th>
        </tr>
        <?$cnt++;}?>
        <? foreach ($model2 as $val) {
            $beforeDates = date('Y-m-d',strtotime($dates)-86400);
            $costPrice = $stuff->getCostPrice($val['just_id'],$dates);
            $price = $prices->getPrice($val['just_id'],$val['mType'],$val['type'],$dates);
            $beforeCostPrice = $stuff->getCostPrice($val['just_id'],$beforeDates);
            $beorePrice = $prices->getPrice($val['just_id'],$val['mType'],$val['type'],$beforeDates);
            if($costPrice == 0 ){
                $margin = (($price*100)/1)-100;
            }
            elseif($price == 0){
                $margin = ((1*100)/$costPrice)-100;
            }
            else{
                $margin = (($price*100)/$costPrice)-100;
            }

            if($beforeCostPrice == 0 ){
                $beforeMargin = (($beorePrice*100)/1)-100;
            }
            elseif($beorePrice == 0){
                $beforeMargin = ((1*100)/$beforeCostPrice)-100;
            }
            else{
                $beforeMargin = (($beorePrice*100)/$beforeCostPrice)-100;
            }
            ?>
            <tr>
                <th><?=$cnt?></th>
                <th><?=$val['name']?></th>
                <th><?=number_format($costPrice,0,',',' ')?></th>
                <th><?=number_format($price,0,',',' ')?></th>
                <th>50</th>
                <th><?=number_format($margin,0,',',' ')?></th>
                <th><?=number_format($margin-50,0,',',' ')?></th>
                <th >
                    <?if(number_format($margin-50,0,',',' ') == number_format($beforeMargin-50,0,',',' ')){?>

                    <?}elseif(number_format($margin-50,0,',',' ') > number_format($beforeMargin-50,0,',',' ')){?>
                        <span class="green"><i class="fa fa-caret-up"></i></span>
                    <?}elseif(number_format($margin-50,0,',',' ') < number_format($beforeMargin-50,0,',',' ')){?>
                        <span class="red"><i class="fa fa-caret-down"></i></span>
                    <?}?>
                </th>
            </tr>
            <?$cnt++;}?>
        <? foreach ($model3 as $val) {
            $beforeDates = date('Y-m-d',strtotime($dates)-86400);
            $costPrice = $prod->getCostPrice($val['just_id'],$dates);
            $price = $prices->getPrice($val['just_id'],$val['mType'],$val['type'],$dates);
            $beforeCostPrice = $prod->getCostPrice($val['just_id'],$beforeDates);
            $beorePrice = $prices->getPrice($val['just_id'],$val['mType'],$val['type'],$beforeDates);
            if($costPrice == 0 ){
                $margin = (($price*100)/1)-100;
            }
            elseif($price == 0){
                $margin = ((1*100)/$costPrice)-100;
            }
            else{
                $margin = (($price*100)/$costPrice)-100;
            }

            if($beforeCostPrice == 0 ){
                $beforeMargin = (($beorePrice*100)/1)-100;
            }
            elseif($beorePrice == 0){
                $beforeMargin = ((1*100)/$beforeCostPrice)-100;
            }
            else{
                $beforeMargin = (($beorePrice*100)/$beforeCostPrice)-100;
            }
            ?>
            <tr>
                <th><?=$cnt?></th>
                <th><?=$val['name']?></th>
                <th><?=number_format($costPrice,0,',',' ')?></th>
                <th><?=number_format($price,0,',',' ')?></th>
                <th>50</th>
                <th><?=number_format($margin,0,',',' ')?></th>
                <th><?=number_format($margin-50,0,',',' ')?></th>
                <th >
                    <?if(number_format($margin-50,0,',',' ') == number_format($beforeMargin-50,0,',',' ')){?>

                    <?}elseif(number_format($margin-50,0,',',' ') > number_format($beforeMargin-50,0,',',' ')){?>
                        <span class="green"><i class="fa fa-caret-up"></i></span>
                    <?}elseif(number_format($margin-50,0,',',' ') < number_format($beforeMargin-50,0,',',' ')){?>
                        <span class="red"><i class="fa fa-caret-down"></i></span>
                    <?}?>
                </th>
            </tr>
            <?$cnt++;}?>
    </tbody>
</table>
<div id="bottom_anchor"></div>
<script>
    function moveScroll(){
        var scroll = $(window).scrollTop();
        var anchor_top = $("#dataTable").offset().top;
        var anchor_bottom = $("#bottom_anchor").offset().top;
        if (scroll>anchor_top && scroll<anchor_bottom) {
            clone_table = $("#clone");
            if(clone_table.length == 0){
                clone_table = $("#dataTable").clone();
                clone_table.attr('id', 'clone');
                clone_table.css({position:'fixed',
                    'pointer-events': 'none',
                    top:0});
                clone_table.width($("#dataTable").width());
                $("#content").append(clone_table);
                $("#clone").css({visibility:'hidden'});
                $("#clone thead").css({'visibility':'visible','pointer-events':'auto'});
            }
        } else {
            $("#clone").remove();
        }
    }
    $(window).scroll(moveScroll);

</script>