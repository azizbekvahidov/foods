
<style>
    thead{
        background-color:white;
    }
</style><?
$cnt = 1; 
$dates = date('Y-m-d'); 
$dish = new Dishes(); 
$stuff = new Halfstaff();
$prod = new Products();
$prices = new Prices();?>
<table class="table table-bordered table-hover" id="dataTable">
    <thead>
        <tr>
            <th></th>
            <th>Наименование блюд</th>
            <th>Себестоимость</th>
            <th>Установленная цена</th>
            <th>Установленная наценка</th>
            <th>Текущая наценка</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?foreach ($model as $value) {?>
            <?
            $costPrice = $dish->getCostPrice($value->just_id,$dates);
            $price = $prices->getPrice($value->just_id,$value->mType,$value->type,$dates);
            if($costPrice == 0)
                $costPrice = 1;
            $percent = ($price*100/$costPrice)-100;
            ?>
            <? if($percent < 50){?>
            <tr class="error">
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('dish')->name?></td>
                <td><?=number_format($costPrice,0,',',' ')?></td>
                <td><?=number_format($price,0,',',' ')?></td>
                <td>50%</td>
                <td><?=number_format(($price*100/$costPrice)-100,0,',',' ')?>%</td>
                <td><?=CHtml::link('<i class="icon-eye-open"></i>',array('/dishes/view?id='.$value->just_id),array('target'=>'_blank','class'=>'btn btn-success edit'));?></td>
            </tr>
            <?}else{?>
            <tr class="success">
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('dish')->name?></td>
                <td><?=number_format($costPrice,0,',',' ')?></td>
                <td><?=number_format($price,0,',',' ')?></td>
                <td>50%</td>
                <td><?=number_format(($price*100/$costPrice)-100,0,',',' ')?>%</td>
                <td><?=CHtml::link('<i class="icon-eye-open"></i>',array('/dishes/view?id='.$value->just_id),array('target'=>'_blank','class'=>'btn btn-success edit'));?></td>
            </tr>
            <?}?>
        <?$cnt++;}?>
        <?foreach ($model2 as $value) {?>
            <?
            $costPrice = $stuff->getCostPrice($value->just_id,$dates);
            $price = $prices->getPrice($value->just_id,$value->mType,$value->type,$dates);
            if($costPrice == 0)
                $costPrice = 1;
            $percent = ($price*100/$costPrice)-100;
            ?>
            <? if($percent < 50){?>
            <tr class="error">
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('halfstuff')->name?></td>
                <td><?=number_format($costPrice,0,',',' ')?></td>
                <td><?=number_format($price,0,',',' ')?></td>
                <td>50%</td>
                <td><?=number_format(($price*100/$costPrice)-100,0,',',' ')?>%</td>
                <td><?=CHtml::link('<i class="icon-eye-open"></i>',array('/halfstaff/view?id='.$value->just_id),array('target'=>'_blank','class'=>'btn btn-success edit'));?></td>
            </tr>
            <?}else{?>
            <tr class="success">
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('halfstuff')->name?></td>
                <td><?=number_format($costPrice,0,',',' ')?></td>
                <td><?=number_format($price,0,',',' ')?></td>
                <td>50%</td>
                <td><?=number_format(($price*100/$costPrice)-100,0,',',' ')?>%</td>
                <td><?=CHtml::link('<i class="icon-eye-open"></i>',array('/halfstaff/view?id='.$value->just_id),array('target'=>'_blank','class'=>'btn btn-success edit'));?></td>
            </tr>
            <?}?>
        <?$cnt++;}?>
        <?foreach ($model3 as $value) {?>
            <?
            $costPrice = $prod->getCostPrice($value->just_id,$dates);
            $price = $prices->getPrice($value->just_id,$value->mType,$value->type,$dates);
            if($costPrice == 0)
                $costPrice = 1;
            $percent = ($price*100/$costPrice)-100;
            ?>
            <? if($percent < 50){?>
            <tr class="error">
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('products')->name?></td>
                <td><?=number_format($costPrice,0,',',' ')?></td>
                <td><?=number_format($price,0,',',' ')?></td>
                <td>50%</td>
                <td><?=number_format(($price*100/$costPrice)-100,0,',',' ')?>%</td>
                <td><?=CHtml::link('<i class="icon-eye-open"></i>',array('/products/view?id='.$value->just_id),array('target'=>'_blank','class'=>'btn btn-success edit'));?></td>
            </tr>
            <?}else{?>
            <tr class="success">
                <td><?=$cnt?></td>
                <td><?=$value->getRelated('products')->name?></td>
                <td><?=number_format($costPrice,0,',',' ')?></td>
                <td><?=number_format($price,0,',',' ')?></td>
                <td>50%</td>
                <td><?=number_format(($price*100/$costPrice)-100,0,',',' ')?>%</td>
                <td><?=CHtml::link('<i class="icon-eye-open"></i>',array('/products/view?id='.$value->just_id),array('target'=>'_blank','class'=>'btn btn-success edit'));?></td>
            </tr>
            <?}?>
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

        jQuery(document).on('click','#dataTable a.view',function(){
    		data=$(this).attr("href").split("|")
    		$("#myModalHeader").html(data[1]);
    		$("#myModalBody").load("/dishes/view?id="+data[0]+"&asModal=true");
    		$("#myModal").modal();
    		return false;
    	});
</script>

<?php  $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
); ?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        
        <h4 id="myModalHeader">Modal header</h4> 
    </div>
 
    <div class="modal-body" id="myModalBody">
        <p>One fine body...</p>
    </div>
 
    <div class="modal-footer">
        <?php  $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Close',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
 
<?php  $this->endWidget(); ?>