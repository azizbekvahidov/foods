<?php

class MonitoringController extends Controller
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

            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request

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
                'actions' => array('index','refreshTable'),
                'roles' => array('2'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'roles' => array('3'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex(){
        $dates = date('Y-m-d');
        $model = Expense::model()->with('employee')->findAll('date(order_date) = :dates',array(':dates'=>$dates));

        $this->render('index',array(
            'model'=>$model
        ));
    }
    
    public function actionRefreshTable(){
        $dates = date('Y-m-d');
        $model = Expense::model()->with('employee')->findAll('date(order_date) = :dates',array(':dates'=>$dates));

        $this->renderPartial('cook',array(
            'model'=>$model,
        ));
    }

    public function actionCookMonitoring(){
        echo "asdadsa";
    }

    

}