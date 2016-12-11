<?php

class DepRealizeController extends Controller
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
				'actions'=>array('update','index',),
				'roles'=>array('2'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('ajaxBackStorage','backStorage','create','view','admin','delete','export','import','editable','toggle','todayStorage','move','prodlist','getDepOut','DepOut'),
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

	public function actionGetDepOut(){
		$model = $Depfaktura = DepFaktura::model()->with('realizedProd')->findAll(array('group'=>'date(t.real_date)'));

        $this->render('getDepOut',array(
            'model'=>$model
        ));
	}

	public function actionDepOut(){
		$dates = $_POST['dates'];
		$model = $Depfaktura = DepFaktura::model()->with('realizedProd.product','department')->findAll('date(real_date) = :real_date AND t.fromDepId = :depId',array(':real_date'=>$dates,':depId'=>0));
		$this->renderPartial('depOut',array(
            'model'=>$model,
			'dates'=>$dates
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionTodayStorage(){
        if($_POST['dates'] == ''){
            $dates = date('Y:m:d');
        }else{
            $dates = $_POST['dates'];
        }
        $depId = $_POST['depId'];
        $Products = array();
        /*$model = DepBalance::model()->with('products')->findAll('t.department_id = :depId AND t.type = :type',array(':depId'=>$depId,'type'=>1));
        foreach ($model as $val) {
            $products[$val->prod_id] = $val->getRelated('products')->name;
        }*/

        //$model = new Products();
        $func = new Functions();
        $prod = $func->getStorageCount($dates);

        $this->renderPartial(
            'todayStorage',
            array(
                'Products'=>$prod['id'],
                'products'=>$prod['name'],
                'depId'=>$depId,
            )
        );
    }
	public function actionCreate()
	{
        if($_POST['from'] == ''){
            $dates = date('Y:m:d H:i:s');
        }else{
            $dates = $_POST['from']." ".date("H:i:s");
        }
		$model=new DepRealize;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['department']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
                $newModel = new DepFaktura();
                $depBalance = new DepBalance();
                $newModel->real_date = $dates;
                $newModel->department_id = $_POST['department'];

				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				if($newModel->save()){
				    foreach($_POST['products'] as $key => $val){
				        if($val != null){
    			            $realizeModel = new DepRealize();
                            $realizeModel->dep_faktura_id = $newModel->dep_faktura_id;
                            $realizeModel->prod_id = $key;
                            $realizeModel->price = 0;
                            $realizeModel->count = $this->changeToFloat($val);
                            $realizeModel->save();
                            $depBalance->addProd($key,$_POST['department'],$max_date['b_date']);
                        }
				    }
					$messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";

					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);

					$this->redirect(array('create'));
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
            'Products'=>$Products,
					));


	}

		public function actionBackStorage(){


				if(isset($_POST['products']))
				{
			        if($_POST['from'] == ''){
			            $dates = date('Y:m:d H:i:s');
			        }else{
			            $dates = $_POST['from']." ".date("H:i:s");
			        }
					$transaction = Yii::app()->db->beginTransaction();
					try{
						$messageType='warning';
						$message = "There are some errors ";
										$newModel = new DepFaktura();
										$newModel->real_date = $dates;
										$newModel->department_id = 0;
										$newModel->fromDepId = $_POST['department'];

						//$uploadFile=CUploadedFile::getInstance($model,'filename');
						if($newModel->save()){
								foreach($_POST['products'] as $key => $val){
										if($val != null){
													$realizeModel = new DepRealize();
																$realizeModel->dep_faktura_id = $newModel->dep_faktura_id;
																$realizeModel->prod_id = $key;
																$realizeModel->price = 0;
																$realizeModel->count = $this->changeToFloat($val);
																$realizeModel->save();
														}
								}
							$messageType = 'success';
							$message = "<strong>Well done!</strong> You successfully create data ";

							$transaction->commit();
							Yii::app()->user->setFlash($messageType, $message);

							$this->redirect(array('backStorage'));
						}
					}
					catch (Exception $e){
						$transaction->rollBack();
						Yii::app()->user->setFlash('error', "{$e->getMessage()}");
						//$this->refresh();
					}

			}
							$this->render('backStorage');
		}

		public function actionAjaxBackStorage(){
        if($_POST['dates'] == ''){
            $dates = date('Y:m:d');
        }else{
            $dates = $_POST['dates'];
        }
				$depId = $_POST['depId'];
				$depRealize = new DepRealize();
				$prod = $depRealize->getDepProdCurCount($depId,$dates);
				$this->renderPartial('ajaxBackStorage',array(
						'products' => $prod
				));
		}

    public function actionProdlist(){
        if($_POST['dates'] == ''){
            $dates = date('Y:m:d');
        }else{
            $dates = $_POST['dates'];
        }
        $halfstuff = array();
        $products = array();
        $inProducts = array();
        $inHalfstuff = array();
        $depId = $_POST['depId'];$model = DepBalance::model()->with('products')->findAll('t.department_id = :depId AND t.type = :type',array(':depId'=>$depId,'type'=>1));
        foreach ($model as $val) {
            $products[$val->prod_id] = $val->getRelated('products')->name;
        }

        //$model = new Products();
        //$products = $model->getProdName($depId);


        $departMoveOut = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.department_id = :depId AND t.fromDepId <> :fromDepId',array(':dates'=>$dates,':depId'=>$_POST['depsId'],':fromDepId'=>0));

        foreach($departMoveOut as $key => $val){
            foreach($val->getRelated('realizedProd') as $value){
                $depIn[$value->prod_id] = $depIn[$value->prod_id] + $value->count;
            }
        }

        $departMoveIn = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.fromDepId = :depId',array(':dates'=>$dates,':depId'=>$_POST['depsId']));

        foreach($departMoveIn as $value){
            foreach($value->getRelated('realizedProd') as $val){

                $depOut[$val->prod_id] = $depOut[$val->prod_id] + $val->count;
            }
        }
        $balance = DepBalance::model()->with('products')->findAll('t.b_date = :dates AND t.department_id = :depId AND t.type = :types',array(':dates'=>$dates,':depId'=>$_POST['depsId'],':types'=>1));



        $model = DepFaktura::model()->with('realizedProd')->findAll('t.department_id = :depId AND date(t.real_date) = :dates',array(':depId'=>$_POST['depsId'],':dates'=>$dates));
        if(!empty($model)){
            foreach($model as $value){
                foreach($value->getRelated('realizedProd') as $val){
                    $inProducts[$val->prod_id] = $inProducts[$val->prod_id] + $val->count ;
                }
            }
        }

        $dishProd = Expense::model()->with('order.dish.dishStruct.Struct')->findAll('date(order_date) = :dates AND dish.department_id = :department_id',array(':dates'=>$dates,':department_id'=>$_POST['depsId']));

        if(!empty($dishProd)){
            foreach($dishProd as $value){
                foreach($value->getRelated('order') as $val){
                    foreach($val->getRelated('dish')->getRelated('dishStruct') as $vals){

                        $outProduct[$vals->prod_id] = $outProduct[$vals->prod_id] + $vals->amount/$val->getRelated('dish')->count*$val->count;

                    }
                }
            }
        }

        $Prod = Expense::model()->with('order.products')->findAll('date(order_date) = :dates AND products.department_id = :department_id',array(':dates'=>$dates,':department_id'=>$_POST['depsId']));
        if(!empty($Prod)){
            foreach($Prod as $value){
                foreach($value->getRelated('order') as $val){
                    $outProduct[$val->just_id] = $outProduct[$val->just_id] + $val->count;
                }
            }
        }
        foreach($balance as $value){
            $endCount[$value->prod_id] = $value->startCount + $inProducts[$value->prod_id] - $outProduct[$value->prod_id] + $depIn[$value->prod_id] - $depOut[$value->prod_id];
        }
        $this->renderPartial('lists',array(
            'endCount' => $endCount,
            'products' => $products,
            'halfstuff' => $halfstuff,
            'inHalfstuff' => $inHalfstuff,
        ));

    }
    public function actionMove(){

        if($_POST['from'] == ''){
            $dates = date('Y:m:d H:i:s');
        }else{
            $dates = $_POST['from'];
        }
		$model=new DepRealize;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['department']) && isset($_POST['departments']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
                $newModel = new DepFaktura;
                $newModel->real_date = $dates;
                $newModel->department_id = $_POST['department'];
                $newModel->fromDepId = $_POST['departments'];

				if($newModel->save()){
				    foreach($_POST['products'] as $key => $val){
			            if($val != null){
    			            $realizeModel = new DepRealize;
                            $realizeModel->dep_faktura_id = $newModel->dep_faktura_id;
                            $realizeModel->prod_id = $key;
                            $realizeModel->price = 0;
                            $realizeModel->count = $this->changeToFloat($val);
                            if($realizeModel->save()){
            					$messageType = 'success';
            					$message = "<strong>Well done!</strong> You successfully create data ";

                            }
                        }
				    }
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('move'));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				//$this->refresh();
			}

		}

		$this->render('move',array(
			'model'=>$model,
            'Products'=>$Products,
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

		if(isset($_POST['DepRealize']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$model->attributes=$_POST['DepRealize'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";

				/*
				$uploadFile=CUploadedFile::getInstance($model,'filename');
				if(!empty($uploadFile)) {
					$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
					if(!empty($uploadFile)) {
						if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'deprealize'.DIRECTORY_SEPARATOR.$model->dep_realize_id.DIRECTORY_SEPARATOR.$model->dep_realize_id.'.'.$extUploadFile)){
							$model->filename=$model->dep_realize_id.'.'.$extUploadFile;
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
					$this->redirect(array('view','id'=>$model->dep_realize_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh();
			}

			$model->attributes=$_POST['DepRealize'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->dep_realize_id));
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
		$dataProvider=new CActiveDataProvider('DepRealize');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/

		$model=new DepRealize('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DepRealize']))
			$model->attributes=$_GET['DepRealize'];

		$this->render('index',array(
			'model'=>$model,
					));

			}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{

		$model=new DepRealize('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DepRealize']))
			$model->attributes=$_GET['DepRealize'];

		$this->render('admin',array(
			'model'=>$model,
					));

			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DepRealize the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DepRealize::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DepRealize $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dep-realize-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExport()
    {
        $model=new DepRealize;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['DepRealize']))
			$model->attributes=$_POST['DepRealize'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of DepRealize',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(

					'dep_realize_id',
					'dep_faktura_id',
					'prod_id',
					'price',
					'count',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{

		$model=new DepRealize;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DepRealize']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['DepRealize']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['DepRealize']['name']['fileImport']);
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
						//$dep_realize_id=  $sheetData[$baseRow]['A'];
						$dep_faktura_id=  $sheetData[$baseRow]['B'];
						$prod_id=  $sheetData[$baseRow]['C'];
						$price=  $sheetData[$baseRow]['D'];
						$count=  $sheetData[$baseRow]['E'];

						$model2=new DepRealize;
						//$model2->dep_realize_id=  $dep_realize_id;
						$model2->dep_faktura_id=  $dep_faktura_id;
						$model2->prod_id=  $prod_id;
						$model2->price=  $price;
						$model2->count=  $count;

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
	    $es = new TbEditableSaver('DepRealize');
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'DepRealize',
        		)
    	);
	}


}
