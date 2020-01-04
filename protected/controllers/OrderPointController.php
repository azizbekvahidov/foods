<?php

class OrderPointController extends SetupController
{


    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */

    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */

    public function filters()
    {
        return array(
            'accessControl',
            'postOnly + delete',
            array('ext.yiibooster.filters.BootstrapFilter - delete')
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'view',),
                'roles' => array('2'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'admin', 'delete', 'export', 'import', 'editable', 'toggle',),
                'roles' => array('3'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAdmin(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('orderPoint')
            ->where('status = 0')
            ->queryAll();
        $this->render("admin",array(
            'model'=>$model
        ));
    }

    public function actionCreate(){
        if(isset($_POST['point']))
        {
            $name = htmlspecialchars($_POST['point']['name'],ENT_QUOTES);
            $login = htmlspecialchars($_POST['point']['login'],ENT_QUOTES);
            $pass = htmlspecialchars($_POST['point']['password'],ENT_QUOTES);
            Yii::app()->db->createCommand()->insert('orderPoint',array(
                'name'=>$name,
                'login'=>$login,
                'password'=>md5($pass),
                'status'=>0
            ));
            $this->redirect(array('admin'));
        }
        $this->render('create',array());
    }

    public function actionUpdate($id){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('orderPoint')
            ->where('point_id = :id',array(':id'=>$id))
            ->queryRow();
        if(isset($_POST['point']))
        {
            $name = htmlspecialchars($_POST['point']['name'],ENT_QUOTES);
            $login = htmlspecialchars($_POST['point']['login'],ENT_QUOTES);
            $pass = htmlspecialchars($_POST['point']['password'],ENT_QUOTES);
            Yii::app()->db->createCommand()->update('orderPoint',array(
                'name'=>$name,
                'login'=>$login,
                'password'=>md5($pass),
                'status'=>0
            ),'point_id = :id',array(':id'=>$id));
            $this->redirect(array('admin'));
        }
        $this->render('update',array('model'=>$model));
    }

    public function actionDelete($id){
        Yii::app()->db->createCommand()->update('orderPoint',array(
            'status'=>1
        ),'point_id = :id',array(':id'=>$id));
    }
}