    С
    <div class="input-prepend">
        <span class="add-on"><i class="icon-calendar"></i></span><?
        $this->widget(
            'bootstrap.widgets.TbDatePicker',
            array(

                'name' => 'from',
                'options' => array(
                    'language' => 'ru',
                    'format' => 'yyyy-mm-dd',
                )
            )
        );
        ?></div>
    По
    <div class="input-prepend">
        <span class="add-on"><i class="icon-calendar"></i></span><?
        $this->widget(
            'bootstrap.widgets.TbDatePicker',
            array(

                'name' => 'to',
                'options' => array(
                    'language' => 'ru',
                    'format' => 'yyyy-mm-dd',
                )
            )
        );
        ?></div>
        <?=CHtml::dropDownList('product','',$prodList,array(''))?>
    <a href="javascript:;" id="view" class="btn" style="  margin-top: -11px; margin-left: 10px;">Показать</a>
    <span class="heading-title">Расходы по продуктам</span>
    <div id="data"></div>
    <script>
        $(document).ready(function(){
            $("#product").chosen({
                no_results_text: "Oops, nothing found!",
            });
            var from,
                to,
                prod;
            $('#view').click(function(){
                from = $('#from').val();
                to = $('#to').val();
                prod = $('#product').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('report/ajaxProdExp'); ?>",
                    data: "from="+from+'&to='+to+'&prod='+prod,
                    success: function(data){
                        $('#data').html(data);
                    }
                });
            });
        });
    </script>
    <script>
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "Excel Document Name"
            });
        });
    </script>