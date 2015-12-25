<?php

class ProductsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','export','import','editable','toggle','ajaxCheckProd'),
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
    private function logs($action,$tbName,$ID,$message){
        $dates = $dates = date('Y-m-d H:i:s');
        $modelss = new Logs;
        $modelss->log_date = $dates;
        $modelss->actions = $action;
        $modelss->table_name = $tbName;
        $modelss->curId = $ID;
        $modelss->message = $message;
        $modelss->save();
    }

    public function actionAjaxCheckProd(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('products')
            ->where('name = :name',array(':name'=>$_POST['value']))
            ->queryRow();
        if(!empty($model)){
            echo 1;
        }
        else{
            echo 0;
        }
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Products;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Products']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
				$model->attributes=$_POST['Products'];
				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				if($model->save()){
				    $this->addProd($model->product_id);
                    $this->logs('create','products',$model->product_id,$model->name);
					$messageType = 'success';
					$message = "<strong>Поздравляю!</strong> Вы успешно добавили продукт ";
					
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('create','id'=>$model->product_id));
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

     public function checkProd($id){
        $max_date = Balance::model()->find(array('select'=>'MAX(b_date) as b_date'));
        $result;
        $curProd = Balance::model()->findAll('date(t.b_date) = :dates',array(':dates'=>$max_date->b_date));
        
        foreach($curProd as $value){
            if($value->prod_id == $id){
                $result = true;
                break;
            }
            else{
                $result = false;
            }
        }
        return $result;
    }
    
    public function addProd($id){
        if($this->checkProd($id) != true){
            $max_date = Balance::model()->find(array('select'=>'MAX(b_date) as b_date'));
            $model = new Balance;
            $model->b_date = $max_date->b_date;
            $model->prod_id = $id;
            $model->startCount = 0;
            $model->endCount = 0;
            $model->save();
        }
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

		if(isset($_POST['Products']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$model->attributes=$_POST['Products'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";

				if($model->save()){
                    $this->logs('update','products',$model->product_id,$model->name);
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->product_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['Products'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->product_id));
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
			$this->loadModel($id)->updateByPk($id,array(
                'status'=>1
            ));

            $this->logs('delete','products',$id,'delete');
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
		$dataProvider=new CActiveDataProvider('Products');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
        $newModel = Products::model()->with('measure')->findAll();
		
		$model=new Products('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Products']))
			$model->attributes=$_GET['Products'];

		$this->render('index',array(
			'model'=>$model,
            'newModel'=>$newModel,
					));
		
			}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $newModel = Products::model()->with('measure')->findAll('status = :status',array(':status'=>0));
		
		$model=new Products('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Products']))
			$model->attributes=$_GET['Products'];

		$this->render('admin',array(
			'model'=>$model,
            'newModel'=>$newModel,
        ));
		
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Products the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Products::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Products $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='products-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Products;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Products']))
			$model->attributes=$_POST['Products'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Products',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'product_id',
					'name',
					'measure_id',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{
		
		$model=new Products;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Products']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Products']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Products']['name']['fileImport']);
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
						//$product_id=  $sheetData[$baseRow]['A'];
						$name=  $sheetData[$baseRow]['B'];
						$measure_id=  $sheetData[$baseRow]['C'];

						$model2=new Products;
						//$model2->product_id=  $product_id;
						$model2->name=  $name;
						$model2->measure_id=  $measure_id;

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
	    $es = new TbEditableSaver('Products'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Products',
        		)
    	);
	}



	
}
