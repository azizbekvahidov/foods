<?php

class ReportController extends Controller
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
                'actions'=>array(
                    'ajaxDetail','ajaxSettedMargin','settedMargin','ajaxSettedMargin','prodExp','ajaxProdExp',
                    'allProd','ajaxAllProd','depDish','depDishList','dishIncome','ajaxDishIncome','depIncome','ajaxDepIncome',
                    'intervalFaktura','ajaxIntervalFaktura'
                ),
                'roles'=>array('3'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionProdExp(){
        $prodList = CHtml::listData(Products::model()->findAll(),'product_id','name');
        $this->render('prodExp',array(
            'prodList'=>$prodList
        ));
    }

    public function actionAjaxProdExp(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $product_id = $_POST['prod'];
        $dishPorce = array();
        $dishName = array();
        $stuffPorce = array();
        $stuffName = array();
        $dishOrders = array();
        $stuffOrders = array();
        $model = Dishes::model()->with('dishStruct')->findAll('dishStruct.prod_id = :prod',array(':prod'=>$product_id));
        foreach ($model as $value) {
            foreach ($value->getRelated('dishStruct') as $val) {
                $dishPorce[$value->dish_id] = $val->amount/$value->count;
                $dishName[$value->dish_id] = $value->name;
            }
        }

        $model5 = Dishes::model()->with('halfstuff.Struct.stuffStruct')->findAll('stuffStruct.prod_id = :prod AND stuffStruct.types = :types',array(':prod'=>$product_id,':types'=>1));
        foreach ($model5 as $value) {
            foreach ($value->getRelated('halfstuff') as $val) {
                foreach ($val->getRelated('Struct')->getRelated('stuffStruct') as $vals) {
                    $dishPorce[$value->dish_id] = $dishPorce[$value->dish_id] + $vals->amount/$val->getRelated('Struct')->count*$val->amount/$value->count;
                    $dishName[$value->dish_id] = $value->name;
                }
            }
        }
        $model3 = Halfstaff::model()->with('stuffStruct')->findAll('stuffStruct.prod_id = :prod AND stuffStruct.types = :types',array(':prod'=>$product_id,':types'=>1));
        foreach ($model3 as $value) {
            foreach ($value->getRelated('stuffStruct') as $val) {
                $stuffPorce[$value->halfstuff_id] = $val->amount/$value->count;
                $stuffName[$value->halfstuff_id] = $value->name;
            }
        }
        $model2 = Expense::model()->with('order')->findAll('date(order_date) BETWEEN :from AND :to AND type = :types',array(':from'=>$from,':to'=>$to,':types'=>1));
        foreach ($model2 as $value) {
            foreach ($value->getRelated('order') as $val) {
                $dishOrders[$val->just_id] = $dishOrders[$val->just_id] + $val->count;
            }
        }
        $model4 = Expense::model()->with('order')->findAll('date(order_date) BETWEEN :from AND :to AND type = :types',array(':from'=>$from,':to'=>$to,':types'=>2));
        foreach ($model4 as $value) {
            foreach ($value->getRelated('order') as $val) {
                $stuffOrders[$val->just_id] = $stuffOrders[$val->just_id] + $val->count;
            }
        }

        $prodCount = array();
        $prodSumm = array();
        $model6 = Faktura::model()->with('realize')->findAll('date(realize_date) BETWEEN :from AND :to',array(':from'=>$from,':to'=>$to));
        foreach ($model6 as $value) {
            foreach ($value->getRelated('realize') as $val) {
                $prodCount[$val->prod_id] = $prodCount[$val->prod_id] + $val->count;
                $prodSumm[$val->prod_id] = $prodSumm[$val->prod_id] + $val->count*$val->price;
            }

        }
        $this->renderPartial('ajaxProdExp',array(
            'dishPorce' => $dishPorce  ,
            'dishName' => $dishName,
            'stuffPorce' => $stuffPorce ,
            'stuffName' => $stuffName,
            'dishOrder' => $dishOrders   ,
            'stuffOrder' => $stuffOrders,
            'realizeCount' => $prodCount[$product_id],
        ));

    }

    public function actionAllProd(){

        $this->render('allProd');
    }

    public function actionAjaxAllProd(){
        $halfstuff = new Halfstaff();
        $from = $_POST['from'];
        $to = $_POST['to'];
        $allOutProd = array();
        $products = array();
        $prodCount = array();
        $prodSumm = array();
        $outProdSumm = array();





        for($i = 0; $i <= date('j',strtotime($to))-date('j',strtotime($from)); $i++){
            $dish = new Expense();
            $outProduct = array();

            $model = Expense::model()->with('order.dish.dishStruct')->findAll('date(t.order_date) = :dates',array(':dates'=>date('Y-m-d',strtotime($from)+($i*3600*24))));

            foreach ($model as $value) {
                foreach ($value->getRelated('order') as $val) {
                    $curDish[$val->getRelated('dish')->dish_id] = $val->getRelated('dish')->name;
                    $dishCount[$val->getRelated('dish')->dish_id] = $dishCount[$val->getRelated('dish')->dish_id] + $val->count;

                    foreach ($val->getRelated('dish')->getRelated('dishStruct') as $vals) {
                        $products[$vals->prod_id] = $products[$vals->prod_id] + $vals->amount/$val->getRelated('dish')->count*$val->count;
                    }

                }

            }
            $model2 = Expense::model()->with('order.dish.halfstuff')->findAll('date(t.order_date) = :dates',array(':dates'=>date('Y-m-d',strtotime($from)+($i*3600*24))));
            foreach ($model2 as $value) {
                foreach ($value->getRelated('order') as $val) {
                    $curDish[$val->getRelated('dish')->dish_id] = $val->getRelated('dish')->name;
                    foreach ($val->getRelated('dish')->getRelated('halfstuff') as $vals) {
                        $stuff[$val->getRelated('dish')->dish_id][$vals->halfstuff_id] = $stuff[$val->getRelated('dish')->dish_id][$vals->halfstuff_id] + $vals->amount/$val->getRelated('dish')->count*$val->count;
                    }

                }

            }
            $model3 = Expense::model()->with('order.halfstuff.stuffStruct')->findAll('date(t.order_date) = :dates',array(':dates'=>date('Y-m-d',strtotime($from)+($i*3600*24))));

            foreach ($model3 as $value) {
                foreach ($value->getRelated('order') as $val) {

                    $curStuff[$val->getRelated('halfstuff')->halfstuff_id] = $val->getRelated('halfstuff')->name;
                    $stuffCount[$val->getRelated('halfstuff')->halfstuff_id] = $dishCount[$val->getRelated('halfstuff')->halfstuff_id] + $val->count;
                    foreach ($val->getRelated('halfstuff')->getRelated('stuffStruct') as $vals) {
                        if($vals->types == 1)
                            $products[$vals->prod_id] = $products[$vals->prod_id] + $vals->amount/$val->getRelated('halfstuff')->count*$val->count;
                        elseIf($vals->types)
                            $stuff[$val->getRelated('halfstuff')->halfstuff_id][$vals->prod_id] = $stuff[$val->getRelated('halfstuff')->halfstuff_id][$vals->prod_id] + $vals->amount/$val->getRelated('halfstuff')->count*$val->count;
                    }

                }

            }

            $model6 = Faktura::model()->with('realize')->findAll('date(realize_date) = :dates',array(':dates'=>date('Y-m-d',strtotime($from)+($i*3600*24))));
            foreach ($model6 as $value) {
                foreach ($value->getRelated('realize') as $val) {
                    $prodCount[$val->prod_id] = $prodCount[$val->prod_id] + $val->count;
                    $prodSumm[$val->prod_id] = $prodSumm[$val->prod_id] + $val->count*$val->price;
                    $outProdSumm[$val->prod_id] = $outProdSumm[$val->prod_id] + $products[$val->prod_id]*$val->price;
                }

            }
            /*foreach (Department::model()->findAll() as $val) {
                //echo date('Y-m-d',strtotime($from)+($i*3600*24))."<br>";
                $outProduct = $halfstuff->sumArray($outProduct,$dish->getDishProd($val->department_id, date('Y-m-d',strtotime($from)+($i*3600*24))));

                //$outDishStuff = $dish->getDishStuff($depId,$dates);
            }
            $allOutProd = $halfstuff->sumArray($allOutProd,$outProduct);*/
        }

        $prodModel = Products::model()->findAll(array('order'=>'name'));
        $this->renderPartial('ajaxAllProd',array(
            'prodCount' => $prodCount,
            'prodSumm' => $prodSumm,
            'prodModel' => $prodModel,
            'products' => $products,
            'outProdSumm' => $outProdSumm
        ));

    }

    public function actionDepDish(){
        $this->render('depDish',array());
    }

    public function actionDepDishList(){
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $cost = array();
        $dishes = array();
        $summ = array();
        $counting = array();
        $prices = new Prices();
        $model = Expense::model()->with('order.dish')->findAll(
            'date(t.order_date) BETWEEN :from AND :to AND dish.department_id = :DepId',
            array(':from'=>$_POST['from'],':to'=>$_POST['to'],':DepId'=>$_POST['depId'])
        );
        foreach ($model as $value) {
            foreach ($value->getRelated('order') as $val) {
                $counting[$val->just_id] = $counting[$val->just_id] + $val->count;
                $summ[$val->just_id] = $summ[$val->just_id] + $val->count*$prices->getPrice($val->just_id,$value->mType,$val->type,$value->order_date);
                $dishes[$val->just_id] = $val->getRelated('dish')->name;
                $cost[$val->just_id] = $cost[$val->just_id] + $dish->getCostPrice($val->just_id,$value->order_date)*$val->count;
            }
        }
        $model2 = Expense::model()->with('order.halfstuff')->findAll(
            'date(t.order_date) BETWEEN :from AND :to AND halfstuff.department_id = :DepId',
            array(':from'=>$_POST['from'],':to'=>$_POST['to'],':DepId'=>$_POST['depId'])
        );
        foreach ($model2 as $value) {
            foreach ($value->getRelated('order') as $val) {
                $counting[$val->just_id] = $counting[$val->just_id] + $val->count;
                $summ[$val->just_id] = $summ[$val->just_id] + $val->count*$prices->getPrice($val->just_id,$value->mType,$val->type,$value->order_date);
                $dishes[$val->just_id] = $val->getRelated('halfstuff')->name;
                $cost[$val->just_id] = $cost[$val->just_id] + $stuff->getCostPrice($val->just_id,$value->order_date)*$val->count;
            }
        }
        $model3 = Expense::model()->with('order.products')->findAll(
            'date(t.order_date) BETWEEN :from AND :to AND products.department_id = :DepId',
            array(':from'=>$_POST['from'],':to'=>$_POST['to'],':DepId'=>$_POST['depId'])
        );
        foreach ($model3 as $value) {
            foreach ($value->getRelated('order') as $val) {
                $counting[$val->just_id] = $counting[$val->just_id] + $val->count;
                $summ[$val->just_id] = $summ[$val->just_id] + $val->count*$prices->getPrice($val->just_id,$value->mType,$val->type,$value->order_date);
                $dishes[$val->just_id] = $val->getRelated('products')->name;
                $cost[$val->just_id] = $cost[$val->just_id] + $prod->getCostPrice($val->just_id,$value->order_date)*$val->count;
            }
        }
        $this->renderPartial('dishDepList',array(
            'summ' => $summ,
            'dishes' => $dishes,
            'cost' => $cost,
            'counting' => $counting
        ));
    }

    public function actionDishIncome(){
        $this->render('dishIncome');
    }

    public function actionAjaxDishIncome(){
        $prices = new Prices();
        $dishCnt = array();
        $dCount = array();
        $prodCnt = array();
        $pCount = array();
        $stuffCnt = array();
        $sCount = array();
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $model = Expense::model()->with('order.dish')->findAll('date(t.order_date) BETWEEN :from AND :to',array(':from'=>$_POST['from'],':to'=>$_POST['to']));
        if(!empty($model)) {
            foreach ($model as $value) {
                foreach ($value->getRelated('order') as $val) {
                    $dishes[$val->just_id] = $val->getRelated('dish')->name;
                    $dishCnt[$val->just_id] = $dishCnt[$val->just_id] + $val->count * $prices->getPrice($val->just_id, $value->mType, $val->type, $value->order_date) - $dish->getCostPrice($val->just_id, $value->order_date);
                    $dCount[$val->just_id] = $dCount[$val->just_id] + $val->count;
                }
            }
        }
        $model2 = Expense::model()->with('order.halfstuff')->findAll('date(t.order_date) BETWEEN :from AND :to',array(':from'=>$_POST['from'],':to'=>$_POST['to']));
        if(!empty($model2)) {
            foreach ($model2 as $value) {
                foreach ($value->getRelated('order') as $val) {
                    $halfstuff[$val->just_id] = $val->getRelated('halfstuff')->name;
                    $stuffCnt[$val->just_id] = $stuffCnt[$val->just_id] + $val->count * $prices->getPrice($val->just_id, $value->mType, $val->type, $value->order_date) - $stuff->getCostPrice($val->just_id, $value->order_date);
                    $sCount[$val->just_id] = $sCount[$val->just_id] + $val->count;
                }
            }
        }
        $model3 = Expense::model()->with('order.products')->findAll('date(t.order_date) BETWEEN :from AND :to',array(':from'=>$_POST['from'],':to'=>$_POST['to']));
        if(!empty($model3)) {
            foreach ($model3 as $value) {
                foreach ($value->getRelated('order') as $val) {
                    $products[$val->just_id] = $val->getRelated('products')->name;
                    $prodCnt[$val->just_id] = $prodCnt[$val->just_id] + $val->count * $prices->getPrice($val->just_id, $value->mType, $val->type, $value->order_date) - $prod->getCostPrice($val->just_id, $value->order_date);
                    $pCount[$val->just_id] = $pCount[$val->just_id] + $val->count;
                }
            }
        }

        $this->renderPartial('ajaxDishIncome',array(
            'dishes'=>$dishes,
            'dishCnt'=>$dishCnt,
            'halfstuff'=>$halfstuff,
            'stuffCnt'=>$stuffCnt,
            'products'=>$products,
            'prodCnt'=>$prodCnt,
            'dCount'=>$dCount,
            'sCount'=>$sCount,
            'pCount'=>$pCount
        ));
    }

    public function actionSettedMargin(){
        $this->render('settedMargin');
    }

    public function actionAjaxSettedMargin(){
        $dates = $_POST['dates'];
        $model = Yii::app()->db->createCommand()
            ->select('d.name,m.just_id,m.mType,m.type')
            ->from('menu m')
            ->join('dishes d','d.dish_id = m.just_id')
            ->where('m.type = 1')
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('h.name,m.just_id,m.mType,m.type')
            ->from('menu m')
            ->join('halfstaff h','h.halfstuff_id = m.just_id')
            ->where('m.type = 2')
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('p.name,m.just_id,m.mType,m.type')
            ->from('menu m')
            ->join('products p','p.product_id = m.just_id')
            ->where('m.type = 3')
            ->queryAll();
        $this->renderPartial('ajaxSettedMargin',array(
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
            'dates'=>$dates,
        ));
    }

    public function actionDepIncome(){
        $dates = date('Y-m-d',strtotime(date('Y-m-d'))-86400);
        $this->render('depIncome',array(
            'dates'=>$dates
        ));
    }

    public function actionAjaxDepIncome(){
        $till = $_POST['till'];
        $from = $_POST['from'];
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();
        $this->renderPartial('ajaxDepIncome',array(
            'from'=>$from,
            'till'=>$till,
            'model'=>$model
        ));
    }

    public function actionAjaxDetail($depId,$key,$dates,$till){
        $model = array();
        $model2 = array();
        $model3 = array();
        $expense = new Expense();
        $prod = new Products();
        $stuff = new Halfstaff();
        $measure = new Measurement();
        if($key == 'begin'){
            $model = Yii::app()->db->createCommand()
                ->select('db.startCount as count,db.prod_id,p.name as name,m.name as Mname')
                ->from('dep_balance db')
                ->join('products p','p.product_id = db.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$depId,':types'=>1))
                ->queryAll();

            $model2 = Yii::app()->db->createCommand()
                ->select('db.startCount as count,db.prod_id,h.name as name, m.name as Mname')
                ->from('dep_balance db')
                ->join('halfstaff h','h.halfstuff_id = db.prod_id')
                ->join('measurement m','m.measure_id = h.stuff_type')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$depId,':types'=>2))
                ->queryAll();
        }
        if($key == 'end'){
            $model = Yii::app()->db->createCommand()
                ->select('db.endCount as count,db.prod_id,p.name as name,m.name as Mname')
                ->from('dep_balance db')
                ->join('products p','p.product_id = db.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$depId,':types'=>1))
                ->queryAll();

            $model2 = Yii::app()->db->createCommand()
                ->select('db.endCount as count,db.prod_id,h.name as name, m.name as Mname')
                ->from('dep_balance db')
                ->join('halfstaff h','h.halfstuff_id = db.prod_id')
                ->join('measurement m','m.measure_id = h.stuff_type')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$depId,':types'=>2))
                ->queryAll();
        }
        if($key == 'curEnd'){
            $model = Yii::app()->db->createCommand()
                ->select('db.CurEndCount as count,db.prod_id,p.name as name,m.name as Mname')
                ->from('dep_balance db')
                ->join('products p','p.product_id = db.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$depId,':types'=>1))
                ->queryAll();

            $model2 = Yii::app()->db->createCommand()
                ->select('db.CurEndCount as count,db.prod_id,h.name as name, m.name as Mname')
                ->from('dep_balance db')
                ->join('halfstaff h','h.halfstuff_id = db.prod_id')
                ->join('measurement m','m.measure_id = h.stuff_type')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>$dates,':depId'=>$depId,':types'=>2))
                ->queryAll();
        }
        if($key == 'realize'){
            $model = Yii::app()->db->createCommand()
                ->select('dr.count as count,dr.prod_id,p.name as name,m.name as Mname')
                ->from('dep_faktura df')
                ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
                ->join('products p','p.product_id = dr.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('date(df.real_date) = :dates AND df.department_id = :depId AND df.fromDepId = :fromDepId ',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0))
                ->queryAll();
        }
        if($key == 'price'){
            $model = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,p.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('products p','p.product_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND p.department_id = :DepId AND ord.type = :type',
                        array(':dates'=>$dates,':DepId'=>$depId,':type'=>3))
                ->group('ord.just_id')
                ->queryAll();
            $model2 = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,h.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('halfstaff h','h.halfstuff_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND h.department_id = :DepId AND ord.type = :type',
                    array(':dates'=>$dates,':DepId'=>$depId,':type'=>2))
                ->group('ord.just_id')
                ->queryAll();
            $model3 = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,d.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('dishes d','d.dish_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND d.department_id = :DepId AND ord.type = :type',
                    array(':dates'=>$dates,':DepId'=>$depId,':type'=>1))
                ->group('ord.just_id')
                ->queryAll();
        }
        if($key == 'other'){
            $model = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,p.name as name')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('products p','p.product_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND ex.kind  = :kind AND ord.type = :type',
                    array(':dates'=>$dates,':kind'=>1,':type'=>3))
                ->group('ord.just_id')
                ->queryAll();
        }
        if($key == 'costPrice'){
            $temp = $expense->getDishProd($depId,$dates,$dates);
            $count = 0;
            foreach ($temp as $key => $val) {
                $model[$count]['count'] = $val;
                $model[$count]['prod_id'] = $key;
                $model[$count]['name'] = $prod->getName($key);
                $model[$count]['Mname'] = $measure->getMeasure($key,'prod');
                $count++;
            }
            $temp2 = $expense->getDishStuff($depId,$dates,$dates);
            $count = 0;
            foreach ($temp2 as $key => $val) {
                $model2[$count]['count'] = $val;
                $model2[$count]['prod_id'] = $key;
                $model2[$count]['name'] = $stuff->getName($key);
                $model2[$count]['Mname'] = $measure->getMeasure($key,'stuff');
                $count++;
            }
            /*$model = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,p.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('products p','p.product_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND p.department_id = :DepId AND ord.type = :type',
                    array(':dates'=>$dates,':DepId'=>$depId,':type'=>3))
                ->group('ord.just_id')
                ->queryAll();
            $model2 = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,h.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('halfstaff h','h.halfstuff_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND h.department_id = :DepId AND ord.type = :type',
                    array(':dates'=>$dates,':DepId'=>$depId,':type'=>2))
                ->group('ord.just_id')
                ->queryAll();
            $model3 = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,d.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('dishes d','d.dish_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND d.department_id = :DepId AND ord.type = :type',
                    array(':dates'=>$dates,':DepId'=>$depId,':type'=>1))
                ->group('ord.just_id')
                ->queryAll();*/

        }
        if($key == 'inRealize'){
            $model = Yii::app()->db->createCommand()
                ->select('dr.count as count,dr.prod_id,p.name as name,m.name as Mname')
                ->from('dep_faktura df')
                ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
                ->join('products p','p.product_id = dr.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('date(df.real_date) = :dates AND df.department_id = :depId AND df.fromDepId != :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0))
                ->queryAll();

            $model2 = Yii::app()->db->createCommand()
                ->select('inord.count as count,inord.stuff_id as prod_id,h.name as name,m.name as Mname')
                ->from('inexpense inexp')
                ->join('inorder inord','inord.inexpense_id = inexp.inexpense_id')
                ->join('halfstaff h','h.halfstuff_id = inord.stuff_id')
                ->join('measurement m','m.measure_id = h.stuff_type')
                ->where('date(inexp.inexp_date) = :dates AND inexp.department_id = :depId AND inexp.fromDepId != :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0))
                ->queryAll();
        }
        if($key == 'inExp'){
            $model = Yii::app()->db->createCommand()
                ->select('dr.count as count,dr.prod_id,p.name as name,m.name as Mname')
                ->from('dep_faktura df')
                ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
                ->join('products p','p.product_id = dr.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('date(df.real_date) = :dates AND df.department_id != :depId AND df.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>$depId))
                ->queryAll();

            $model2 = Yii::app()->db->createCommand()
                ->select('inord.count as count,inord.stuff_id as prod_id,h.name as name,m.name as Mname')
                ->from('inexpense inexp')
                ->join('inorder inord','inord.inexpense_id = inexp.inexpense_id')
                ->join('halfstaff h','h.halfstuff_id = inord.stuff_id')
                ->join('measurement m','m.measure_id = h.stuff_type')
                ->where('date(inexp.inexp_date) = :dates AND inexp.department_id != :depId AND inexp.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>$depId))
                ->queryAll();
        }
        $this->renderPartial('ajaxDetail',array(
            'till'=>$till,
            'dates'=>$dates,
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
            'key'=>$key
        ),false,true);
    }

    public function actionIntervalFaktura(){
        $this->render('intervalFaktura');
    }

    public function actionAjaxIntervalFaktura(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $products = Yii::app()->db->createCommand()
            ->select()
            ->from('products')
            ->queryAll();
        $this->renderPartial('ajaxIntervalFaktura',array(
            'from'=>$from,
            'to'=>$to,
            'prod'=>$products
        ));
    }

}
