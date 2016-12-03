<?php

class DishesController extends Controller
{
    public  $allProduct;
    public  $chosenProduct;
    public  $allHalfstuff;
    public  $chosenHalfstuff;
    public  $allDishes;
    public  $chosenDishes;
    public  $dish_product;
    public  $dish_struct;
    public  $prod_measure;
    public  $prod_count;

    public  $dish_stuff;
    public  $dish_stuffstruct;
    public  $stuff_measure;
    public  $stuff_count;
	
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
				'actions'=>array('index','view'),
				'roles'=>array('2'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('refreshAdd','create','update','admin','delete','export','import','editable','toggle','structSave','copy','checkMargin'),
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
	public function actionView($id)
	{
        $dish = new Dishes();
        $dishProd = $dish->getStruct($id);
/*
	    $Products = Dishes::model()->with('dishStruct','products.measure','products.realize.faktures','products.storageProd')->findByPk($id);
        
        $Halfstuff = Dishes::model()->with('stuffs.halfstuffType','halfstuff','stuffs.stuffStruct.Struct.storageProd','stuffs.stuffStruct.Struct.realize.faktures')->findByPk($id);
        
        $HalfstuffProd = Dishes::model()->with('halfstuff.Structs.halfstuffType','halfstuff','halfstuff.Structs.stuffStruct.Struct.storageProd','halfstuff.Structs.stuffStruct.Struct.realize.faktures')->findByPk($id,'stuffStruct.types = :types',array(':types'=>1));
        
        $HalfstuffStuff = Dishes::model()->with('halfstuff.Structs.stuffStruct.stuff.halfstuffType','halfstuff.Structs.stuffStruct.stuff.podstuffStruct.Struct.realize.faktures','halfstuff.Structs.stuffStruct.stuff.podstuffStruct.Struct.storageProd')->findByPk($id,'stuffStruct.types = :types',array(':types'=>2));
        
        $this->dish_product = $Products;
        $this->dish_stuff = $Halfstuff;
        
        $prod_id = CHtml::listData(DishStructure::model()->findAll(array("condition"=>"dish_id = $id")),'struct_id','prod_id');
        $stuff_id = CHtml::listData(DishStructure2::model()->findAll(array("condition"=>"dish_id = $id")),'struct2_id','halfstuff_id');
         
        $this->chosenProduct = CHtml::listData(Products::model()->findAllByPk($prod_id),'product_id','name');
        $this->chosenHalfstuff = CHtml::listData(Halfstaff::model()->findAllByPk($stuff_id),'halfstuff_id','name');
*/
		if(isset($_GET['asModal'])){
			$this->renderPartial('view',array(
                'id'=>$id,
                'dishProd'=>$dishProd,
			));
		}
		else{
						
			$this->render('view',array(
                'id'=>$id,
				'model'=>$this->loadModel($id),
                'dishProd'=>$dishProd,
			));
			
		}
	}

    public function actionCopy($id){

        $model = Dishes::model()->findByPk($id);
        $model2 = DishStructure::model()->findAll('t.dish_id = :dish_id',array(':dish_id'=>$model->dish_id));
        $model3 = DishStructure2::model()->findAll('t.dish_id = :dish_id',array(':dish_id'=>$model->dish_id));

        $transaction = Yii::app()->db->beginTransaction();
        try{
            $dishes = new Dishes();
            $dishes->attributes = $model->attributes;
            if($dishes->save()) {
                if (!empty($model2))
                    foreach ($model2 as $value) {
                        $dishProd = new DishStructure();
                        $dishProd->dish_id = $dishes->dish_id;
                        $dishProd->prod_id = $value->prod_id;
                        $dishProd->amount = $value->amount;
                        $dishProd->save();
                    }
                if (!empty($model3))
                    foreach ($model3 as $value) {
                        $dishStuff = new DishStructure2();
                        $dishStuff->dish_id = $dishes->dish_id;
                        $dishStuff->halfstuff_id = $value->halfstuff_id;
                        $dishStuff->amount = $value->amount;
                        $dishStuff->save();
                    }
                $this->logs('create','dishes',$dishes->dish_id,$dishes->name." ->  =>");
            }

            $transaction->commit();

            $this->redirect(array('update','id'=>$dishes->dish_id));

		}
        catch (Exception $e){
            $transaction->rollBack();
            Yii::app()->user->setFlash('error', "{$e->getMessage()}");
            //$this->refresh();
        }

    }

    public function actionMove($id){

        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->where('d.dish_id = :id',array(':id'=>$id))
            ->queryRow();
        $model2 = Yii::app()->db->createCommand()
            ->select('')
            ->from('dish_structure ds')
            ->where('ds.dish_id = :id',array(':id'=>$id))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('')
            ->from('dish_structure2 ds')
            ->where('ds.dish_id = :id',array(':id'=>$id))
            ->queryAll();
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $stuff = new Halfstaff();
            $stuff->name = $model['name'];
            $stuff->stuff_type = 1;
            $stuff->price = $model['price'];
            $stuff->count = $model['count'];
            $stuff->department_id = $model['department_id'];
            $stuff->status = $model['status'];
            if($stuff->save()) {
                if (!empty($model2))
                    $prodMes = "prod>";
                    foreach ($model2 as $value) {
                        $stuffProd = new HalfstuffStructure();
                        $stuffProd->halfstuff_id = $stuff->halfstuff_id;
                        $stuffProd->prod_id = $value['prod_id'];
                        $stuffProd->amount = $value['amount'];
                        $stuffProd->types = 1;
                        $stuffProd->save();
                        $prodMes .= $stuffProd['prod_id'].":".$stuffProd['amount'].",";
                    }
                if (!empty($model3))
                    $stuffMes = "stuff>";
                    foreach ($model3 as $value) {
                        $stuffProd = new HalfstuffStructure();
                        $stuffProd->halfstuff_id = $stuff->halfstuff_id;
                        $stuffProd->prod_id = $value['halfstuff_id'];
                        $stuffProd->amount = $value['amount'];
                        $stuffProd->types = 2;
                        $stuffProd->save();
                        $stuffMes .= $value['halfstuff_id'].":".$value['amount'].",";
                    }
                $this->logs('create','halfstaff',$stuff->halfstuff_id,$stuff->name."->".$prodMes."=>".$stuffMes);
            }

            $transaction->commit();

            $this->redirect(array('halfstaff/update','id'=>$stuff->halfstuff_id));

        }
        catch (Exception $e){
            $transaction->rollBack();
            Yii::app()->user->setFlash('error', "{$e->getMessage()}");
            //$this->refresh();
        }
    }


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    
	public function actionCreate()
	{
        $products = new Products();
        $prodList = $products->getUseProdList();
        $chosenProd = array();
        $stuff = new Halfstaff();
		$stuffList = $stuff->getUseStuffList();
        $chosenStuff = array();

        
		$model=new Dishes;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dishes']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$_POST['Dishes']['count'] = $this->changeToFloat($_POST['Dishes']['count']);
                if($_POST['Dishes']['count'] == '' or $_POST['Dishes']['count'] == 0){
                    $_POST['Dishes']['count'] = 1;
                }
                    
				$messageType='warning';
				$message = "There are some errors ";
				$model->attributes=$_POST['Dishes'];
				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				if($model->save()){
				    if($_POST['product_id'] !=null){
				        $count = 0;
                        $prodMes = "prod>";
				        for($i = 0; $i < count($_POST['product_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['prod'][$i]);
                            $struct = new DishStructure;
				            $struct->dish_id = $model->dish_id;
				            $struct->prod_id = $_POST['product_id'][$i];
                            $struct->amount = $ss;
                            if($struct->save()){
                                $prodMes .= $struct->prod_id.",";
                            } 
                            $count++;
				        }
				    }
                    if($_POST['stuff_id'] !=null){
                        $stuffMes = "stuff>";
				        for($i = 0; $i < count($_POST['stuff_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['stuff'][$i]);
                            $struct2 = new DishStructure2;
				            $struct2->dish_id = $model->dish_id;
				            $struct2->halfstuff_id = $_POST['stuff_id'][$i];
                            $struct2->amount = $ss;
                            if($struct2->save()){
                                $stuffMes .= $struct2->halfstuff_id.",";
                            }
				        }
				    }
                    /*if($_POST['dish_id'] !=null){
				        for($i = 0; $i < count($_POST['dish_id']); $i++){
                            $struct3 = new DishStructure3;
				            $struct3->dish_id = $model->dish_id;
				            $struct3->dishes_id = $_POST['dish_id'][$i];
                            $struct3->amount = $_POST['dish'][$i];
                            if($struct3->save()){}
				        }
				    }*/
                    $messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";
					
					$this->logs('create','dishes',$model->dish_id,$model->name."->".$prodMes."=>".$stuffMes);
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					//$this->redirect(array('view','id'=>$model->dish_id));
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
            'prodList'=>$prodList,
            'chosenProd'=>$chosenProd,
            'stuffList'=>$stuffList,
            'chosenStuff'=>$chosenStuff
					));
		
				
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
        $products = new Products();
        $stuff = new Halfstaff();
        $prodList = $products->getUseProdList();
        $prod_id = CHtml::listData(DishStructure::model()->findAll(array("condition"=>"dish_id = $id")),'struct_id','prod_id');
        $stuff_id = CHtml::listData(DishStructure2::model()->findAll(array("condition"=>"dish_id = $id")),'struct2_id','halfstuff_id');
//        $dish_id = CHtml::listData(DishStructure3::model()->findAll(array("condition"=>"dish_id = $id")),'struct3_id','dishes_id');

        $chosenProd = Products::model()->with('Struct')->findAllByPk($prod_id,'Struct.dish_id = :dish_id',array(':dish_id'=>$id));
        $stuffList = $stuff->getUseStuffList();
        $chosenStuff = Halfstaff::model()->with('Struct')->findAllByPk($stuff_id,'Struct.dish_id = :dish_id',array(':dish_id'=>$id));

        $model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dishes']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
                $_POST['Dishes']['count'] = $this->changeToFloat($_POST['Dishes']['count']);
			     if($_POST['Dishes']['count'] == '' or $_POST['Dishes']['count'] == 0){
                    $_POST['Dishes']['count'] = 1;
                }
				$model->attributes=$_POST['Dishes'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";

                
				if($model->save()){
				    DishStructure::model()->deleteAll('dish_id=:dish_id', array(':dish_id'=>$id));
                    DishStructure2::model()->deleteAll('dish_id=:dish_id', array(':dish_id'=>$id));
                    if($_POST['product_id'] !=null){
				        $count = 0;
                        $prodMes = "prod>";
				        for($i = 0; $i < count($_POST['product_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['prod'][$i]);
                            $struct = new DishStructure;
				            $struct->dish_id = $id;
				            $struct->prod_id = $_POST['product_id'][$i];
                            $struct->amount = $ss;
                            if($struct->save()){
                                $messageType = 'success';
                                $prodMes .= $struct->prod_id.":".$struct->amount.",";} 
                            $count++;
				        }
				    }
                    if($_POST['stuff_id'] !=null){  
                        $stuffMes = "stuff>";
				        for($i = 0; $i < count($_POST['stuff_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['stuff'][$i]);
                            $struct2 = new DishStructure2;
				            $struct2->dish_id = $id;
				            $struct2->halfstuff_id = $_POST['stuff_id'][$i];
                            $struct2->amount = $ss;
                            if($struct2->save()){
					            $message = "<strong>Well done!</strong> You successfully create data ";
                                $stuffMes .= $struct2->halfstuff_id.":".$struct2->amount.",";}
				        }
				    }
                    $this->logs('update','dishes',$model->dish_id,$model->name."->".$prodMes."=>".$stuffMes);
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->dish_id));
				}
			}
            
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['Dishes'];
			if($model->save()){}
				//$this->redirect(array('view','id'=>$model->dish_id));
		}

		$this->render('update',array(
			'model'=>$model,
            'prodList'=>$prodList,
            'chosenProd'=>$chosenProd,
            'stuffList'=>$stuffList,
            'chosenStuff'=>$chosenStuff
		));

    }


    public function actionStructSave($id){
        $struct = new DishStructure;
        $halfStruct = new DishStructure2;
        $prod_id = CHtml::listData(DishStructure::model()->findAll(array("condition"=>"dish_id = $id")),'struct_id','prod_id');
        $stuff_id = CHtml::listData(DishStructure2::model()->findAll(array("condition"=>"dish_id = $id")),'struct2_id','halfstuff_id');
        $this->chosenProduct = CHtml::listData(Products::model()->findAllByPk($prod_id),'product_id','name');
        $this->chosenHalfstuff = CHtml::listData(Halfstaff::model()->findAllByPk($stuff_id),'halfstuff_id','name');
        
        if($_POST){
			$transaction = Yii::app()->db->beginTransaction();
            try{
                if($_POST['prod']){
                    foreach($_POST['prod'] as $key => $val){
                        $tempStruct = DishStructure::model()->updateAll(array('amount'=>$val),'dish_id = :dish_id AND prod_id = :prod_id',array(':dish_id'=>$id,':prod_id'=>$key));
                        
                    }
                }  
                if($_POST['half']){
                    foreach($_POST['half'] as $key => $val){
                        $tempStruct = DishStructure2::model()->updateAll(array('amount'=>$val),'dish_id = :dish_id AND halfstuff_id = :halfstuff_id',array(':dish_id'=>$id,':halfstuff_id'=>$key));
                       
                    }
                }
                    $transaction->commit();
                    Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('index'));
                
            }
            catch(exception $ex){
                $transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                
            }
            
        }
                        
        $this->render('structSave',array(
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
                'status'=>1,
                'department_id'=>0
            ));
            /*DishStructure::model()->deleteAll('dish_id=:dish_id', array(':dish_id'=>$id));
            DishStructure2::model()->deleteAll('dish_id=:dish_id', array(':dish_id'=>$id));*/
            $this->logs('delete','dishes',$id,'delete');
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
		$dataProvider=new CActiveDataProvider('Dishes');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
        
        $DishModel = Dishes::model()->findAll('status = :status',array(':status'=>0));
        $model=new Dishes('search');
        $model->unsetAttributes();  // clear any default values
		if(isset($_GET['Dishes']))
			$model->attributes=$_GET['Dishes'];

		$this->render('index',array(
			'model'=>$model,
            'dishModel'=>$DishModel,
					));
		
			}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
        $DishModel = Dishes::model()->findAll('status = :status',array(':status'=>0));
		$model=new Dishes('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Dishes']))
			$model->attributes=$_GET['Dishes'];

		$this->render('admin',array(
			'model'=>$model,
            'dishModel'=>$DishModel,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Dishes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
	    
		$model=Dishes::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Dishes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dishes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Dishes;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Dishes']))
			$model->attributes=$_POST['Dishes'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Dishes',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'dish_id',
					'name',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{
		
		$model=new Dishes;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dishes']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Dishes']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Dishes']['name']['fileImport']);
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
						//$dish_id=  $sheetData[$baseRow]['A'];
						$name=  $sheetData[$baseRow]['B'];

						$model2=new Dishes;
						//$model2->dish_id=  $dish_id;
						$model2->name=  $name;

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

    public function actionCheckMargin(){
        $model = Menu::model()->with('dish')->findAll('t.mType = :mType',array(':mType'=>1));
        $model2 = Menu::model()->with('halfstuff')->findAll('t.mType = :mType',array(':mType'=>1));
        $model3 = Menu::model()->with('products')->findAll('t.mType = :mType',array(':mType'=>1));

        $this->render('checkMargin',array(
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
        ));
    }

	public function actionEditable(){
		Yii::import('bootstrap.widgets.TbEditableSaver'); 
	    $es = new TbEditableSaver('Dishes'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Dishes',
        		)
    	);
	}

    public function actionRefreshAdd(){
        $prod = new Products();
        $prodList = $prod->getUseProdList();
        $stuff = new Halfstaff();
        $stuffList = $stuff->getUseStuffList();
        $this->renderPartial('refreshAdd',array(
            'stuffList'=>$stuffList,
            'prodList'=>$prodList,
            'form'=>$_POST['form']
        ));
    }

}
