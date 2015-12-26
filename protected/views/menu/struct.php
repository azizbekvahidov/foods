<? $prices = new Prices()?>
<?php echo CHtml::dropDownList('dish_id','',$dishList,array('empty' => '--Выберите блюда--','class'=>'','id'=>'dish')); ?>&nbsp; &nbsp;
<?php echo CHtml::dropDownList('halfstuff_id','',$stuffList,array('empty' => '--Выберите полуфабрикат--','class'=>'','id'=>'halfstuff')); ?>&nbsp; &nbsp;
<?php echo CHtml::dropDownList('product_id','',$prodList,array('empty' => '--Выберите продукт--','class'=>'','id'=>'product')); ?>&nbsp; &nbsp;
</div>
<br /><br />
<div class="form-group">
    <table id="menuList" class="table  table-hover table-bordered ">
        <thead>
            <tr>
                <th style="text-align:center;">Название продукта</th>
                <th style="text-align:center;">Цена</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            
            <? if(!empty($dishModel))?>
                <? foreach($dishModel as $value){

                ?>
                    <tr>
                        <td style='text-align:center;'>
                            <input type='text' class="menuId" style='display: none;' name='dish[menu_id][]' value='<?=$value->menu_id?>' />
                            <input type='text' style='display: none;' name='dish[type][]' value='<?=$id?>' />
                            <input type='text' style='display: none;' name='dish[id][]' value='<?=$value->getRelated('dish')->dish_id?>' /><?=$value->getRelated('dish')->name?></td>
                        <td style='text-align:center;'><input type="text" class='span2' value="<?=$prices->getPrice($value->getRelated('dish')->dish_id,$mType,1,date('Y-m-d'));?>" name="dish[price][]"/></td>
                        <td style='text-align:center;'>
                            <select name="dish[dep][]" class="span2">
                            <? foreach($listDep as $key => $dep){?>
                                <? if($key == $value->getRelated('dish')->department_id){?>
                                <option value="<?=$key?>" selected="selected"><?=$dep?></option>
                                <? } else{?>
                                <option value="<?=$key?>"><?=$dep?></option>
                                <? }?>
                            <? }?>
                            </select>
                        </td>
                        <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a></td>
                        
                    </tr>
                <?}?>
                <? if(!empty($prodModel))?>
                <? foreach($prodModel as $value){?>
                        <tr>
                            
                            <td style='text-align:center;'>
                                <input type='text' class="menuId" style='display: none;' name='product[menu_id][]' value='<?=$value->menu_id?>' />
                                <input type='text' style='display: none;' name='product[type][]' value='<?=$id?>' />
                                <input type='text' style='display: none;' name='product[id][]' value='<?=$value->getRelated('products')->product_id?>' /><?=$value->getRelated('products')->name?>
                            </td>
                            <td style='text-align:center;'><input class='span2' type="text" value="<?=$prices->getPrice($value->getRelated('products')->product_id,$mType,3,date('Y-m-d'));?>" name="product[price][]"/></td>
                            <td style='text-align:center;'>
                                <select name="product[dep][]" class="span2">
                                <? foreach($listDep as $key => $dep){?>
                                    <? if($key == $value->getRelated('products')->department_id){?>
                                    <option value="<?=$key?>" selected="selected"><?=$dep?></option>
                                    <? } else{?>
                                    <option value="<?=$key?>"><?=$dep?></option>
                                    <? }?>
                                <? }?>
                                </select>
                            </td>
                            <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a></td>
                        </tr>
                <?}?>
            <? if(!empty($stuffModel))?>
                <? foreach($stuffModel as $value){?>
                        <tr>
                            
                            <td style='text-align:center;'>
                                <input type='text' class="menuId" style='display: none;' name='stuff[menu_id][]' value='<?=$value->menu_id?>' />
                                <input type='text' style='display: none;' name='stuff[type][]' value='<?=$id?>' />
                                <input type='text' style='display: none;' name='stuff[id][]' value='<?=$value->getRelated('halfstuff')->halfstuff_id?>' /><?=$value->getRelated('halfstuff')->name?></td>
                            <td style='text-align:center;'><input class='span2' type="text" value="<?=$prices->getPrice($value->getRelated('halfstuff')->halfstuff_id,$mType,2,date('Y-m-d'));?>" name="stuff[price][]"/></td>
                            <td style='text-align:center;'>
                                <select name="stuff[dep][]" class="span2">
                                <? foreach($listDep as $key => $dep){?>
                                    <? if($key == $value->getRelated('halfstuff')->department_id){?>
                                    <option value="<?=$key?>" selected="selected"><?=$dep?></option>
                                    <? } else{?>
                                    <option value="<?=$key?>"><?=$dep?></option>
                                    <? }?>
                                <? }?>
                                </select>
                            </td>
                            <td style='text-align:center;'><a href='javascript:;' class = 'deleteRow'><i class='icon-trash '></i></a></td>
                        </tr>
                <?}?>
            
        </tbody>
    </table>
</div>
<script>
    <? foreach($listDep as $key => $val){?>
        optionData += "<option value=<?=$key?>><?=$val?></option>"
    <?}?>
    $("#dish").chosen({
        no_results_text: "Oops, nothing found!",
    });
    $("#halfstuff").chosen({
        no_results_text: "Oops, nothing found!",
    });
    $("#product").chosen({
        no_results_text: "Oops, nothing found!",
    });    
    $("#dish").change(function(){    
        var prodValue = $(this).val();
        var prodText = $(this).children('option:selected').text();
        if(prodValue != ''){
            $("#menuList tr:last").after("\
                <tr>\
                    <td style='text-align:center;'><input type='text' style='display: none;' name='dish[type][]' value='<?=$id?>' />\
                        <input type='text' style='display: none;' name='dish[id][]' value='"+prodValue+"' />"+prodText+"</td>\
                    <td style='text-align:center;'><input class='span2' type='text' name='dish[price][]' /></td>\
                    <td style='text-align:center;'><select class='span2' name='dish[dep][]' >"+optionData+"</select</td>\
                    <td style='text-align:center;'><a href='javascript:;' class = 'Rowdelete'><i class='icon-trash '></i></a></td>\
                </tr>\
            ");
        }
    });
    $("#halfstuff").change(function(){
        var prodValue = $(this).val();
        var prodText = $(this).children('option:selected').text();
        if(prodValue != ''){
            $("#menuList tr:last").after("\
                <tr>\
                    <td style='text-align:center;'><input type='text' style='display: none;' name='stuff[type][]' value='<?=$id?>' />\
                    <input type='text' style='display: none;' name='stuff[id][]' value='"+prodValue+"' />"+prodText+"</td>\
                    <td style='text-align:center;'><input class='span2' type='text' name='stuff[price][]' /></td>\
                    <td style='text-align:center;'><select class='span2' name='stuff[dep][]' >"+optionData+"</select</td>\
                    <td style='text-align:center;'><a href='javascript:;' class = 'Rowdelete'><i class='icon-trash '></i></a></td>\
                </tr>\
            ");
        }
    });
    $("#product").change(function(){
        var prodValue = $(this).val();
        var prodText = $(this).children('option:selected').text();
        if(prodValue != ''){
            $("#menuList tr:last").after("<tr>\
                    <td style='text-align:center;'><input type='text' style='display: none;' name='product[type][]' value='<?=$id?>' />\
                    <input type='text' style='display: none;' name='product[id][]' value='"+prodValue+"' />"+prodText+"</td>\
                    <td style='text-align:center;'><input class='span2' type='text' name='product[price][]' /></td>\
                    <td style='text-align:center;'><select class='span2' name='product[dep][]' >"+optionData+"</select</td>\
                    <td style='text-align:center;'><a href='javascript:;' class = 'Rowdelete'><i class='icon-trash '></i></a></td>\
                </tr>\
            ");
        }
    });
    $(document).on("click", ".Rowdelete", function() {
        $(this).parent().parent().remove();
    });
     $(document).on("click", ".deleteRow", function() {
        var thisId = $(this).parent().parent().children('td:first-child').children('input:first-child').val();
        console.log(thisId);
        if(!confirm('Вы уверены, что хотите удалить данный элемент?')) return false;
        	var th = this,
        		afterDelete = function(){};
                jQuery(this).parent().parent().remove()
        	jQuery.ajax({
        		type: 'POST',
        		url: "<?php echo Yii::app()->createUrl('menu/delete'); ?>?id="+thisId+"",
        		success: function(data) {
        			//jQuery('#dataTable').yiiGridView('update');
        			afterDelete(th, true, data);
        		},
        		error: function(XHR) {
        			return afterDelete(th, false, XHR);
        		}
        	});
        	return false;
    });
</script>