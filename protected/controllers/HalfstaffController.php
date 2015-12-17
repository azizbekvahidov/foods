<?php

class HalfstaffController extends Controller
{
	public  $allProduct;
    public  $chosenProduct;
    public  $stuff_product;
    public  $stuff_struct;
    public  $prod_measure;
    public  $prod_count;
	
	
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
				'actions'=>array('create','update','index','view','admin','delete','export','import','editable','toggle','structSave','copy'),
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
		$Products = Halfstaff::model()->with('stuffStruct.Struct.measure','stuffStruct.Struct.realize.faktures','stuffStruct.Struct.storageProd')->findByPk($id,'stuffStruct.types = :types',array(':types'=>1));//,array('order'=>'fakture.realize_date desc'));
        $Stuff = Halfstaff::model()->with('stuffStruct.stuff.halfstuffType','stuffStruct.stuff.podstuffStruct.Struct.realize.faktures','stuffStruct.stuff.podstuffStruct.Struct.storageProd')->findByPk($id,'stuffStruct.types = :types',array(':types'=>2));//,array('order'=>'fakture.realize_date desc'));

        //$chosenStuff[$key] = Halfstaff::model()->with('halfstuffType','stuffStruct.Struct')->findByPk($value); 
		if(isset($_GET['asModal'])){
			$this->renderPartial('view',array(
				'model'=>$this->loadModel($id),
                'Products'=>$Products,
                'Stuff'=>$Stuff,
			));
		}
		else{
						
			$this->render('view',array(
				'model'=>$this->loadModel($id),
                'Products'=>$Products,
                'Stuff'=>$Stuff,
			));
			
		}
	}

    public function actionCopy(){

        $model = Halfstaff::model()->findByPk($_GET['id']);
        $model2 = HalfstuffStructure::model()->findAll('t.halfstuff_id = :halfstuff_id',array(':halfstuff_id'=>$model->halfstuff_id));

        $transaction = Yii::app()->db->beginTransaction();
        try{
            $halfstuff = new Halfstaff();
            $halfstuff->attributes = $model->attributes;
            if($halfstuff->save()) {
                if (!empty($model2))
                    foreach ($model2 as $value) {
                        $stuffProd = new HalfstuffStructure();
                        $stuffProd->halfstuff_id = $halfstuff->halfstuff_id;
                        $stuffProd->prod_id = $value->prod_id;
                        $stuffProd->amount = $value->amount;
                        $stuffProd->types = $value->types;
                        $stuffProd->save();
                    }

                $this->logs('create','dishes',$halfstuff->halfstuff_id,$halfstuff->name." ->  =>");
            }

            $transaction->commit();

            $this->redirect(array('update','id'=>$halfstuff->halfstuff_id));

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
        $chosenProduct = array();	
				
		$model=new Halfstaff;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Halfstaff']))
		{
		  
			$transaction = Yii::app()->db->beginTransaction();
			try{
			$_POST['Halfstaff']['count'] = $this->changeToFloat($_POST['Halfstaff']['count']);
			  if($_POST['Halfstaff']['count'] == '' or $_POST['Halfstaff']['count'] == 0){
                    $_POST['Halfstaff']['count'] = 1;
                }
				$messageType='warning';
				$message = "There are some errors ";
				$model->attributes=$_POST['Halfstaff'];
				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				if($model->save()){
				    if($_POST['product_id'] !=null){
				        $count = 0;
                        $prodMes = "prod >";
				        for($i = 0; $i < count($_POST['product_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['prod'][$i]);
                            $struct = new HalfstuffStructure;
				            $struct->halfstuff_id = $model->halfstuff_id;
				            $struct->prod_id = $_POST['product_id'][$i];
                            $struct->amount = $ss;
                            $struct->types = 1;
                            if($struct->save()){
                                $prodMes .= $struct->prod_id.":".$struct->amount.",";
                            } 
                            $count++;
				        }
				    }
                    if($_POST['stuff_id'] !=null){
				        $count = 0;
                        $stuffMes = "stuff >";
				        for($i = 0; $i < count($_POST['stuff_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['stuff'][$i]);
                            $struct = new HalfstuffStructure;
				            $struct->halfstuff_id = $model->halfstuff_id;
				            $struct->prod_id = $_POST['stuff_id'][$i];
                            $struct->amount = $ss;
                            $struct->types = 2;
                            if($struct->save()){
                                $stuffMes .= $struct->prod_id.":".$struct->amount.",";
                            } 
                            $count++;
				        }
				    }
					$messageType = 'success';
					$message = "<strong>Well done!</strong> You successfully create data ";
					
                    $this->logs('create','halfstuff',$model->halfstuff_id,$model->name." -> ".$prodMes."=>".$stuffMes);
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->halfstuff_id));
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
        $chosenStuff = array();	   
        $prod_id = CHtml::listData(HalfstuffStructure::model()->findAll(array("condition"=>"halfstuff_id = $id AND types = 1")),'halfstruct_id','prod_id');
        
        $stuff_id = CHtml::listData(HalfstuffStructure::model()->findAll(array("condition"=>"halfstuff_id = $id AND types = 2")),'halfstruct_id','prod_id');
        
        $chosenProduct = Products::model()->with('stuffStruct')->findAllByPk($prod_id,'stuffStruct.halfstuff_id = :halfstuff_id AND stuffStruct.types = :types',array(':halfstuff_id'=>$id,':types'=>1));        
        
        foreach($stuff_id as $key => $value){
            
            $chosenStuff[$key] = HalfstuffStructure::model()->with('stuff')->findAllByPk($key,'t.halfstuff_id = :halfstuff_id AND types = :types',array(':halfstuff_id'=>$id,':types'=>2));            
         }
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Halfstaff']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$_POST['Halfstaff']['count'] = $this->changeToFloat($_POST['Halfstaff']['count']);
			     if($_POST['Halfstaff']['count'] == '' or $_POST['Halfstaff']['count'] == 0){
                    $_POST['Halfstaff']['count'] = 1;
                }
				$model->attributes=$_POST['Halfstaff'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";


				if($model->save()){
				    HalfstuffStructure::model()->deleteAll('halfstuff_id=:halfstuff_id', array(':halfstuff_id'=>$id));
                    if($_POST['product_id'] !=null){
				        $count = 0;
                        $prodMes = "prod >";
				        for($i = 0; $i < count($_POST['product_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['prod'][$i]);				            
                            $struct = new HalfstuffStructure;
				            $struct->halfstuff_id = $id;
				            $struct->prod_id = $_POST['product_id'][$i];
                            $struct->amount = $ss;
                            $struct->types = 1;
                            if($struct->save()){
                                $messageType = 'success';
                                $prodMes .= $struct->prod_id.":".$struct->amount.",";
					            $message = "<strong>Well done!</strong> Your successfully create data ";} 
                            $count++;
				                             }
				    }
                    
                    if($_POST['stuff_id'] !=null){
				        $count = 0;
                        $stuffMes = "stuff >";
				        for($i = 0; $i < count($_POST['stuff_id']); $i++){
				            
                            $ss = $this->changeToFloat($_POST['stuff'][$i]);
                            $struct = new HalfstuffStructure;
				            $struct->halfstuff_id = $model->halfstuff_id;
				            $struct->prod_id = $_POST['stuff_id'][$i];
                            $struct->amount = $ss;
                            $struct->types = 2;
                            if($struct->save()){
                                $stuffMes .= $struct->prod_id.":".$struct->amount.",";
                            } 
                            $count++;
				        }
				    }                  
					$this->logs('update','halfstuff',$model->halfstuff_id,$model->name." -> ".$prodMes."=>".$stuffMes);
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->halfstuff_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['Halfstaff'];
			if($model->save()){}
				$this->redirect(array('view','id'=>$model->halfstuff_id));
		}

		$this->render('update',array(
			'model'=>$model,
            'chosenProduct'=>$chosenProduct,
            'chosenStuff'=>$chosenStuff,
            
					));
		
			}



    public function actionStructSave($id){
        $struct = new HalfstuffStructure;
        $prod_id = CHtml::listData(HalfstuffStructure::model()->findAll(array("condition"=>"halfstuff_id = $id")),'halfstruct_id','prod_id');
        
        $this->chosenProduct = CHtml::listData(Products::model()->findAllByPk($prod_id),'product_id','name');
        
        if($_POST){
			$transaction = Yii::app()->db->beginTransaction();
            try{
                if($_POST['prod']){
                    foreach($_POST['prod'] as $key => $val){
                        $tempStruct = HalfstuffStructure::model()->updateAll(array('amount'=>$val),'halfstuff_id = :halfstuff_id AND prod_id = :prod_id',array(':halfstuff_id'=>$id,':prod_id'=>$key));
                        
                    }
                }
                    $transaction->commit();
                    Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('index'));
                
            }
            catch(exception $ex){
                
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
            $this->loadModel($id)->deleteByPk($id);
            HalfstuffStructure::model()->deleteAll('halfstuff_id=:halfstuff_id', array(':halfstuff_id'=>$id));
			$this->logs('delete','halfstuff',$id,'delete');
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
		$dataProvider=new CActiveDataProvider('Halfstaff');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		$newModel = Halfstaff::model()->findAll();
		$model=new Halfstaff('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Halfstaff']))
			$model->attributes=$_GET['Halfstaff'];

		$this->render('index',array(
            'newModel'=>$newModel,
			'model'=>$model,
					));
		
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$newModel = Halfstaff::model()->findAll();
		$model=new Halfstaff('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Halfstaff']))
			$model->attributes=$_GET['Halfstaff'];

		$this->render('admin',array(
            'newModel'=>$newModel,
			'model'=>$model,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Halfstaff the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Halfstaff::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Halfstaff $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='halfstaff-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Halfstaff;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Halfstaff']))
			$model->attributes=$_POST['Halfstaff'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Halfstaff',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'halfstuff_id',
					'name',
					'stuff_type',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{
		
		$model=new Halfstaff;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Halfstaff']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Halfstaff']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Halfstaff']['name']['fileImport']);
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
						//$halfstuff_id=  $sheetData[$baseRow]['A'];
						$name=  $sheetData[$baseRow]['B'];
						$stuff_type=  $sheetData[$baseRow]['C'];

						$model2=new Halfstaff;
						//$model2->halfstuff_id=  $halfstuff_id;
						$model2->name=  $name;
						$model2->stuff_type=  $stuff_type;

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
	    $es = new TbEditableSaver('Halfstaff'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Halfstaff',
        		)
    	);
	}

	
}
