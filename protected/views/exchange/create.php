<h1>Обмен продуктов</h1>
<form action="" method="post">
  <div class="input-prepend col-sm-3">
      <span class="add-on"><i class="icon-calendar"></i></span><?
      $this->widget(
          'bootstrap.widgets.TbDatePicker',
          array(
              'name' => 'exchange_date',
              'options' => array(
                  'language' => 'ru',
                  'format' => 'yyyy-mm-dd',
              )
          )
      );
      ?>
  </div>
  <div class="col-sm-3">
      <select name="contractor_id" id="">
        <option value=""></option>
        <? foreach ($contractor as $key => $value) {?>
          <option value="<?=$key?>"><?=$value?></option>
        <?}?>
      </select>
  </div>
  <div class="col-sm-6">
    <div class="form-group">
      <input placeholder="Комментарий" type="text" name="comment" />
    </div>    
  </div>
  <div class="col-sm-2">
    <div class="form-group">
      <label class="radio">
        <input type="radio" name="exchangeType" class="exchangeType" value="0" checked/>
        Принятые
      </label>
      <label class="radio">
        <input type="radio" name="exchangeType" class="exchangeType" value="1" />
        Отправленные
      </label>
    </div>
  </div>
  <div class="col-sm-5 prodList">
    <table>
      <tr>
        <td>
          <select name="product[]" id="" class="product">
            <? foreach ($product as $key => $value) {?>
              <option value="<?=$key?>"><?=$value?></option>
            <?}?>
          </select>
        </td>
        <td>
          <input type="text" name="count[]" placeholder="Количество"/>
        </td>
      </tr>
    </table>
    <div class="form-group">
      <button class="plus" type="button">
        <i class="glyphicon glyphicon-plus"></i>
      </button>
    </div>
  </div>
    <div class="col-sm-5 prodTable"></div>
  <div class="col-sm-12">
      <button class="btn btn-success">Сохранить</button>
  </div>
</form>
<script>
  $(".product").chosen({
        no_results_text: "Oops, nothing found!",
    });
  $(document).on('click','.plus',function(){
    var text = '<tr>\
            <td>\
              <select name="product[]" id="" class="product">\
                <? foreach ($product as $key => $value) {?>\
                  <option value="<?=$key?>"><?=$value?></option>\
                <?}?>\
              </select>\
            </td>\
            <td>\
              <input type="text" name="count[]" placeholder="Количество"/>\
            </td>\
          </tr>';
    $('.prodList table').append(text);
    $(".product").chosen({
        no_results_text: "Oops, nothing found!",
    });
  });
  $(document).on('click','.exchangeType', function(){
    if($(this).val() == 1){
      $('.prodTable').removeClass('hide');
      $('.prodList').addClass('hide');
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('exchange/ajaxStorageTable'); ?>",
            data: 'dates='+$('#exchange_date').val(),
            success: function(data){

                $('.prodTable').html(data);
            }
        });
    }
    else{
      $('.prodList').removeClass('hide');
      $('.prodTable').addClass('hide');
    }
  });
</script>