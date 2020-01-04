<?php
require_once Yii::app()->basePath . '/library/printer/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
class ExpenseController extends Controller
{
    public $layout='/layouts/column1';

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
                'actions'=>array('PrintCheck','addExp','create','update','RemoveFromOrder','refuse'),
                'roles'=>array('1'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array(),
                'roles'=>array('2'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array(),
                'roles'=>array('3'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionRefuse(){
        Yii::app()->db->createCommand()->delete("expense","expense_id = :expId",array(":expId"=>$_POST["refuse"]));
        $archive_message = "";
        $archive_message .= "Отказ счета удаление";
        $archive = new ArchiveOrder();
        $archive->setArchive('delete', $_POST["refuse"], $archive_message);
    }
    public function changeToFloat($number){
        $ss = $number;
        $arr = NULL;
        $arr = str_split($ss);
        $k = 0;
        while($k != strlen($ss))
        {
            if ($arr[$k] == ',')
                $arr[$k] = '.';
            $k++;
        }
        $ss = implode($arr);
        return $ss;
    }
    public function actionCreate()
    {

        $menuModel = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>0));

        $model=new Expense;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        $this->render('create',array(
            'model'=>$model,
            'menuModel'=>$menuModel,
        ));


    }

    public function actionAddExp(){
        if(isset($_POST['Expense']))
        {

            $_POST['Expense']['order_date'] = date('Y-m-d H:i:s');

            if($_POST['Expense']['comment'] == ''){
                $_POST['Expense']['comment'] = '';
            }
            if($_POST['Expense']['comment'] == ''){
                $_POST['Expense']['comment'] = '';
            }
            //$_POST['Expense']['employee_id'] = Yii::app()->user->getId();
            try {
                $expense = new Expense();
                $dates = date('Y-m-d H:i:s');
                $dishMsg = '*dish=>';
                $stuffMsg = '*stuff=>';
                $prodMsg = '*prod=>';
                $dishMessage = '';
                $stuffMessage = '';
                $prodMessage = '';
                $archive_message = '';
                
                $temps = Yii::app()->db->createCommand()->insert('expense',array(
                    'order_date'=>($_POST['prepaid'] == "true") ? '2000-01-01 00:00:00' : $dates,
                    'employee_id'=>Yii::app()->user->getId(),
                    'status'=>0,
                    'debt'=> (isset($_POST['Expense']['debt'])) ? $_POST['Expense']['debt'] : 0,
                    'comment'=>$_POST['Expense']['comment'],
                    'prepaid'=>($_POST['prepaid'] == "true") ? 1 : 0,
                    'prepaidSum'=>($_POST['prepaid'] == "true") ? $_POST['prepaidSum'] : 0,
                    'debtor_type'=>(!empty($_POST['Expense']['contr'])) ? 1 : 0,
                    'debtor_id'=>(!empty($_POST['Expense']['contr'])) ? $_POST['Expense']['contr'] : $_POST['Expense']['empId'],
                    'mType'=>1,
                    'prepCreate'=>($_POST['prepaid'] == "true") ? time() : 0,
                    'expSum'=>$_POST['expSum']
                ));
                $expId = Yii::app()->db->getLastInsertID();
                foreach ($_POST['id'] as $key => $val) {
                    $count = floatval($_POST['count'][$key]);
                    $types = 0;
                    $temp = explode('_',$val);
                    if($temp[0] == 'dish') {
                        $types = 1;
                        $dishMessage .= $temp[1].":".$count.",";
                    }
                    if($temp[0] == 'stuff') {
                        $types = 2;
                        $stuffMessage .= $temp[1].":".$count.",";
                    }
                    if($temp[0] == 'product') {
                        $types = 3;
                        $prodMessage .= $temp[1].":".$count.",";
                    }

                    Yii::app()->db->createCommand()->insert('orders',array(
                        'expense_id'=>$expId,
                        'just_id'=>$temp[1],
                        'count'=>$count,
                        'type'=>$types
                    ));
                    //$expense->addExpenseList($temp[1],$types,date("Y-m-d"),$count);
                    //if($_POST['prepaid'] = "true"){
                    //    $expense->addExpenseList($temp[1],$types,date("Y-m-d"),$count);
                    //}

//                    $order_id = Yii::app()->db->0000getLastInsertID();
//
//                    Yii::app()->db->createCommand()->insert('orderRefuse',array(
//                        'order_id'=>$order_id,
//                        'count'=>$count,
//                        'add'=>1,
//                        'refuse_time'=>$dates
//                    ));
                }
//                if($_POST['prepaid'] == "true") {
//                    Yii::app()->db->createCommand()->insert('prepaid', array(
//                            'prepDate' => $dates,
//                            'expense_id'=>$expId,
//                            "terminal"=>$_POST["prepStatus"],
//                            'expSum' => $_POST['prepaidSum']
//                        )
//                    );
//                }
                $func = new Expense();
                echo $expId;
                //$func->getExpenseCostPrice($expId,$dates);
                $archive_message .= ((!empty($dishMessage)) ? $dishMsg.$dishMessage : '').((!empty($stuffMessage)) ? $stuffMsg.$stuffMessage : '').((!empty($prodMessage)) ? $prodMsg.$prodMessage : '');
                $archive = new ArchiveOrder();
                $archive->setArchive('create', $expId, $archive_message);
//                $function = new Functions();
//                $function->PrintCheck($expId,'create',$_POST['id'],$_POST['employee_id'],$_POST['count'],$_POST['table']);


            } catch (Exception $e) {
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }
        }
    }

    public function actionPrintCheck(){
        $expId = $_POST["expId"];
        $function = new Functions();
        $function->PrintCheck($expId,'create',$_POST['id'],$_POST['employee_id'],$_POST['count'],$_POST['table']);

    }

    public function actionLists(){
        $id = $_POST['id'];
        $model = Yii::app()->db->createCommand()
            ->select("mType")
            ->from("employee")
            ->where("employee_id = :id",array(":id"=>Yii::app()->user->getId()))
            ->queryRow();
        $newModel1 = Menu::model()->with('dish')->findAll('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>1,':mType'=>$model["mType"]));
        $newModel3 = Menu::model()->with('stuff')->findAll('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>2,':mType'=>$model["mType"]));
        $newModel2 = Menu::model()->with('products')->findAll('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>3,':mType'=>$model["mType"]));

        $this->renderPartial('lists',array(
            'newModel1'=>$newModel1,
            'newModel3'=>$newModel3,
            'newModel2'=>$newModel2,
        ));
    }

    public function actionUpLists(){
        $id = $_POST['id'];

        $newModel1 = Menu::model()->with('dish')->findAll('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>1,':mType'=>1));
        $newModel3 = Menu::model()->with('stuff')->findAll('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>2,':mType'=>1));
        $newModel2 = Menu::model()->with('products')->findAll('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>3,':mType'=>1));

        $this->renderPartial('uplist',array(
            'newModel1'=>$newModel1,
            'newModel3'=>$newModel3,
            'newModel2'=>$newModel2,
        ));
    }

    public function actionTodayOrder(){
        $dates = date('Y-m-d');
        //$model = Expense::model()->with('order','employee')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId',array(':dates'=>$dates,':empId'=>Yii::app()->user->getId()));
        $model = Expense::model()->with('order','employee')->findAll('date(t.order_date) = :dates AND employee.role > 2',array(':dates'=>$dates));
        $percent = new Percent();
        $percent = new Percent();
        $this->renderPartial('todayOrder',array(
            'dates'=>$dates,
            'model'=>$model,
            'percent'=>$percent->getPercent($dates)
        ));

    }

    public function actionRemoveFromOrder(){
        $expId = intval($_POST['expenseId']);
        $types = 0;
        $refuseTime = date("Y-m-d H:i:s");
        $temp = explode('_',$_POST['id']);
        if($temp[0] == 'dish')
            $types = 1;
        if($temp[0] == 'stuff')
            $types = 2;
        if($temp[0] == 'product')
            $types = 3;
        $count = floatval($_POST['count']);
        if(isset($expId)){
            if ($count == 0) {
                $model = Yii::app()->db->createCommand()
                    ->select()
                    ->from('orders')
                    ->where('expense_id = :id AND just_id = :just_id AND type = :types', array(':id' => $expId, ':just_id' => $temp[1], ':types' => $types))
                    ->queryRow();
                if (!empty($model)) {

                    Yii::app()->db->createCommand()->insert('orderRefuse',array(
                        'order_id'=>$model['order_id'],
                        'count'=>$model['count'],
                        'refuse_time'=>$refuseTime
                    ));
                    Yii::app()->db->createCommand()->update('orders', array(
                        'deleted' => 1
                    ), 'order_id = :id', array(':id' => $model['order_id']));
                }

            }
        }
    }

    public function actionUpdate($id){

        $menuModel = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>0));
        $updateDish = Expense::model()->with('order.dish','employee')->findByPk($id,'order.deleted != 1');
        $updateStuff = Expense::model()->with('order.halfstuff','employee')->findByPk($id,'order.deleted != 1');
        $updateProd = Expense::model()->with('order.products','employee')->findByPk($id,'order.deleted != 1');
        if(!empty($updateDish)){
            $empId = $updateDish->employee_id;
            $table = $updateDish->table;
            $status = $updateDish->debt;
        }
        if(!empty($updateStuff)){
            $empId = $updateStuff->employee_id;
            $table = $updateStuff->table;
            $status = $updateStuff->debt;
        }
        if(!empty($updateProd)){
            $empId = $updateProd->employee_id;
            $table = $updateProd->table;
            $status = $updateProd->debt;
        }

        $orders = new Orders();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['expense_id']))
        {
            $expense = new Expense();
            $model= Expense::model()->findByPk($id);
            $_POST['Expense']['order_date'] = date('Y-m-d H:i:s');
            $summ = $expense->getExpenseSumReal($id,date('Y-m-d'));
            $costPrice  = $expense->getExpenseCostPrice($id,date('Y-m-d'));
            echo "<pre>";
            print_r($costPrice);
            echo "</pre>";
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            //$_POST['Expense']['employee_id'] = Yii::app()->user->getId();
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $dishMsg = '*dish=>';
                $stuffMsg = '*stuff=>';
                $prodMsg = '*prod=>';
                $dishMessage = '';
                $stuffMessage = '';
                $prodMessage = '';
                $archive_message = '';
                $expId = intval($id);
                $refuseTime = date('Y-m-d H:i:s');
                $model->employee_id = $_POST['employee_id'];
                $model->debt = $_POST['debt'];
                $model->mType = 1;
                $model->comment =$_POST['comment'];
                $model->expSum = $summ;
                $model->costPrice = $costPrice;
                if($model->save()) {
                    // $tempModel = Yii::app()->db->createCommand()
                    //     ->select()
                    //     ->from('orders')
                    //     ->where('expense_id = :id AND status = 1', array(':id' => $expId))
                    //     ->queryAll();
                    // Yii::app()->db->createCommand()->update('orders', array(
                    //     'deleted' => 1,
                    //     'status' => 1
                    // ), 'expense_id = :id', array(':id' => $expId));
                    foreach ($_POST['id'] as $key => $val) {
                        $count = floatval($_POST['count'][$key]);
                        $types = 0;
                        $temp = explode('_', $val);
                        if ($temp[0] == 'dish') {
                            $types = 1;
                            $dishMessage .= $temp[1] . ":" . $count . ",";
                        }
                        if ($temp[0] == 'stuff') {
                            $types = 2;
                            $stuffMessage .= $temp[1] . ":" . $count . ",";
                        }
                        if ($temp[0] == 'product') {
                            $types = 3;
                            $prodMessage .= $temp[1] . ":" . $count . ",";
                        }

                        $model = Yii::app()->db->createCommand()
                            ->select()
                            ->from('orders')
                            ->where('expense_id = :id AND just_id = :just_id AND type = :types ',array(':id'=>$expId,':just_id'=>$temp[1],':types'=>$types))
                            ->queryRow();
                        if(!empty($model)){
                            if($count != 0) {
                                if ($model['count'] > $count) {
                                    Yii::app()->db->createCommand()->insert('orderRefuse', array(
                                        'order_id' => $model['order_id'],
                                        'count' =>  $model['count'] - $count,
                                        'refuse_time' => $refuseTime
                                    ));
                                    Yii::app()->db->createCommand()->update('orders', array(
                                        'count' => $count,
                                        'deleted'=>0
                                    ), 'order_id = :id', array(':id' => $model['order_id']));
                                }
                                if ($model['count'] < $count) {
                                    Yii::app()->db->createCommand()->insert('orderRefuse', array(
                                        'order_id' => $model['order_id'],
                                        'count' => $count - $model['count'],
                                        'add' => 1,
                                        'not_time'=>$refuseTime,
                                        'refuse_time' => $refuseTime
                                    ));
                                    Yii::app()->db->createCommand()->update('orders', array(
                                        'count' => $count
                                    ), 'order_id = :id', array(':id' => $model['order_id']));
                                }
                            } else{
                                Yii::app()->db->createCommand()->insert('orderRefuse', array(
                                    'order_id' => $model['order_id'],
                                    'count' => $model['count'],
                                    'not_time'=>$refuseTime,
                                    'refuse_time' => $refuseTime
                                ));
                                Yii::app()->db->createCommand()->update('orders', array(
                                    'deleted' => 1
                                ), 'order_id = :id', array(':id' => $model['order_id']));

                            }
                        }
                        else{
                            Yii::app()->db->createCommand()->insert('orders',array(
                                'expense_id'=>$expId,
                                'just_id'=>$temp[1],
                                'count'=>$count,
                                'type'=>$types
                            ))
                            ;$order_id = Yii::app()->db->getLastInsertID();
                            Yii::app()->db->createCommand()->insert('orderRefuse',array(
                                'order_id'=>$order_id,
                                'count'=>$count,
                                'add'=>1,
                                'not_time'=>$refuseTime,
                                'refuse_time'=>$refuseTime
                            ));
                        }
                    }
                    $refuse = Yii::app()->db->createCommand()
                        ->select()
                        ->from('orders')
                        ->where('expense_id = :id AND deleted = 1 AND status = 1 AND count != 0',array(':id'=>$expId))
                        ->queryAll();
                    if(!empty($refuse)){
                        foreach ($refuse as $val) {
                            Yii::app()->db->createCommand()->insert('orderRefuse',array(
                                'order_id'=>$val['order_id'],
                                'count'=>$val['count'],
                                'refuse_time'=>$refuseTime
                            ));
                            Yii::app()->db->createCommand()->update('orders',array(
                                'count'=>0,
                            ), 'order_id = :id',array(':id'=>$val['order_id']));
                        }
                    }

                    if(!empty($tempModel)){
                        foreach ($tempModel as $val) {
                            Yii::app()->db->createCommand()->update('orders',array(
                                'status'=>$val['status'],
                                'deleted'=>$val['deleted']
                            ), 'order_id = :id',array(':id'=>$val['order_id']));
                        }
                    }
                }
                $archive_message .= ((!empty($dishMessage)) ? $dishMsg.$dishMessage : '').((!empty($stuffMessage)) ? $stuffMsg.$stuffMessage : '').((!empty($prodMessage)) ? $prodMsg.$prodMessage : '');
                $archive = new ArchiveOrder();
                $archive->setArchive('update', $expId, $archive_message);
                $transaction->commit();
            }
            catch (Exception $e){
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }
            /*try{
                $archive = new ArchiveOrder();
                $archive_message = '';

                $messageType='warning';
                $message = "There are some errors ";
                $model->table = $_POST['table'];
                $model->employee_id = $_POST['employee_id'];
                $model->status = $_POST['status'];
                $model->debt = $_POST['debt'];
                $model->mType = 1;
                $model->comment =$_POST['comment'];
                if($model->save()){
                    $orders->model()->deleteAll('expense_id = :expId',array(':expId'=>$_POST['expense_id']));

                    $messageType = 'success';
                    $message = "<strong>Well done!</strong> You successfully create data ";
                    if(isset($_POST['dish'])){
                        $archive_message .= '*dish=>';
                        foreach($_POST['dish']['id'] as $key => $val){
                            $prodModel = new Orders;
                            $prodModel->expense_id = $_POST['expense_id'];
                            $prodModel->just_id = $val;
                            $prodModel->type = 1;
                            $prodModel->count = $this->changeToFloat($_POST['dish']['count'][$key]);
                            $prodModel->save();
                            $archive_message .= $val.":".$this->changeToFloat($_POST['dish']['count'][$key]).",";
                        }
                    }
                    if(isset($_POST['stuff'])){
                        $archive_message .= '*stuff=>';
                        foreach($_POST['stuff']['id'] as $key => $val){
                            $prodModel = new Orders;
                            $prodModel->expense_id = $_POST['expense_id'];
                            $prodModel->just_id = $val;
                            $prodModel->type = 2;
                            $prodModel->count = $this->changeToFloat($_POST['stuff']['count'][$key]);
                            $prodModel->save();
                            $archive_message .= $val.":".$this->changeToFloat($_POST['stuff']['count'][$key]).",";
                        }
                    }
                    if(isset($_POST['product'])){
                        $archive_message .= '*prod=>';
                        foreach($_POST['product']['id'] as $key => $val){
                            $prodModel = new Orders;
                            $prodModel->expense_id = $_POST['expense_id'];
                            $prodModel->just_id = $val;
                            $prodModel->type = 3;
                            $prodModel->count = $this->changeToFloat($_POST['product']['count'][$key]);
                            $prodModel->save();
                            $archive_message .= $val.":".$this->changeToFloat($_POST['product']['count'][$key]).",";
                        }
                    }


                    $archive->setArchive('update',$model->expense_id,$archive_message);
                    $transaction->commit();
                    Yii::app()->user->setFlash($messageType, $message);
                    //$this->redirect(array('view','id'=>$model->expense_id));
                }
            }
            catch (Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }*/

        }

        $this->render('update',array(
            'model'=>$model,
            'menuModel'=>$menuModel,
            'updateDish'=>$updateDish,
            'updateStuff'=>$updateStuff,
            'updateProd'=>$updateProd,
            'empId'=>$empId,
            'table'=>$table,
            'debt'=>$status,
            'expense_id'=>$id
        ));

    }

}