<?


class ConfigureController extends Controller{


    public $layout='//layouts/config';
    public function filters()
    {
        return array(
            'accessControl',
            'postOnly + delete',
            array('ext.yiibooster.filters.BootstrapFilter - delete')
        );
    }


    public function actionIndex(){
        $this->render("index");
    }



    public function actionActive(){
        $func = new Functions();
        if(isset($_POST["placeName"])) {
            $SN = $func->UniqueMachineID("C");
            $hash = $func->GetHash($_POST["placeName"],$_POST["secretKey"],$SN);
            $_POST["prog_type"] = 'kafe';
            $_POST["SN"] = $SN;
            $_POST["hash"] = $hash;
            $this->render("active",[
                'data' => $_POST
            ]);
        }
        else{
            $this->redirect("/config/index");
        }
    }

    public function actionSaveActive(){
        Yii::app()->config->set("name", $_POST["placeName"]);
        Yii::app()->db->createCommand()
            ->insert("employee",[
                'name' => "Администратор",
                'login' => $_POST["login"],
                'password' => md5($_POST["password"]),
                'role' => 3
            ]);
        Yii::app()->db->createCommand()
            ->insert("license",[
                'progType' => $_POST["prog_type"],
                'hash' => $_POST["hash"],
                'secretKey' => $_POST["secretKey"],
                'registerDate' => date("Y-m-d H:i:s"),
                'active' => 1,
            ]);

    }


}