<?php

class OrdersController extends SetupController
{
	
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
		
	public $layout='//layouts/column1';		
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
				'actions'=>array('index','view',),
				'roles'=>array('2'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('PrintCheck','create','update','admin','delete','export','import','editable','toggle','orderRefuse','refuse','RemoveFromOrder','printCheck'),
                'roles'=>array('3'),
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
		
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
		if(isset($_GET['asModal'])){
			$this->renderPartial('view',array(
				'model'=>$this->loadModel($id),
			));
		}
		else{
						
			$this->render('view',array(
				'model'=>$this->loadModel($id),
			));
			
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
				
		$model=new Orders;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Orders']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
				$model->attributes=$_POST['Orders'];
				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				if($model->save()){
					$messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";
					/*
					$model2 = Orders::model()->findByPk($model->order_id);						
					if(!empty($uploadFile)) {
						$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
						if(!empty($uploadFile)) {
							if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'orders'.DIRECTORY_SEPARATOR.$model2->order_id.DIRECTORY_SEPARATOR.$model2->order_id.'.'.$extUploadFile)){
								$model2->filename=$model2->order_id.'.'.$extUploadFile;
								$model2->save();
								$message .= 'and file uploded';
							}
							else{
								$messageType = 'warning';
								$message .= 'but file not uploded';
							}
						}						
					}
					*/
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->order_id));
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
					));
		
				
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Orders']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$model->attributes=$_POST['Orders'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";

				/*
				$uploadFile=CUploadedFile::getInstance($model,'filename');
				if(!empty($uploadFile)) {
					$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
					if(!empty($uploadFile)) {
						if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'orders'.DIRECTORY_SEPARATOR.$model->order_id.DIRECTORY_SEPARATOR.$model->order_id.'.'.$extUploadFile)){
							$model->filename=$model->order_id.'.'.$extUploadFile;
							$message .= 'and file uploded';
						}
						else{
							$messageType = 'warning';
							$message .= 'but file not uploded';
						}
					}						
				}
				*/

				if($model->save()){
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->order_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['Orders'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->order_id));
		}

		$this->render('update',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		/*
		$dataProvider=new CActiveDataProvider('Orders');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		
		$model=new Orders('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Orders']))
			$model->attributes=$_GET['Orders'];

		$this->render('index',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$model=new Orders('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Orders']))
			$model->attributes=$_GET['Orders'];

		$this->render('admin',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Orders the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Orders::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Orders $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='orders-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Orders;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Orders']))
			$model->attributes=$_POST['Orders'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Orders',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'order_id',
					'expense_id',
					'just_id',
					'type',
					'count',
					'table_id',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{
		
		$model=new Orders;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Orders']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Orders']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Orders']['name']['fileImport']);
				if (in_array(@$fileParts['extension'],$fileTypes)) {

					Yii::import('ext.heart.excel.EHeartExcel',true);
	        		EHeartExcel::init();
	        		$inputFileType = PHPExcel_IOFactory::identify($tempFile);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($tempFile);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$baseRow = 2;
					$inserted=0;
					$read_status = false;
					while(!empty($sheetData[$baseRow]['A'])){
						$read_status = true;						
						//$order_id=  $sheetData[$baseRow]['A'];
						$expense_id=  $sheetData[$baseRow]['B'];
						$just_id=  $sheetData[$baseRow]['C'];
						$type=  $sheetData[$baseRow]['D'];
						$count=  $sheetData[$baseRow]['E'];
						$table_id=  $sheetData[$baseRow]['F'];

						$model2=new Orders;
						//$model2->order_id=  $order_id;
						$model2->expense_id=  $expense_id;
						$model2->just_id=  $just_id;
						$model2->type=  $type;
						$model2->count=  $count;
						$model2->table_id=  $table_id;

						try{
							if($model2->save()){
								$inserted++;
							}
						}
						catch (Exception $e){
							Yii::app()->user->setFlash('error', "{$e->getMessage()}");
							//$this->refresh();
						} 
						$baseRow++;
					}	
					Yii::app()->user->setFlash('success', ($inserted).' row inserted');	
				}	
				else
				{
					Yii::app()->user->setFlash('warning', 'Wrong file type (xlsx, xls, and ods only)');
				}
			}


			$this->render('admin',array(
				'model'=>$model,
			));
		}
		else{
			$this->render('admin',array(
				'model'=>$model,
			));
		}
	}

	public function actionEditable(){
		Yii::import('bootstrap.widgets.TbEditableSaver'); 
	    $es = new TbEditableSaver('Orders'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Orders',
        		)
    	);
	}

    public function actionOrderRefuse($id){
        $expId = $id;
        $this->render("orderRefuse",array(
            'expId'=>$expId
        ));
    }

    public function actionRefuse(){
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

        $this->renderPartial('refuse',array(
            'order'=>$order,
            'order2'=>$order2,
            'order3'=>$order3,
            'model'=>$model,
            'dates'=>$dates
        ));
    }


    public function actionRemoveFromOrder(){
        $expId = intval($_POST['expenseId']);
        $types = 0;
        $refuseTime = date("Y-m-d H:i:s");
        $temp = explode('_',$_POST['id']);
        $empId = 0;
        if($temp[0] == 'dish')
            $types = 1;
        if($temp[0] == 'stuff')
            $types = 2;
        if($temp[0] == 'product')
            $types = 3;
        $count = floatval($_POST['count']);
        if(isset($expId)){
            if ($count >= 0) {
                $model = Yii::app()->db->createCommand()
                    ->select()
                    ->from('orders o')
                    ->join("expense ex","ex.expense_id = o.expense_id")
                    ->where('o.expense_id = :id AND o.just_id = :just_id AND o.type = :types', array(':id' => $expId, ':just_id' => $temp[1], ':types' => $types))
                    ->queryRow();
                if (!empty($model)){
                    Yii::app()->db->createCommand()->insert('orderRefuse',array(
                        'order_id' => $model['order_id'],
                        'count' => $model['count']-$count,
                        'refuse_time' => $refuseTime
                    ));
                    if($count == 0){
                        Yii::app()->db->createCommand()->update('orders', array(
                            'deleted' => 1,
                            'count' => $count
                        ), 'order_id = :id', array(':id' => $model['order_id']));
                    }
                    else {
                        Yii::app()->db->createCommand()->update('orders', array(

                            'count' => $count
                        ), 'order_id = :id', array(':id' => $model['order_id']));
                    }
                    $percent = new Percent();
                    $emp = Yii::app()->db->CreateCommand()
                        ->select()
                        ->from("employee")
                        ->where("employee_id = :id",array(":id"=>$model["employee_id"]))
                        ->queryRow();
                    echo "<pre>";
                    print_r($model);
                    echo "</pre>";
                    if($emp["check_percent"] == 1) {
                        if($model['banket'] == 0){
                            $_POST['expSum']=number_format(($_POST['expSum'] + $_POST['expSum'] * $percent->getPercent(date("Y-m-d")) / 100) / 100, 0, ',', '') * 100;
                        }
                        else{
                            echo "<pre>";
                            print_r($_POST);
                            echo "</pre>";
                            $_POST['expSum']=number_format(($_POST['expSum'] + $_POST['expSum'] * Yii::app()->config->get("banket_percent") / 100) / 100, 0, ',', '') * 100;
                        }
                    }
                    else{
                        $_POST['expSum']=number_format(($_POST['expSum'] + $_POST['expSum'] * $percent->getPercent(date("Y-m-d")) / 100) / 100, 0, ',', '') * 100;
                    }
					Yii::app()->db->createCommand()->update('expense', array(

                            'expSum' => $_POST["expSum"]
                        ), 'expense_id = :id', array(':id' => $expId));
					
                }

            }
        }
        $orders = Yii::app()->db->createCommand()
            ->select()
            ->from("expense ex")
            ->join("orders o","o.expense_id = ex.expense_id")
            ->where("ex.expense_id = :id and o.deleted != 1",array(":id"=>$expId))
            ->queryAll();
        $dishMsg = '*dish=>';
        $stuffMsg = '*stuff=>';
        $prodMsg = '*prod=>';
        $dishMessage = '';
        $stuffMessage = '';
        $prodMessage = '';
        $archive_message = '';
        foreach ($orders as $key => $val) {
            $empId = $val["employee_id"];
            $count = floatval($val["count"]);

            if($val["type"] == "1") {
                $dishMessage .= $val["just_id"].":".$count.",";
            }
            if($val["type"] == "2") {
                $stuffMessage .= $val["just_id"].":".$count.",";
            }
            if($val["type"] == "3") {
                $prodMessage .= $val["just_id"].":".$count.",";
            }



        }
        $archive_message .= ((!empty($dishMessage)) ? $dishMsg.$dishMessage : '').((!empty($stuffMessage)) ? $stuffMsg.$stuffMessage : '').((!empty($prodMessage)) ? $prodMsg.$prodMessage : '');

        $archive = new Orders();
        $archive->setArchive('update', $expId, $archive_message,$empId);
    }

    public function actionPrintCheck(){
        $expId = $_POST["expId"];
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("expense ex")
            ->where("ex.expense_id = :id",array(":id"=>$expId))
            ->queryRow();

        $function = new Orders();
        $function->PrintCheck($expId,'update',$_POST['id'],$model['employee_id'],$_POST['count'],$model['table']);

    }
}
