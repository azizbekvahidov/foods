<?php

class MenuController extends Controller
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
				'actions'=>array('index','view','create','update','checkParent','struct','admin','delete','export','import','editable','toggle','menuList'),
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionCheckParent(){
        $id = $_POST['type_id'];
        $mType = $_POST['mType'];
        $lists = CHtml::listData(Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>$id)),'type_id','name');
        $this->renderPartial('child',array(
            'lists'=>$lists,
            'mType'=>$mType
        ));
    }     
    public function actionStruct(){
        $id = $_POST['type_id'];
        $mType = $_POST['mType'];

        $dishModel = Menu::model()->with('dish')->findAll('t.type_id = :typeId AND mType = :mType',array(':typeId'=>$id,':mType'=>$mType));

        
        $prodModel = Menu::model()->with('products')->findAll('t.type_id = :typeId AND mType = :mType',array(':typeId'=>$id,':mType'=>$mType));
        
        $stuffModel = Menu::model()->with('halfstuff')->findAll('t.type_id = :typeId AND mType = :mType',array(':typeId'=>$id,':mType'=>$mType));
        $listDep = CHtml::listData(Department::model()->findAll(),'department_id','name');
        $this->renderPartial('struct',array(
            'id'=>$id,
            'mType'=>$mType,
            'listDep'=>$listDep,
            'dishModel'=>$dishModel,
            'prodModel'=>$prodModel,
            'stuffModel'=>$stuffModel,    
        ));
        
    }
	public function actionCreate()
	{

		$model=new Menu;
        $depId = array();
        $depBalance = new DepBalance();
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['dish']) || isset($_POST['stuff']) || isset($_POST['product']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors";
                foreach($_POST as $keys => $value){
                    $message = "<strong>";
                    if($keys == 'dish'){ 
                        foreach($value['id'] as $key => $val){
                            $newModel = Menu::model()->findByPk($value['menu_id'][$key]);
                            if(empty($newModel)){
                                $newModel = new Menu;
                            }
                            $newModel->just_id = $val;
                            $newModel->mType = $_POST['mType'];
                            $newModel->type = 1;
                            $newModel->type_id = $value['type'][$key];
                            if($newModel->save()) {
			                    $messageType = 'success';
                            }
                            $this->priceAdd($val,$_POST['mType'],$value['price'][$key],1);
                            Dishes::model()->updateByPk($val,array('price'=>$value['price'][$key],'department_id'=>$value['dep'][$key]));
			                   $message .= "Блюда";
                            $depId[$value['dep'][$key]] = $value['dep'][$key];
                        }
                    }
                    if($keys == 'stuff'){
                        
                        foreach($value['id'] as $key => $val){
                            $newModel = Menu::model()->findByPk($value['menu_id'][$key]);
                            if(empty($newModel)){
                                $newModel = new Menu;
                            }
                            $newModel->just_id = $val;
                            $newModel->mType = $_POST['mType'];
                            $newModel->type = 2;
                            $newModel->type_id = $value['type'][$key];
                            if($newModel->save()) {
			                    $messageType = 'success';
                            }
                            $this->priceAdd($val,$_POST['mType'],$value['price'][$key],2);
                            Halfstaff::model()->updateByPk($val,array('price'=>$value['price'][$key],'department_id'=>$value['dep'][$key]));
			                   $message .= ", Полуфабрикаты";
                            $depId[$value['dep'][$key]] = $value['dep'][$key];
                        }
                    }
                    if($keys == 'product'){
                        foreach($value['id'] as $key => $val){
                            $newModel = Menu::model()->findByPk($value['menu_id'][$key]);
                            if(empty($newModel)){
                                $newModel = new Menu;
                            }
                            $newModel->just_id = $val;
                            $newModel->mType = $_POST['mType'];
                            $newModel->type = 3;
                            $newModel->type_id = $value['type'][$key];
                            if($newModel->save()) {
			                    $messageType = 'success';
                            }
                            $this->priceAdd($val,$_POST['mType'],$value['price'][$key],3);
                               Products::model()->updateByPk($val,array('price'=>$value['price'][$key],'department_id'=>$value['dep'][$key]));
			                   $message .= ", Продукты ";
                            $depId[$value['dep'][$key]] = $value['dep'][$key];
                        }
                    }
                }
                foreach ($depId as $val) {
                    $depBalance->refreshBalance($val);
                }

                $message .= "Успешно добавлены</strong>";
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
				
					$this->redirect(array('index'));
								
                //$this->redirect(array('index'));
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				//$this->refresh();
			}
			
		}

		$this->render('create',array(
			'model'=>$model,
            'dishModel'=>$dishModel,
            'prodModel'=>$prodModel,
            'stuffModel'=>$stuffModel
					));

				
	}

    public function priceAdd($id,$mType,$price,$types){
        $model = new Prices();
        $dates = date('Y-m-d H:i:s');
        $model->price_date = $dates;
        $model->price = $price;
        $model->menu_type = $mType;
        $model->just_id = $id;
        $model->types = $types;
        $model->save();


    }

    public function actionMenuList(){

        $this->renderPartial('menuList',array(
            'mType'=>$_POST['mType']
        ));
    }

    public function checkProd($id,$depId){
        $max_date = DepBalance::model()->find(array('select'=>'MAX(b_date) as b_date'));

        $curDepProd = DepBalance::model()->findAll('date(t.b_date) = :dates AND t.type = :types AND t.department_id = :depId',array(':dates'=>$max_date->b_date,':types'=>1,':depId'=>$depId));
        
        foreach($curDepProd as $value){
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
    
    public function checkStuff($id,$depId){
        $max_date = DepBalance::model()->find(array('select'=>'MAX(b_date) as b_date'));

            $curDepProd = DepBalance::model()->findAll('date(t.b_date) = :dates AND t.type = :types AND t.department_id = :depId',array(':dates'=>$max_date->b_date,':types'=>2,':depId'=>$depId));
        
        foreach($curDepProd as $value){
            
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
    
    public function addProd($id,$depId){
        if($this->checkProd($id,$depId) != true){
            $max_date = DepBalance::model()->find(array('select'=>'MAX(b_date) as b_date'));
            $model = new DepBalance;
            $model->b_date = $max_date->b_date;
            $model->prod_id = $id;
            $model->startCount = 0;
            $model->endCount = 0;
            $model->department_id = $depId;
            $model->type = 1;
            $model->save();
        }
    }
    
    public function addStuff($id,$depId){
        if($this->checkStuff($id,$depId) != true){
            $max_date = DepBalance::model()->find(array('select'=>'MAX(b_date) as b_date'));
            $model = new DepBalance;
            $model->b_date = $max_date->b_date;
            $model->prod_id = $id;
            $model->startCount = 0;
            $model->endCount = 0;
            $model->department_id = $depId;
            $model->type = 2;
            $model->save();
                //Список полуфабрикатов и их продуктов
                $dishStruct = Halfstaff::model()->with('stuffStruct.Struct')->findByPk($id,'stuffStruct.types = :types',array(':types'=>1));
                
                if(!empty($dishStruct))
                    foreach($dishStruct->getRelated('stuffStruct') as $val){
                        $this->addProd($val->getRelated('Struct')->product_id,$depId);
                    }
                
                //Список подполуфабрикатов и их продуктов
                $stuffStructs = Halfstaff::model()->with('stuffStruct.podstuff.podstuffStruct.Struct')->findByPk($id,'stuffStruct.types = :types',array(':types'=>2));
                
                if(!empty($stuffStructs))
                    foreach($stuffStructs->getRelated('stuffStruct') as $val){
                        $this->addStuff($val->prod_id,$depId);
                        foreach($val->getRelated('podstuff')->getRelated('podstuffStruct') as $vals){
                            $this->addProd($vals->prod_id,$depId);
                        }
                    }
            
            
        }
    }
    public function addDish($id,$depId){
        //Корневые продукты блюда выбранного отдела
        $dishProducts = Dishes::model()->with('products')->findByPk($id,'t.department_id = :depId',array(':depId'=>$depId));
        if(!empty($dishProducts))
            foreach($dishProducts->getRelated('products') as $val){
                $this->addProd($val->product_id,$depId);
            }
        
        //Корневые полуфабрикаты блюда выбранного отдела
        $DishStuff = Dishes::model()->with('stuff')->findByPk($id,'t.department_id = :depId',array(':depId'=>$depId));
        if(!empty($DishStuff))
            foreach($DishStuff->getRelated('stuff') as $val){
                $this->addStuff($val->halfstuff_id,$depId);
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

		if(isset($_POST['dish']) or isset($_POST['stuff']) or isset($_POST['product']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
                foreach($_POST as $keys => $value){
                    $message = "<strong>";
                    if($keys == 'dish'){
                        foreach($value['id'] as $key => $val){
                            $newModel = new Menu;
                            $newModel->just_id = $val;
                            $newModel->type = 1;
                            $newModel->type_id = $value['type'][$key];
                            if($newModel->save()) 
			                   $messageType = 'success';
                            Dishes::model()->updateByPk($val,array('price'=>$value['price'][$key]));
			                   $message .= "Блюда";
                        }
                    }
                    /*if($keys == 'stuff'){
                        foreach($value['id'] as $key => $val){
                            echo $value['price'][$key]."<br />";
                            $newModel = new Menu;
                            $newModel->just_id = $val;
                            $newModel->type = 2;
                            if($newModel->save()) 
			                   $messageType = 'success';
                            Halfstaff::model()->updateByPk($val,array('price'=>$value['price'][$key]));
			                   $message .= ", Полуфабрикаты";
                        }
                    }*/
                    if($keys == 'product'){
                        foreach($value['id'] as $key => $val){
                            $newModel = new Menu;
                            $newModel->just_id = $val;
                            $newModel->type = 3;
                            $newModel->type_id = $value['type'][$key];
                            if($newModel->save()) 
			                   $messageType = 'success';
                            Products::model()->updateByPk($val,array('price'=>$value['price'][$key]));
			                   $message .= ", Продукты ";
                        }
                    }
                }
                    $message .= "Успешно добавлены</strong>";                    
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				/*if($model->save()){
					$messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";
					/*
					$model2 = Menu::model()->findByPk($model->menu_id);						
					if(!empty($uploadFile)) {
						$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
						if(!empty($uploadFile)) {
							if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'menu'.DIRECTORY_SEPARATOR.$model2->menu_id.DIRECTORY_SEPARATOR.$model2->menu_id.'.'.$extUploadFile)){
								$model2->filename=$model2->menu_id.'.'.$extUploadFile;
								$model2->save();
								$message .= 'and file uploded';
							}
							else{
								$messageType = 'warning';
								$message .= 'but file not uploded';
							}
						}						
					}
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->menu_id));
				}				*/
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				//$this->refresh();
			}
			
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
			$this->loadModel($id)->deleteByPk($id);

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
	   
       $dishModel = Dishtype::model()->with('menu.dish')->findAll();//Dishes::model()->findAllByPk(CHtml::listData(Menu::model()->findAll('type = :type',array(':type'=>1)),'menu_id','just_id'));
       $stuffModel = Dishtype::model()->with('menu.halfstuff')->findAll();
       $prodModel = Dishtype::model()->with('menu.products')->findAll();
        
		/*
		$dataProvider=new CActiveDataProvider('Menu');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		
		$model=new Menu('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Menu']))
			$model->attributes=$_GET['Menu'];

		$this->render('index',array(
			'model'=>$model,
            'dishModel'=>$dishModel,
            'prodModel'=>$prodModel,
            'stuffModel'=>$stuffModel,
					));
		
			}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$model=new Menu('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Menu']))
			$model->attributes=$_GET['Menu'];

		$this->render('admin',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Menu the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Menu::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Menu $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='menu-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Menu;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Menu']))
			$model->attributes=$_POST['Menu'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Menu',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'menu_id',
					'just_id',
					'type',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{
		
		$model=new Menu;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Menu']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Menu']['name']['fileImport']);
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
						//$menu_id=  $sheetData[$baseRow]['A'];
						$just_id=  $sheetData[$baseRow]['B'];
						$type=  $sheetData[$baseRow]['C'];

						$model2=new Menu;
						//$model2->menu_id=  $menu_id;
						$model2->just_id=  $just_id;
						$model2->type=  $type;

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
	    $es = new TbEditableSaver('Menu'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Menu',
        		)
    	);
	}

	
}
