<?php

class OffController extends Controller
{
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
                'actions'=>array(),
                'roles'=>array('2'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('create','saveList','getList'),
                'roles'=>array('3'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    
    public function actionCreate(){
        $this->render('create');
    }
    
    public function actionSaveList(){
        $function = new Functions();
        
        if(!empty($_POST)){
            $dates = $_POST['from']." ".date('H:i:s');
            Yii::app()->db->createCommand()->insert('off',array(
                'off_date'=>$dates,
                'employee_id'=>Yii::app()->user->getId(),
                'comment'=>$_POST['comment'],
                'department_id'=>$_POST['department']
            ));
            $offId = Yii::app()->db->getLastInsertID();
            if(isset($_POST['prod'])){
                foreach($_POST['prod']['id'] as $key => $val){
                    Yii::app()->db->createCommand()->insert('offList',array(
                        'off_id'=>$offId,
                        'prod_id'=>$val,
                        'type'=>3,
                        'count'=>$function->changeToFloat($_POST['prod']['count'][$key])
                    ));
                }
            }
            if(isset($_POST['stuff'])){
                foreach($_POST['stuff']['id'] as $key => $val){
                    Yii::app()->db->createCommand()->insert('offList',array(
                        'off_id'=>$offId,
                        'prod_id'=>$val,
                        'type'=>2,
                        'count'=>$function->changeToFloat($_POST['stuff']['count'][$key])
                    ));
                }
            }
                    $this->redirect(array('site/index'));
        }
    }
    
    public function actionGetList(){
        $dishId = $_POST['dish'];
        $count = $_POST['count'];
        $dish = new Dishes();
        $model = $dish->getStruct($dishId);
        $this->renderPartial('getList',array(
            'model'=>$model,
            'count'=>$count
        ));

    }
}