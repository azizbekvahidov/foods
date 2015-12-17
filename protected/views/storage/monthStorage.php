<??>
<style>
    thead{
        background-color:white;
    }
    .tempTr{
        display: none;
    }
</style>
<table class="table table-bordered table-hover" id="dataTable">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Начальное сальдо</th>
            <th>Приход</th>
            <th>Расход</th>
            <th>План. кон. сальдо</th>
            <th>Факт. кон. сальдо</th>
            <th>Разница</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($tempDate as $value) {?>
        <tr class="trHead" id="<?=$value?>">
            <td><?=$value?></td>
            <td><?=number_format($startCount[$value],0,',',' ')?></td>
            <td><?=number_format($realized[$value],0,',',' ')?></td>
            <td><?=number_format($expenses[$value],0,',',' ')?></td>
            <td><?=number_format($endCount[$value],0,',',' ')?></td>
            <td><?=number_format($curEndCount[$value],0,',',' ')?></td>
            <td><?=number_format($endCount[$value]-$curEndCount[$value],0,',',' ')?></td>
        </tr>
            <tr class="tempTr">
                <td colspan="7"></td>
            </tr>
        <?}?>
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
    $(function(){
        $(".trHead").click(function(){
            var dates = $(this).attr('id');
            $(this).next('tr').toggle(function(){
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('storage/allStorage'); ?>",
                    data: 'dates='+dates,
                    success: function(data){
                        $('#'+dates).next('tr').children('td').html(data);
                    }

                });
            });/*
            */
        });
    })
</script>