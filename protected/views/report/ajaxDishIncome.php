<?
    if(empty($dishCnt)){
        $dishCnt = 0;
    }
    else{
        $dishCnt = array_sum($dishCnt);
    }
    if(empty($stuffCnt)){
        $stuffCnt = 0;
    }
    else{
        $stuffCnt = array_sum($stuffCnt);
    }
    if(empty($prodCnt)){
        $prodCnt = 0;
    }
    else{
        $prodCnt = array_sum($prodCnt);
    }
?>
<style>
    thead{
        background-color:white;
    }
</style><? $cnt = 1;?><table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Наимен. блюда</th>
            <th>Кол-во</th>
            <th>наценка факт (<?=number_format(($dishCnt+$stuffCnt+$prodCnt),0,',',' ')?> сум)</th>
            <th>удельный вес в общей наценке (%)</th>
        </tr>
    </thead>
    <tbody>
        <?if(!empty($dishes)) {
            foreach ($dishes as $key => $val) {
                ?>
                <tr>
                    <td><?= $cnt ?></td>
                    <td><?= $val ?></td>
                    <td><?= $dCount[$key] ?></td>
                    <td><?= number_format($dishCnt[$key], 0, ',', ' ') ?></td>
                    <td><?= number_format($dishCnt[$key] / ($dishCnt + $stuffCnt + $prodCnt) * 100, 2, ',', '') ?></td>
                </tr>
                <?$cnt++;
            }
        }?>
        <?if(!empty($halfstuff)) {
            foreach ($halfstuff as $key => $val) {
                ?>
                <tr>
                    <td><?= $cnt ?></td>
                    <td><?= $val ?></td>
                    <td><?= $sCount[$key] ?></td>
                    <td><?= number_format($stuffCnt[$key], 0, ',', ' ') ?></td>
                    <td><?= number_format($stuffCnt[$key] / ($dishCnt + $stuffCnt + $prodCnt) * 100, 2, ',', '') ?></td>
                </tr>
                <?$cnt++;
            }
        }?>
        <?if(!empty($products)) {
            foreach ($products as $key => $val) {
                ?>
                <tr>
                    <td><?= $cnt ?></td>
                    <td><?= $val ?></td>
                    <td><?= $pCount[$key] ?></td>
                    <td><?= number_format($prodCnt[$key], 0, ',', ' ') ?></td>
                    <td><?= number_format($prodCnt[$key] / ($dishCnt + $stuffCnt + $prodCnt) * 100, 2, ',', '') ?></td>
                </tr>
                <?$cnt++;
            }
        }?>
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