<?php


class DepStorageController extends Controller
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
				'actions'=>array('view','update','admin','delete','export','import','editable','toggle','calendar', 'calendarEvents','today','viewStorage','storageView','depForm','allIn','allStorage','usedProdLists','usedProd','downloadCsv' ),
				'roles'=>array('2'),
			),
            array('allow',
                'actions'=>array(),
                'roles'=>array('3'),
            ),
            array('allow',
                'actions'=>array('index','create'),
                'roles'=>array('4'),
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
     
    public function actionAllIn(){
        $dates = date('Y-m-d');
        $this->render('allIn',array(
            'dates'=>$dates,
        ));
        
    }

    public function actionMonthStorage(){
        $dates = explode('-',$_POST['dates']);
        $depId = $_POST['depId'];
        $number = cal_days_in_month(CAL_GREGORIAN, $dates[1], $dates[0]);

        $realized = array();
        $expense = new Expense();
        $expenses = array();
        $startCount = array();
        $endCount = array();
        $depRealize = new DepFaktura();
        $inexp = new Expense();
        $balance = new Balance();

        for($i = 1; $i <= $number;$i++){
            $tempDate = $dates[0]."-".$dates[1]."-".$i;
            $listDate[$i] = $tempDate;

            $realized[$tempDate] = $depRealize->getDepRealizesSumm($tempDate,$depId);
            $expenses[$tempDate] = $expense->getDepCost($depId,$tempDate);
            $tempBalance = $balance->getDepBalanceSumm($tempDate,$depId);
            $startCount[$tempDate] = $tempBalance[0];
            $endCount[$tempDate] = $tempBalance[1];
            $curEndCount[$tempDate] = $tempBalance[2];
        }
        $this->renderPartial('monthStorage',array(
            'tempDate'=>$listDate,
            'realized'=>$realized,
            'expenses'=>$expenses,
            'startCount'=>$startCount,
            'endCount'=>$endCount,
            'curEndCount'=>$curEndCount,
            'depId'=>$depId,
        ));
    }

    public function actionAllStorage(){
        $dates = $_POST['dates'];
        $depId = $_POST['depId'];
        $outProduct = array();
        $outStuff = array();
        $depIn = array();
        $depOut = array();
        $prodModel = Products::model()->findAll();
        foreach($prodModel as $value){
            $outProduct[$value->product_id] = $outProduct[$value->product_id] + 0;
        }
        
        $departMoveOut = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.department_id = :depId AND t.fromDepId <> :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0));

        foreach($departMoveOut as $key => $val){
            foreach($val->getRelated('realizedProd') as $value){
                $depIn[$value->prod_id] = $depIn[$value->prod_id] + $value->count;
            }
        } 

        $departMoveIn = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.fromDepId = :depId',array(':dates'=>$dates,':depId'=>$depId));
        foreach($departMoveIn as $value){ 
            foreach($value->getRelated('realizedProd') as $val){
                $depOut[$val->prod_id] = $depOut[$val->prod_id] + $val->count;
            }
        }
        
        $curProd = DepBalance::model()->with('products')->findAll(
            'date(t.b_date) = :dates AND t.department_id = :department_id AND t.type = :type',
            array(
                ':dates'=>$dates,
                ':department_id'=>$depId,
                ':type'=>1,
            )
        );
        
        $curStuff = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_balance t')
            ->join('halfstaff h','h.halfstuff_id = t.prod_id')
            ->where(
                'date(t.b_date) = :dates AND t.department_id = :department_id AND t.type = :type',
                array(
                    ':dates'=>$dates,
                    ':department_id'=>$depId,
                    ':type'=>2,
                ))
            ->queryAll();


        $dish = new Expense();

        $stuff = new Halfstaff();

        $outProduct = $dish->getDishProd($depId,$dates);
        
        $outDishStuff = $dish->getDishStuff($depId,$dates);

        $inProducts = array();
        $model = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.department_id = :depId AND t.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0));
        foreach($model as $key => $val){
            foreach($val->getRelated('realizedProd') as $value){
                $inProducts[$value->prod_id] = $inProducts[$value->prod_id] + $value->count;
            }
        } 
        $instuff = array();
        $outStuffProd = array();
        
        $model2 = Inexpense::model()->with('inorder.stuffs.stuffStruct')->findAll('date(t.inexp_date) = :dates AND t.department_id = :depId AND stuffStruct.types = :types AND t.fromDepId = :fromDepId',array(':dates'=>$dates,'depId'=>$depId,':types'=>1,':fromDepId'=>0));
        foreach($model2 as $val){
            foreach($val->getRelated('inorder') as $value){
                $instuff[$value->stuff_id] = $instuff[$value->stuff_id] + $value->count;
                foreach($value->getRelated('stuffs')->getRelated('stuffStruct') as $values){
                    $outStuffProd[$values->prod_id] = $outStuffProd[$values->prod_id] + $values->amount/$value->getRelated('stuffs')->count*$value->count; 
                }
                
            }
        }
        $model3 = Inexpense::model()->with('inorder.stuffs.stuffStruct.podstuff.podstuffStruct.Struct')->findAll('date(t.inexp_date) = :dates AND t.department_id = :depId AND stuffStruct.types = :types AND t.fromDepId = :fromDepId',array(':dates'=>$dates,'depId'=>$depId,':types'=>2,':fromDepId'=>0));
        foreach($model3 as $val){
            foreach($val->getRelated('inorder') as $value){
                $instuff[$value->stuff_id] = $instuff[$value->stuff_id] + $value->count;
                foreach($value->getRelated('stuffs')->getRelated('stuffStruct') as $values){
                    $outStuff[$values->prod_id] = $outStuff[$values->prod_id] + $values->amount/$value->getRelated('stuffs')->count*$value->count;
                    foreach($values->getRelated('podstuff')->getRelated('podstuffStruct') as $vals){
                        $outStuffProd[$values->prod_id] = $outStuffProd[$values->prod_id] + $vals->amount/$values->getRelated('podstuff')->count*$values->amount/$value->getRelated('stuffs')->count*$value->count;
                    }
                }
                
            }
        }
        $outStuff = $stuff->sumArray($outDishStuff,$outStuff);

        $inexpense = new Inexpense();
        $depStuffIn = $inexpense->getDepIn($depId,$dates);
        $depStuffOut = $inexpense->getDepOut($depId,$dates);


        $this->renderPartial('allStorage',array(
            'depIn'=>$depIn,
            'depOut'=>$depOut,
            'depStuffOut'=>$depStuffOut,
            'depStuffIn'=>$depStuffIn,
            'prodModel'=>$prodModel,
            'model'=>$curProd,
            'curStuff'=>$curStuff,
            'inProduct'=>$inProducts,
            'instuff'=>$instuff,
            'outProduct'=>$outProduct,
            'outStuffProd'=>$outStuffProd,
            'outStuff'=>$outStuff,
        ));
    } 
    
    

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
     
     public function actionUsedProd(){
        $dates = date('Y-m-d');
        
        $this->render('usedProdToday',array(
            'dates'=>$dates,
        ));
    }
    
    public function actionUsedProdLists(){
        $dates = $_POST['dates'];
        $depId = $_POST['department_id'];
        $prodType = $_POST['prod_type'];
        $curDish = array();
        $products = array();
        $stuff = array();
        $dishCount = array();

        $prodList = DepBalance::model()->with('products')->findAll('t.b_date = :dates AND t.department_id = :depId AND t.type = :type',array(':dates'=>$dates,':depId'=>$depId,':type'=>1));
        $stuffList = DepBalance::model()->with('stuff')->findAll('t.b_date = :dates AND t.department_id = :depId AND t.type = :type',array(':dates'=>$dates,':depId'=>$depId,':type'=>2));


        if($prodType == 1) {
            $model = Expense::model()->with('order.dish.dishStruct')->findAll('date(t.order_date) = :dates AND dish.department_id = :depId',array(':dates'=>$dates,':depId'=>$depId));
            
            foreach ($model as $value) {
                foreach ($value->getRelated('order') as $val) {
                    $curDish[$val->getRelated('dish')->dish_id] = $val->getRelated('dish')->name;
                    $dishCount[$val->getRelated('dish')->dish_id] = $dishCount[$val->getRelated('dish')->dish_id] + $val->count;
                    
                    foreach ($val->getRelated('dish')->getRelated('dishStruct') as $vals) {
                        $products[$val->getRelated('dish')->dish_id][$vals->prod_id] = $products[$val->getRelated('dish')->dish_id][$vals->prod_id] + $vals->amount/$val->getRelated('dish')->count*$val->count;
                    }

                }

            }
            $model2 = Expense::model()->with('order.dish.halfstuff')->findAll('date(t.order_date) = :dates AND dish.department_id = :depId',array(':dates'=>$dates,':depId'=>$depId));
            foreach ($model2 as $value) {
                foreach ($value->getRelated('order') as $val) {
                    $curDish[$val->getRelated('dish')->dish_id] = $val->getRelated('dish')->name;
                    foreach ($val->getRelated('dish')->getRelated('halfstuff') as $vals) {
                        $stuff[$val->getRelated('dish')->dish_id][$vals->halfstuff_id] = $stuff[$val->getRelated('dish')->dish_id][$vals->halfstuff_id] + $vals->amount/$val->getRelated('dish')->count*$val->count;
                    }

                }

            }
            $this->renderPartial('usedDishProd', array(
                'dishCount' => $dishCount,
                'prodList' => $prodList,
                'stuffList' => $stuffList,
                'products' => $products,
                'stuff' => $stuff,
                'curDish' => $curDish,
            ));
        }
        elseif($prodType == 2){
            $model3 = Expense::model()->with('order.halfstuff.stuffStruct')->findAll('date(t.order_date) = :dates AND halfstuff.department_id = :depId',array(':dates'=>$dates,':depId'=>$depId));

            foreach ($model3 as $value) {
                foreach ($value->getRelated('order') as $val) {

                    $curStuff[$val->getRelated('halfstuff')->halfstuff_id] = $val->getRelated('halfstuff')->name;
                    $stuffCount[$val->getRelated('halfstuff')->halfstuff_id] = $dishCount[$val->getRelated('halfstuff')->halfstuff_id] + $val->count;
                    foreach ($val->getRelated('halfstuff')->getRelated('stuffStruct') as $vals) {
                        if($vals->types == 1)
                            $products[$val->getRelated('halfstuff')->halfstuff_id][$vals->prod_id] = $products[$val->getRelated('halfstuff')->halfstuff_id][$vals->prod_id] + $vals->amount/$val->getRelated('halfstuff')->count*$val->count;
                        elseIf($vals->types)
                            $stuff[$val->getRelated('halfstuff')->halfstuff_id][$vals->prod_id] = $stuff[$val->getRelated('halfstuff')->halfstuff_id][$vals->prod_id] + $vals->amount/$val->getRelated('halfstuff')->count*$val->count;
                    }

                }

            }

            $this->renderPartial('usedStuffProd', array(
                'stuffCount' => $stuffCount,
                'prodList' => $prodList,
                'stuffList' => $stuffList,
                'products' => $products,
                'stuff' => $stuff,
                'curStuff' => $curStuff,
            ));
        }
    }
    
    
    public function actionViewStorage(){
        $dates = date("Y-m-d");
        $outProduct = array();
        $depOut = array();
        $depIn = array();
        $outStuff = array();
        $prodModel = Products::model()->findAll();
        foreach($prodModel as $value){
            $outProduct[$value->product_id] = $outProduct[$value->product_id] + 0;
            $depOut[$value->product_id] = $depOut[$value->product_id] + 0;
        }  
        $depId = $_POST['department_id'];
        
        $departMoveOut = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.department_id = :depId AND t.fromDepId <> :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0));
        
        foreach($departMoveOut as $key => $val){
            foreach($val->getRelated('realizedProd') as $value){
                $depIn[$value->prod_id] = $depIn[$value->prod_id] + $value->count;
            }
        } 

        $departMoveIn = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.fromDepId = :depId',array(':dates'=>$dates,':depId'=>$depId));
        
        foreach($departMoveIn as $value){ 
            foreach($value->getRelated('realizedProd') as $val){
                
                $depOut[$val->prod_id] = $depOut[$val->prod_id] + $val->count;
            }
        }
        $curProd = DepBalance::model()->with('products')->findAll(
            'date(t.b_date) = :dates AND t.department_id = :department_id AND t.type = :type',
            array(
                ':dates'=>$dates,
                ':department_id'=>$depId,
                ':type'=>1,
            )
        );
        $curStuff = DepBalance::model()->with('stuff')->findAll(
            'date(t.b_date) = :dates AND t.department_id = :department_id AND t.type = :type',
            array(
                ':dates'=>$dates,
                ':department_id'=>$depId,
                ':type'=>2,
            )
        );


        $dish = new Expense();

        $stuff = new Halfstaff();

        $outProduct = $dish->getDishProd($depId,$dates);
        $outDishStuff = $dish->getDishStuff($depId,$dates);

        $inProducts = array();
        $model = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.department_id = :depId AND t.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0));
        
        foreach($model as $key => $val){
            foreach($val->getRelated('realizedProd') as $value){
                $inProducts[$value->prod_id] = $inProducts[$value->prod_id] + $value->count;
            }
        } 
        $instuff = array();
        $outStuffProd = array();
        $model2 = Inexpense::model()->with('inorder.stuffs.stuffStruct')->findAll('date(t.inexp_date) = :dates AND t.department_id = :depId AND stuffStruct.types = :types AND t.fromDepId = :fromDepId',array(':dates'=>$dates,'depId'=>$depId,':types'=>1,':fromDepId'=>0));
        foreach($model2 as $val){
            foreach($val->getRelated('inorder') as $value){
                $instuff[$value->stuff_id] = $instuff[$value->stuff_id] + $value->count;
                foreach($value->getRelated('stuffs')->getRelated('stuffStruct') as $values){
                    $outStuffProd[$values->prod_id] = $outStuffProd[$values->prod_id] + $values->amount/$value->getRelated('stuffs')->count*$value->count; 
                }
                
            }
        }
        $model3 = Inexpense::model()->with('inorder.stuffs.stuffStruct.podstuff.podstuffStruct.Struct')->findAll('date(t.inexp_date) = :dates AND t.department_id = :depId AND stuffStruct.types = :types AND t.fromDepId = :fromDepId',array(':dates'=>$dates,'depId'=>$depId,':types'=>2,':fromDepId'=>0));
        foreach($model3 as $val){
            foreach($val->getRelated('inorder') as $value){
                $instuff[$value->stuff_id] = $instuff[$value->stuff_id] + $value->count;
                foreach($value->getRelated('stuffs')->getRelated('stuffStruct') as $values){
                    $outStuff[$values->prod_id] = $outStuff[$values->prod_id] + $values->amount/$value->getRelated('stuffs')->count*$value->count;
                    foreach($values->getRelated('podstuff')->getRelated('podstuffStruct') as $vals){
                        $outStuffProd[$values->prod_id] = $outStuffProd[$values->prod_id] + $vals->amount/$values->getRelated('podstuff')->count*$values->amount/$value->getRelated('stuffs')->count*$value->count;
                    }
                }
                
            }
        }
        $outStuff = $stuff->sumArray($outDishStuff,$outStuff);

        $inexpense = new Inexpense();
        $depStuffIn = $inexpense->getDepIn($depId,$dates);
        $depStuffOut = $inexpense->getDepOut($depId,$dates);

        $this->renderPartial('viewStorage',array(
                'depOut'=>$depOut,
                'depIn'=>$depIn,
                'depStuffOut'=>$depStuffOut,
                'depStuffIn'=>$depStuffIn,
                'prodModel'=>$prodModel,
                'curProd'=>$curProd,
                'curStuff'=>$curStuff,
                'inProduct'=>$inProducts,
                'inStuff'=>$instuff,
                'outProduct'=>$outProduct,
                'outStuff'=>$outStuff,
                'outStuffProd'=>$outStuffProd,
        ));
    }
  
    
    public function actionToday(){       
        
        $this->render('today',array(
        ));
    }
     
	public function actionCreate()
	{
	   
		$products = Products::model()->with('measure')->findAll();
        
		$curModel = DepStorage::model()->with('product.measure')->findAll('t.type = :type',array(':type'=>1));
		$model=new DepStorage;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DepStorage']))
		{
            if(!empty($curModel) && $curModel->department_id == $_POST['DepStorage']['department_id']){
                $model->deleteAll(); 
            }
            if($_POST['DepStorage']['curDate'] == ''){
                $_POST['DepStorage']['curDate'] = date('Y-m-d');
            }
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors";
                if(isset($_POST['prod']))
                foreach($_POST['prod']['product_id'] as $key => $value){
                    $tempModel = new DepStorage;
                    $tempModel->curDate = $_POST['DepStorage']['curDate'];
                    $tempModel->prod_id = $value;
                    $tempModel->curCount = $this->changeToFloat($_POST['prod']['count'][$key]);
                    //$tempModel->price = $_POST['price'][$key];
                    $tempModel->department_id = $_POST['DepStorage']['department_id'];
                    $tempModel->type = 1;
                    if($tempModel->save()){
                        $messageType = 'success';
				        $message = "<strong>Well done!</strong> You successfully create data ";
                    }
                }
                if(isset($_POST['stuff']))
                foreach($_POST['stuff']['stuff_id'] as $key => $value){
                    $tempModel = new DepStorage;
                    $tempModel->curDate = $_POST['DepStorage']['curDate'];
                    $tempModel->prod_id = $value;
                    $tempModel->curCount = $this->changeToFloat($_POST['stuff']['count'][$key]);
                    //$tempModel->price = $_POST['price'][$key];
                    $tempModel->department_id = $_POST['DepStorage']['department_id'];
                    $tempModel->type = 2;
                    if($tempModel->save()){
                        $messageType = 'success';
				        $message = "<strong>Well done!</strong> You successfully create data ";
                    }
                }
                if(isset($_POST['dish']))
                    foreach($_POST['dish']['dish_id'] as $key => $value){
                        $tempModel = new DepStorage;
                        $tempModel->curDate = $_POST['DepStorage']['curDate'];
                        $tempModel->prod_id = $value;
                        $tempModel->curCount = $this->changeToFloat($_POST['dish']['count'][$key]);
                        //$tempModel->price = $_POST['price'][$key];
                        $tempModel->department_id = $_POST['DepStorage']['department_id'];
                        $tempModel->type = 3;
                        if($tempModel->save()){
                            $messageType = 'success';
                            $message = "<strong>Well done!</strong> You successfully create data ";
                        }
                    }
				//$uploadFile=CUploadedFile::getInstance($model,'filename');
				
				$transaction->commit();
				Yii::app()->user->setFlash($messageType, $message);
				//$this->redirect(array('view','id'=>$model->dep_storage_id));
					
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				//$this->refresh();
			}
			
		}

		$this->render('create',array(
			'model'=>$model,
                'curModel'=>$curModel,
                'products'=>$products,
					));
		
				
	}
    public function actionDepForm(){
        $depId = $_POST['depId'];

        $model = new Products();
        $products = $model->getProdName($depId);
        $models = new Halfstaff();
        $products = $products + $models->getStuffProdName($depId);

        $model2 = Dishes::model()->with('stuff.products')->findAll('t.department_id = :depId',array(':depId'=>$depId));

        if(!empty($model2))
        foreach($model2 as $value){
            foreach($value->getRelated('stuff') as $values){
                foreach($values->getRelated('products') as $val){
                    $products[$val->product_id] = $val->name;
                }
            }
        }

        $stuffs = $models->getStuffName($depId);

        $curProdModel = DepStorage::model()->with('product.measure')->findAll('t.department_id = :depId AND t.type = :type',array('depId'=>$depId,':type'=>1));
		$curStuffModel = DepStorage::model()->with('stuff.halfstuffType')->findAll('t.department_id = :depId AND t.type = :type',array('depId'=>$depId,':type'=>2));


        $this->renderPartial('depForm',array(
            'curProdModel'=>$curProdModel,
            'curStuffModel'=>$curStuffModel,

            'products'=>$products,
            'stuffs'=>$stuffs,

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

		if(isset($_POST['DepStorage']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$model->attributes=$_POST['DepStorage'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";

				/*
				$uploadFile=CUploadedFile::getInstance($model,'filename');
				if(!empty($uploadFile)) {
					$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
					if(!empty($uploadFile)) {
						if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'depstorage'.DIRECTORY_SEPARATOR.$model->dep_storage_id.DIRECTORY_SEPARATOR.$model->dep_storage_id.'.'.$extUploadFile)){
							$model->filename=$model->dep_storage_id.'.'.$extUploadFile;
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
					$this->redirect(array('view','id'=>$model->dep_storage_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['DepStorage'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->dep_storage_id));
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
		$dataProvider=new CActiveDataProvider('DepStorage');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		$depModel = DepStorage::model()->findAll();
		$model=new DepStorage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DepStorage']))
			$model->attributes=$_GET['DepStorage'];

		$this->render('index',array(
			'model'=>$model,
					));
		
	}
    public function actionStorageView(){
        $depId = $_POST['depId'];
        
        $depProdModel = DepStorage::model()->with('product.measure')->findAll('t.department_id = :depId AND t.type = :type',array('depId'=>$depId,':type'=>1)); 
        $depStuffModel = DepStorage::model()->with('stuff.halfstuffType')->findAll('t.department_id = :depId AND t.type = :type',array('depId'=>$depId,':type'=>2));
        $depDishModel = DepStorage::model()->with('dish')->findAll('t.department_id = :depId AND t.type = :type',array('depId'=>$depId,':type'=>3));

        $this->renderPartial('storageView',array(
            'depProdModel'=>$depProdModel,
            'depStuffModel'=>$depStuffModel,
            'depDishModel'=>$depDishModel,
        ));
    } 

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$model=new DepStorage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DepStorage']))
			$model->attributes=$_GET['DepStorage'];

		$this->render('admin',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DepStorage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DepStorage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DepStorage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dep-storage-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new DepStorage;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['DepStorage']))
			$model->attributes=$_POST['DepStorage'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of DepStorage',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'dep_storage_id',
					'curDate',
					'prod_id',
					'curCount',
					'price',
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
		
		$model=new DepStorage;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DepStorage']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['DepStorage']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['DepStorage']['name']['fileImport']);
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
						//$dep_storage_id=  $sheetData[$baseRow]['A'];
						$curDate=  $sheetData[$baseRow]['B'];
						$prod_id=  $sheetData[$baseRow]['C'];
						$curCount=  $sheetData[$baseRow]['D'];
						$price=  $sheetData[$baseRow]['E'];
						$department_id=  $sheetData[$baseRow]['F'];

						$model2=new DepStorage;
						//$model2->dep_storage_id=  $dep_storage_id;
						$model2->curDate=  $curDate;
						$model2->prod_id=  $prod_id;
						$model2->curCount=  $curCount;
						$model2->price=  $price;
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
	    $es = new TbEditableSaver('DepStorage'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'DepStorage',
        		)
    	);
	}

	
	public function actionCalendar()
	{
		$model=new DepStorage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DepStorage']))
			$model->attributes=$_GET['DepStorage'];
		$this->render('calendar',array(
			'model'=>$model,
		));	
	}

	public function actionCalendarEvents()
	{	 	
	 	$items = array();
	 	$model=DepStorage::model()->findAll();	
		foreach ($model as $value) {
			$items[]=array(
				'id'=>$value->dep_storage_id,
								
				//'color'=>'#CC0000',
	        	//'allDay'=>true,
	        	'url'=>'#',
			);
		}
	    echo CJSON::encode($items);
	    Yii::app()->end();
	}

	
}
