<div class="row justify-content-center" >
    <div class="col-md-6 col-sm-6 ">
        <div class="x_panel">
            <div class="x_title">
                <h1 class="float-left">Настройка</h1>
                <div class=" float-right logo-middle">
                    <img src="/images/CafeLogo.png" class="" alt="">
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" autocomplete="off" action="/configure/active" method="post">

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="owner">Имя владельца <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="owner" name="owner" required="required" class="form-control " placeholder="Ф.И.О.">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="placeName">Название заведения <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="placeName" name="placeName" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="email">E-mail <span></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="phone" class="col-form-label col-md-3 col-sm-3 label-align">Тел. номер <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input id="phone" class="form-control" required type="text" name="phone">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="login" class="col-form-label col-md-3 col-sm-3 label-align">Логин <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input id="login" name="login" class=" form-control" required="required" type="text">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="password" class="col-form-label col-md-3 col-sm-3 label-align">Пароль <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input id="password" name="password" class=" form-control" required="required" type="password">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="secretKey" class="col-form-label col-md-3 col-sm-3 label-align">Секретный ключ <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input id="secretKey" name="secretKey" class=" form-control" required="required" type="text">
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" class="btn btn-success">Сохранить и активировать</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>