
<style>
    .verticalText {
        -moz-transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        writing-mode: tb-rl;
        width: 25px;
        border: none!important;
        text-align: center!important;
        vertical-align: middle!important;
        height: 100px!important;
        padding: 0!important;
    }

     thead{
         background-color:white;
     }
</style>
<? if(!empty($curDish)) {?>
<table class="items table-bordered table table-striped table-hover dataTable no-footer" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <? $counter = 0; foreach($curDish as $value){?>
                <th class="verticalText"><?=$value?></th>

            <? $counter++;}?>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>выход в шт.</th>
            <? foreach($curDish as $key => $val){?>
            <td style="font-weight: bolder;"><?=number_format($dishCount[$key],2, ',', ' ')?></td>
            <? }?>
            <td></td>
        </tr>
        <tr><th style="text-align: center" colspan="<?=$counter+2?>">Продукты</th></tr>
        <? if(!empty($prodList)) ?>
        <? foreach($prodList as $value){ $count = 0;?>
        <tr>
            <td><?=$value->getRelated('products')->name?></td>
            <? foreach($curDish as $key => $val){
                                ?>
            <td style=""><?=number_format($products[$key][$value->prod_id],2, ',', ' ')?></td>
            <?
            $count = $count + $products[$key][$value->prod_id] ?>
            <? }
            ?>
            <td style="font-weight: bolder;"><?=number_format($count,2, ',', ' ');?></td>
        </tr>
        <? }?>
        <tr><th style="text-align: center" colspan="<?=$counter+2?>">Полуфабрикаты</th></tr>
        <? if(!empty($stuffList)) ?>
        <? foreach($stuffList as $value){ $count = 0;?>
            <tr>
                <td><?=$value->getRelated('stuff')->name?></td>
                <? foreach($curDish as $key => $val){
                    ?>
                    <td style=""><?=number_format($stuff[$key][$value->prod_id],2, ',', ' ')?></td>
                    <?
                    $count = $count + $stuff[$key][$value->prod_id] ?>
                <? }
                ?>
                <td style="font-weight: bolder;"><?=number_format($count,2, ',', ' ');?></td>
            </tr>
        <? }?>
    </tbody>
</table>
<? } else{?>
    <div style="text-align: center"><h2>Ничего не найдено</h2></div>
<?}?>
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