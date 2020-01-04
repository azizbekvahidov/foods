
<style>
    thead{
        background-color:white;
    }
</style><? $count = 1;?>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Себестоимость (<?=number_format($cost,0,',',' ')?> )</th>
            <th>Прибыль (<?=number_format($summ,0,',',' ')?> )</th>
        </tr>
    </thead>
    <tbody>
        <? if(!empty($dishes))  foreach ($dishes["summ"] as $key => $val) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$dishes["name"][$key]?></td>
            <td><?=$dishes["counting"][$key]?></td>
            <td><?=number_format($dishes["cost"][$key],0,',',' ')?></td>
            <td><?=number_format($dishes["summ"][$key],0,',',' ')?></td>
        </tr>
        <? $count++;}
        ?>
    <? if(!empty($stuffs)) foreach ($stuffs["summ"] as $key => $val) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$stuffs["name"][$key]?></td>
            <td><?=$stuffs["counting"][$key]?></td>
            <td><?=number_format($stuffs["cost"][$key],0,',',' ')?></td>
            <td><?=number_format($stuffs["summ"][$key],0,',',' ')?></td>
        </tr>
        <? $count++;}
    ?>
    <? if(!empty($prods)) foreach ($prods["summ"] as $key => $val) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$prods["name"][$key]?></td>
            <td><?=$prods["counting"][$key]?></td>
            <td><?=number_format($prods["cost"][$key],0,',',' ')?></td>
            <td><?=number_format($prods["summ"][$key],0,',',' ')?></td>
        </tr>
        <? $count++;}
    ?>
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