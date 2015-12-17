<style>

    thead{
        background-color:white;
    }
</style>
<table class="table table-bordered" id="dataTable">
    <thead>
    <tr>
        <th ></th>
        <th></th>
        <th>Весь расход на сумму <?=number_format(array_sum($outProdSumm),0,',',' ')?></th>
        <th>Весь приход на сумму <?=number_format(array_sum($prodSumm),0,',',' ')?></th>
    </tr>
    <tr>
        <th></th>
        <th>Название</th>
        <th>Расходное количество</th>
        <th>Приходное количество</th>
    </tr>
    </thead>
    <tbody><? $cnt = 1; $all = 0;?>
    <?foreach ($prodModel as $value) {?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$value->name?></td>
            <td><?=$products[$value->product_id] != 0 ? number_format($products[$value->product_id],2,',',' ') : ''?></td>
            <td><?=$prodCount[$value->product_id] != 0 ? number_format($prodCount[$value->product_id],0,',',' ') : ''?></td>
        </tr>
    <?$cnt++;  ?>
    <?}
    ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
<script>
    $(document).ready(function(){
        $('#dataTable').DataTable({
            responsive: true,
            "lengthMenu": [[ -1,10, 25, 50, 100,], [ "Все",10, 25, 50, 100,]]
        });
    });
</script>
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