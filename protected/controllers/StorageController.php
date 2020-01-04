<?php

class StorageController extends SetupController
{


	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

	public $layout='//layouts/column1';
		/**
	 * @return array action filters
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
				'actions'=>array('view',),
				'roles'=>array('2'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('startBalance','beforeEndDepBalance','beforeEnd','monthStorage','update','admin','delete','export','import','editable','toggle','calendar', 'calendarEvents', 'today','start','end','allstorage','allIn','endDepBalance'),
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

        $coockie = Yii::app()->db->createCommand()
            ->select()
            ->from("coockie")
            ->order('coockie_date DESC')
            ->queryRow();
        $dates = date("Y-m-d",strtotime($coockie["coockie_date"])+86400);//date('Y-m-d');
        //if($coockie->coockie_end != null){
            $this->startDay($sess,$dates);
//            $this->redirect(Yii::app()->homeUrl);
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
        $this->redirect(Yii::app()->homeUrl);
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
                $newModel->costPrice = 0;
                $newModel->save();
            }
        }
    }
    public function startDay($dates){
         $coockie = Yii::app()->db->CreateCommand()
             ->select()
             ->from("coockie")
             ->order("coockie_date desc")
             ->queryRow();
         $model = Yii::app()->db->CreateCommand()
             ->select()
             ->from("balance")
             ->where("b_date = :dates",array(":dates" => $coockie["coockie_date"]))
             ->queryAll();
        foreach ($model as $item) {
            Yii::app()->db->createCommand()->insert("balance",array(
                'prod_id' => $item["prod_id"],
                'b_date' => date('Y-m-d'),
                'startCount' => $item["endCount"],
                'endCount' => 0,
                'CurEndCount' => 0,
            ));
         }

         $depModel = Yii::app()->db->CreateCommand()
             ->select()
             ->from("dep_balance")
             ->where("b_date = :dates",array(":dates" => $coockie["coockie_date"]))
             ->queryAll();
        foreach ($depModel as $item) {
            Yii::app()->db->createCommand()->insert("dep_balance",array(
                'prod_id' => $item["prod_id"],
                'department_id' => $item["department_id"],
                'b_date' => date('Y-m-d'),
                'startCount' => $item["endCount"],
                'endCount' => 0,
                'CurEndCount' => 0,
                'type' => $item["type"]
            ));
        }


        Yii::app()->db->createCommand()->insert("coockie", array(
            "coockie_date"=>date('Y-m-d'),
            "coockie_start"=>"coockie"
        ));

        $this->redirect(Yii::app()->homeUrl);
    }

    public function sumMBalance($dates,$fromDate){
        $from = $fromDate;
        $to = $dates;
        $days = strtotime($to)-strtotime($from);
        $newModel = Expense::model()->findAll(array('condition' => 'kind = 0', 'group' => 'date(order_date)'));
        $count = 0;
        $expense = new Expense();

        $summ = array();
        $summP = array();
        $dateList = array();
        for ($i = 0; $i <= $days/(3600*24); $i++) {

            $mBalance = MBalance::model()->find('t.b_date = :dates', array(':dates' => date('Y-m-d',strtotime($from)+(3600*24*$i))));

            $temp = $expense->getSum(date('Y-m-d',strtotime($from)+(3600*24*$i)));

            if (!empty($mBalance)) {
                $mBalance->procProceeds = $temp[1];
                $mBalance->proceeds = $temp[2];
                $mBalance->cost = 0;
                $mBalance->save();
            } else {
                $mBalance = new MBalance();
                $mBalance->b_date = date('Y-m-d',strtotime($from)+(3600*24*$i));
                $mBalance->procProceeds = $temp[1];
                $mBalance->proceeds = $temp[2];
                $mBalance->cost = 0;
                $mBalance->save();
            }
        }
    }

    public function endDepBalance($dates){
        $function = new Functions();
        $stuff = new Halfstaff();
        //Количественный расчет по отделам
        $fromDate = $dates;
        $department = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();

        foreach($department as $v){
            $depId = $v['department_id'];
            $depIn = array();
            $depOut = array();
            $inProduct = array();
            $instuff = array();
            $endProduct = array();
            $endStuff = array();
            $outProduct = array();
            $outStuff = array();
            $outStuffProd = array();
            //расход продуктов в другой отдел
            $depOut = $function->depMoveOut($depId,$dates,$fromDate);
            //приход продуктов из других отделов
            $depIn = $function->depMoveIn($depId,$dates,$fromDate);
            $dish = new Expense();


            // $outProduct = $dish->getDishProd($depId,$dates,$fromDate);

            // $outDishStuff = $dish->getDishStuff($depId,$dates,$fromDate);

            $inProduct = $function->depInProducts($depId,$dates,$fromDate);

            //Приход загатовок в отдел и расход их продуктов

            $outProduct = $dish->getDishProd($depId,$dates,$dates);
            $outDishStuff = $dish->getDishStuff($depId,$dates,$dates);

            //Приход загатовок в отдел и расход их продуктов
            $instuff = $function->depInStuff($depId,$dates,$fromDate);

            $outStuffProd = $function->depOutStuffProd($depId,$dates,$fromDate);
            //Приход и расход загатовок в отдел, расход их продуктов
            $outStuff = $function->depOutStuff($depId,$dates,$fromDate);
            $outStuff = $stuff->sumArray($outStuff,$outDishStuff);

            $inexpense = new Inexpense();
            $depStuffIn = $inexpense->getDepIn($depId,$dates,$fromDate);
            $depStuffOut = $inexpense->getDepOut($depId,$dates,$fromDate);
            $curProd = Yii::app()->db->createCommand()
                ->select('*')
                ->from('dep_balance')
                ->where('b_date = :dates AND department_id = :depId AND type = :type',array(':dates'=>$dates,':depId'=>$depId,':type'=>1))
                ->queryAll();
            foreach($curProd as $value){
                $endProduct[$value['prod_id']] = + ($value['startCount'] + $inProduct[$value['prod_id']]-$outProduct[$value['prod_id']]-$outStuffProd[$value['prod_id']]+$depIn[$value['prod_id']]-$depOut[$value['prod_id']]);
                Yii::app()->db->createCommand() -> update('dep_balance',array(
                    'endCount'=>$endProduct[$value['prod_id']]
                ),'prod_id = :prod_id AND department_id = :depId AND b_date = :dates AND type = :type',array(':prod_id'=>$value['prod_id'],':depId'=>$v['department_id'],':dates'=>$dates,':type'=>1));
            }
            $curStuff = Yii::app()->db->createCommand()
                ->select('')
                ->from('dep_balance db')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :type',array(':dates'=>$dates,':depId'=>$depId,':type'=>2))
                ->queryAll();
            foreach($curStuff as $value){
                $endStuff[$value['prod_id']] = ($value['startCount'] + $instuff[$value['prod_id']]+$depStuffIn[$value['prod_id']]-$outStuff[$value['prod_id']]-$depStuffOut[$value['prod_id']]);
                Yii::app()->db->createCommand() -> update('dep_balance',array(
                    'endCount'=>$endStuff[$value['prod_id']]
                ),'prod_id = :prod_id AND department_id = :depId AND b_date = :dates AND type = :type',array(':prod_id'=>$value['prod_id'],':depId'=>$v['department_id'],':dates'=>$dates,':type'=>2));
            }
        }
        //конец
    }

    public function getProduct($id){
        $bl = Yii::app()->config->get("balance");
         if($bl == "start"){
         }
    }

    public function actionStartBalance(){
        $dates = date('Y-m-d',strtotime(date('Y-m-d'))-86400);
        $department = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("products")
            ->where('status != 1')
            ->queryAll();
        foreach ($model as $item) {
            Yii::app()->db->createCommand()->insert("balance",array(
                'b_date' => $dates,
                'prod_id' => $item["product_id"],
                'endCount' => 0,
                'startCount' => 0,
            ));
        }
        $product = new Products();
        $stuff = new Halfstaff();
        foreach($department as $v) {
            $id = $v["department_id"];
            $prodList = $product->getProdName($id);
            $prod = $stuff->getStuffProdName($id);
            $stuffList = $stuff->getStuffName($id);

            foreach ($prod+$prodList as $key => $item) {
                Yii::app()->db->createCommand()->insert("dep_balance",array(
                    'b_date' => $dates,
                    'prod_id' => $key,
                    'department_id' => $id,
                    'endCount' => 0,
                    'startCount' => 0,
                    'type' => 1
                ));
            }

            foreach ($stuffList as $key => $item) {
                Yii::app()->db->createCommand()->insert("dep_balance",array(
                    'b_date' => $dates,
                    'prod_id' => $key,
                    'department_id' => $id,
                    'endCount' => 0,
                    'startCount' => 0,
                    'type' => 2
                ));
            }
        }

        Yii::app()->db->createCommand()->insert("coockie",array(
            'coockie_date' => date('Y-m-d',strtotime(date('Y-m-d'))-86400),
            'coockie_start' => 'coockie',
            'coockie_end' => 'coockie'
        ));


    }

    public function endDay($sess,$dates){
//        $this->Holiday($dates);
        $maxDate = Yii::app()->db->createCommand()
            ->select("")
            ->from("coockie")
            ->where("coockie_end is null")
            ->order("coockie_date desc")
            ->queryRow();
        $expProd = Yii::app()->db->createCommand()
            ->select()
            ->from("expense_list")
            ->where("expense_date = :date",array(":date"=>$maxDate["coockie_date"]))
            ->queryAll();

        foreach ($expProd as $item) {
            $prod = Yii::app()->db->createCommand()
                ->select()
                ->from("dep_balance")
                ->where("prod_id = :id and department_id = :depId and  `type` = :type and b_date = :dates",array(":id"=>$item["prod_id"],":depId"=>$item["department_id"],":type"=>$item["prod_type"],":dates"=>$maxDate["coockie_date"]))
                ->queryRow();
            if(!empty($prod))
                Yii::app()->db->createCommand()->update("dep_balance",array(
                    "endCount"=>$prod["cnt"]-$item["cnt"]
                ),"prod_id = :id and department_id = :depId and  `type` = :type and b_date = :dates",array(":id"=>$item["prod_id"],":depId"=>$item["department_id"],":type"=>$item["prod_type"],":dates"=>$maxDate["coockie_date"]));
            else
                Yii::app()->db->createCommand()->insert("dep_balance",array(
                    "prod_id"=>$item["prod_id"],
                    "department_id"=>$item["department_id"],
                    "type"=>$item["prod_type"],
                    "b_date"=>$maxDate["coockie_date"],
                    "startCount"=>0,
                    "endCount"=>$item["cnt"]
                ),"prod_id = :id and department_id = :depId and  prod_type = :type",array(":id"=>$item["prod_id"],":depId"=>$item["department_id"],":type"=>$item["prod_type"]));
        }
        Yii::app()->db->createCommand()->update("coockie",array(
            "coockie_end"=>$sess
        ),"coockie_id = :id",array(":id"=>$maxDate["coockie_id"]));
           /* $storage=Yii::app()->db->createCommand()
                ->select()
                ->from("storage")
                ->queryAll();
            $storageDep=Yii::app()->db->createCommand()
                ->select()
                ->from("storage_dep")
                ->queryAll();

            foreach ($storage as $value) {
                Yii::app()->db->createCommand()->insert("balance", array(
                    "b_date"=>$maxDate["coockie_date"],
                    "prod_id"=>$value["prod_id"],
                    "endCount"=>$value["cnt"],
                ));
            }
            foreach ($storageDep as $value) {
                Yii::app()->db->createCommand()->insert("dep_balance", array(
                    "b_date"=>$maxDate["coockie_date"],
                    "prod_id"=>$value["prod_id"],
                    "endCount"=>$value["cnt"],
                    "department_id"=>$value["department_id"],
                    "type"=>$value["prod_type"],
                ));
            }

            if($dates != date("Y-m-d")) {
                Yii::app()->db->createCommand()->insert("coockie", array(
                    "coockie_date"=>$dates,
                    "coockie_start"=>$sess
                ));
            }


        $func = new Functions();
        $timeShift = $func->getTime($dates,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        //$transaction = Yii::app()->db->beginTransaction();
//        $this->sumMBalance($dates,$dates);

        //данные основного склада
        $endStorageProducts = array();

        //Приход
        $fakturaProd = Yii::app()->db->createCommand()
            ->select('')
            ->from('faktura f')
            ->join('realize re','re.faktura_id = f.faktura_id')
            ->where('f.realize_date BETWEEN :from AND :till',array(':till'=>$dates,':from'=>$fromDate))
            ->queryAll();
        foreach($fakturaProd as $val){
            $inProducts[$val['prod_id']] = $inProducts[$val['prod_id']] + $val['count'];
        }
        //Расход

        $Depfaktura = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('df.real_date BETWEEN :from AND :till AND df.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,'fromDepId'=>0))
            ->queryAll();

        foreach($Depfaktura as $val){
            $outProducts[$val['prod_id']] = $outProducts[$val['prod_id']] + $val['count'];
        }
        $Depfaktura1 = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('df.real_date BETWEEN :from AND :till AND df.fromDepId != :fromDepId AND df.department_id = 0',array(':till'=>$dates,':from'=>$fromDate,'fromDepId'=>0))
            ->queryAll();

        foreach($Depfaktura1 as $val){
            $inProducts[$val['prod_id']] = $inProducts[$val['prod_id']] + $val['count'];
        }

        $expense = Yii::app()->db->createCommand()
            ->select('')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('ex.order_date BETWEEN :from AND :till AND ex.kind = :kind',array(':kind'=>1,':till'=>$dates,':from'=>$fromDate))
            ->queryAll();
        foreach ($expense as $val) {
            $inOutProducts[$val['just_id']] = $inOutProducts[$val['just_id']] + $val['count'];
        }
        // Обмен продуктов на указанную дату
        $exRec = Yii::app()->db->createCommand()
            ->select()
            ->from('exchange ex')
            ->join('exList el','el.exchange_id = ex.exchange_id')
            ->where('ex.exchange_date = :dates AND ex.recived = 0',array(':dates'=>$fromDate))
            ->queryAll();
        foreach ($exRec as $val) {
            $recive[$val['prod_id']] = $recive[$val['prod_id']] + $val['count'];
        }

        $exSend = Yii::app()->db->createCommand()
            ->select()
            ->from('exchange ex')
            ->join('exList el','el.exchange_id = ex.exchange_id')
            ->where('ex.exchange_date = :dates AND ex.recived = 1',array(':dates'=>$fromDate))
            ->queryAll();
        foreach ($exSend as $val) {
            $send[$val['prod_id']] = $send[$val['prod_id']] + $val['count'];
        }
        $curProd = Yii::app()->db->createCommand()
            ->select('')
            ->from('balance b')
            ->where('b.b_date = :dates',array(':dates'=>$fromDate))
            ->queryAll();
        foreach($curProd as $value){
            $endStorageProducts[$value['prod_id']] = $endStorageProducts[$value['prod_id']] + $value['startCount']+$inProducts[$value['prod_id']]-$outProducts[$value['prod_id']] - $inOutProducts[$value['prod_id']]+$recive[$value['prod_id']]-$send[$value['prod_id']];
            echo "<pre>";
            print_r($endStorageProducts[$value['prod_id']]);
            echo "</pre>";
//            Yii::app()->db->createCommand()->update('balance',array(
//                'endCount'=>$endStorageProducts[$value['prod_id']]
//            ),'prod_id = :prod_id AND b_date = :dates',array(':prod_id'=>$value['prod_id'],':dates'=>$dates));
        }*/

        //конец
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


	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Storage',
        		)
    	);
	}




}
