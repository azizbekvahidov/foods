<?php

class ExpenseController extends SetupController
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
                'actions'=>array('PrintReport','removeCost','RegSalary','salary','paidPrepaid','Avans','ajaxDeptList','taken','test','view','index','empExpense','empOrder','debtList','debtClose','debtCloseJust','paidDebt','lists','ajaxEmpExpense','orderList','empList','print'),
                'roles'=>array('2'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('empOrderByDate','getOut','out','curOrder','update','todayOrder','kindCreate'),
                'roles'=>array('3'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionTaken(){

        $dates = date('Y-m-d');
        $this->render('taken',array(
            'dates'=>$dates
        ));
    }

    public function actionTest(){
        if(isset($_POST['dates'])){
            $to = date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($_POST['dates']))." 23:59:59") + 3600);
            $from = date("Y-m-d H:i:s",strtotime($_POST['dates']) + 3600);
        }
        else{
            $to = date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($_POST['to']))." 23:59:59") + 3600);
            $from = date("Y-m-d H:i:s",strtotime($_POST['from']) + 3600);
        }

        $PERSENT = new Percent();
        $expense = new Expense();
        $model = Employee::model()->findAll();
        $cost = Yii::app()->db->createCommand()
            ->select()
            ->from("costs")
            ->where("cost_date BETWEEN :from AND :to",array(':from'=>$from,':to'=>$to))
            ->queryAll();
        $debt = Yii::app()->db->CreateCommand()
            ->select()
            ->from("expense t")
            ->where(' t.order_date BETWEEN :from AND :to AND (t.status = 1 OR t.status = 0) AND t.debt = 1 AND t.kind != 1 AND t.prepaid != 1',array(':from'=>$from,':to'=>$to))
->queryAll();
        $paidDebt = Yii::app()->db->CreateCommand()
            ->select()
            ->from("debt d")
            ->join('expense ex','d.expense_id = ex.expense_id')
            ->where(' d.d_date BETWEEN :from AND :to ',array(':from'=>date("Y-m-d",strtotime($from)),':to'=>date("Y-m-d",strtotime($to))))
            ->queryAll();
        $empCnt = 0;
        foreach($model as $val){
            $empsum = 0;
            $empPersum = 0;
            $clearSum = 0;
            $term = 0;
            $percent = 0;
            $newModel = Yii::app()->db->createCommand()
                ->select()
                ->from("expense t")
                ->where('t.employee_id = :id AND t.order_date BETWEEN :from AND :to AND t.status != :status AND t.debt != :debt AND t.kind != 1 AND t.prepaid != 1',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':status'=>1,':debt'=>1))
                ->queryAll();

            $debtpaid = Yii::app()->db->createCommand()
                ->select()
                ->from("expense t")
                ->where('t.employee_id = :id AND t.order_date BETWEEN :from AND :to AND  t.debt != :debt AND t.kind != 1 AND t.prepaid != 1',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':debt'=>0))
                ->queryAll();
            //echo $val->employee_id."<br>";
            foreach($newModel as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale["order_date"])));
                    $empCnt++;
                }
                else{
                    $percent = 0;
                }
                $temp = $vale["expSum"];
                $clearSum = $clearSum + ($temp*100/(100+$percent));
                $empsum = $empsum + $temp;
                $term = $term + $vale["terminal"];
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }

            foreach($debtpaid as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale["order_date"])));
                    $empCnt++;
                }
                else{
                    $percent = 0;
                }
                $temp = $vale["debtPayed"];
                $clearSum = $clearSum + ($temp*100/(100+$percent));
                $empsum = $empsum + $temp;
                $term = $term + $vale["terminal"];
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }
            $sum["empId"][$val->name] = $val->employee_id;
            $sum['cost'][$val->name] = $empsum;
            $sum["clearSum"][$val->name] = $clearSum;
            $sum["check"][$val->name] = $val->check_percent;
            $clearSumm = $clearSumm + $clearSum;
            $sumPer['cost'][$val->name] = $empPersum;
            $summ['cost'] = $summ['cost'] + $empsum;
            $terminal['cost'][$val->name] = $term;
            $terminalAll['cost'] = $terminalAll['cost'] + $term;
            $perSumm['cost'] = $perSumm['cost'] + $empPersum;


            $department = Yii::app()->db->createCommand()
                ->select('')
                ->from('department')
                ->queryAll();

        }

        $avans = Yii::app()->db->createCommand()
            ->select("sum(expSum) as summ")
            ->from("prepaid")
            ->where("prepDate BETWEEN :from AND :to ",array(":from"=>$from,":to"=>$to))
            ->queryRow();

        $this->renderPartial('test',array(
            'department'=>$department,
            'from'=>$from,
            'to'=>$to,
            'sum'=>$summ,
            'debt' => $debt,
            'paidDebt' => $paidDebt,
            'empSum'=>$sum,
            'sumPer'=>$perSumm,
            'clearSumm'=>$clearSumm,
            'empPerSum'=>$sumPer,
            'terminal'=>$terminal,
            'terminalAll'=>$terminalAll,
            'avans'=>$avans["summ"],
            'empCnt' => $empCnt,
            'cost' => $cost
            
        ));
    }

    public function actionPrintReport(){
        if(isset($_GET['dates'])){
            $to = date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($_GET['to']))." 23:59:59") + 3600);
            $from = date("Y-m-d H:i:s",strtotime($_GET['from']) + 3600);
        }
        else{
            $to = date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($_GET['to']))." 23:59:59") + 3600);
            $from = date("Y-m-d H:i:s",strtotime($_GET['from']) + 3600);
        }

        $PERSENT = new Percent();
        $expense = new Expense();
        $model = Employee::model()->findAll();
        $cost = Yii::app()->db->createCommand()
            ->select()
            ->from("costs")
            ->where("cost_date BETWEEN :from AND :to",array(':from'=>$from,':to'=>$to))
            ->queryAll();
        $debt = Yii::app()->db->CreateCommand()
            ->select()
            ->from("expense t")
            ->where(' t.order_date BETWEEN :from AND :to AND (t.status = 1 OR t.status = 0) AND t.debt = 1 AND t.kind != 1 AND t.prepaid != 1',array(':from'=>$from,':to'=>$to))
->queryAll();
        $paidDebt = Yii::app()->db->CreateCommand()
            ->select()
            ->from("debt d")
            ->join('expense ex','d.expense_id = ex.expense_id')
            ->where(' d.d_date BETWEEN :from AND :to ',array(':from'=>date("Y-m-d",strtotime($from)),':to'=>date("Y-m-d",strtotime($to))))
            ->queryAll();
        $empCnt = 0;
        foreach($model as $val){
            $empsum = 0;
            $empPersum = 0;
            $clearSum = 0;
            $term = 0;
            $percent = 0;
            $newModel = Yii::app()->db->createCommand()
                ->select()
                ->from("expense t")
                ->where('t.employee_id = :id AND t.order_date BETWEEN :from AND :to AND t.status != :status AND t.debt != :debt AND t.kind != 1 AND t.prepaid != 1',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':status'=>1,':debt'=>1))
                ->queryAll();
            $debtpaid = Yii::app()->db->createCommand()
                ->select()
                ->from("expense t")
                ->where('t.employee_id = :id AND t.order_date BETWEEN :from AND :to AND  t.debt != :debt AND t.kind != 1 AND t.prepaid != 1',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':debt'=>0))
                ->queryAll();
            //echo $val->employee_id."<br>";
            foreach($newModel as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale["order_date"])));
                    $empCnt++;
                }
                else{
                    $percent = 0;
                }
                $temp = $vale["expSum"];
                $clearSum = $clearSum + ($temp*100/(100+$percent));
                $empsum = $empsum + $temp;
                $term = $term + $vale["terminal"];
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }

            foreach($debtpaid as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale["order_date"])));
                    $empCnt++;
                }
                else{
                    $percent = 0;
                }
                $temp = $vale["debtPayed"];
                $clearSum = $clearSum + ($temp*100/(100+$percent));
                $empsum = $empsum + $temp;
                $term = $term + $vale["terminal"];
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }
            $sum["empId"][$val->name] = $val->employee_id;
            $sum['cost'][$val->name] = $empsum;
            $sum["clearSum"][$val->name] = $clearSum;
            $sum["check"][$val->name] = $val->check_percent;
            $clearSumm = $clearSumm + $clearSum;
            $sumPer['cost'][$val->name] = $empPersum;
            $summ['cost'] = $summ['cost'] + $empsum;
            $terminal['cost'][$val->name] = $term;
            $terminalAll['cost'] = $terminalAll['cost'] + $term;
            $perSumm['cost'] = $perSumm['cost'] + $empPersum;


            $department = Yii::app()->db->createCommand()
                ->select('')
                ->from('department')
                ->queryAll();

        }

        $avans = Yii::app()->db->createCommand()
            ->select("sum(expSum) as summ")
            ->from("prepaid")
            ->where("prepDate BETWEEN :from AND :to ",array(":from"=>$from,":to"=>$to))
            ->queryRow();

        $this->renderPartial('printReport',array(
            'department'=>$department,
            'from'=>$from,
            'to'=>$to,
            'sum'=>$summ,
            'debt' => $debt,
            'paidDebt' => $paidDebt,
            'empSum'=>$sum,
            'sumPer'=>$perSumm,
            'clearSumm'=>$clearSumm,
            'empPerSum'=>$sumPer,
            'terminal'=>$terminal,
            'terminalAll'=>$terminalAll,
            'avans'=>$avans["summ"],
            'empCnt' => $empCnt,
            'cost' => $cost

        ));
    }

    public function actionDetail($depId,$dates,$till){

        $model = Yii::app()->db->createCommand()
            ->select('sum(ord.count) as count,ord.just_id as prod_id,p.name as name,ex.mType,ord.type')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ex.debtor_id = :DepId AND ord.type = :type AND ord.deleted != 1',
                array(':dates'=>$dates,':DepId'=>$depId,':type'=>3))
            ->group('ord.just_id')
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('sum(ord.count) as count,ord.just_id as prod_id,h.name as name,ex.mType,ord.type')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ex.debtor = :DepId AND ord.type = :type AND ord.deleted != 1',
                array(':dates'=>$dates,':DepId'=>$depId,':type'=>2))
            ->group('ord.just_id')
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('sum(ord.count) as count,ord.just_id as prod_id,d.name as name,ex.mType,ord.type')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ex.debtor = :DepId AND ord.type = :type AND ord.deleted != 1',
                array(':dates'=>$dates,':DepId'=>$depId,':type'=>1))
            ->group('ord.just_id')
            ->queryAll();
        $this->renderPartial("/report/ajaxDetail",array(
            'dates'=>$dates,
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
        ));
    }
    
    public function actionView($id,$order_date)
    {
        $dishModel = Expense::model()->with('order.dish','employee')->findAll('t.employee_id = :id AND t.order_date = :date AND t.kind = :kind  AND order.deleted != 1',array(':kind'=>0,':id'=>$id,'date'=>$order_date));
        $stuffModel = Expense::model()->with('order.halfstuff','employee')->findAll('t.employee_id = :id AND t.order_date = :date AND t.kind = :kind AND order.deleted != 1',array(':kind'=>0,':id'=>$id,'date'=>$order_date));
        $prodModel = Expense::model()->with('order.products','employee')->findAll('t.employee_id = :id AND t.order_date = :date AND t.kind = :kind  AND order.deleted != 1',array(':kind'=>0,':id'=>$id,'date'=>$order_date));
		$percent = new Percent();
        if(isset($_GET['asModal'])){
            $this->renderPartial('view',array(
                'prodModel'=>$prodModel,
                'dishModel'=>$dishModel,
                'stuffModel'=>$stuffModel,
	            'percent'=>$percent->getPercent(date('Y-m-d',strtotime($order_date))),
            ));
        }
        else{

            $this->render('view',array(
                'prodModel'=>$prodModel,
                'dishModel'=>$dishModel,
                'stuffModel'=>$stuffModel,
                'percent'=>$percent->getPercent(date('Y-m-d',strtotime($order_date))),
            ));

        }
    }

    public function actionEmpOrder(){

        $dates = $_POST['dates'];
        $model = Employee::model()->findAll('role < :numb AND status = 0',array(':numb'=>3));
        foreach ($model as $val) {
            $employee[$val->name] = array('ajax'=>$this->createUrl('expense/orderList?empId='.$val->employee_id.'&dates='.$dates));
        }
        $employee['Печать'] = array('ajax'=>$this->createUrl('expense/empList'));

        $this->render('empOrder',array(
            'employee'=>$employee,
        ));
    }

    public function actionSalary(){
        $cook = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("role = 0")
            ->queryAll();
        $waiter = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("role = 1")
            ->queryAll();

        $this->render("salary",array(
            'cook' => $cook,
            'waiter' => $waiter
        ));
    }

    public function actionRegSalary(){
        Yii::app()->db->createCommand()->insert("costs",array(
            'summ' => $_POST["sum"],
            'comment' => "Зарплата ".$_POST["name"],
            'user_id' => Yii::app()->user->getId(),
            'cost_date' => date("Y-m-d H:i:s")
        ));
    }

    public function actionRemoveCost(){
        Yii::app()->db->createCommand()->delete("costs","cost_id = :id",array(":id"=>$_POST["id"]));
    }

    public function actionEmpList(){
        $model = Employee::model()->findAll('role < :numb AND status = 0',array(':numb'=>3));
        $this->renderPartial('empList',array(
            'model'=>$model
        ));
    }

    public function actionOrderList($empId){
        if($_GET['dates'] != '')
            $dates = $_GET['dates'];
        else
            $dates = date('Y-m-d');
        $model = Expense::model()->with('order','employee')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND t.kind = :kind',array(':dates'=>$dates,':empId'=>$empId,':kind'=>0));

        $this->renderPartial('orderList',array(
            'model'=>$model,
            'empId'=>$empId,
        ));
    }

    public function actionEmpOrderByDate(){
        $dates = date('Y-m-d');
        $this->render('orderByDate',array(
            'dates'=>$dates
        ));
    }

    public function actionPrint(){
        $dates = date('Y-m-d');
        //$model = Expense::model()->with('order')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId AND t.kind = :kind',array(':dates'=>$dates,':empId'=>$_GET['empId'],':kind'=>0));

        $this->renderPartial('print',array(
                'empId'=>$_GET['empId'],
                'dates'=>$dates,
            ),
            false,
            true
        );
    }

    public function actionGetOut(){
        $this->render('getOut');
    }

    public function actionOut(){
        $dates = $_POST['dates'];
        $outProduct = array();
        $dish = new Expense();
        $stuff = new Halfstaff();
        foreach (Department::model()->findAll() as $val) {
            $outProduct = $stuff->sumArray($outProduct,$dish->getDishProd($val->department_id,$dates));
            $outDishStuff = $dish->getDishStuff($val->department_id,$dates);
        }
        $Products = Products::model()->findAll();

        $this->renderPartial('out',array(
            'outProduct'=>$outProduct,
            'product'=>$Products,
            'dates'=>$dates,
        ));
    }

    public function actionIndex()
    {
        /*
        $dataProvider=new CActiveDataProvider('Expense');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
        */
        $newModel = Expense::model()->findAll(array('condition'=>'kind = 0','group'=>'date(order_date)'));
        $model=new Expense('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Expense']))
            $model->attributes=$_GET['Expense'];

        $this->render('index',array(
            'model'=>$model,
            'newModel'=>$newModel,
        ));

    }

    public function actionCurOrder(){
        $dates = date('Y-m-d');
        $model = Expense::model()->with('order','employee')->findAll('date(order_date) = :dates',array(':dates'=>$dates));
        $percent = new Percent();
        $this->render('curOrder',array(
            'model'=>$model,
            'percent'=>$percent->getPercent($dates)
        ));
    }

    public function actionUpdate($id){
        $model = Expense::model()->with('order.dish')->findByPk($id);
        $this->render('update',array(

        ));
    }

    public function loadModel($id)
    {
        $model=Expense::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    private function changeToFloat($number){
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

    public function actionTodayOrder(){
        $dates = $_GET['order_date'];
        $model = Yii::app()->db->createCommand()
            ->select('emp.check_percent, emp.employee_id, ex.table ,emp.name,ex.order_date,ex.expense_id,ex.expSum,t.name as Tname')
            ->from('expense ex')
            ->join('employee emp','emp.employee_id = ex.employee_id')
            ->leftjoin('tables t','t.table_num = ex.table')
            ->where('date(ex.order_date) = :dates AND ex.kind = :kind',array(':dates'=>$dates,':kind'=>0))
            ->queryAll();
        //$model = Expense::model()->with('order')->findAll('date(t.order_date) = :dates AND t.kind = :kind',array(':dates'=>$dates,':kind'=>0));
	    $percent = new Percent();
        $this->render('todayOrder',array(
            'newModel'=>$model,
	        'percent'=>$percent->getPercent($dates)
        ));
    }

    public function actionCreate()
    {
        $menuModel = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>0));
        
		$model=new Expense;
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
		if(isset($_POST['Expense']))
		{
        $_POST['Expense']['order_date'] = date('Y-m-d H:i:s');	
        $_POST['Expense']['status'] = 1;
            $transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
				$model->attributes=$_POST['Expense'];
				if($model->save()){
					$messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";
                    if(isset($_POST['dish'])){
                        foreach($_POST['dish']['id'] as $key => $val){
                            $prodModel = new Orders;
                            $prodModel->expense_id = $model->expense_id;
                            $prodModel->just_id = $val;
                            $prodModel->type = 1;
                            $prodModel->count = $this->changeToFloat($_POST['dish']['count'][$key]);
                            $prodModel->save();
                        }
                    }   
                    if(isset($_POST['stuff'])){
                        foreach($_POST['stuff']['id'] as $key => $val){
                            $prodModel = new Orders;
                            $prodModel->expense_id = $model->expense_id;
                            $prodModel->just_id = $val;
                            $prodModel->type = 2;
                            $prodModel->count = $this->changeToFloat($_POST['stuff']['count'][$key]);
                            $prodModel->save();
                        }
                    }   
                    if(isset($_POST['product'])){
                        foreach($_POST['product']['id'] as $key => $val){
                            $prodModel = new Orders;
                            $prodModel->expense_id = $model->expense_id;
                            $prodModel->just_id = $val;
                            $prodModel->type = 3;
                            $prodModel->count = $this->changeToFloat($_POST['product']['count'][$key]);
                            $prodModel->save();
                        }
                    } 
                    
				
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

		$this->render('create',array(
			'model'=>$model,
            'menuModel'=>$menuModel,
		));
		
				
    }
    public function actionKindCreate(){
        $model = new Expense();
        if(isset($_POST['product']))
        {
            $dates = $_POST['from']." ".date('H:i:s');
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $messageType='warning';
                $message = "There are some errors ";
                $model->order_date = $dates;
                $model->employee_id = 1;
                $model->table = 0;
                $model->status = 0;
                $model->kind = 1;
                //$uploadFile=CUploadedFile::getInstance($model,'filename');
                if($model->save()){
                    $storage = new Storage();
                    $messageType = 'success';
                    $message = "<strong>Well done!</strong> You successfully create data ";

                    foreach($_POST['product']['id'] as $key => $value){
                        $newModel = new Orders();
                        $newModel->expense_id = $model->expense_id;
                        $newModel->just_id = $value;
                        $newModel->type = 3;
                        $newModel->count = $this->changeToFloat($_POST['product']['count'][$key]);
                        $newModel->save();
                        $storage->removeToStorage($value,$this->changeToFloat($_POST['product']['count'][$key]));
                    }

                    $transaction->commit();
                    Yii::app()->user->setFlash($messageType, $message);
                    $this->redirect(array('site/index'));
                }
            }
            catch (Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }

        }
        $this->render('kindCreate',array(
            'model'=>$model,
        ));
    }
    public function actionLists(){
        $id = $_POST['id'];

        $newModel1 = Menu::model()->with('dish')->findAll('t.type_id = :types AND t.type = :type',array(':types'=>$id,':type'=>1));
        $newModel3 = Menu::model()->with('stuff')->findAll('t.type_id = :types AND t.type = :type',array(':types'=>$id,':type'=>2));
        $newModel2 = Menu::model()->with('products')->findAll('t.type_id = :types AND t.type = :type',array(':types'=>$id,':type'=>3));

        $this->renderPartial('lists',array(
        'newModel1'=>$newModel1, 
        'newModel3'=>$newModel3, 
        'newModel2'=>$newModel2,
        ));
    }
    public function actionDebtList()
    {
        $this->render('debtList');
    }

    public function actionAjaxDeptList(){

        $from = $_POST['from'];
        $till = $_POST['till'];

        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("expense ex")
            ->join("employee e",'e.employee_id = ex.employee_id')
            ->where('date(ex.order_date) BETWEEN :from AND :till AND  ex.debt = :debt AND ex.status = 1',array(':debt'=>1,':from'=>$from,':till'=>$till))
            ->queryAll();
        $this->renderPartial('ajaxDeptList',array(
            'model'=>$model,
        ));

    }

    public function actionDebtClose($id)
    {
        $exp = new Expense();
        
        Expense::model()->updateByPk($id,array('status'=>0));

            $dates = date('Y-m-d');
            $debt = new Debt();
            $debt->d_date = $dates;
            $debt->expense_id = $id;
            $debt->save();
    }

    public function actionDebtCloseJust($id)
    {
        $exp = new Expense();

        Expense::model()->updateByPk($id,array('status'=>0));

    }

    public function actionPaidDebt(){
        $model = Debt::model()->with('expense')->findAll();
        $this->render('paidDebt',array(
            'model'=>$model,
        ));
    }
    
    public function actionEmpExpense(){
        $this->render('empExpense');
    }
    
    public function actionAjaxEmpExpense(){
        $model = Expense::model()->with('employee')->findAll('date(t.order_date) = :dates AND t.employee_id = :empId',array(':dates'=>$_POST['dates'],':empId'=>$_POST['empId']));
        $percent = new Percent();
        
        $this->renderPartial('ajaxEmpExpense',array(
            'newModel'=>$model,
	        'percent'=>$percent->getPercent($_POST['dates'])
        ));
    }



    public function actionAvans(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("expense e")
            ->where("e.prepaid = 1 AND date(order_date) = '2000-01-01'")
            ->queryAll();

        $this->render("avans",array(
            "model"=>$model
        ));
    }

    public function actionPaidPrepaid(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("expense")
            ->where("expense_id = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        if(intval($model["prepaidSum"] + $_POST["sum"]) == intval($model["expSum"])){
            Yii::app()->db->createCommand()->update("expense",array(
                'prepaidSum'=>$model["prepaidSum"] + $_POST["sum"],
                'order_date'=>date("Y-m-d H:i:s")
            ),"expense_id = :id",array(":id"=>$_POST["id"]));
        }
        else {
            Yii::app()->db->createCommand()->update("expense", array(
                'prepaidSum' => $model["prepaidSum"] + $_POST["sum"],
            ), "expense_id = :id", array(":id" => $_POST["id"]));
        }

        Yii::app()->db->createCommand()->insert("prepaid",array(
            'terminal'=>$_POST["prepStatus"],
            'expSum'=>$_POST["sum"],
            'expense_id'=>$_POST["id"],
            'prepDate'=>date("Y-m-d H:i:s"),
        ));

    }
    
}