<?php

class RealizeController extends SetupController
{
	public $allProducts;
    public $allProvider;
    public $allGroups;
	
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
				'actions'=>array(),
				'roles'=>array('2'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('prodPlanView','prodPlan','create','update','detail','GetAjaxProduct','index','view','admin','delete','export','import','editable','toggle','prodList','today','realizedProd','realized','getProdList'),
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
     
    public function actionProdList(){
        $id = $_POST['provider_id'];
		$products = Provideprod::model()->with('products.measure')->findAll('provider_id = :provider_id',array(':provider_id'=>$id));
        $this->renderPartial('providerProdList',array(
            'products'=>$products,
        ));
    }
    
    public function actionGetAjaxProduct(){
        $model = CHtml::listData(Products::model()->findAll('groupProd_id = :groupProd_id',array(':groupProd_id'=>$_POST['id'])),'product_id','name');

        $this->renderPartial('getProduct', array('model'=>$model,'id'=>$_POST['id']));
    }
     
    public function actionDetail(){
        
        $currentProduct = Faktura::model()->with(array(
                                                            'realize',
                                                    ))->findAll(array(
                                                                'select'=>'realize_date',
                                                                'group'=>'date(realize_date)'
                                                        ));                                                    
        foreach($currentProduct as $key => $value){
            $dateFormat = new DateTime($value['realize_date']);
            $formateDate[$key] = $dateFormat->format('Y-m-d ')."<br />";  
            $Products = Faktura::model()->findAll(
                                                            'date(realize_date) = :realize_date',
                                                            array(
                                                                ':realize_date'=>$formateDate[$key]
                                                            ));
            $summ = 0;
            foreach($Products as $val){
                $realize = $val->getRelated('realize');
                foreach($realize as $row){
                    $prod = $row->getRelated('products');
                    //$measure = $prod->getRelated('measure'); 
                    $summ = $summ + $row['price']*$row['count'];
                }
            }
            $summa[$key] = $summ;

            
        }
                                                        /*
        $summ = 0; $counter = 1;
        foreach($Products as $val){
            $realize = $val->getRelated('realize');
            foreach($realize as $row){
                $prod = $row->getRelated('products');
                $measure = $prod->getRelated('measure'); 
                $summ = $summ + $row['price']*$row['count']; $counter++;
            }
        }*/
        $this->render('detail',array(
			'model'=>$currentProduct,
            'Products'=>$formateDate,
            'summa'=>$summa,
		));    
    }
	public function actionView($currentDate)
	{
		$currentProduct = Faktura::model()->with('realize.products.measure')->findAll('date(realize_date) = :realize_date',array(':realize_date'=>$currentDate));
        
		if(isset($_GET['asModal'])){
			$this->renderPartial('view',array(
				'model'=>$currentProduct,
                'Products'=>$currentProduct,
                'currentDate'=>$currentDate,
			));
		}
		else{
						
			$this->render('view',array(
				'model'=>$currentProduct,
                'Products' => $currentProduct,
                'currentDate'=>$currentDate,
			));
			
		}
	}
    public function beforeSave($currentDate,$id){
        $providerId = Faktura::model()->find('realize_date = :realize_date AND provider_id = :provider_id', array(':realize_date'=>$currentDate,':provider_id'=>$id));
        if($providerId == true){
            
            $provide_id = $providerId['attributes']['faktura_id'];
        }
        else{
            try{
                $transaction = Yii::app()->db->beginTransaction();
                $model = new Faktura;
                $model->realize_date = $currentDate;
                $model->provider_id = $id;
                if($model->save()){
                    $provide_id = $model->faktura_id;
                    $transaction->commit();
                }
            }
            catch(Exception $e){
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
            }
            
        }
        return $provide_id;
    }

    public function actionToday(){
        $dates = date('Y-m-d');
        $model = Faktura::model()->with('realize.products.measure')->findAll('date(t.realize_date) = :dates',array(':dates'=>$dates));

        $this->render('today',array(
            'model'=>$model,
        ));
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $products = new Products();
        $provider = new Provider();
        $prodList = $products->getUseProdList();
        $provList = $provider->getProvList();
		$model=new Realize;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['provider']))
		{
            $currentDate = $_POST['from']." ".date("H:i:s");
            $fakturaId = $this->beforeSave($currentDate,$_POST['provider']);
            //echo $fakturaId;
			$transaction = Yii::app()->db->beginTransaction();
			try{
			    $storage = new Storage();
				$messageType='warning';
				$message = "There are some errors ".count($_POST['product_id']);
                if($_POST['product_id']){
                    foreach($_POST['product_id'] as $key => $val){
                        $models = new Realize;
                        $models->faktura_id = $fakturaId;
                        $models->prod_id = $val;
                        $models->price = $_POST['price'][$key];
                        $models->count = $this->changeToFloat($_POST['count'][$key]);
                        if($models->save()) {
                            $storage->addToStorage($val,$this->changeToFloat($_POST['count'][$key]));
                            $messageType = 'success';
                            $message = "<strong>Well done!</strong> You successfully create data";
                        }
                    }
                }
                Yii::app()->user->setFlash($messageType, $message);
                $transaction->commit();
				//$this->redirect(array('create'));
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				$this->refresh();
			}
			
		}

		$this->render('create',array(
			'model'=>$model,
            'prodList'=>$prodList,
            'provList'=>$provList
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

		if(isset($_POST['Realize']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$model->attributes=$_POST['Realize'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";


				if($model->save()){
					$transaction->commit();
					Yii::app()->user->setFlash($messageType, $message);
					$this->redirect(array('view','id'=>$model->realize_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['Realize'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->realize_id));
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
		/*
		$dataProvider=new CActiveDataProvider('Realize');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		$newModel = Realize::model()->with('products','fakture','fakture.provider')->findAll();

		$model=new Realize('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Realize']))
			$model->attributes=$_GET['Realize'];

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
		
		$model=new Realize('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Realize']))
			$model->attributes=$_GET['Realize'];

		$this->render('admin',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Realize the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Realize::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Realize $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='realize-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Realize;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Realize']))
			$model->attributes=$_POST['Realize'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Realize',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'realize_id',
					'faktura_id',
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
		
		$model=new Realize;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Realize']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Realize']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Realize']['name']['fileImport']);
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
						//$realize_id=  $sheetData[$baseRow]['A'];
						$faktura_id=  $sheetData[$baseRow]['B'];
						$prod_id=  $sheetData[$baseRow]['C'];
						$price=  $sheetData[$baseRow]['D'];
						$count=  $sheetData[$baseRow]['E'];

						$model2=new Realize;
						//$model2->realize_id=  $realize_id;
						$model2->faktura_id=  $faktura_id;
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
	    $es = new TbEditableSaver('Realize'); 
			    $es->update();
	}

    public function actionRealizedProd(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $prodCount = array();
        $prodSumm = array();
        $model = Faktura::model()->with('realize')->findAll('date(realize_date) BETWEEN :from AND :to',array(':from'=>$from,':to'=>$to));
        foreach ($model as $value) {
            foreach ($value->getRelated('realize') as $val) {
                $prodCount[$val->prod_id] = $prodCount[$val->prod_id] + $val->count;
                $prodSumm[$val->prod_id] = $prodSumm[$val->prod_id] + $val->count*$val->price;
            }

        }
        $prodModel = Products::model()->findAll(array('order'=>'name'));
        $this->renderPartial('realizedProd',array(
            'prodSumm'=>$prodSumm,
            'prodCount'=>$prodCount,
            'prodModel'=>$prodModel
        ));
    }

    public function actionRealized(){
        $this->render('realized');
    }

    public function actionGetProdList(){
        $model = Products::model()->with('measure')->findByPk($_POST['id']);
        $this->renderPartial('getProdList',array('model'=>$model));
    }

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Realize',
        		)
    	);
	}

    public function actionProdPlan(){
        $dates = date('Y-m-d');
        if(isset($_POST['dish']) or isset($_POST['stuff']) or isset($_POST['prod'])){
            foreach ($_POST['dish'] as $key => $val) {
                if($val != null) {
                    Yii::app()->db->createCommand()->insert('sold_plan', array(
                        'plan_date'=>$dates,
                        'just_id'=>$key,
                        'count'=>$val,
                        'type'=>1
                    ));
                }
            }
            foreach ($_POST['stuff'] as $key => $val) {
                if($val != null) {
                    Yii::app()->db->createCommand()->insert('sold_plan', array(
                        'plan_date'=>$dates,
                        'just_id'=>$key,
                        'count'=>$val,
                        'type'=>2
                    ));
                }
            }
            foreach ($_POST['prod'] as $key => $val) {
                if($val != null) {
                    Yii::app()->db->createCommand()->insert('sold_plan', array(
                        'plan_date'=>$dates,
                        'just_id'=>$key,
                        'count'=>$val,
                        'type'=>3
                    ));
                }
            }
            $this->redirect(array('prodPlanView','dates'=>$dates));

        }
        $dishes = Yii::app()->db->createCommand()
            ->select('ord.just_id,sum(ord.count) as count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types',array(':dates'=>$dates,':types'=>1))
            ->group('ord.just_id')
            ->queryAll();
        foreach ($dishes as $val) {
            $dCount[$val['just_id']] = $val['count'];
        }
        $dish = Yii::app()->db->createCommand()
            ->select('d.name,m.just_id')
            ->from('menu m')
            ->join('dishes d','d.dish_id = m.just_id')
            ->where('m.type = :types',array(':types'=>1))
            ->queryAll();
        $halfstuff = Yii::app()->db->createCommand()
            ->select('ord.just_id,sum(ord.count) as count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types',array(':dates'=>$dates,':types'=>2))
            ->group('ord.just_id')
            ->queryAll();

        foreach ($halfstuff as $val) {
            $sCount[$val['just_id']] = $val['count'];
        }
        $stuff = Yii::app()->db->createCommand()
            ->select('h.name,m.just_id')
            ->from('menu m')
            ->join('halfstaff h','h.halfstuff_id = m.just_id')
            ->where('m.type = :types',array(':types'=>2))
            ->queryAll();
        $product = Yii::app()->db->createCommand()
            ->select('ord.just_id,sum(ord.count) as count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types',array(':dates'=>$dates,':types'=>3))
            ->group('ord.just_id')
            ->queryAll();

        foreach ($product as $val) {
            $pCount[$val['just_id']] = $val['count'];
        }
        $prod = Yii::app()->db->createCommand()
            ->select('p.name,m.just_id')
            ->from('menu m')
            ->join('products p','p.product_id = m.just_id')
            ->where('m.type = :types',array(':types'=>3))
            ->queryAll();
        $this->render('prodPlan',array(
            'dates'=>$dates,
            'dCount'=>$dCount,
            'dish'=>$dish,
            'sCount'=>$sCount,
            'stuff'=>$stuff,
            'pCount'=>$pCount,
            'prod'=>$prod
        ));
    }

    public function actionProdPlanView($dates){
        $stuff = new Halfstaff();
        $dishes = new Dishes();
        $department = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();
        $products = Yii::app()->db->createCommand()
            ->select('')
            ->from('products')
            ->queryAll();
        foreach ($products as $val) {
            $prodName[$val['product_id']] = $val['name'];
        }
        $balance = Yii::app()->db->createCommand()
            ->select('')
            ->from('balance b')
            ->where('b.b_date = :dates',array(':dates'=>$dates))
            ->queryAll();
        foreach ($balance as $val) {
            $balanceProd[$val['prod_id']] = $val['CurEndCount'];
        }

        foreach ($department as $val) {
            //$val['department_id'] = 9;
            $depProdBalance = Yii::app()->db->createCommand()
                ->select('db.prod_id,db.CurEndCount,p.name')
                ->from('dep_balance db')
                ->join('products p','p.product_id = db.prod_id')
                ->where('db.b_date = :dates AND  db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$val['department_id'],':types'=>1))
                ->queryAll();
            foreach ($depProdBalance as $value) {
                $curEndProd[$val['department_id']][$value['prod_id']] = $value['CurEndCount'];
            }
            $depStuffBalance = Yii::app()->db->createCommand()
                ->select('db.prod_id,db.CurEndCount,h.name')
                ->from('dep_balance db')
                ->join('halfstaff h','h.halfstuff_id = db.prod_id')
                ->where('db.b_date = :dates AND  db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$val['department_id'],':types'=>2))
                ->queryAll();

            if(!empty($depStuffBalance))
            foreach ($depStuffBalance as $value) {
                $temp = $stuff->stuffProd($dates,$value['prod_id']);
                $sTemporary = $stuff->multiplyArray($temp,$value['CurEndCount']);
                $curEndProd[$val['department_id']] = $stuff->sumArray($sTemporary,$curEndProd[$val['department_id']]);
                //$stuffName[$val['department_id']][$value['prod_id']] = $value['name'];
            }

            $outProd[$val['department_id']] = array();
            $outStuff[$val['department_id']] = array();
        }

        $model = Yii::app()->db->createCommand()
            ->select('sum(sp.count) as count,sp.just_id,d.department_id')
            ->from('sold_plan sp')
            ->join('dishes d','d.dish_id = sp.just_id')
            ->where('sp.plan_date = :dates AND sp.type = :types',array(':dates'=>$dates,':types'=>1))
            ->group('sp.just_id,d.department_id')
            ->queryAll();
        foreach($model as $val){
            $prod = $dishes->getProd($val['just_id']);
            $pTemporary = $stuff->multiplyArray($prod,$val['count']);
            $outProd[$val['department_id']] = $stuff->sumArray($pTemporary,$outProd[$val['department_id']]);

            $sTemp = $dishes->getStuff($val['just_id']);
            foreach ($sTemp as $key => $value) {
                $sProd = $stuff->stuffProd($dates,$key);
                $pTemporary = $stuff->multiplyArray($sProd,$val['count']);
                $outProd[$val['department_id']] = $stuff->sumArray($outProd[$val['department_id']],$pTemporary);
            }
            /*$sTemporary = $stuff->multiplyArray($sTemp,$val['count']);
            $outStuff[$val['department_id']] = $stuff->sumArray($outStuff[$val['department_id']],$sTemporary);*/
        }

        $this->render('prodPlanView',array(
            'outProd'=>$outProd,
            'outStuff'=>$outStuff,
            'department'=>$department,
            'curEndProd'=>$curEndProd,
            'prodName'=>$prodName,
            'balanceProd'=>$balanceProd,
            'dates'=>$dates
        ));
    }


	
}
