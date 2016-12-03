<script type="text/javascript">
    $(document).ready(function(){
        
        $("#orgForm").hide();
        $("#orgInfo").show();
        
    });
    function decodeEntities(encodedString) {
        var div = document.createElement('div');
        div.innerHTML = encodedString;
        return div.textContent;
    }
    function RegUserClick(){
        $(document).ready(function(){    
        var data=$("#user-registration-form").serialize();
        //alert('click');    
        $.ajax({
                   url: "<?php echo Yii::app()->createAbsoluteUrl("site/ajaxReg1"); ?>",
                   data:data,
                   type:"POST",
                   datatype:"json",
                   success:function(data){
                        if(data.substr(0,1)=="{"){
            var jsobj = $.parseJSON(data);                
            
            var ht = jsobj.html;
            $("#reg1").html(decodeEntities(jsobj.html));
            //$("#reg1").append();
                            $("#reg1 :input").attr("disabled", true);
                            $('[name="userid"').val(jsobj.id);
                            ///$("#reg2 :input").attr("disabled", false);
                            $("#orgInfo").hide();
                            $("#orgForm").show();
                            //$('#regTab li:first').attr('class', '');
                            //$('#regTab li:last').attr('class', 'active');
                               $('#regTab a:last').tab('show');
            
                            
                            
                        }
                        else{
                            $("#reg1").html(data);
                            alert("oshibka");
                        }
                },
                error:function(request, status, error){console.log(request.responseText);}
               });
               });
        }
        function RegOrgClick(element){
            $(document).ready(function(){
                var form = element.form;
//alert($(form).attr('name'));
//                switch($(form).id)
//                {
//                    case 'org-exist-form':
//                        
//                        break;
//                    case 'org-form':
//                        break;
//                }
        var data=$(form).serialize();
        //alert('click');    
        $.ajax({
                   url: "<?php echo Yii::app()->createAbsoluteUrl("site/ajaxReg2"); ?>",
                   data:data,
                   type:"POST",
                   datatype:"json",
                   success:function(data){
                       if(data="OK")
                       {
                           alert('Регистрация прошла успешно!');
                           window.location.href = "<?php echo Yii::app()->createAbsoluteUrl("site/index");?>";
                       }else{
                           $("#reg2").html(data);
                       }
                },
                error:function(request, status, error){console.log(request.responseText);}
               });
            });
        }
</script>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="reg1">
    <?php    $this->renderPartial('/site/reg/_reg', array('model'=> $model));?>
        
    </div>
    <div role="tabpanel" class="tab-pane" id="reg2">
        <h3 id="orgInfo"> Перейдите к шагу1 и заполните поля </h3> 
        <div id="orgForm"><?php    $this->renderPartial('/site/reg/_reg2', array('model'=>$modelOrg));?></div>
    </div>
    
  </div>
    <?php


