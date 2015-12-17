<style>

    thead{
        background-color:white;
    }
</style>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th>Количество</th>
            <th>Расход на порцию</th>
            <th>Расход на общий объем</th>
        </tr>
    </thead>
    <tbody><? $cnt = 1; $all = 0;?>
        <? foreach ($dishPorce as $key => $val) {?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$dishName[$key]?></td>
                <td><?=$dishOrder[$key]?></td>
                <td><?=number_format($val,2,',',' ')?></td>
                <td><?=number_format($dishOrder[$key]*$val,2,',',' ')?></td>
            </tr>
        <? $all = $all + $dishOrder[$key]*$val; $cnt++;}
        ?>
    <? foreach ($stuffPorce as $key => $val) {?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$stuffName[$key]?></td>
            <td><?=$stuffOrder[$key]?></td>
            <td><?=number_format($val,2,',',' ')?></td>
            <td><?=number_format($stuffOrder[$key]*$val,2,',',' ')?></td>
        </tr>
        <? $all = $all + $stuffOrder[$key]*$val; $cnt++;}
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Весь расход</th>
            <th><?=number_format($all,2,',',' ')?></th>
            <th colspan="">Весь приход</th>
            <th><?=number_format($realizeCount,2,',',' ')?></th>
        </tr>
    </tfoot>
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