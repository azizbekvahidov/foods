<?php

class ExchangeController extends SetupController
{

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
                'actions'=>array(),
                'roles'=>array('2'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('create'),
                'roles'=>array('3'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionCreate(){
        $department = CHtml::listData(Department::model()->findAll(),'department_id','name');
        $product = CHtml::listData(Products::model()->findAll('status != 1'),'product_id','name');
        $contractor = CHtml::listData(Contractor::model()->findAll('status != 1'),'contractor_id','name');
        $func = new Functions();
        $storage = new Storage();
        if(isset($_POST['exchangeType'])){
            $dates = $_POST['exchange_date'];
            $contractor_id = $_POST['contractor_id'];
            $comment = $_POST['comment'];
            $prodId = $_POST['product'];
            $prodCount = $_POST['count'];
            if($_POST['exchangeType'] == 0){
                Yii::app()->db->CreateCommand()->insert('exchange',array(
                    'comment'=>$comment,
                    'recived'=>0,
                    'exchange_date'=>$dates,
                    'contractor_id'=>$contractor_id
                ));
                $lastId = Yii::app()->db->lastInsertID;
                foreach ($prodId as $key => $val) {
                    Yii::app()->db->createCommand()->insert('exList',array(
                        'prod_id'=>$val,
                        'count'=>$func->changeToFloat($prodCount[$key]),
                        'exchange_id'=>$lastId
                    ));
                    $storage->addToStorage($val,$func->changeToFloat($prodCount[$key]));
                }
            }
            else{
                Yii::app()->db->CreateCommand()->insert('exchange',array(
                    'comment'=>$comment,
                    'recived'=>1,
                    'exchange_date'=>$dates,
                    'contractor_id'=>$contractor_id
                ));
                $lastId = Yii::app()->db->lastInsertID;
                foreach ($_POST['prod'] as $key => $val) {
                    if($val != '') {
                        Yii::app()->db->createCommand()->insert('exList', array(
                            'prod_id' => $key,
                            'count' => $func->changeToFloat($val),
                            'exchange_id' => $lastId
                        ));
                        $storage->removeToStorage($key,$func->changeToFloat($val));
                    }
                }

            }
            $this->redirect(array('create'));
        }
        $this->render('create',array(
            'department'=>$department,
            'product'=>$product,
            'contractor'=>$contractor
        ));
    }

    public function actionAjaxStorageTable(){
        $dates = $_POST['dates'];
        $func = new Functions();
        $this->renderPartial('ajaxStorageTable',array(
            'prod'=>$func->getStorageCount($dates)
        ));
    }

    
}