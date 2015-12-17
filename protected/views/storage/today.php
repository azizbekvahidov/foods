<div style="float: right;">
    <?//=CHtml::link('Просмотреть остатки',array(),array('class'=>'btn btn-default'))?>
</div>
<style>
    thead{
        background-color:white;
    }
</style>
<? $count = 1;?>
<table id="dataTable" class="items table-bordered table table-striped table-hover dataTable no-footer">
    <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th>Начальное сальдо</th>
            <th>Приход</th>
            <th>Расход</th>
            <th>Внутренний расход</th>
            <th>Конечное сальдо</th>
        </tr>
    </thead>
    <tbody>
        <? foreach($model as $value){?>
        <tr>
            <td><?=$count?></td>
            <td><?=$value->getRelated('products')->name?></td>
            <td><?=number_format( $value->startCount, 2 )?></td>
            <td><?=number_format( $inProducts[$value->prod_id], 2 )?></td>
            <td><?=number_format( $outProducts[$value->prod_id], 2 )?></td>
            <td><?=number_format( $inOutProducts[$value->prod_id], 2 )?></td>
            <td><?=number_format( $endProducts[$value->prod_id], 2 )?></td>
        </tr>
        <? $count++;}?>
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