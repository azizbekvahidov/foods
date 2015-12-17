<? $cnt = 1; $product = new Products(); $faktura = new Faktura()?>
<style>
    thead{
        background-color:white;
    }
</style>
<table id="dataTable" class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Цена</th>
            <? foreach ($dep as $val) {?>
                <th><?=$val?></th>
            <?}?>
            <th>Прочие</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($model as $val) {?>
            <tr>
                <td><?=$cnt?></td>
                <td><a href='javascript:;' class = 'deleteRow'><i class='fa fa-times '></i></a> <?=$val['Pname']?> (<?=$val['Mname']?>)</td>
                <td><input class='span1' type='text' name='price[<?=$val['prod_id']?>]' value="<?=$product->getCostPrice($val['prod_id'],$dates)?>" /></td>
                <? foreach ($dep as $key => $value) {?>
                    <td><input class='span1' type='text' name='request[<?=$key?>][<?=$val['prod_id']?>][count]' value="<?=$faktura->getReqCount($val['request_id'],$key,$val['prod_id'])?>" /></td>
                <?}?>
                <td><input class='span1' type='text' name='request[0][<?=$val['prod_id']?>][count]' value="<?=$faktura->getReqCount($val['request_id'],0,$val['prod_id'])?>" /></td>
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
<script>
    jQuery(document).ready(function() {
        $('#dataTable').DataTable({
            "paging":   false
        });
    });
</script>