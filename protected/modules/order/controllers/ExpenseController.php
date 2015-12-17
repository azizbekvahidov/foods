<?php

class ExpenseController extends Controller
{
    public $layout='/layouts/column1';
	public function actionIndex()
	{
		$this->render('index');
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
        
		if(isset($_POST['Expense']))
		{
        $_POST['Expense']['order_date'] = date('Y-m-d H:i:s');
            if($_POST['Expense']['debt'] == 0){
                $_POST['Expense']['status'] = 0;
            }
            else{
                $_POST['Expense']['status'] = 1;
            }
            if($_POST['Expense']['comment'] == ''){
                $_POST['Expense']['comment'] = '';
            }
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            $transaction = Yii::app()->db->beginTransaction();
			try{
                $archive = new ArchiveOrder();
                $archive_message = '';
				$messageType='warning';
				$message = "There are some errors ";
				$model->attributes=$_POST['Expense'];
				if($model->save()){
					$messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";
                    if(isset($_POST['dish'])){
                        $archive_message .= '*dish=>';
                        foreach($_POST['dish']['id'] as $key => $val){
                            $prodModel = new Orders;
                            $prodModel->expense_id = $model->expense_id;
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
                            $prodModel->expense_id = $model->expense_id;
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
                            $prodModel->expense_id = $model->expense_id;
                            $prodModel->just_id = $val;
                            $prodModel->type = 3;
                            $prodModel->count = $this->changeToFloat($_POST['product']['count'][$key]);
                            $prodModel->save();
                            $archive_message .= $val.":".$this->changeToFloat($_POST['product']['count'][$key]).",";
                        }
                    }
				    $archive->setArchive('create',$model->expense_id,$archive_message);
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

    public function actionUpLists(){
        $id = $_POST['id'];

        $newModel1 = Menu::model()->with('dish')->findAll('t.type_id = :types AND t.type = :type',array(':types'=>$id,':type'=>1));
        $newModel3 = Menu::model()->with('stuff')->findAll('t.type_id = :types AND t.type = :type',array(':types'=>$id,':type'=>2));
        $newModel2 = Menu::model()->with('products')->findAll('t.type_id = :types AND t.type = :type',array(':types'=>$id,':type'=>3));

        $this->renderPartial('uplist',array(
            'newModel1'=>$newModel1,
            'newModel3'=>$newModel3,
            'newModel2'=>$newModel2,
        ));
    }

    public function actionTodayOrder(){
        $dates = date('Y-m-d');
        $model = Expense::model()->with('order','employee')->findAll('date(t.order_date) = :dates',array(':dates'=>$dates));
        $percent = new Percent();
        $this->renderPartial('todayOrder',array(
            'model'=>$model,
            'percent'=>$percent->getPercent($dates)
        ));

    }

    public function actionUpdate($id){

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
            $model= Expense::model()->findByPk($_POST['expense_id']);
            $_POST['Expense']['order_date'] = date('Y-m-d H:i:s');
            if(isset($_POST['debt'])) {
                if ($_POST['debt'] == 1) {
                    $_POST['status'] = 1;
                }
            }
            else{
                $_POST['debt'] = 0;
                $_POST['status'] = 0;
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
            'expense_id'=>$id
        ));

    }

}