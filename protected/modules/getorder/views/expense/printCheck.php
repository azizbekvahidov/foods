<style>
    *{
        text-align: center;
    }
    .check{
        border-bottom: 1px solid #555;
    }
    .nameD{
        font-size: 18px;
        font-weight: bold;
    }
    .count{
        font-size: 14px;
        font-weight: bold;
    }
</style>
<?foreach($result as $key => $val){?>
    <div class="check" >
        <h4><?=date("Y-m-d H:i:s");?></h4>
        <h1><?=$key?></h1>
        <?foreach($val as $keys =>$value){?>
            <div class="nameD"><?=$keys?></div>
            <div class="count"><?=$value?></div>
        <?}?>
        <h2><?=$user['name']?></h2>
    </div>
<?}?>