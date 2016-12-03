<? $first = '';?>
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?foreach ($dates as $key => $val) {
            if($key == 0){ $first = $val?>
                <li role="presentation" class="active tabs"><a href="<?=$val?>"  role="tab" data-toggle="tab"><?=$val?></a></li>
            <?}else{?>
                <li role="presentation" class="tabs"><a href="<?=$val?>"  role="tab" data-toggle="tab"><?=$val?></a></li>
        <?  }
        }
        ?>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="cont">

        </div>
    </div>

</div>
<script>
    $(document).ready(function(){
        var dep = $('#dep').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('report/ajaxReadyTimeDetail'); ?>",
            data: "id=<?=$first?>&depId="+dep,
            success: function(data){
                $('#cont').html(data);
            }
        });
    });
    $(document).on('click','.tabs', function(){
        var id = $(this).children('a').attr('href');
        var dep = $('#dep').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('report/ajaxReadyTimeDetail'); ?>",
            data: "id="+id+'&depId='+dep,
            success: function(data){
                $('#cont').html(data);
            }
        });
    });

</script>