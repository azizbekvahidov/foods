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
            ->select('')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->where('date(ex.order_date) = :dates AND ex.status = :status AND ex.debt = :debt',array(':dates'=>$dates,':status'=>1,':debt'=>0))
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
            ->select('')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->where('ex.expense_id = :id ',array(':id'=>$exp))
            ->queryRow();
        if($expense['check_percent'] != 0){
            $percent = 10;
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
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
            'expense'=>$expense,
            'percent'=>$percent
        ));
    }

    public function actionCloseExp(){
        $id = $_POST['id'];

        $model = Yii::app()->db->createCommand()->update('expense',array(
            'status'=>0
        ),'expense_id = :id',array(':id'=>$id));
    }

    public function actionCloseDebt(){
        $id = $_POST['id'];
        $text = $_POST['text'];
        $empId = $_POST['empId'];
        $cont = $_POST['cont'];

        $model = Yii::app()->db->createCommand()->update('expense',array(
            'status'=>0,
            'debt'=>1,
            'comment'=>$text,
            'debtor_id'=>(!empty($cont))? $cont : $empId,
            'debtor_type'=>(!empty($cont))? 1 : 0
        ),'expense_id = :id',array(':id'=>$id));
    }

    public function actionCloseTerm($id = 0){
        if($id == 0) {
            $id = $_POST['id'];
        }
        $summ = $_POST['term'];

        $dates = Yii::app()->db->createCommand()
            ->select('date(order_date) as dates')
            ->from('expense')
            ->where('expense_id = :id',array(':id'=>$id))
            ->queryRow();
        if($summ == ''){
            $func = new Expense();
            $summ = $func->getExpenseSum($id,$dates['dates']);

            $model = Yii::app()->db->createCommand()->update('expense',array(
                'status'=>0,
                'terminal'=>$summ
            ),'expense_id = :id',array(':id'=>$id));
        }
        else{
            $model = Yii::app()->db->createCommand()->update('expense',array(
                'status'=>0,
                'terminal'=>$summ
            ),'expense_id = :id',array(':id'=>$id));
        }
    }
}