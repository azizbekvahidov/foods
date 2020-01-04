<?php

class ExpenseController extends Controller
{
    public $layout='/layouts/column1';

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
                'actions'=>array('SaveCost','closeExp','getPrice','printExpCheck','checkTable','getCurTables','ChangeTable','removeEx','RemoveFromOrder','addToOrder','printCheck','orders','tables','login','create','update','checkOrder','checkExpense','ckeckOrder','lists','upLists','todayOrder','checkBeginOrder'),
                'roles'=>array(),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array(),
                'roles'=>array('1'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('monitoring','ajaxMonitoring','printExp'),
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
        $user = (array)json_decode(json_decode($_POST['json'],true));

        $menuModel = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>0));

        $model=new Expense;
        $table = Yii::app()->db->createCommand()
            ->select('')
            ->from('tables')
            ->queryAll();
		$this->render('index',array(
            'user'=>$user,
            'model'=>$model,
            'menuModel'=>$menuModel,
            'table'=>$table
        ));
	}

    public function actionGetCurTables(){
        $expId = $_POST['expId'];
        if($expId != 0){
            $tables = Yii::app()->db->createCommand()
                ->select('')
                ->from('tables')
                ->queryAll();
            $dates = date("Y-m-d");
            $table = Yii::app()->db->createCommand()
                ->select('ex.table as table')
                ->from('expense ex')
                ->where('expense_id = :expId',array(':expId'=>$expId))
                ->group('ex.table')
                ->queryRow();
            $curTables = Yii::app()->db->createCommand()
                ->select('ex.table as table')
                ->from('expense ex')
                ->where('date(ex.order_date) = :dates',array(':dates'=>$dates))
                ->group('ex.table')
                ->queryAll();
            $this->render('getCurTables',array(
                'table'=>$table['table'],
                'tables'=>$tables,
                'curTables'=>$curTables
            ));
        }
        else{
            $tables = Yii::app()->db->createCommand()
                ->select('')
                ->from('tables')
                ->queryAll();
            $dates = date("Y-m-d");

            $curTables = Yii::app()->db->createCommand()
                ->select('ex.table as table')
                ->from('expense ex')
                ->where('date(ex.order_date) = :dates',array(':dates'=>$dates))
                ->group('ex.table')
                ->queryAll();
            $this->render('getCurTables',array(
                'table'=>0,
                'tables'=>$tables,
                'curTables'=>$curTables
            ));


        }
    }

    public function actionTables(){
        $dates = date('Y-m-d');
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('expense ex')
            ->where('ex.status = :status AND ex.deleted = 0',array(':status'=>1))
            ->order('ex.order_date')
            ->queryAll();

        echo json_encode($model);
    }

    public function actionOrders(){
        $res = array();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('expense ex')
            ->where('ex.expense_id = :id',array(':id'=>$_POST['id']))
            ->order('ex.order_date')
            ->queryRow();
        $order = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count,d.name,ord.type,ord.order_id')
            ->from('orders ord')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('ord.expense_id = :id AND ord.type = :types AND ord.deleted = 0',array(':id'=>$model['expense_id'],':types'=>1))
            ->queryAll();
        $order2 = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count,h.name,ord.type,ord.order_id')
            ->from('orders ord')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('ord.expense_id = :id AND ord.type = :types AND ord.deleted = 0',array(':id'=>$model['expense_id'],':types'=>2))
            ->queryAll();
        $order3 = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count,p.name,ord.type,ord.order_id')
            ->from('orders ord')
            ->join('products p','p.product_id = ord.just_id')
            ->where('ord.expense_id = :id AND ord.type = :types AND ord.deleted = 0',array(':id'=>$model['expense_id'],':types'=>3))
            ->queryAll();
        $this->renderPartial('orders',array(
            'order'=>$order,
            'order2'=>$order2,
            'order3'=>$order3,
            'model'=>$model,
        ));

    }

    public function actionSaveCost(){
        Yii::app()->db->createCommand()->insert("costs",array(
            "summ" => $_POST["costSum"],
            "comment" => $_POST["costDesc"],
            "cost_date" => date("Y-m-d H:i:s"),
            "user_id" => $_POST["user"],
            "contractor_id" => 0,
            "employee_id" => 0,
            "status" => 0
        ));
    }



    public function actionCloseExp(){
        if($_POST["discount"] == ""){
            $_POST["discount"] = 0;
        }

        $func = new Functions();
        $sum = 0;
        $percent = new Percent();
        if($_POST["check"] == 1) {

            $sum=number_format(($_POST['sum'] + $_POST['sum'] * $percent->getPercent(date("Y-m-d")) / 100) / 100, 0, ',', '') * 100;
        }
        //$func->getExpenseCostPrice($_POST["id"],date('Y-m-d'));
        if($_POST["paid"] == "debt"){
            Yii::app()->db->createCommand()->update("expense",array(
                'status'=>1,
                'debt'=>1,
                'comment'=>$_POST['text'],
                'expSum'=>$_POST["sum"]-$_POST["discount"],
                'discount'=>$_POST["discount"],
                'debtPayed' => $_POST["paidDebt"],
            ),"expense_id = :id",array(":id"=>$_POST["id"]));
        }
        elseif ($_POST["paid"] == "cash"){
            Yii::app()->db->createCommand()->update("expense",array(
                'status' => 0,
                'expSum'=>$_POST["sum"]-$_POST["discount"],
                'discount'=>$_POST["discount"],
            ),"expense_id = :id",array(":id"=>$_POST["id"]));
        }
        elseif ($_POST["paid"] == "term"){
            if($_POST['text'] != ""){
                Yii::app()->db->createCommand()->update("expense",array(
                    'status' => 0,
                    'terminal' => $_POST['text'],
                    'expSum'=>$_POST["sum"]-$_POST["discount"],
                    'discount'=>$_POST["discount"],
                ),"expense_id = :id",array(":id"=>$_POST["id"]));
            }
            else{
                Yii::app()->db->createCommand()->update("expense",array(
                    'status' => 0,
                    'terminal' => $_POST["sum"],
                    'expSum'=>$_POST["sum"]-$_POST["discount"],
                    'discount'=>$_POST["discount"],
                ),"expense_id = :id",array(":id"=>$_POST["id"]));
            }
//            if($_POST["types"] == "true"){
//                Yii::app()->db->createCommand()->update("expense",array(
//                    'status' => 0,
//                    'terminal' => $_POST["sum"]
//                ),"expense_id = :id",array(":id"=>$_POST["id"]));
//            }
//            else{
//                Yii::app()->db->createCommand()->update("expense",array(
//                    'status' => 0,
//                    'terminal' => $sum
//                ),"expense_id = :id",array(":id"=>$_POST["id"]));
//            }
        }
    }

    public function actionPrintCheck(){
        $result = array();
        $resultArchive = array();
        $user = Yii::app()->db->createCommand()
            ->select('')
            ->from('employee e')
            ->where('e.employee_id = :id',array(':id'=>$_GET['user']))
            ->queryRow();
        if($_GET['action'] == 'create'){
            if(!empty($_GET['id']))
                // echo "<pre>";
                // print_r($_GET);
                // echo "</pre>";
                foreach ($_GET['id'] as $key => $val) {
                    $expl = explode('_',$val);
                    if($expl[0] == 'dish') {
                        $model = Yii::app()->db->createCommand()
                            ->select('d.name as dName, dep.name as depName')
                            ->from('dishes d')
                            ->join('department dep', 'dep.department_id = d.department_id')
                            ->where('d.dish_id = :id', array(':id' => $expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $_GET['count'][$key];
                    }
                    if($expl[0] == 'stuff'){
                        $model = Yii::app()->db->createCommand()
                            ->select('h.name as dName, dep.name as depName')
                            ->from('halfstaff h')
                            ->join('department dep','dep.department_id = h.department_id')
                            ->where('h.halfstuff_id = :id',array(':id'=>$expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $_GET['count'][$key];
                    }
                    if($expl[0] == 'product'){
                        $model = Yii::app()->db->createCommand()
                            ->select('p.name as dName, dep.name as depName')
                            ->from('products p')
                            ->join('department dep','dep.department_id = p.department_id')
                            ->where('p.product_id = :id',array(':id'=>$expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $_GET['count'][$key];
                    }
                }
        }
        if($_GET['action'] == 'update'){
            $expId = $_GET['expId'];
            $archive = Yii::app()->db->createCommand()
                ->select('')
                ->from('archiveorder ao')
                ->where('ao.expense_id = :id',array(':id'=>$expId))
                ->order('ao.archive_date DESC')
                ->limit(1,1)
                ->queryRow();
            $temp = explode('*',$archive['archive_message']);
            foreach ($temp as $key => $value) {
                $temporary = explode('=>',$value);

                if($temporary[0] == 'dish'){
                    $dishes = explode(',',$temporary[1]);
                    foreach ($dishes as $val) {
                        $core = explode(':',$val);
                        $model = Yii::app()->db->createCommand()
                            ->select('d.name as dName, dep.name as depName')
                            ->from('dishes d')
                            ->join('department dep','dep.department_id = d.department_id')
                            ->where('d.dish_id = :id',array(':id'=>$core[0]))
                            ->queryRow();
                        $resultArchive[$model['depName']][$model['dName']] = $core[1];
                    }
                }
                if($temporary[0] == 'stuff'){
                    $dishes = explode(',',$temporary[1]);
                    foreach ($dishes as $val) {
                        $core = explode(':',$val);
                        $model = Yii::app()->db->createCommand()
                            ->select('h.name as dName, dep.name as depName')
                            ->from('halfstaff h')
                            ->join('department dep','dep.department_id = h.department_id')
                            ->where('h.halfstuff_id = :id',array(':id'=>$val))
                            ->queryRow();
                        $resultArchive[$model['depName']][$model['dName']] = $core[1];
                    }
                }
                if($temporary[0] == 'prod'){
                    $dishes = explode(',',$temporary[1]);
                    foreach ($dishes as $val) {
                        $core = explode(':',$val);
                        $model = Yii::app()->db->createCommand()
                            ->select('p.name as dName, dep.name as depName')
                            ->from('products p')
                            ->join('department dep','dep.department_id = p.department_id')
                            ->where('p.product_id = :id',array(':id'=>$val))
                            ->queryRow();
                        $resultArchive[$model['depName']][$model['dName']] = $core[1];
                    }
                }
            }
            if(!empty($_GET['id']))
                foreach ($_GET['id'] as $key => $val) {
                    $expl = explode('_',$val);
                    if($expl[0] == 'dish') {
                        $model = Yii::app()->db->createCommand()
                            ->select('d.name as dName, dep.name as depName')
                            ->from('dishes d')
                            ->join('department dep', 'dep.department_id = d.department_id')
                            ->where('d.dish_id = :id', array(':id' => $expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $_GET['count'][$key];
                    }
                    if($expl[0] == 'stuff'){
                        $model = Yii::app()->db->createCommand()
                            ->select('h.name as dName, dep.name as depName')
                            ->from('halfstaff h')
                            ->join('department dep','dep.department_id = h.department_id')
                            ->where('h.halfstuff_id = :id',array(':id'=>$expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $_GET['count'][$key];
                    }
                    if($expl[0] == 'product'){
                        $model = Yii::app()->db->createCommand()
                            ->select('p.name as dName, dep.name as depName')
                            ->from('products p')
                            ->join('department dep','dep.department_id = p.department_id')
                            ->where('p.product_id = :id',array(':id'=>$expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $_GET['count'][$key];
                    }
                }

            // echo "<pre>";
            // print_r($result);
            // echo "</pre>";

            // echo "<pre>";
            // print_r($resultArchive);
            // echo "</pre>";
            $result = $this->ShowChange($result,$resultArchive);
        }
        $this->renderPartial('printCheck',array(
            'result'=>$result,
            'user'=>$user
        ));
    }

    public function actionPaidPartDebt(){
        Yii::app()->db->createCommand()->update("expense",array(
            'debtPayed' => $_POST["paidDebt"],
        ),"expense_id = :id",array(":id"=>$_POST["id"]));
    }

    public function ShowChange($array1,$array2){
        $result = array();
        foreach ($array1 as $key => $value) {
            foreach ($value as $keys => $val) {
                $temp = $val - $array2[$key][$keys];
                if($temp != 0){
                    $result[$key][$keys] = $temp;
                }
            }
        }
        foreach ($array2 as $key => $value) {
            foreach ($value as $keys => $val) {
                $temp = $val - $array1[$key][$keys];
                if($temp != 0){
                    $result[$key][$keys] = -$temp;
                }
            }
        }
        return $result;
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

     public function actionCreateExp(){
         Yii::app()->db->createCommand()->insert('expense',array(
             'order_date'=>date("Y-m-d H:i:s"),
             'employee_id'=>Yii::app()->user->getId(),
             'table'=>0,
             'status'=>1,
             'mType'=>1,
             'pCount'=>0,
             'expSum'=>0,
             'banket'=>0
         ));
         $expId = Yii::app()->db->getLastInsertID();

         $archive = new ArchiveOrder();
         $archive->setArchive('create', $expId, "",Yii::app()->user->getId());
         echo $expId;
     }

    public function actionCreate()
    {
        $expense = new Expense();
		    $model=new Expense;
        $func = new Functions();
		$percent = new Percent();
        if($_POST['action'] == 'create') {
            //$transaction = Yii::app()->db->beginTransaction();
            try {
                $dates = date('Y-m-d H:i:s');
                $dishMsg = '*dish=>';
                $stuffMsg = '*stuff=>';
                $prodMsg = '*prod=>';
                $dishMessage = '';
                $stuffMessage = '';
                $prodMessage = '';
                $archive_message = '';
                $pCount = $_POST['peoples'];
				if($_POST["check"] == 1) {
				    if($_POST['banket'] == 0){
                        $_POST['expSum']=number_format(($_POST['expSum'] + $_POST['expSum'] * $percent->getPercent(date("Y-m-d")) / 100) / 100, 0, ',', '') * 100;
                    }
                    else{
                        $_POST['expSum']=number_format(($_POST['expSum'] + $_POST['expSum'] * 15 / 100) / 100, 0, ',', '') * 100;
                    }
                }
                Yii::app()->db->createCommand()->insert('expense',array(
                    'order_date'=>$dates,
                    'employee_id'=>$_POST['employee_id'],
                    'table'=>$_POST['table'],
                    'status'=>1,
                    'mType'=>1,
                    'pCount'=>$pCount,
                    'expSum'=>$_POST['expSum'],
                    'banket'=>$_POST['banket']
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
                    $order_id = Yii::app()->db->getLastInsertID();
                    Yii::app()->db->createCommand()->insert('orderRefuse',array(
                        'order_id'=>$order_id,
                        'count'=>$count,
                        'add'=>1,
                        'not_time'=>$dates,
                        'refuse_time'=>$dates
                    ));
                }
                //$expense->addExpenseList($temp[1],$types,date("Y-m-d"),$count);
                $archive_message .= ((!empty($dishMessage)) ? $dishMsg.$dishMessage : '').((!empty($stuffMessage)) ? $stuffMsg.$stuffMessage : '').((!empty($prodMessage)) ? $prodMsg.$prodMessage : '');
                $archive = new ArchiveOrder();
                $archive->setArchive('create', $expId, $archive_message,$_POST['employee_id']);
                //$transaction->commit();
                //$func->PrintCheck($expId,'create',$_POST['id'],$_POST['employee_id'],$_POST['count'],$_POST['table']);
                echo $expId;

            } catch (Exception $e) {

                //$transaction->rollBack();
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }
        }
        if($_POST['action'] == 'update'){
            try{
                $function = new Functions();
                $dishMsg = '*dish=>';
                $stuffMsg = '*stuff=>';
                $prodMsg = '*prod=>';
                $dishMessage = '';
                $stuffMessage = '';
                $prodMessage = '';
                $archive_message = '';
                $expId = intval($_POST['expenseId']);
                if($_POST['banket'] == 0){
                    $_POST['expSum']=number_format(($_POST['expSum'] + $_POST['expSum'] * $percent->getPercent(date("Y-m-d")) / 100) / 100, 0, ',', '') * 100;
                }
                else{
                    $_POST['expSum']=number_format(($_POST['expSum'] + $_POST['expSum'] * 15 / 100) / 100, 0, ',', '') * 100;
                }
				//Yii::app()->db->createCommand()->update('expense',array('expSum'=>$_POST['expSum'],'banket'=>$_POST['banket']),'expense_id = :id',array(':id'=>$expId));
                
                $refuseTime = date('Y-m-d H:i:s');
                Yii::app()->db->createCommand()->update("orders",array(
                    'count' => 0,
                    'deleted' => 1
                ),'expense_id = '.$expId);
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

                    $model = Yii::app()->db->createCommand()
                        ->select()
                        ->from('orders')
                        ->where('expense_id = :id AND just_id = :just_id AND type = :types ',array(':id'=>$expId,':just_id'=>$temp[1],':types'=>$types))
                        ->queryRow();
                    if(!empty($model)){

                        if($count != 0) {
                            /*if ($model['count'] > $count) {
                                Yii::app()->db->createCommand()->update('orders', array('count' => $count,'deleted'=>0), 'order_id = :id', array(':id' => $model['order_id']));
                                }*/
                            if ($model['count'] < $count) {
                                Yii::app()->db->createCommand()->update('orders', array(
                                    'count' => $count,
                                    'deleted'=>0
                                ), 'order_id = :id', array(':id' => $model['order_id']));
                            }
                            if(($model['count'] > $count && $model['deleted'] == 1) || ($model['count'] == $count && $model['deleted'] == 1)){
                                Yii::app()->db->createCommand()->update('orders', array(
                                    'count' => $count,
                                    'deleted'=>0
                                ), 'order_id = :id', array(':id' => $model['order_id']));
                            }
                            else{
                                Yii::app()->db->createCommand()->update('orders', array(
                                    'count' => $count,
                                    'deleted'=>0
                                ), 'order_id = :id', array(':id' => $model['order_id']));
                            }
                            $expense->addExpenseList($temp[1],$types,date("Y-m-d"),$count - $model["count"]);
                            Yii::app()->db->createCommand()->update('expense',array('expSum'=>$_POST['expSum'],'banket'=>$_POST['banket']),'expense_id = :id',array(':id'=>$expId));
                        } 
                        else{
                            Yii::app()->db->createCommand()->update('orders', array(
                                'deleted' => 1
                            ), 'order_id = :id', array(':id' => $model['order_id']));
                            $expense->addExpenseList($temp[1],$types,date("Y-m-d"),$count - $model["count"]);

                        }
                    }
                    else{
                // echo "<pre>";
                // print_r($_POST);
                // echo "</pre>";
                        Yii::app()->db->createCommand()->insert('orders',array(
                            'expense_id'=>$expId,
                            'just_id'=>$temp[1],
                            'count'=>$count,
                            'type'=>$types
                        ));
                        $order_id = Yii::app()->db->getLastInsertID();
                        Yii::app()->db->createCommand()->insert('orderRefuse',array(
                            'order_id'=>$order_id,
                            'count'=>$count,
                            'add'=>1,
                            'not_time'=>$refuseTime,
                            'refuse_time'=>$refuseTime
                        ));
                        $expense->addExpenseList($temp[1],$types,date("Y-m-d"),$count - $model["count"]);
                    }
                }
                $refuse = Yii::app()->db->createCommand()
                    ->select()
                    ->from('orders')
                    ->where('expense_id = :id AND deleted = 1 AND status = 1 AND count != 0',array(':id'=>$expId))
                    ->queryAll();
                if(!empty($refuse)){
                    foreach ($refuse as $val) {
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
                $archive_message .= ((!empty($dishMessage)) ? $dishMsg.$dishMessage : '').((!empty($stuffMessage)) ? $stuffMsg.$stuffMessage : '').((!empty($prodMessage)) ? $prodMsg.$prodMessage : '');

                $archive = new ArchiveOrder();
                $archive->setArchive('update', $expId, $archive_message,$_POST['employee_id']);

                $func->printCheck($expId,'update',$_POST['id'],$_POST['employee_id'],$_POST['count'],$_POST['table']);
//                echo $expId;
            }
            catch (Exception $e){
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }
		}
    }


    public function actionChangeTable(){
        $table = $_POST['table'];
        $user = $_POST['user'];
        $expId = $_POST['expId'];
        Yii::app()->db->createCommand()->update('expense',array(
            'table'=>$table
        ),'expense_id = :id AND employee_id = :user',array(':id'=>$expId,':user'=>$user));
    }

    public function actionAddToOrder(){
        $expId = intval($_POST['expenseId']);
        $types = 0;
        $temp = explode('_',$_POST['id']);
        if($temp[0] == 'dish')
            $types = 1;
        if($temp[0] == 'stuff')
            $types = 2;
        if($temp[0] == 'product')
            $types = 3;
        $count = floatval($_POST['count']);
        if(isset($expId)){
            if($expId != 0){
                $model = Yii::app()->db->createCommand()
                    ->select()
                    ->from('orders')
                    ->where('expense_id = :id AND just_id = :just_id AND type = :types',array(':id'=>$expId,':just_id'=>$temp[1],':types'=>$types))
                    ->queryRow();
                if(!empty($model)){
                    if($model['count'] != $count) {
                        Yii::app()->db->createCommand()->update('orders', array(
                            'count' => $count
                        ), 'order_id = :id', array(':id' => $model['order_id']));
                    }
                }
                else{
                    Yii::app()->db->createCommand()->insert('orders',array(
                        'expense_id'=>$expId,
                        'just_id'=>$temp[1],
                        'count'=>$count,
                        'type'=>$types
                    ));
                }
                echo $expId;
            }
            else{
                $dates = date('Y-m-d H:i:s');
                Yii::app()->db->createCommand()->insert('expense',array(
                    'order_date'=>$dates,
                    'employee_id'=>$_POST['user'],
                    'table'=>$_POST['table'],
                    'status'=>1,
                    'mType'=>1
                ));
                $expId = Yii::app()->db->getLastInsertID();
                Yii::app()->db->createCommand()->insert('orders',array(
                    'expense_id'=>$expId,
                    'just_id'=>$temp[1],
                    'count'=>$_POST['count'],
                    'type'=>$types
                ));
                echo $expId;
            }
        }
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
                            'deleted' => 1,
                            'count'=>0
                        ), 'order_id = :id', array(':id' => $model['order_id']));
                    }

                }
//                else {
//                    $model = Yii::app()->db->createCommand()
//                        ->select()
//                        ->from('orders')
//                        ->where('expense_id = :id AND just_id = :just_id AND type = :types', array(':id' => $expId, ':just_id' => $temp[1], ':types' => $types))
//                        ->queryRow();
//                    if (!empty($model)) {
//                        Yii::app()->db->createCommand()->update('orders', array(
//                            'count' => $count,
//                            'deleted' => 1
//                        ), 'order_id = :id', array(':id' => $model['order_id']));
//                    }
//                }

//            $expense = Yii::app()->db->createCommand()
//                ->select()
//                ->from('orders')
//                ->where('expense_id = :id AND deleted = 0', array(':id' => $expId))
//                ->queryAll();
//            if(!empty($expense)) {
//
//                echo $expId;
//            }
//            else{
//                Yii::app()->db->createCommand()->update('expense',array(
//                    'deleted'=>1
//                ),'expense_id = :id',array(':id'=>$expId));
//                echo 0;
//            }
        }
    }

    public function actionRemoveEx(){
        $expId = intval($_POST['expenseId']);
        if(isset($expId)){
            Yii::app()->db->createCommand()->update('expense',array(
                'deleted'=>1
            ),'expense_id = :id',array(':id'=>$expId));
            echo 0;
        }
    }

    public function actionLogin(){
        $pass = $_POST['pass'];
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('employee e')
            ->where('e.password = :pass',array(':pass'=>md5($pass)))
            ->queryRow();
        echo json_encode($model);
    }


    public function actionLists(){
        $id = $_POST['id'];

        $newModel1 = Yii::app()->db->createCommand()
            ->select()
            ->from("menu t")
            ->join("dishes d","d.dish_id = t.just_id")
            ->where('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>1,':mType'=>1))
            ->queryAll();

        $newModel2 = Yii::app()->db->createCommand()
            ->select()
            ->from("menu t")
            ->join("halfstaff h","h.halfstuff_id = t.just_id")
            ->where('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>2,':mType'=>1))
            ->queryAll();

        $newModel3 = Yii::app()->db->createCommand()
            ->select()
            ->from("menu t")
            ->join("products p","p.product_id = t.just_id")
            ->where('t.type_id = :types AND t.type = :type AND t.mType = :mType',array(':types'=>$id,':type'=>3,':mType'=>1))
            ->queryAll();

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
        $empId = $_POST['user'];
        $dates = date('Y-m-d');
        //$model = Expense::model()->with('order','employee')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId',array(':dates'=>$dates,':empId'=>Yii::app()->user->getId()));
        $model = Expense::model()->with('employee')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND t.deleted = 0',array(':dates'=>$dates,':empId'=>$empId));

        $percent = new Percent();
        $this->renderPartial('todayOrder',array(
            'dates'=>$dates,
            'model'=>$model,
            'percent'=>$percent->getPercent($dates)
        ));

    }

    public function actionGetPrice(){
        $price = new Prices();
        echo $price->getPrice($_POST["id"],$_POST["mType"],$_POST["type"],$_POST["orderDate"]);
    }

    public function actionUpdate($id){
        $tables = array(
            1=>1,
            2=>2,
            3=>3,
            4=>4,
            5=>5,
            6=>6,
            7=>7,
            8=>8,
            9=>9,
            10=>10,
            11=>11,
            12=>12,
            13=>13,
            14=>14,
            15=>15,
            16=>16,
            17=>17,
            18=>18,
            19=>19,
            20=>20,
        );
        $menuModel = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>0));
        $updateDish = Expense::model()->with('order.dish','employee')->findByPk($id);
        $updateStuff = Expense::model()->with('order.halfstuff','employee')->findByPk($id);
        $updateProd = Expense::model()->with('order.products','employee')->findByPk($id);
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
            $model= Expense::model()->findByPk($id);
            $_POST['Expense']['order_date'] = date('Y-m-d H:i:s');
            //$_POST['Expense']['employee_id'] = Yii::app()->user->getId();
            $_POST['status'] = 1;
            if(!isset($_POST['debt'])) {
                $_POST['debt'] = 0;
                $_POST['comment'] = '';
            }
            $transaction = Yii::app()->db->beginTransaction();
            try{
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


                    $archive->setArchive('update',$model->expense_id,$archive_message,$_POST['employee_id']);
                    $transaction->commit();
                    Yii::app()->user->setFlash($messageType, $message);
                    //$this->redirect(array('view','id'=>$model->expense_id));
                }
            }
            catch (Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }

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
            'expense_id'=>$id,
            'tables'=>$tables
        ));

    }

    public function actionCheckExpense(){

        $empId = Yii::app()->user->getId();
        $dates = date('Y-m-d');
        $expense = new Expense();
        $json = array();
        $model = Expense::model()->with('order')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId',array(':dates'=>$dates,':empId'=>$empId));
        foreach ($model as $value) {
            $count = count($value->getRelated('order'));
            if($count == $expense->getOrderNumber($value->expense_id) && $value->notification == 0){
                $json[$value->table] = 'notification';
                $temp = Expense::model()->findByPk($value->expense_id);
                $temp->notification = 1;
                $temp->save();
            }
            else{
                $json[$value->table] = 'nonnotification';
            }
        }
        echo json_encode($json);

    }

    public function actionCheckOrder(){
        $empId = Yii::app()->user->getId();
        $dates = date('Y-m-d');
        $expense = new Expense();
        $json = array();
        $model = Expense::model()->with('order.dish')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND order.status = :status',array(':dates'=>$dates,':empId'=>$empId,':status'=>2));
        $model2 = Expense::model()->with('order.halfstuff')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND order.status = :status',array(':dates'=>$dates,':empId'=>$empId,':status'=>2));
        $model3 = Expense::model()->with('order.products')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND order.status = :status',array(':dates'=>$dates,':empId'=>$empId,':status'=>2));

        foreach ($model as $value) {
            foreach ($value->getRelated('order') as $val) {
                if($val->status == 2 && $val->notification == 1){
                    $json[$val->getRelated('dish')->name." ".$value->table] = 'notification';
                    $temp = Orders::model()->findByPk($val->order_id);
                    $temp->notification = 2;
                    $temp->save();
                }
                else{
                    $json[$val->getRelated('dish')->name." ".$value->table] = 'nonnotification';
                }
            }

        }
        foreach ($model2 as $value) {
            foreach ($value->getRelated('order') as $val) {
                if($val->status == 2 && $val->notification == 1){
                    $json[$val->getRelated('halfstuff')->name." ".$value->table] = 'notification';
                    $temp = Orders::model()->findByPk($val->order_id);
                    $temp->notification = 2;
                    $temp->save();
                }
                else{
                    $json[$val->getRelated('halfstuff')->name." ".$value->table] = 'nonnotification';
                }
            }

        }
        foreach ($model3 as $value) {
            foreach ($value->getRelated('order') as $val) {
                if($val->status == 2 && $val->notification == 1){
                    $json[$val->getRelated('products')->name." ".$value->table] = 'notification';
                    $temp = Orders::model()->findByPk($val->order_id);
                    $temp->notification = 2;
                    $temp->save();
                }
                else{
                    $json[$val->getRelated('products')->name." ".$value->table] = 'nonnotification';
                }
            }

        }
        echo json_encode($json);

    }
    public function actionCheckBeginOrder(){
        $empId = Yii::app()->user->getId();
        $dates = date('Y-m-d');
//        $expense = new Expense();
        $json = array();
        $model = Expense::model()->with('order.dish')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND order.status = :status',array(':dates'=>$dates,':empId'=>$empId,':status'=>1));
        $model2 = Expense::model()->with('order.halfstuff')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND order.status = :status',array(':dates'=>$dates,':empId'=>$empId,':status'=>1));
        $model3 = Expense::model()->with('order.products')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND order.status = :status',array(':dates'=>$dates,':empId'=>$empId,':status'=>1));

        foreach ($model as $value) {
            foreach ($value->getRelated('order') as $val) {
                if($val->status == 1 && $val->notification == 0){
                    $json[$val->getRelated('dish')->name." ".$value->table] = 'notification';
                    $temp = Orders::model()->findByPk($val->order_id);
                    $temp->notification = 1;
                    $temp->save();
                }
                else{
                    $json[$val->getRelated('dish')->name." ".$value->table] = 'nonnotification';
                }
            }

        }
        foreach ($model2 as $value) {
            foreach ($value->getRelated('order') as $val) {
                if($val->status == 1 && $val->notification == 0){
                    $json[$val->getRelated('halfstuff')->name." ".$value->table] = 'notification';
                    $temp = Orders::model()->findByPk($val->order_id);
                    $temp->notification = 1;
                    $temp->save();
                }
                else{
                    $json[$val->getRelated('halfstuff')->name." ".$value->table] = 'nonnotification';
                }
            }

        }
        foreach ($model3 as $value) {
            foreach ($value->getRelated('order') as $val) {
                if($val->status == 1 && $val->notification == 0){
                    $json[$val->getRelated('products')->name." ".$value->table] = 'notification';
                    $temp = Orders::model()->findByPk($val->order_id);
                    $temp->notification = 1;
                    $temp->save();
                }
                else{
                    $json[$val->getRelated('products')->name." ".$value->table] = 'nonnotification';
                }
            }

        }
        echo json_encode($json);

    }

    public function actionMonitoring(){
        $this->render('monitoring');
    }

    public function actionAjaxMonitoring(){
        $dates = date('Y-m-d');
        //$model = Expense::model()->with('order','employee')->findAll('date(t.order_date) = :dates AND t.status = :status',array(':dates'=>$dates,':status'=>1));
        $model = Yii::app()->db->createCommand()
            ->select('ex.*,emp.name')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->where('date(ex.order_date) = :dates AND ex.status = :status',array(':dates'=>$dates,':status'=>1))
            ->queryAll();
        $this->renderPartial('ajaxMonitoring',array(
            'model'=>$model
        ));
    }

    public function actionPrintExp($exp){
        $expense = Expense::model()->with('employee')->findByPk($exp);
        $model = Expense::model()->with('order.dish')->findByPk($exp);
        $model2 = Expense::model()->with('order.halfstuff')->findByPk($exp);
        $model3 = Expense::model()->with('order.products')->findByPk($exp);
        /*Yii::app()->db->createCommand()
            ->select()
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','ord.just_id = d.dish_id')
            ->where('ex.expense_id = :expId AND ord.type = :types',array(':expId'=>$exp,':types'=>1))
            ->queryAll();*/

        $this->renderPartial('printExp',array(
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
            'expense'=>$expense
        ));
    }

    public function actionCheckTable(){
      $table = $_POST['table'];
      $user = $_POST['user'];
      $dates = date('Y-m-d');
      $result = array();
      if(!isset($_POST['newOrder'])){
          $model = Yii::app()->db->createCommand()
              ->select()
              ->from('expense ex')
              ->where('ex.table = :table AND ex.employee_id = :user AND ex.status != 0',array(':table'=>$table,':user'=>$user))
              ->queryAll();
      }
      else{
          $model = array();
      }
      if(empty($model)){
        for($i = 0;$i < 50;$i++){
          $result['people'][$i] = $i+1;
        }
      }
      else{
        $result['expense'] = $model;
      }
      echo json_encode($result);
    }

    public function actionPrintExpCheck($exp){
        $percent = Yii::app()->config->get('percent');
        $expense = Yii::app()->db->createCommand()
            ->select('ex.order_date,emp.name,ex.expense_id,ex.banket,t.name as Tname,emp.check_percent')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->join('tables t','t.table_num = ex.table')
            ->where('ex.expense_id = :id ',array(':id'=>$exp))
            ->queryRow();
            
                Yii::app()->db->createCommand()->update("expense",array(
                    'print' => 1
                ),"expense_id = :id",array(":id"=>$exp));
        if($expense['check_percent'] == 0){
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


        $this->renderPartial('printExpCheck',array(
            'check'=>$expense['check_percent'],
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
            'expense'=>$expense,
            'percent'=>$percent
        ));
    }


}
