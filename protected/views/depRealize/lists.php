
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css"/>
<table class="items table table-striped table-hover dataTable table-bordered no-footer ">
        <thead>
            <tr>
                <th>Название Продукта</th>
                <th>Количество</th>
                <th>Количество перемешаемого продукта</th>
            </tr>
        </thead>
        <tbody>
            <?  foreach($products as $key => $val){?>
            <tr class="<?=$key?>">
                <td><?=$val?></td>
                <td class="count"><?=number_format( $endCount[$key],2)?></td>
                <td><input type="text" class="depCount" name="products[<?=$key?>]" /></td>
            </tr>
            <? } ?>
            
        </tbody>
    </table>
    <script>
function str_split ( str, len ) {
			
	str = str.split('');
	if ( !len ) { return str; }

	var r = [];
	for( var k=0;k<(str.length/len); k++ ) {
		r[k] = '';
	}
	for( var k in str ) {
		r[ Math.floor(k/len) ] += str[k];
	}
return r;
};
function implode( glue, pieces ) {	
	return ( ( pieces instanceof Array ) ? pieces.join ( glue ) : pieces );
}

function changeToFloat($number){
        var $ss = $number;
        $arr = null;
        $arr = str_split($ss,1);
        var $k = 0;
        while($k != $ss.length)
        {
            if ($arr[$k] == ',')
                $arr[$k] = '.';
            $k++;
        } 
        $ss = implode('',$arr);
        return $ss;    
}
    $(document).ready(function() {
        $('.dataTable').DataTable({
                responsive: true,      
                "lengthMenu": [[ -1], ["Все"]]       
        });
        $('.depCount').change(function(){
            var curCount = $(this).parent().parent().children('td.count');
            var depCount = changeToFloat($(this).val());
            var summ = curCount.text()-depCount;
            if(summ < 0){
                alert('В складе нет столько товара');
                $(this).val('');
            }
            /*else{
                curCount.text(summ);
            }*/
        });
    });
</script>  