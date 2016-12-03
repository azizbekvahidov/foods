<?php

class InexpenseController extends Controller
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
				'actions'=>array('index','view',),
				'roles'=>array('2'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('create','update','stuffList','move','admin','delete','deleteInorder','export','import','editable','toggle','listStuff','ProdList','createDish','listDish','dishList','struct','count','today'),
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


    public function actionMove(){
        if($_POST['from'] == ''){
            $dates = date('Y:m:d H:i:s');
        }else{
            $dates = $_POST['from'];
        }
        $model=new Inexpense;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['department']) && isset($_POST['departments']))
        {
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $messageType='warning';
                $message = "There are some errors ";
                $model = new Inexpense();

                $model->inexp_date = $dates;
                $model->department_id = $_POST['department'];
                $model->fromDepId = $_POST['departments'];
                if($model->save()){
                    foreach($_POST['products'] as $key => $val){
                        if($val != null){
                            $realizeModel = new Inorder;
                            $realizeModel->inexpense_id = $model->inexpense_id;
                            $realizeModel->stuff_id = $key;
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
        ));
    }

    public function actionStuffList(){
        if($_POST['dates'] == ''){
            $dates = date('Y-m-d');
        }else{
            $dates = $_POST['dates'];
        }
        $stuff = array();
        $depId = $_POST['depId'];
        $depsId = $_POST['depsId'];

        $model = DepBalance::model()->with('stuff')->findAll('t.department_id = :depId AND date(t.b_date) = :dates AND t.type = :type',array(':depId'=>$depsId,':dates'=>$dates,':type'=>2));

        foreach ($model as $val) {
            $stuff[$val->prod_id] = $stuff[$val->prod_id] + $val->startCount;
        }


        $model2 = Inexpense::model()->with('inorder')->findAll('date(t.inexp_date) = :dates AND t.department_id = :depId AND t.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>$depsId,':fromDepId'=>0));

        foreach ($model2 as $value) {
            foreach ($value->getRelated('inorder') as $val) {
                $stuff[$val->stuff_id] = $stuff[$val->stuff_id] + $val->count;
            }
        }

        $model3 = DepBalance::model()->with('stuff')->findAll('t.department_id = :depId AND date(t.b_date) = :dates AND t.type = :type',array(':depId'=>$depId,':dates'=>$dates,':type'=>2));
        $model4 = Inexpense::model()->with('inorder')->findAll('date(t.inexp_date) = :dates AND t.department_id != :depId AND t.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>0,':fromDepId'=>$depsId));


        foreach ($model4 as $value) {
            foreach ($value->getRelated('inorder') as $val) {
                $stuff[$val->stuff_id] = $stuff[$val->stuff_id] - $val->count;
            }

        }


        $this->renderPartial('stuffList',array(
            'stuff'=>$stuff,
            'model'=>$model3,
        ));

    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
     
    public function actionListStuff(){
        echo "asdadsad";
        $dep_id = $_POST['depId'];
        $stuffs = array();
        $model = new Halfstaff();
        $stuffs = $model->getStuffName($dep_id);
        $this->renderPartial('listStuff',array(
            'model'=>$stuffs,
        ));
        
    }
    
    public function actionProdList(){
        foreach($_GET['count'] as $key => $value){
            if($value != ''){
                $model[$_GET['product_id'][$key]] = Halfstaff::model()->with('stuffStruct.Struct.measure')->findAll('t.halfstuff_id = :id AND stuffStruct.types = :types',array(':id'=>$_GET['product_id'][$key],':types'=>1));
                $count[$_GET['product_id'][$key]] = $this->changeToFloat($value);
                
            }
        }
            $content = $this->renderPartial('ProdList',array(
                'model'=>$model,
                'count'=>$count,
                'model2'=>$model2,
    		));
        
        
    }

    public function actionStruct(){
        $model = Halfstaff::model()->with('stuffStruct.Struct.measure')->findByPk($_GET['data']);
        $this->renderPartial('struct',array(
            'model'=>$model
        ));
    }

    public function actionCount(){
        $prodVal = $_POST['prodVal'];
        $stuffId = $_POST['stuffId'];
        $prodId = $_POST['prodId'];
        $result = 0;
        $model = Halfstaff::model()->with('stuffStruct.Struct.measure')->findByPk($stuffId);
        foreach ($model->getRelated('stuffStruct') as $val) {
            if($val->prod_id == $prodId){
                $result = $prodVal*$model->count/$val->amount;
            }
        }
        echo $result;
    }

    public function actionToday(){
        $dates = date('Y-m-d');
        $depId = CHtml::listData(Department::model()->findAll(),'department_id','name');
        $model = Inexpense::model()->with('inorder.stuffs.halfstuffType')->findAll('date(t.inexp_date) = :dates AND t.fromDepId = :fromDepId ',array(':dates'=>$dates,':fromDepId'=>0));

        $this->render('today',array(
            'model'=>$model,
            'depId'=>$depId,
        ));
    }

	public function actionCreate()
	{
        if($_POST['from'] == ''){
            $dates = date('Y:m:d H:i:s');
        }else{
            $dates = $_POST['from'];
        }
		$model=new Inexpense;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Inexpense']))
		{
            $_POST['Inexpense']['type'] = 1;
            $_POST['Inexpense']['inexp_date'] = $dates;
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
				$model->attributes=$_POST['Inexpense'];
				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				if($model->save()){
				    
				    if(isset($_POST['product_id'])){
    				    foreach($_POST['product_id'] as $key => $value){
    				        if($_POST['count'][$key] != '') {
                                $newModel = new Inorder;
                                $newModel->inexpense_id = $model->inexpense_id;
                                $newModel->stuff_id = $value;
                                $newModel->count = $this->changeToFloat($_POST['count'][$key]);
                                $newModel->save();
                            }
    				    }
                    }
					$messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";
					/*
					$model2 = Inexpense::model()->findByPk($model->inexpense_id);						
					if(!empty($uploadFile)) {
						$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
						if(!empty($uploadFile)) {
							if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'inexpense'.DIRECTORY_SEPARATOR.$model2->inexpense_id.DIRECTORY_SEPARATOR.$model2->inexpense_id.'.'.$extUploadFile)){
								$model2->filename=$model2->inexpense_id.'.'.$extUploadFile;
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
					));
		
				
	}

    public function actionDishList(){
        foreach($_GET['count'] as $key => $value){
            if($value != ''){
                $model[$_GET['product_id'][$key]] = Dishes::model()->with('dishStruct.Struct','halfstuff.Structs')->findAll('t.dish_id = :id',array(':id'=>$_GET['product_id'][$key]));
                $count[$_GET['product_id'][$key]] = $value;

            }
        }
        $content = $this->renderPartial('dishList',array(
            'model'=>$model,
            'count'=>$count,
        ));


    }
    public function actionListDish(){
        $depId = $_POST['depId'];
        $dishList = Dishes::model()->findAll('t.department_id = :depId',array(':depId'=>$depId));

        $this->renderPartial('listDish',array(
            'model' => $dishList,
        ));
    }

    public function actionCreateDish(){
        $model = new Inexpense();
        if(isset($_POST['Inexpense']))
        {
            $_POST['Inexpense']['type'] = 0;
            $_POST['Inexpense']['inexp_date'] = date('Y-m-d H:i:s');
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $messageType='warning';
                $message = "There are some errors ";
                $model->attributes=$_POST['Inexpense'];
                //$uploadFile=CUploadedFile::getInstance($model,'filename');
                if($model->save()){

                    if(isset($_POST['product_id'])){
                        foreach($_POST['product_id'] as $key => $value){
                            if($_POST['count'][$key] != '') {
                                $newModel = new Inorder;
                                $newModel->inexpense_id = $model->inexpense_id;
                                $newModel->stuff_id = $value;
                                $newModel->count = $_POST['count'][$key];
                                $newModel->save();
                            }
                        }
                    }
                    $messageType = 'success';
                    $message = "<strong>Well done!</strong> You successfully create data ";
                    /*
                    $model2 = Inexpense::model()->findByPk($model->inexpense_id);
                    if(!empty($uploadFile)) {
                        $extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
                        if(!empty($uploadFile)) {
                            if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'inexpense'.DIRECTORY_SEPARATOR.$model2->inexpense_id.DIRECTORY_SEPARATOR.$model2->inexpense_id.'.'.$extUploadFile)){
                                $model2->filename=$model2->inexpense_id.'.'.$extUploadFile;
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
                    $this->redirect(array('view','id'=>$model->inexpense_id));
                }
            }
            catch (Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }

        }
        $this->render('createDish',array(
            'model' => $model,
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

		if(isset($_POST['Inexpense']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$model->attributes=$_POST['Inexpense'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";

				/*
				$uploadFile=CUploadedFile::getInstance($model,'filename');
				if(!empty($uploadFile)) {
					$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
					if(!empty($uploadFile)) {
						if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'inexpense'.DIRECTORY_SEPARATOR.$model->inexpense_id.DIRECTORY_SEPARATOR.$model->inexpense_id.'.'.$extUploadFile)){
							$model->filename=$model->inexpense_id.'.'.$extUploadFile;
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
					$this->redirect(array('view','id'=>$model->inexpense_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['Inexpense'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->inexpense_id));
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

    public function actionDeleteInorder($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model = new Inorder();
            $model->deleteByPk($id);

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
		$dataProvider=new CActiveDataProvider('Inexpense');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		
		$model=new Inexpense('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Inexpense']))
			$model->attributes=$_GET['Inexpense'];

		$this->render('index',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$model=new Inexpense('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Inexpense']))
			$model->attributes=$_GET['Inexpense'];

		$this->render('admin',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Inexpense the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Inexpense::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Inexpense $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='inexpense-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Inexpense;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Inexpense']))
			$model->attributes=$_POST['Inexpense'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Inexpense',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'inexpense_id',
					'inexp_date',
					'department_id',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{
		
		$model=new Inexpense;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Inexpense']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Inexpense']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Inexpense']['name']['fileImport']);
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
						//$inexpense_id=  $sheetData[$baseRow]['A'];
						$inexp_date=  $sheetData[$baseRow]['B'];
						$department_id=  $sheetData[$baseRow]['C'];

						$model2=new Inexpense;
						//$model2->inexpense_id=  $inexpense_id;
						$model2->inexp_date=  $inexp_date;
						$model2->department_id=  $department_id;

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
	    $es = new TbEditableSaver('Inexpense'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Inexpense',
        		)
    	);
	}

	
}
