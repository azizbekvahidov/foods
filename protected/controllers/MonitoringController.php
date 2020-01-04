<?php

class MonitoringController extends SetupController
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
                'actions' => array('index','refreshTable','printCheck','closeExp','closeDebt','closeTerm'),
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
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->where('date(ex.order_date) = :dates AND ex.status = :status',array(':dates'=>$dates,':status'=>1))
            ->queryAll();

        $this->render('index',array(
            'model'=>$model
        ));
    }
    
    public function actionRefreshTable(){
        $dates = date('Y-m-d');
        $model = Yii::app()->db->createCommand()
            ->select('emp.name,ex.order_date,ex.expense_id,ex.expSum,t.name as Tname, ex.discount')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->join('tables t','t.table_num = ex.table')
            ->where('ex.status = :status AND ex.debt = :debt',array(':status'=>1,':debt'=>0))
            ->order("ex.table")
            ->queryAll();
        $this->renderPartial('cook',array(
            'model'=>$model,
        ));
    }

    public function actionCookMonitoring(){
        echo "asdadsa";
    }

    public function actionPrintCheck($exp){
        $percent = 0;
        $expense = Yii::app()->db->createCommand()
            ->select('emp.name,ex.order_date,ex.expense_id,ex.banket,t.name as Tname,emp.check_percent')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->join('tables t','t.table_num = ex.table')
            ->where('ex.expense_id = :id ',array(':id'=>$exp))
            ->queryRow();
        if($expense['check_percent'] != 0){
            $percent = Yii::app()->config->get("percent");
        }
        else{
            $percent = 1;
        }
        $model = Expense::model()->with('order.dish')->findByPk($exp,('order.deleted != 1'));
        $model2 = Expense::model()->with('order.halfstuff')->findByPk($exp,('order.deleted != 1'));
        $model3 = Expense::model()->with('order.products')->findByPk($exp,('order.deleted != 1'));
        /*Yii::app()->db->createCommand()
            ->select()
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','ord.just_id = d.dish_id')
            ->where('ex.expense_id = :expId AND ord.type = :types',array(':expId'=>$exp,':types'=>1))
            ->queryAll();*/

        $this->renderPartial('printCheck',array(
            'check'=>$expense['check_percent'],
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
            'expense'=>$expense,
            'percent'=>$percent
        ));
    }

    public function actionCloseExp(){
        $id = $_POST['id'];
        $func = new Expense();
        $func->getExpenseCostPrice($id,date('Y-m-d'));
        $exp = Yii::app()->db->CreateCommand()
            ->select()
            ->from('expense')
            ->where('expense_id = :id',array(':id'=>$id))
            ->queryRow();

        $model = Yii::app()->db->createCommand()->update('expense',array(
            'status'=>0,
            'expSum'=>$exp["expSum"]-$exp["discount"]
        ),'expense_id = :id',array(':id'=>$id));
    }

    public function actionCloseDebt(){
        $func = new Expense();
        $id = $_POST['id'];
        $text = $_POST['text'];
        $payed = $_POST['payed'];
        $func->getExpenseCostPrice($id,date('Y-m-d'));
        $exp = Yii::app()->db->CreateCommand()
            ->select()
            ->from('expense')
            ->where('expense_id = :id',array(':id'=>$id))
            ->queryRow();
        $model = Yii::app()->db->createCommand()->update('expense',array(
            'status'=>1,
            'debt'=>1,
            'comment'=>$text,
            'debtPayed' => $payed,
            'expSum'=>$exp["expSum"]-$exp["discount"]
        ),'expense_id = :id',array(':id'=>$id));
    }

    public function actionCloseTerm($id = 0){
        if($id == 0) {
            $id = $_POST['id'];
        }
        $summ = $_POST['term'];
        $exp = Yii::app()->db->CreateCommand()
            ->select()
            ->from('expense')
            ->where('expense_id = :id',array(':id'=>$id))
            ->queryRow();
     $dates = Yii::app()->db->createCommand()
            ->select('date(order_date) as dates')
            ->from('expense')
            ->where('expense_id = :id',array(':id'=>$id))
            ->queryRow();
        $func = new Expense();
        if($exp["debt"] != 1) {
            if ($summ == '') {
                $summ = $func->getExpenseSum($id, $dates['dates']);
                $model = Yii::app()->db->createCommand()->update('expense', array(
                    'status' => 0,
                    'terminal' => $summ,
                    'expSum' => $exp["expSum"] - $exp["discount"]
                ), 'expense_id = :id', array(':id' => $id));
            } else {
                $model = Yii::app()->db->createCommand()->update('expense', array(
                    'status' => 0,
                    'terminal' => $summ,
                    'expSum' => $exp["expSum"] - $exp["discount"]
                ), 'expense_id = :id', array(':id' => $id));
            }
            $func->getExpenseCostPrice($id, $dates['dates']);
        }
        else{
            if ($summ == '') {
                $summ = $func->getExpenseSum($id, $dates['dates']);
                $model = Yii::app()->db->createCommand()->update('expense', array(
                    'status' => 0,
                        'terminal' => $summ,
                ), 'expense_id = :id', array(':id' => $id));
            } else {
                $model = Yii::app()->db->createCommand()->update('expense', array(
                    'status' => 0,
                    'terminal' => $summ,
                ), 'expense_id = :id', array(':id' => $id));
            }
            $func->getExpenseCostPrice($id, $dates['dates']);
        }
    }

    public function actionSetDiscount(){
        Yii::app()->db->createCommand()->update("expense",array(
            "discount"=>$_POST["val"]
        ),"expense_id = :id",array(":id"=>$_POST["id"]));
        $model = Yii::app()->db->CreateCommand()
            ->select("count(*) as cnt")
            ->from("orders")
            ->where("expense_id = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        Yii::app()->db->createCommand()->update("orders",array(
            "discount"=>$_POST["val"]/$model["cnt"]
        ),"expense_id = :id",array(":id"=>$_POST["id"]));
    }
}