
<style>
    thead{
        background-color:white;
    }
</style>
<? $count = 1;?>
<table class="items table-bordered table table-striped table-hover dataTable no-footer" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th style="text-align: center;">Название</th>
            <th style="text-align: center;">Начальное сальдо</th>
            <th style="text-align: center;">Приход</th>
            <th style="text-align: center;">Расход</th>
            <th style="text-align: center;">Расход на загатовки</th>
            <th style="text-align: center; width: 100px;">Перемещение
                <table>
                    <tr style="border-top: 1px solid #ccc;">
                        <th style="text-align: center; border: 0; background: none;">Приход</th>
                        <th style="text-align: center; background: none;">Расход</th>
                    </tr>
                </table>
            </th>
            <th style="text-align: center;">Конечное сальдо</th>
        </tr>
    </thead>
    <tbody>


    <? if(!empty($curProd)){ ?>
        <tr>
            <th colspan="8">Продукты</th>
        </tr>
        <? foreach($curProd as $value){?>
            <tr>
                <td><?=$count?></td>
                <td><?=$value->getRelated('products')->name?></td>
                <td><?=number_format( $value->startCount,2,',','')?></td>
                <td><?=number_format( $inProduct[$value->prod_id],2,',','')?></td>
                <td><?=number_format( $outProduct[$value->prod_id],2,',','')?></td>
                <td><?=number_format( $outStuffProd[$value->prod_id],2,',','')?></td>
                <td>
                    <table>
                        <tr>
                            <th style="width:70px; padding: 0!important; text-align: center; border: 0; background: none;"><?=number_format( $depIn[$value->prod_id],2,',','')?></th>
                            <th style="width:70px; padding: 0!important; text-align: center; background: none;"><?=number_format( $depOut[$value->prod_id],2,',','')?></th>
                        </tr>
                    </table>
                </td>
                <td>
                    <?=number_format( $value->startCount+$inProduct[$value->prod_id]-$outProduct[$value->prod_id]-$outStuffProd[$value->prod_id]+$depIn[$value->prod_id]-$depOut[$value->prod_id],2,',','')?>
                </td>
            </tr>
            <? $count++;
            
        }
    }?>
        <?  if(!empty($curStuff)){?>
            <tr>
                <th colspan="8">Полуфабрикаты</th>
            </tr>
            <?foreach($curStuff as $value){ ?>
                <? //if(number_format( $value->startCount,2) != 0 || number_format( $inProduct[$value->prod_id],2) != 0 || number_format( $outProduct[$value->prod_id],2) != 0){?>
                <tr>
                    <td><?=$count?></td>
                    <td><?=$value->getRelated('stuff')->name?></td>
                    <td><?=number_format( $value->startCount,2,',','')?></td>
                    <td><?=number_format( $inStuff[$value->prod_id],2,',','')?></td>
                    <td><?=number_format( $outStuff[$value->prod_id],2,',','')?></td>
                    <td><?=number_format(0,2,',','')?></td>
                    <td>
                        <table>
                            <tr>
                                <th style="width:70px; padding: 0!important; text-align: center; border: 0; background: none;"><?=number_format( $depStuffIn[$value->prod_id],2,',','')?></th>
                                <th style="width:70px; padding: 0!important; text-align: center; background: none;"><?=number_format( $depStuffOut[$value->prod_id],2,',','')?></th>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <?=number_format( $value->startCount+$inStuff[$value->prod_id]+$depStuffIn[$value->prod_id]-$outStuff[$value->prod_id]-$depStuffOut[$value->prod_id],2,',','')?>
                    </td>
                </tr>
                <? $count++;
                //}
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