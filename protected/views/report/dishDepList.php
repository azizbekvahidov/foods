
<style>
    thead{
        background-color:white;
    }
</style><? $count = 1;?>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th>Кол-во</th>
            <th>Себестоимость (<?=number_format(array_sum($cost),0,',',' ')?> сум)</th>
            <th>Выручка (<?=number_format(array_sum($summ),0,',',' ')?> сум)</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($dishes as $key => $val) {?>
        <tr>
            <td><?=$count?></td>
            <td><?=$val?></td>
            <td><?=$counting[$key]?></td>
            <td><?=number_format($cost[$key],0,',',' ')?></td>
            <td><?=number_format($summ[$key],0,',',' ')?></td>
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