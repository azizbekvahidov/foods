<?php

class ExpenseController extends Controller
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
                'actions'=>array('ajaxDeptList','taken','test','view','index','empExpense','empOrder','debtList','debtClose','paidDebt','lists','ajaxEmpExpense','orderList','empList','print'),
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
            $from = $_POST['dates'];
            $to = $_POST['dates'];
        }
        else{
            $from = $_POST['from'];
            $to = $_POST['to'];
        }
        $PERSENT = new Percent();
        $expense = new Expense();
        $model = Employee::model()->findAll();
        foreach($model as $val){
            $empsum = 0;
            $empPersum = 0;
            $percent = 0;
            $newModel = Expense::model()->findAll('t.employee_id = :id AND date(t.order_date) BETWEEN :from AND :to AND t.status != :status AND t.debt != :debt AND t.kind != 1',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':status'=>1,':debt'=>1));
            //echo $val->employee_id."<br>";
            foreach($newModel as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale->order_date)));
                }
                $temp = $expense->getExpenseSum($vale->expense_id,date('Y-m-d',strtotime($vale->order_date)));
                $empsum = $empsum + $temp;
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }
            $sum['cost'][$val->name] = $empsum;
            $sumPer['cost'][$val->name] = $empPersum;
            $summ['cost'] = $summ['cost'] + $empsum;
            $perSumm['cost'] = $perSumm['cost'] + $empPersum;
            $empsum = 0;
            $empPersum = 0;
            $percent = 0;
            $newModel2 = Expense::model()->findAll('t.employee_id = :id AND date(t.order_date) BETWEEN :from AND :to AND t.status != :status AND t.debt = :debt AND t.kind != 1 AND debtor_id = 0',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':status'=>1,':debt'=>1));
            //echo $val->employee_id."<br>";
            foreach($newModel2 as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale->order_date)));
                }
                $temp = $expense->getExpenseSum($vale->expense_id,date('Y-m-d',strtotime($vale->order_date)));
                $empsum = $empsum + $temp;
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }
            $sum['debt'][$val->name] = $empsum;
            $sumPer['debt'][$val->name] = $empPersum;
            $summ['debt'] = $summ['debt'] + $empsum;
            $perSumm['debt'] = $perSumm['debt'] + $empPersum;

            $empsum = 0;
            $empPersum = 0;
            $percent = 0;
            $newModel3 = Expense::model()->findAll('t.employee_id = :id AND date(t.order_date) BETWEEN :from AND :to AND t.status != :status AND t.debt = :debt AND t.kind != 1 AND debtor_id != 0 AND debtor_type = 0',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':status'=>1,':debt'=>1));
            //echo $val->employee_id."<br>";
            foreach($newModel3 as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale->order_date)));
                }
                $temp = $expense->getExpenseSum($vale->expense_id,date('Y-m-d',strtotime($vale->order_date)));
                $empsum = $empsum + $temp;
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }
            $sum['empdebt'][$val->name] = $empsum;
            $sumPer['empdebt'][$val->name] = $empPersum;
            $summ['empdebt'] = $summ['empdebt'] + $empsum;
            $perSumm['empdebt'] = $perSumm['empdebt'] + $empPersum;


            $empsum = 0;
            $empPersum = 0;
            $percent = 0;
            $newModel4 = Expense::model()->findAll('t.employee_id= :id AND date(t.order_date) BETWEEN :from AND :to AND t.status != :status AND t.debt = :debt AND t.kind != 1 AND debtor_id != 0 AND debtor_type = 1',array(':id'=>$val->employee_id,':from'=>$from,':to'=>$to,':status'=>1,':debt'=>1));
//echo "<pre>";
//print_r($newModel4);
//echo "</pre>";
            //echo $val->employee_id."<br>";
            foreach($newModel4 as $vale){
                if($val->check_percent == 1){
                    $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale->order_date)));
                }
                $temp = $expense->getExpenseSum($vale->expense_id,date('Y-m-d',strtotime($vale->order_date)));
                $empsum = $empsum + $temp;
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }
            $sum['cont'][$val->name] = $empsum;
            $sumPer['cont'][$val->name] = $empPersum;
            $summ['cont'] = $summ['cont'] + $empsum;
            $perSumm['cont'] = $perSumm['cont'] + $empPersum;
        }

        $this->renderPartial('test',array(
            'sum'=>$summ,
            'empSum'=>$sum,
            'sumPer'=>$perSumm,
            'empPerSum'=>$sumPer,
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
        $model = Expense::model()->with('order')->findAll('date(t.order_date) = :dates AND t.kind = :kind',array(':dates'=>$dates,':kind'=>0));
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
                    $messageType = 'success';
                    $message = "<strong>Well done!</strong> You successfully create data ";

                    foreach($_POST['product']['id'] as $key => $value){
                        $newModel = new Orders();
                        $newModel->expense_id = $model->expense_id;
                        $newModel->just_id = $value;
                        $newModel->type = 3;
                        $newModel->count = $_POST['product']['count'][$key];
                        $newModel->save();
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

        $model = Expense::model()->with()->findAll('date(t.order_date) BETWEEN :from AND :till AND  t.debt = :debt',array(':debt'=>1,':from'=>$from,':till'=>$till));
        $this->renderPartial('ajaxDeptList',array(
            'model'=>$model,
        ));

    }

    public function actionDebtClose($id)
    {
        $exp = new Expense();
        
        Expense::model()->updateByPk($id,array('debt'=>0));
        
            $dates = date('Y-m-d');
            $debt = new Debt();
            $debt->d_date = $dates;
            $debt->expense_id = $id;
            $debt->save();
        /*if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel($id)->updateByPk($id,array('debt'=>0));
            $dates = date('Y-m-d');
            $debt = new Debt();
            $debt->d_date = $dates;
            $debt->expense_id = $id;
            $debt->save();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('debtList'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    */
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
    
}