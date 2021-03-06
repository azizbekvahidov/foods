<?php

class ReportController extends SetupController
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

                'actions'=>array('PrintDepDishList','departmentPrice','ajaxDepartmentPrice',
                    'ajaxDetail','ajaxSettedMargin','settedMargin','ajaxSettedMargin','prodExp','ajaxProdExp','infoReport','ajaxInfoReport',
                    'allProd','ajaxAllProd','depDish','depDishList','dishIncome','ajaxDishIncome','depIncome','ajaxDepIncome',
                    'intervalFaktura','ajaxIntervalFaktura','empExpense','ajaxEmpExpense','ReadyTime','ajaxReadyTime','ajaxReadyTimeDetail',
                    'exchange','ajaxExchange','archive','ajaxArchive'
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
        $depId = $_GET['depId'];
        $func = new Functions();
        $timeShift = $func->getTime($_GET['from'],$_GET['to']);
        $from = $timeShift[0];
        $till = $timeShift[1];
        //old summary script
        $model = Yii::app()->db->createCommand()
            ->select('(select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1) as sum, ord.just_id, d.name, ord.count, ex.order_date')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND d.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>1,':depId'=>$depId))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('(select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1) as sum, ord.just_id, h.name, ord.count, ex.order_date')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND h.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>2,':depId'=>$depId))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('(select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1) as sum, ord.just_id, p.name, ord.count, ex.order_date')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND p.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>3,':depId'=>$depId))
            ->queryAll();

        $cost = 0;
        $dishes = array();
        $stuffs = array();
        $prods = array();
        $summ = 0;
        $counting = array();
        foreach ($model as $val) {
            $dishes["counting"][$val["just_id"]] = $dishes["counting"][$val["just_id"]] + $val["count"];
            $dishes["summ"][$val["just_id"]] = $dishes["summ"][$val["just_id"]] + $val["sum"]*$val["count"];
            $dishes["name"][$val["just_id"]] = $val["name"];
            $dishes["cost"][$val["just_id"]] = $dishes["cost"][$val["just_id"]] + $dish->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
            $summ = $summ + $val["sum"]*$val["count"];
            $cost = $cost + $dish->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
        }
        foreach ($model2 as $val) {
            $stuffs["counting"][$val["just_id"]] = $stuffs["counting"][$val["just_id"]] + $val["count"];
            $stuffs["summ"][$val["just_id"]] = $stuffs["summ"][$val["just_id"]] + $val["sum"]*$val["count"];
            $stuffs["name"][$val["just_id"]] = $val["name"];
            $stuffs["cost"][$val["just_id"]] = $stuffs["cost"][$val["just_id"]] + $stuff->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
            $summ = $summ + $val["sum"]*$val["count"];
            $cost = $cost + $stuff->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
        }
        foreach ($model3 as $val) {
            $prods["counting"][$val["just_id"]] = $prods["counting"][$val["just_id"]] + $val["count"];
            $prods["summ"][$val["just_id"]] = $prods["summ"][$val["just_id"]] + $val["sum"]*$val["count"];
            $prods["name"][$val["just_id"]] = $val["name"];
            $prods["cost"][$val["just_id"]] = $prods["cost"][$val["just_id"]] + $prod->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
            $summ = $summ + $val["sum"]*$val["count"];
            $cost = $cost + $prod->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
        }
        $this->renderPartial('dishDepList',array(
            'summ' => $summ,
            'dishes' => $dishes,
            'prods' => $prods,
            'stuffs' => $stuffs,
            'cost' => $cost,
            'counting' => $counting
        ));
    }

    public function actionPrintDepDishList(){
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $depId = $_GET['depId'];
        $func = new Functions();
        $timeShift = $func->getTime($_GET['from'],$_GET['to']);
        $from = $timeShift[0];
        $till = $timeShift[1];
        //old summary script
        $model = Yii::app()->db->createCommand()
            ->select('(select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1) as sum, ord.just_id, d.name, ord.count, ex.order_date')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND d.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>1,':depId'=>$depId))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('(select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1) as sum, ord.just_id, h.name, ord.count, ex.order_date')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND h.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>2,':depId'=>$depId))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('(select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1) as sum, ord.just_id, p.name, ord.count, ex.order_date')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND p.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>3,':depId'=>$depId))
            ->queryAll();

        $cost = 0;
        $dishes = array();
        $stuffs = array();
        $prods = array();
        $summ = 0;
        $counting = array();
        foreach ($model as $val) {
            $dishes["counting"][$val["just_id"]] = $dishes["counting"][$val["just_id"]] + $val["count"];
            $dishes["summ"][$val["just_id"]] = $dishes["summ"][$val["just_id"]] + $val["sum"]*$val["count"];
            $dishes["name"][$val["just_id"]] = $val["name"];
            $dishes["cost"][$val["just_id"]] = $dishes["cost"][$val["just_id"]] + $dish->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
            $summ = $summ + $val["sum"]*$val["count"];
            $cost = $cost + $dish->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
        }
        foreach ($model2 as $val) {
            $stuffs["counting"][$val["just_id"]] = $stuffs["counting"][$val["just_id"]] + $val["count"];
            $stuffs["summ"][$val["just_id"]] = $stuffs["summ"][$val["just_id"]] + $val["sum"]*$val["count"];
            $stuffs["name"][$val["just_id"]] = $val["name"];
            $stuffs["cost"][$val["just_id"]] = $stuffs["cost"][$val["just_id"]] + $stuff->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
            $summ = $summ + $val["sum"]*$val["count"];
            $cost = $cost + $stuff->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
        }
        foreach ($model3 as $val) {
            $prods["counting"][$val["just_id"]] = $prods["counting"][$val["just_id"]] + $val["count"];
            $prods["summ"][$val["just_id"]] = $prods["summ"][$val["just_id"]] + $val["sum"]*$val["count"];
            $prods["name"][$val["just_id"]] = $val["name"];
            $prods["cost"][$val["just_id"]] = $prods["cost"][$val["just_id"]] + $prod->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
            $summ = $summ + $val["sum"]*$val["count"];
            $cost = $cost + $prod->getCostPrice($val["just_id"],$val["order_date"])*$val["count"];
        }
        $this->renderPartial('printDishDepList',array(
            'summ' => $summ,
            'dishes' => $dishes,
            'prods' => $prods,
            'stuffs' => $stuffs,
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
        $prod = new Products(); $expense = new Expense();
        //echo $expense->getFactCostPrice('2016-12-01','2016-12-01',1);
        // $costPrice = $expense->getDepCost(1,'2016-12-01','2016-12-01');
        // echo $prod->getCostPrice(32,'2016-12-01')."<br>";
        // echo $prod->getOldCostPrice(32,'2016-12-01');
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
        $sumAll = Yii::app()->db->createCommand()
            ->select("sum(expSum) as summ")
            ->from("mDepBalance")
            ->where("b_date between :start and :end",array(":start"=>$from,":end"=>$till))
            ->queryRow();
        $this->renderPartial('ajaxDepIncome',array(
            'from'=>$from,
            'till'=>$till,
            'model'=>$model,
            'sumAll'=>$sumAll
        ));
    }

    public function actionDepartmentPrice(){
        $dates = date('Y-m-d',strtotime(date('Y-m-d'))-86400);
        $prod = new Products(); $expense = new Expense();
        //echo $expense->getFactCostPrice('2016-12-01','2016-12-01',1);
        // $costPrice = $expense->getDepCost(1,'2016-12-01','2016-12-01');
        // echo $prod->getCostPrice(32,'2016-12-01')."<br>";
        // echo $prod->getOldCostPrice(32,'2016-12-01');
        $this->render('departmentPrice',array(
            'dates'=>$dates
        ));
    }

    public function actionAjaxDepartmentPrice(){
        $till = $_POST['till'];
        $from = $_POST['from'];
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();
        $this->renderPartial('ajaxDepartmentPrice',array(
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
                ->select('db.CurEndCount as count,db.prod_id,p.name as name,m.name as Mname')
                ->from('dep_balance db')
                ->join('products p','p.product_id = db.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>date("Y-m-d",strtotime($dates)-86400),':depId'=>$depId,':types'=>1))
                ->queryAll();

            $model2 = Yii::app()->db->createCommand()
                ->select('db.CurEndCount as count,db.prod_id,h.name as name, m.name as Mname')
                ->from('dep_balance db')
                ->join('halfstaff h','h.halfstuff_id = db.prod_id')
                ->join('measurement m','m.measure_id = h.stuff_type')
                ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :types',array(':dates'=>date("Y-m-d",strtotime($dates)-86400),':depId'=>$depId,':types'=>2))
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
                ->where('date(ex.order_date) = :dates AND p.department_id = :DepId AND ord.type = :type AND ord.deleted != 1',
                        array(':dates'=>$dates,':DepId'=>$depId,':type'=>3))
                ->group('ord.just_id')
                ->queryAll();
            $model2 = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,h.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('halfstaff h','h.halfstuff_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND h.department_id = :DepId AND ord.type = :type AND ord.deleted != 1',
                    array(':dates'=>$dates,':DepId'=>$depId,':type'=>2))
                ->group('ord.just_id')
                ->queryAll();
            $model3 = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as count,ord.just_id as prod_id,d.name as name,ex.mType,ord.type')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('dishes d','d.dish_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND d.department_id = :DepId AND ord.type = :type AND ord.deleted != 1',
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
            $function = new Functions();
            $prod = Yii::app()->db->createCommand()
                ->select("sum(ex.cnt) as cnt,ex.prod_id,p.name,m.name as Mname")
                ->from("expense_list ex")
                ->join("products p","p.product_id = ex.prod_id")
                ->join("measurement m","p.measure_id = m.measure_id")
                ->where("ex.expense_date >= :from and ex.expense_date <= :to and ex.department_id = :depId and ex.prod_type = 1",array(":from"=>$dates,":to"=>$till,":depId"=>$depId))
                ->group("ex.prod_id")
                ->queryAll();
            //$temp = $expense->getDishProd($depId,$dates,$dates);
            $count = 0;
            foreach ($prod as $key => $val) {
                $model[$count]['count'] = $val["cnt"];
                $model[$count]['prod_id'] = $val["prod_id"];
                $model[$count]['name'] = $val["name"];
                $model[$count]['Mname'] = $val["Mname"];
                $count++;
            }
            $stuff = Yii::app()->db->createCommand()
                ->select("sum(ex.cnt) as cnt,ex.prod_id,p.name,m.name as Mname")
                ->from("expense_list ex")
                ->join("halfstaff p","p.halfstuff_id = ex.prod_id")
                ->join("measurement m","p.stuff_type = m.measure_id")
                ->where("ex.expense_date >= :from and ex.expense_date <= :to and ex.department_id = :depId and ex.prod_type = 2",array(":from"=>$dates,":to"=>$till,":depId"=>$depId))
                ->group("ex.prod_id")
                ->queryAll();
            //$temp2 = $expense->getDishStuff($depId,$dates,$dates);
            $count = 0;
            foreach ($stuff as $key => $val) {
                $model2[$count]['count'] = $val["cnt"];
                $model2[$count]['prod_id'] = $val["prod_id"];
                $model2[$count]['name'] = $val["name"];
                $model2[$count]['Mname'] = $val["Mname"];
                $count++;
            }


//    				echo "<pre>";
//    				print_r($temp2);
//    				echo "</pre>";
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

    public function actionEmpExpense(){

        $this->render('empExpense');
    }

    public function actionAjaxEmpExpense(){
        $model = Yii::app()->db->createCommand()
            ->select('sum(o.count) as count, o.just_id, o.type')
            ->from('expense ex')
            ->join('orders o','o.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ex.kind != 1 AND ex.employee_id = :empId AND o.deleted != 1 AND o.type = 1 ',array(':dates'=>$_POST['from'],':empId'=>$_POST['empId']))
            ->group('o.just_id')
            ->queryAll();
        $model1 = Yii::app()->db->createCommand()
            ->select('sum(o.count) as count, o.just_id, o.type')
            ->from('expense ex')
            ->join('orders o','o.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ex.kind != 1 AND ex.employee_id = :empId AND o.deleted != 1 AND o.type = 2',array(':dates'=>$_POST['from'],':empId'=>$_POST['empId']))
            ->group('o.just_id')
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('sum(o.count) as count, o.just_id, o.type')
            ->from('expense ex')
            ->join('orders o','o.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ex.kind != 1 AND ex.employee_id = :empId AND o.deleted != 1 AND o.type = 3',array(':dates'=>$_POST['from'],':empId'=>$_POST['empId']))
            ->group('o.just_id')
            ->queryAll();
        $this->renderPartial('ajaxEmpExpense',array(
            'model'=>$model,
            'model1'=>$model1,
            'model2'=>$model2
        ));
    }

    public function actionReadyTime(){

        $this->render('readyTime');
    }

    public function actionAjaxReadyTime(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $depId = $_POST['depId'];
        $tempDate = $this->createDateRangeArray( $from, $to );

        $this->renderPartial('ajaxReadyTime',array(
            'dates'=>$tempDate,
            'depId'=>$depId
        ));
    }
    public function actionAjaxReadyTimeDetail(){
        $temp = $_POST['id'];
        $depId = $_POST['depId'];
        $model = Yii::app()->db->createCommand()
            ->select('d.name, sum(o.count) as sumCount, o.just_id')
            ->from('expense e')
            ->join('orders o','o.expense_id = e.expense_id')
            ->join('dishes d','d.dish_id = o.just_id')
            ->where('date(e.order_date) = :dates AND o.type = 1 AND d.department_id = :depId',array(':dates'=>$temp,':depId'=>$depId))
            ->group('o.just_id')
            ->order('sumCount DESC')
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('d.name, sum(o.count) as sumCount, o.just_id')
            ->from('expense e')
            ->join('orders o','o.expense_id = e.expense_id')
            ->join('halfstaff d','d.halfstuff_id = o.just_id')
            ->where('date(e.order_date) = :dates AND o.type = 2 AND d.department_id = :depId',array(':dates'=>$temp,':depId'=>$depId))
            ->group('o.just_id')
            ->order('sumCount DESC')
            ->queryAll();

        $model3 = Yii::app()->db->createCommand()
            ->select('d.name, sum(o.count) as sumCount, o.just_id')
            ->from('expense e')
            ->join('orders o','o.expense_id = e.expense_id')
            ->join('products d','d.product_id = o.just_id')
            ->where('date(e.order_date) = :dates AND o.type = 3 AND d.department_id = :depId',array(':dates'=>$temp,':depId'=>$depId))
            ->group('o.just_id')
            ->order('sumCount DESC')
            ->queryAll();

        $this->renderPartial('ajaxReadyTimeDetail',array(
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3,
            'dates'=>$temp
        ));
    }
    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }

    public function actionInfoReport(){
        $till = $_POST['till'];
        $from = $_POST['from'];

        $this->render('infoReport',array(
            'till'=>$till,
            'from'=>$from
        ));
    }

    public function actionAjaxInfoReport(){
        $from = $_POST['from'];
        $till = $_POST['till'];

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('MInfo')
            ->where('info_date >= :from AND info_date <= :till',array(':till'=>$till,':from'=>$from))
            ->group('info_date')
            ->queryAll();
        $this->renderPartial('ajaxInfoReport',array(
            'model'=>$model,
        ));
    }

    public function actionExchange(){
        $this->render('exchange');
    }

    public function actionAjaxExchange(){
        $from = $_POST['from'];
        $till = $_POST['to'];
        $cont = $_POST['cont'];
        $model = Yii::app()->db->createCommand()
            ->select('ex.exchange_date, co.name as Cname, p.name as Pname, el.count')
            ->from('exchange ex')
            ->join('contractor co','co.contractor_id = ex.contractor_id')
            ->join('exList el','el.exchange_id = ex.exchange_id')
            ->join('products p','p.product_id = el.prod_id')
            ->where('ex.exchange_date BETWEEN :from AND :till AND ex.contractor_id = :id AND ex.recived = 0',array(':from'=>$from,':till'=>$till,':id'=>$cont))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('ex.exchange_date, co.name as Cname, p.name as Pname, el.count')
            ->from('exchange ex')
            ->join('contractor co','co.contractor_id = ex.contractor_id')
            ->join('exList el','el.exchange_id = ex.exchange_id')
            ->join('products p','p.product_id = el.prod_id')
            ->where('ex.exchange_date BETWEEN :from AND :till AND ex.contractor_id = :id AND ex.recived = 1',array(':from'=>$from,':till'=>$till,':id'=>$cont))
            ->queryAll();
        $this->renderPartial('ajaxExchange',array(
            'model'=>$model,
            'model2'=>$model2
        ));
    }

    public function actionArchive(){
        $this->render("archive");
    }

    public function actionAjaxArchive(){

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("archiveOrder")
            ->where("expense_id = :id",array(":id"=>$_POST["expId"]))
            ->queryAll();

        $this->renderPartial("ajaxArchive",array(
            "model"=>$model
        ));
    }
}
