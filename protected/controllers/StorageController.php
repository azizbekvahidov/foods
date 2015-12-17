<?php

class StorageController extends Controller
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
				'actions'=>array('beforeEndDepBalance','beforeEnd','monthStorage','update','view','admin','delete','export','import','editable','toggle','calendar', 'calendarEvents', 'today','start','end','allstorage','allIn','endDepBalance'),
				'roles'=>array('2'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array(),
                'roles'=>array('3'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('create','index'),
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

    public function actionBeforeStart($sess,$dates){

        //if($coockie->coockie_end != null){
        $this->startDay($sess,$dates);
        $this->redirect(Yii::app()->homeUrl);
        /*} else{
            $this->endDay($sess,$coockie->coockie_date);
            $this->startDay($sess,$dates);
            $this->redirect(Yii::app()->homeUrl);
        }*/

    }
    public function actionBeforeEnd($sess,$dates){
        //данные отделов склада

        $this->endDay($sess,$dates);
        $this->redirect(Yii::app()->homeUrl);
    }


    public function actionBeforeEndDepBalance($dates){
        //данные отделов склада

        $this->endDepBalance($dates);
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionStart($sess){

        $coockie = Coockie::model()->find(array('order'=>'coockie_date DESC'));
        $dates = date('Y-m-d');
        //if($coockie->coockie_end != null){
            $this->startDay($sess,$dates);
            $this->redirect(Yii::app()->homeUrl);
        /*} else{
            $this->endDay($sess,$coockie->coockie_date);
            $this->startDay($sess,$dates);
            $this->redirect(Yii::app()->homeUrl);
        }*/

    }

    public function actionEndDepBalance(){
        $dates = date('Y-m-d');
        //данные отделов склада

        $this->endDepBalance($dates);
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionEnd($sess){
        $dates = date('Y-m-d');
        //данные отделов склада

        $this->endDay($sess,$dates);
        //$this->redirect(Yii::app()->homeUrl);
    }

    public function Holiday($dates){
        $model = MBalance::model()->find(array('order'=>'t.b_date DESC'));
        $days = (strtotime($dates)-strtotime($model->b_date))/(3600*24);
        if($days > 1){
            for($i = 1; $i < $days; $i++){
                $newModel = new MBalance();
                $newModel->b_date = date('Y-m-d',strtotime($model->b_date)+(3600*24*$i));
                $newModel->proceeds = 0;
                $newModel->procProceeds = 0;
                $newModel->cost = 0;
                $newModel->save();
            }
        }
    }
    public function startDay($sess,$dates){
            $this->Holiday($dates);
            $prodModel = Products::model()->findAll();
            //$department = Department::model()->findAll();
            $max_date = Balance::model()->find(array('select'=>'MAX(b_date) as b_date'));
            $curProd = Balance::model()->with('products')->findAll('date(t.b_date) = :dates',array(':dates'=>$max_date->b_date));
            $max_date = DepBalance::model()->find(array('select'=>'MAX(b_date) as b_date'));
            $curDepProd = DepBalance::model()->with('products')->findAll('date(t.b_date) = :dates',array(':dates'=>$max_date->b_date));

            $transaction = Yii::app()->db->beginTransaction();
            $coockieModel = Coockie::model()->find('date(t.coockie_date) = :dates',array(':dates'=>$dates));
            if(empty($coockieModel)){
                if(!empty($curProd)){
                    foreach($prodModel as $key => $value){
                        $model = new Balance;
                        $model->b_date = $dates;
                        $model->prod_id = $value->product_id;
                        foreach($curProd as $val){
                            if($val->prod_id == $value->product_id){
                                $model->startCount = $val->CurEndCount;
                            }
                        }
                        $model->save();
                    }
                }
                else{
                    $startProducts = Storage::model()->findAll();
                    foreach($prodModel as $key => $value){
                        $model = new Balance;
                        $model->b_date = $dates;
                        $model->prod_id = $value->product_id;
                        if(!empty($startProducts)){
                            foreach($startProducts as $val){
                                if($val->prod_id == $value->product_id){
                                    $model->startCount = $val->curCount;
                                }
                            }
                        }
                        $model->save();
                    }
                }
                if(!empty($curDepProd)){
                    foreach($curDepProd as $val){
                        $model = new DepBalance;
                        $model->startCount = $val->CurEndCount;
                        $model->department_id = $val->department_id;
                        $model->b_date = $dates;
                        $model->prod_id = $val->prod_id;
                        $model->type = $val->type;
                        $model->save();
                    }
                }
                else{
                    $startProducts = DepStorage::model()->findAll();

                    if(!empty($startProducts)){
                        foreach($startProducts as $val){
                            $model = new DepBalance;
                            $model->b_date = $dates;
                            $model->prod_id = $val->prod_id;
                            $model->startCount = $val->curCount;
                            $model->department_id = $val->department_id;
                            $model->type = $val->type;
                            $model->save();
                        }
                    }
                }


                $coockie = new Coockie;
                $coockie->coockie_date = $dates;
                $coockie->coockie_start = $sess;
                $coockie->save();
            }
            $transaction->commit();
    }

    public function sumMBalance($dates){
        //расчет выручки
        //$stuff = new Halfstaff();
        $sumBalance = Expense::model()->findAll('date(t.order_date) = :dates AND t.status != :status AND t.debt != :debt',array(':dates'=>$dates,':status'=>1,'debt'=>1));
        //$debt = Debt::model()->findAll('t.d_date = :dates',array(':dates'=>$dates));
        //$debts = array();
        $dayBalance = 0;
        $expense = new Expense();
        if(!empty($sumBalance))
            $dayBalance = $expense->getSum($dates);

        $mBalance = MBalance::model()->find('t.b_date = :dates',array(':dates'=>$dates));
        if(!empty($mBalance)){
            $mBalance = $mBalance;
        }
        else{
            $mBalance = new MBalance();
        }

        $mBalance->procProceeds =$dayBalance[1];//+$debts[1];
        $mBalance->b_date = $dates;
        $mBalance->proceeds =$dayBalance[2];//+$debts[2];
        $mBalance->cost = 0;
        $mBalance->save();
    }

    public function endDepBalance($dates){

        $stuff = new Halfstaff();
        //Количественный расчет по отделам
        $department = Department::model()->findAll();

        foreach($department as $v){
            $depId = $v->department_id;
            $depIn = array();
            $depOut = array();
            $inProduct = array();
            $instuff = array();
            $endProduct = array();
            $endStuff = array();
            $outProduct = array();
            $outStuff = array();
            $outStuffProd = array();
            $prodModel = Products::model()->findAll();
            foreach($prodModel as $value){
                $outProduct[$value->product_id] = 0;
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

            $dish = new Expense();


            $outProduct = $dish->getDishProd($depId,$dates);
            $outDishStuff = $dish->getDishStuff($depId,$dates);

            $model = DepFaktura::model()->with('realizedProd')->findAll('date(t.real_date) = :dates AND t.department_id = :depId AND t.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0));
            foreach($model as $key => $val){
                foreach($val->getRelated('realizedProd') as $value){
                    $inProduct[$value->prod_id] = $inProduct[$value->prod_id] + $value->count;
                }
            }

            $models2 = Inexpense::model()->with('inorder.stuffs.stuffStruct')->findAll('date(t.inexp_date) = :dates AND t.department_id = :depId AND stuffStruct.types = :types AND t.fromDepId = :fromDepId',array(':dates'=>$dates,'depId'=>$depId,':types'=>1,':fromDepId'=>0));
            foreach($models2 as $val){
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


            $curProd = DepBalance::model()->findAll('t.b_date = :dates AND t.department_id = :depId AND t.type = :type',array(':dates'=>$dates,':depId'=>$depId,':type'=>1));
            foreach($curProd as $value){
                //echo 'id -> '.$value->prod_id.'|| Начальный =>'.$value->startCount.'|| Приход =>'.$inProduct[$value->prod_id].'|| Расход =>'.$outProduct[$value->prod_id].'|| перемещ in =>'.$depIn[$value->prod_id].'|| перемещ out =>'.$depOut[$value->prod_id]."<br />";
                $endProduct[$value->prod_id] = + ($value->startCount + $inProduct[$value->prod_id]-$outProduct[$value->prod_id]-$outStuffProd[$value->prod_id]+$depIn[$value->prod_id]-$depOut[$value->prod_id]);
                $Models = DepBalance::model()->find('prod_id = :prod_id AND department_id = :depId AND b_date = :dates AND t.type = :type',array(':prod_id'=>$value->prod_id,':depId'=>$v->department_id,':dates'=>$dates,':type'=>1));
                $Models->endCount = $endProduct[$value->prod_id];
                $Models->update(array('endCount'));
            }
            $curStuff = DepBalance::model()->findAll('t.b_date = :dates AND t.department_id = :depId AND t.type = :type',array(':dates'=>$dates,':depId'=>$depId,':type'=>2));
            foreach($curStuff as $value){
                $endStuff[$value->prod_id] = ($value->startCount + $instuff[$value->prod_id]+$depStuffIn[$value->prod_id]-$outStuff[$value->prod_id]-$depStuffOut[$value->prod_id]);
                $Models = DepBalance::model()->find('prod_id = :prod_id AND department_id = :depId AND b_date = :dates AND t.type = :type',array(':prod_id'=>$value->prod_id,':depId'=>$v->department_id,':dates'=>$dates,':type'=>2));
                $Models->endCount = $endStuff[$value->prod_id];
                $Models->update(array('endCount'));
            }


        }

        //конец
    }

    public function endDay($sess,$dates){


        $transaction = Yii::app()->db->beginTransaction();
        $this->sumMBalance($dates);

              //данные основного склада
                $endStorageProducts = array();

                //Приход
                $fakturaProd = Faktura::model()->with('realize.products')->findAll('date(realize_date) = :realize_date',array('realize_date'=>$dates));
                foreach($fakturaProd as $value){
                    foreach($value->getRelated('realize') as $key => $val){
                        $inProducts[$val->getRelated('products')->product_id] = $inProducts[$val->getRelated('products')->product_id] + $val->count;
                    }
                }
                //Расход
                $Depfaktura = DepFaktura::model()->with('realizedProd')->findAll('date(real_date) = :real_date',array(':real_date'=>$dates));
        
                foreach($Depfaktura as $value){
                    foreach($value->getRelated('realizedProd') as $val){
                        $outProducts[$val->prod_id] = $outProducts[$val->prod_id] + $val->count;
                    }
                }
        
                $expense = Expense::model()->with('order.products')->findAll('date(order_date) = :dates AND t.kind = :kind',array(':kind'=>1,':dates'=>$dates));
                foreach ($expense as $value) {
                    foreach ($value->getRelated('order') as $val) {
                        $inOutProducts[$val->just_id] = $inOutProducts[$val->just_id] + $val->count;
                    }
        
                }
        
                $curProd = Balance::model()->with('products')->findAll('b_date = :dates',array(':dates'=>$dates),array('order'=>'products.name'));
                foreach($curProd as $value){
                    $endStorageProducts[$value->prod_id] = $endStorageProducts[$value->prod_id] + $value->startCount+$inProducts[$value->prod_id]-$outProducts[$value->prod_id] - $inOutProducts[$value->prod_id];
                    $Models = Balance::model()->find('prod_id = :prod_id AND b_date = :dates',array(':prod_id'=>$value->prod_id,':dates'=>$dates));
                    $Models->endCount = $endStorageProducts[$value->prod_id];
                    $Models->update(array('endCount'));
                }

                //конец
                $coockieModel = Coockie::model()->find('date(t.coockie_date) = :dates',array(':dates'=>$dates));
                if(!empty($coockieModel)){
                    $coockieModel->coockie_end = $sess;
                    $coockieModel->save();
                }
        $transaction->commit();
    }

    public function actionAllIn(){
        $dates = date('Y-m-d');
        $this->render('allIn',array(
            'dates'=>$dates,
		));
    }

    public function actionMonthStorage(){
        $dates = explode('-',$_POST['dates']);
        $number = cal_days_in_month(CAL_GREGORIAN, $dates[1], $dates[0]);

        $realized = array();
        $expenses = array();
        $startCount = array();
        $endCount = array();
        $curEndCount = array();
        $realize = new Realize();
        $depRealize = new DepFaktura();
        $inexp = new Expense();
        $balance = new Balance();

        for($i = 1; $i <= $number;$i++){
            $tempDate = $dates[0]."-".$dates[1]."-".$i;
            $listDate[$i] = $tempDate;

            $realized[$tempDate] = $realize->getRealizeSumm($tempDate);
            $expenses[$tempDate] = $inexp->getInExp($tempDate)+$depRealize->getDepRealizeSumm($tempDate);
            $tempBalance = $balance->getBalanceSumm($tempDate);
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
            'curEndCount'=>$curEndCount
        ));
    }


    public function actionAllstorage(){
        if($_POST['dates'])
            $dates = $_POST['dates'];
        else
            $dates = date('Y-m-d');
        $inProducts = array();
        $outProducts = array();
        $endProducts = array();
        
        $prodModel = Products::model()->findAll();
         //Приход
        $fakturaProd = Faktura::model()->with('realize.products')->findAll('date(realize_date) = :realize_date',array('realize_date'=>$dates));
        foreach($fakturaProd as $value){
            foreach($value->getRelated('realize') as $key => $val){
                $inProducts[$val->getRelated('products')->product_id] = $inProducts[$val->getRelated('products')->product_id] + $val->count;
            }
        }
        //Расход
        $Depfaktura = DepFaktura::model()->with('realizedProd')->findAll('date(real_date) = :real_date',array(':real_date'=>$dates));
        
        foreach($Depfaktura as $value){
            foreach($value->getRelated('realizedProd') as $val){
                $outProducts[$val->prod_id] = $outProducts[$val->prod_id] + $val->count;
            }
        }
        $expense = Expense::model()->with('order.products')->findAll('date(order_date) = :dates AND t.kind = :kind',array(':kind'=>1,':dates'=>$dates));
        foreach ($expense as $value) {
            foreach ($value->getRelated('order') as $val) {
                $inOutProducts[$val->just_id] = $inOutProducts[$val->just_id] + $val->count;
            }

        }

        $curProd = Balance::model()->with('products')->findAll('b_date = :dates',array(':dates'=>$dates),array('order'=>'products.name'));
        /*foreach($curProd as $value){
            
             $endProducts[$value->prod_id] = $endProducts[$value->prod_id] + $value->startCount+$inProducts[$value->prod_id]-$outProducts[$value->prod_id] - $inOutProducts[$value->prod_id];
            
        }*/
        
        $this->renderPartial('allstorage',array(
            'prodModel'=>$prodModel,
            'model'=>$curProd,
            'inProducts'=>$inProducts,
            'outProducts'=>$outProducts,
            'inOutProducts'=>$inOutProducts,
            'endProducts'=>$endProducts,
		));
    }
    public function actionToday(){
        $dates = date('Y-m-d');
        $startProducts = array();
        $inProducts = array();
        $outProducts = array();
        $inOutProducts = array();
        $endProducts = array();
        
        
        $prodModel = Products::model()->findAll();

        //Приход
        $fakturaProd = Faktura::model()->with('realize.products')->findAll('date(realize_date) = :realize_date',array('realize_date'=>$dates));
        foreach($fakturaProd as $value){
            foreach($value->getRelated('realize') as $key => $val){
                $inProducts[$val->getRelated('products')->product_id] = $inProducts[$val->getRelated('products')->product_id] + $val->count;
            }
        }
        //Расход
        $Depfaktura = DepFaktura::model()->with('realizedProd')->findAll('date(real_date) = :real_date',array(':real_date'=>$dates));
        
        foreach($Depfaktura as $value){
            foreach($value->getRelated('realizedProd') as $val){
                $outProducts[$val->prod_id] = $outProducts[$val->prod_id] + $val->count;
            }
        }

        $expense = Expense::model()->with('order.products')->findAll('date(order_date) = :dates AND t.kind = :kind',array(':kind'=>1,':dates'=>$dates));
        foreach ($expense as $value) {
            foreach ($value->getRelated('order') as $val) {
                $inOutProducts[$val->just_id] = $inOutProducts[$val->just_id] + $val->count;
            }

        }


        $curProd = Balance::model()->with('products')->findAll('b_date = :dates',array(':dates'=>$dates),array('order'=>'products.name'));
        foreach($curProd as $value){
            
             $endProducts[$value->prod_id] = $endProducts[$value->prod_id] + $value->startCount+$inProducts[$value->prod_id]-$outProducts[$value->prod_id]- $inOutProducts[$value->prod_id];
            
        }
        
        $this->render('today',array(
            'prodModel'=>$prodModel,
            'model'=>$curProd,
            'inProducts'=>$inProducts,
            'outProducts'=>$outProducts,
            'inOutProducts'=>$inOutProducts,
            'endProducts'=>$endProducts,
        ));
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
     
	public function actionCreate()
	{
	    $products = Products::model()->with('measure')->findAll(array('order'=>'t.name'));
		$curModel = Storage::model()->with('product.measure')->findAll(array('order'=>'product.name'));
        //if(empty($curModel)){
    		$model=new Storage;
            
    		// Uncomment the following line if AJAX validation is needed
    		// $this->performAjaxValidation($model);
    
    		if(isset($_POST['Storage']))
    		{
                if(!empty($curModel)){
                    $model->deleteAll(); 
                }
                if($_POST['Storage']['curDate'] == ''){
                    $_POST['Storage']['curDate'] = date('Y-m-d');
                }
                
    			$transaction = Yii::app()->db->beginTransaction();
    			try{
    				$messageType='warning';
    				$message = "There are some errors ";
                    foreach($_POST['product_id'] as $key => $value){
                        $tempModel = new Storage;
                        $tempModel->curDate = $_POST['Storage']['curDate'];
                        $tempModel->prod_id = $value;
                        $tempModel->curCount = $this->changeToFloat($_POST['count'][$key]);
                        $tempModel->price = $_POST['price'][$key];
                        if($tempModel->save()){
                            $messageType = 'success';
    				        $message = "<strong>Well done!</strong> You successfully create data ";
                        }
                    }
                    $transaction->commit();
    				Yii::app()->user->setFlash($messageType, $message);
    				$this->redirect(array('index'));
    				//$model->attributes=$_POST['Storage'];
    				//$uploadFile=CUploadedFile::getInstance($model,'filename');
    				/*if($model->save()){
    					$messageType = 'success';
    					$message = "<strong>Well done!</strong> You successfully create data ";
    					/*
    					$model2 = Storage::model()->findByPk($model->storage_id);						
    					if(!empty($uploadFile)) {
    						$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
    						if(!empty($uploadFile)) {
    							if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.$model2->storage_id.DIRECTORY_SEPARATOR.$model2->storage_id.'.'.$extUploadFile)){
    								$model2->filename=$model2->storage_id.'.'.$extUploadFile;
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
    					$this->redirect(array('view','id'=>$model->storage_id));
    				}	*/			
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
         //} else{
         //   $this->redirect(array('index'));
         //}
		
				
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

		if(isset($_POST['Storage']))
		{
			$messageType='warning';
			$message = "There are some errors ";
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$model->attributes=$_POST['Storage'];
				$messageType = 'success';
				$message = "<strong>Well done!</strong> You successfully update data ";

				/*
				$uploadFile=CUploadedFile::getInstance($model,'filename');
				if(!empty($uploadFile)) {
					$extUploadFile = substr($uploadFile, strrpos($uploadFile, '.')+1);
					if(!empty($uploadFile)) {
						if($uploadFile->saveAs(Yii::app()->basePath.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.$model->storage_id.DIRECTORY_SEPARATOR.$model->storage_id.'.'.$extUploadFile)){
							$model->filename=$model->storage_id.'.'.$extUploadFile;
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
					$this->redirect(array('view','id'=>$model->storage_id));
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				// $this->refresh(); 
			}

			$model->attributes=$_POST['Storage'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->storage_id));
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
		$dataProvider=new CActiveDataProvider('Storage');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		$newModel = Storage::model()->with('product.measure')->findAll(array('order'=>'product.name'));
		
		$model=new Storage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Storage']))
			$model->attributes=$_GET['Storage'];

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
		$newModel = Storage::model()->with('product')->findAll();
		$model=new Storage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Storage']))
			$model->attributes=$_GET['Storage'];

		$this->render('admin',array(
			'model'=>$model,
            'newModel'=>$newModel,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Storage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Storage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Storage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='storage-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionExport()
    {
        $model=new Storage;
		$model->unsetAttributes();  // clear any default values
		if(isset($_POST['Storage']))
			$model->attributes=$_POST['Storage'];

		$exportType = $_POST['fileType'];
        $this->widget('ext.heart.export.EHeartExport', array(
            'title'=>'List of Storage',
            'dataProvider' => $model->search(),
            'filter'=>$model,
            'grid_mode'=>'export',
            'exportType'=>$exportType,
            'columns' => array(
	                
					'storage_id',
					'curDate',
					'prod_id',
					'curCount',
	            ),
        ));
    }

    /**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionImport()
	{
		
		$model=new Storage;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Storage']))
		{
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Storage']['tmp_name']['fileImport'];
				$fileTypes = array('xls','xlsx'); // File extensions
				$fileParts = pathinfo($_FILES['Storage']['name']['fileImport']);
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
						//$storage_id=  $sheetData[$baseRow]['A'];
						$curDate=  $sheetData[$baseRow]['B'];
						$prod_id=  $sheetData[$baseRow]['C'];
						$curCount=  $sheetData[$baseRow]['D'];

						$model2=new Storage;
						//$model2->storage_id=  $storage_id;
						$model2->curDate=  $curDate;
						$model2->prod_id=  $prod_id;
						$model2->curCount=  $curCount;

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
	    $es = new TbEditableSaver('Storage'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Storage',
        		)
    	);
	}

	
	public function actionCalendar()
	{
		$model=new Storage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Storage']))
			$model->attributes=$_GET['Storage'];
		$this->render('calendar',array(
			'model'=>$model,
		));	
	}

	public function actionCalendarEvents()
	{	 	
	 	$items = array();
	 	$model=Storage::model()->findAll();	
		foreach ($model as $value) {
			$items[]=array(
				'id'=>$value->storage_id,
								
				//'color'=>'#CC0000',
	        	//'allDay'=>true,
	        	'url'=>'#',
			);
		}
	    echo CJSON::encode($items);
	    Yii::app()->end();
	}

	
}
