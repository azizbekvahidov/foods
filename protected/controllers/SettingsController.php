<?

class SettingsController extends SetupController{
    public $layout='//layouts/column1';

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
                'actions'=>array('wifi','changeBalance','ajaxChangeBalance','ajaxPrintCalculate','calculate','calculateList','prodPrice','refresh','MbalanceRefresh','percent','exportList','prodRelation','prodRelList','setPrice','setInfo'),
                'roles'=>array('3'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionRefresh(){
        $department = Department::model()->findAll();
        $depBalance = new DepBalance();
        $storage = new Storage();
        $product = Yii::app()->db->createCommand()
            ->select('')
            ->from('products p')
            ->where('p.status != 1')
            ->queryAll();

        foreach ($product as $val) {
            $storage->addToStorage($val["product_id"],0);
        }
        foreach ($department as $val) {
            $depBalance->refreshBalance($val->department_id);
            /*$dish = Dishes::model()->findAll('t.department_id = :depId',array(':depId'=>$val->department_id));
            foreach ($dish as $value) {
                $this->addDish($value->dish_id,$val->department_id);
            }

            $stuff = Halfstaff::model()->findAll('t.department_id = :depId',array(':depId'=>$val->department_id));
            foreach ($stuff as $value) {
                $this->addStuff($value->halfstuff_id,$val->department_id);
            }

            $prod = Products::model()->findAll('t.department_id = :depId',array(':depId'=>$val->department_id));
            foreach ($prod as $value) {
                $this->addProd($value->product_id,$val->department_id);
            }*/

        }

        $this->redirect(array('site/index'));
    }

    public function actionDeleteDublicate(){
        $department = Department::model()->findAll();
        $depBalance = new DepBalance();
        foreach ($department as $val) {
            $depBalance->deleteDublicate($val->department_id);

        }

        $this->redirect(array('site/index'));
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
        if($this->checkStuff($id,$depId) != true) {
            $max_date = DepBalance::model()->find(array('select' => 'MAX(b_date) as b_date'));
            $model = new DepBalance;
            $model->b_date = $max_date->b_date;
            $model->prod_id = $id;
            $model->startCount = 0;
            $model->endCount = 0;
            $model->department_id = $depId;
            $model->type = 2;
            $model->save();
        }
            //Список полуфабрикатов и их продуктов
            //$dishStruct = Halfstaff::model()->with('stuffStruct.Struct')->findByPk($id,'stuffStruct.types = :types',array(':types'=>1));

            $model = Halfstaff::model()->with('stuffStruct')->findByPk($id);
            $prod = '';
            foreach ($model->getRelated('stuffStruct') as $val) {
                if($val->types == 2) {
                    $this->addStuff($val->prod_id,$depId);
                }
                else{
                    $this->addProd($val->prod_id,$depId);
                }
            }
            return $prod;


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

    public function actionInterval(){


    }
    public function actionMbalanceRefresh(){

        if(!empty($_POST)) {
            $from = $_POST['from'];
            $to = $_POST['to'];
            $days = strtotime($to)-strtotime($from);
            $expense = new Expense();

            for ($i = 0; $i < $days/(3600*24); $i++) {

                $mBalance = MBalance::model()->find('t.b_date = :dates', array(':dates' => date('Y-m-d',strtotime($from)+(3600*24*$i))));

                $temp = $expense->getSum(date('Y-m-d',strtotime($from)+(3600*24*$i)));

                if (!empty($mBalance)) {
                    $mBalance->proceeds = $temp;
                    $mBalance->cost = 0;
                    $mBalance->save();
                } else {
                    $mBalance = new MBalance();
                    $mBalance->b_date = date('Y-m-d',strtotime($from)+(3600*24*$i));
                    $mBalance->proceeds = $temp;
                    $mBalance->cost = 0;
                    $mBalance->save();
                }
            }

            $this->redirect('/');
        }

        $this->render('interval',array(

        ));
    }

    public function actionCountDay(){
        $dates = date('Y-m-d');
        Yii::app()->db->createCommand()->delete('mDepBalance','b_date = :dates',array(':dates'=>$dates));
        Yii::app()->db->createCommand()->delete('mbalance','b_date = :dates',array(':dates'=>$dates));
        //$cnt = ($timeStptill-$timeStpfrom)/86400;
        $expense = new Expense();
        $expense = new Expense();
        $costPrice = 0;
        $prodModel = new Products();
        $stuffs = new Halfstaff();
        $dep = Department::model()->findAll();
        foreach ($dep as $value) {
            $cost = 0;
            //$cost = $expense->getDepCost($value->department_id,$dates,$dates);
            $exp = $expense->getDepIncome($value->department_id,$dates,$dates);
            $prod = Yii::app()->db->createCommand()
                ->select("sum(ex.cnt) as cnt,ex.prod_id,p.name,m.name as Mname")
                ->from("expense_list ex")
                ->join("products p","p.product_id = ex.prod_id")
                ->join("measurement m","p.measure_id = m.measure_id")
                ->where("ex.expense_date >= :from and ex.expense_date <= :to and ex.department_id = :depId and ex.prod_type = 1",array(":from"=>$dates,":to"=>$dates,":depId"=>$value->department_id))
                ->group("ex.prod_id")
                ->queryAll();
            //$temp = $expense->getDishProd($depId,$dates,$dates);
            $count = 0;
            foreach ($prod as $key => $val) {
                $cost = $cost + $prodModel->getCostPrice($val["prod_id"],$dates)*$val["cnt"];
            }
            $stuff = Yii::app()->db->createCommand()
                ->select("sum(ex.cnt) as cnt,ex.prod_id,p.name,m.name as Mname")
                ->from("expense_list ex")
                ->join("halfstaff p","p.halfstuff_id = ex.prod_id")
                ->join("measurement m","p.stuff_type = m.measure_id")
                ->where("ex.expense_date >= :from and ex.expense_date <= :to and ex.department_id = :depId and ex.prod_type = 2",array(":from"=>$dates,":to"=>$dates,":depId"=>$value->department_id))
                ->group("ex.prod_id")
                ->queryAll();
            //$temp2 = $expense->getDishStuff($depId,$dates,$dates);
            $count = 0;
            foreach ($stuff as $key => $val) {
                $cost = $cost + $stuffs->getCostPrice($val["prod_id"],$dates)*$val["cnt"];
            }
            Yii::app()->db->createCommand()->insert('mDepBalance',
                array(
                    'b_date'=>$dates,
                    'costPrice'=>$cost,
                    'department_id'=>$value->department_id,
                    'expSum'=>$exp
                )
            );
            $costPrice = $costPrice + $cost;

        }


        $expSum = $expense->getSum($dates);
        Yii::app()->db->createCommand()->insert('mbalance',
            array(
                'b_date'=>$dates,
                'costPrice'=>$costPrice,
                'proceeds'=>$expSum
            )
        );

        $this->redirect('/');

    }

	public function actionSetting(){
		$model = Percent::model()->find(array('order'=>'t.percent_date DESC'));

		if($_POST['setting']){
			try{
				$messageType='warning';
				$message = "There are some errors ";
				Yii::app()->config->set("name",$_POST["setting"]["name"]);
                Yii::app()->config->set("printerLang",$_POST["setting"]["printerLang"]);
                Yii::app()->config->set("waiterSalary",$_POST["setting"]["waiterSalary"]);
                Yii::app()->config->set("printer_interface",$_POST["setting"]["printer_interface"]);
                Yii::app()->config->set("banket",(isset($_POST["setting"]["banket"])) ? $_POST["setting"]["banket"] : "0");
                Yii::app()->config->set("banket_percent",$_POST["setting"]["banket_percent"]);
                Yii::app()->user->setFlash( $messageType, $message );
                if($model->percent != $_POST["setting"]["percent"]){
                    Yii::app()->config->set("percent",$_POST["setting"]["percent"]);
                    $percent = new Percent();
                    $percent->percent_date = date('Y-m-d');
                    $percent->percent = $_POST["setting"]["percent"];
                    $percent->save();
                }
                //$this->redirect( array( 'site/index' ) );
			}
			catch (Exception $e){
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				//$this->refresh();
			}
		}

		$this->render('setting',array(
			'model'=>$model,

		));
	}

    public function actionDumbDb(){
        Yii::import('ext.dumpDB');
        $dumper = new dumpDB();
        $bk_file = CHtml::encode(Yii::app()->name).'-'.date('dmY').'.sql';
        $fh = fopen($bk_file, 'w') or die("can't open file");
        fwrite($fh, $dumper->getDump(FALSE));
        fclose($fh);
        $this->redirect('index.php');
    }

    public function actionExportList(){

        $department = Department::model()->findAll();


        $this->render('exportList',array(
            'department'=>$department
        ));


    }

    public function actionProdRelation(){

        $this->render('prodRelation');
    }

    public function actionProdRelList($id){
        $model = Dishes::model()->with('products')->findAll('products.product_id = :prodId',array(':prodId'=>$id));
        $model2 = Halfstaff::model()->with('products')->findAll('products.product_id = :prodId',array(':prodId'=>$id));
        $this->renderPartial('prodRelList',array(
            'model'=>$model,
            'model2'=>$model2,
        ));
    }

    public function actionSetPrice(){
        $dates = date('Y-m-d H:i:s');
        $model = Menu::model()->with('dish')->findAll();
        foreach ($model as $val) {
            $price = new Prices();
            $price->price_date = $dates;
            $price->price = $val->getRelated('dish')->price;
            $price->menu_type = $val->mType;
            $price->just_id = $val->just_id;
            $price->types = $val->type;
            $price->save();
        }
        $model2 = Menu::model()->with('halfstuff')->findAll();
        foreach ($model2 as $val) {
            $price = new Prices();
            $price->price_date = $dates;
            $price->price = $val->getRelated('halfstuff')->price;
            $price->menu_type = $val->mType;
            $price->just_id = $val->just_id;
            $price->types = $val->type;
            $price->save();
        }
        $model3 = Menu::model()->with('products')->findAll();
        foreach ($model3 as $val) {
            $price = new Prices();
            $price->price_date = $dates;
            $price->price = $val->getRelated('products')->price;
            $price->menu_type = $val->mType;
            $price->just_id = $val->just_id;
            $price->types = $val->type;
            $price->save();
        }

    }

    public function actionSetInfo(){
        $dates = date('Y-m-d');
        $this->render('setInfo',array('dates'=>$dates));
    }

    public function actionajaxMInfo(){
        $dates = $_POST['dates'];
        $mInfo = MInfo::model()->find('info_date = :dates',array(':dates'=>$dates));
        $model = new MInfo();
        if(isset($_POST['Info'])){
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $model->info_date = $_POST['Info']['dates'];
                $model->proceed = $_POST['Info']['proceed'];
                $model->parish = $_POST['Info']['parish'];
                $model->term = $_POST['Info']['term'];
                $model->azizTerm = $_POST['Info']['azizTerm'];
                $model->tortShams = $_POST['Info']['tortShams'];
                $model->meat = $_POST['Info']['meat'];
                $model->other = $_POST['Info']['other'];
                $model->kassa = $_POST['Info']['kassa'];
                $model->gosBank = $_POST['Info']['gosBank'];
                $model->waitor = $_POST['Info']['waitor'];
                $model->genDir = $_POST['Info']['genDir'];
                if($model->save()){
                    $messageType = 'success';
                    $message = "<strong>Well done!</strong> You successfully create data ";

                    $transaction->commit();
                    Yii::app()->user->setFlash($messageType, $message);
                    $this->redirect(array('/site/index'));
                }
            }
            catch (Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }
        }
        $this->renderPartial('ajaxMinfo',array(
            'dates'=>$dates,
            'mInfo'=>$mInfo,
        ));

    }

    public function actionSetBalance(){
        $this->render('setBalance');
    }

    public function actionSave(){
        $function = new Functions();
        $dates = $_POST['dates'];
        $types = $_POST['types'];
        $depId = $_POST['depId'];
        $pType = $_POST['pType'];
        $pId = $_POST['pId'];
        $pVal = $function->changeToFloat($_POST['pVal']);
        if(!empty($dates)){
            if ($types == 0) {
                $model = Yii::app()->db->createCommand()
                    ->select('b.storage_id,b.cnt,p.name as pName,m.name as mName,b.prod_id')
                    ->from('storage b')
                    ->join('products p','p.product_id = b.prod_id')
                    ->join('measurement m','m.measure_id = p.measure_id')
                    ->where('b.prod_id = :id', array(':id' => $pId))
                    ->queryRow();
                if(!empty($model) && empty($model['CurEndCount'])){
                   /* $temp = Yii::app()->db->createCommand()->update('balance',array(
                        'CurEndCount'=>$pVal
                    ),'balance_id = :id',array(':id'=>$model['balance_id']));*/
                    $model['CurEndCount'] = $pVal;
                    $model['type'] = 1;
                    $return = $model;
                }
            }
            else{
                if($pType == 1) {
                    $model = Yii::app()->db->createCommand()
                        ->select('b.storage_dep_id,b.cnt,p.name as pName,m.name as mName,b.prod_id,b.prod_type')
                        ->from('storage_dep b')
                        ->join('products p', 'p.product_id = b.prod_id')
                        ->join('measurement m','m.measure_id = p.measure_id')
                        ->where('b.prod_id = :id AND b.prod_type = :types AND b.department_id =:depId', array(':id' => $pId, ':types' => $pType, ':depId' => $depId))
                        ->queryRow();
                }
                if($pType == 2){
                    $model = Yii::app()->db->createCommand()
                        ->select('b.storage_dep_id,b.cnt,h.name as pName,m.name as mName,b.prod_id,b.prod_type')
                        ->from('storage_dep b')
                        ->join('halfstaff h', 'h.halfstuff_id = b.prod_id')
                        ->join('measurement m','m.measure_id = h.stuff_type')
                        ->where('b.prod_id = :id AND b.prod_type = :types AND b.department_id =:depId', array(':id' => $pId, ':types' => $pType, ':depId' => $depId))
                        ->queryRow();
                }
                if(!empty($model) && empty($model['CurEndCount'])){

                    $model['CurEndCount'] = $pVal;
                    $return = $model;
                }
            }
        }
        if(isset($return)){
            $this->renderPartial('save', array(
                'result' => $return
            ));
        }
    }

    public function actionBalanceList(){
        $function = new Functions();
        $dates = $_POST['dates'];
        $depId = $_POST['depId'];
        if(isset($_POST['types'])){
            if($_POST['types'] == 0){
                if(!empty($_POST['count'])){
                    foreach ($_POST['count'] as $key => $val) {
                        if($key == 1){
                            foreach ($val as $keys => $value) {
                                Yii::app()->db->createCommand()->update('balance',array(
                                    'CurEndCount'=>$function->changeToFloat($value)
                                ),'b_date = :dates AND prod_id = :id ',array(':dates'=>$dates,':id'=>$keys));
                                Yii::app()->db->createCommand()->update('storage',array(
                                    'cnt'=>$function->changeToFloat($value)
                                ),'prod_id = :id ',array(':id'=>$keys));
                            }
                        }
                    }

                }
            }
            if($_POST['types'] == 1){
                if(!empty($_POST['count'])){
                    foreach ($_POST['count'] as $key => $val) {
                        if($key == 1){
                            foreach ($val as $keys => $value) {
                                Yii::app()->db->createCommand()->update('dep_balance',array(
                                    'CurEndCount'=>$function->changeToFloat($value)
                                ),'b_date = :dates AND prod_id = :id AND type = :types AND department_id = :depId',array(':dates'=>$dates,':id'=>$keys,':types'=>$key,':depId'=>$depId));
                                Yii::app()->db->createCommand()->update('storage_dep',array(
                                    'cnt'=>$function->changeToFloat($value)
                                ),'prod_id = :id AND prod_type = :types AND department_id = :depId',array(':id'=>$keys,':types'=>$key,':depId'=>$depId));
                            }
                        }
                        if($key == 2){
                            foreach ($val as $keys => $value) {
                                Yii::app()->db->createCommand()->update('dep_balance',array(
                                    'CurEndCount'=>$function->changeToFloat($value)
                                ),'b_date = :dates AND prod_id = :id AND type = :types AND department_id = :depId',array(':dates'=>$dates,':id'=>$keys,':types'=>$key,':depId'=>$depId));
                                Yii::app()->db->createCommand()->update('storage_dep',array(
                                    'cnt'=>$function->changeToFloat($value)
                                ),'prod_id = :id AND prod_type = :types AND department_id = :depId',array(':id'=>$keys,':types'=>$key,':depId'=>$depId));
                            }
                        }
                    }
                }
            }
        }
        /*$this->renderPartial('balanceList',array(
            'types'=>$_POST['types'],
            'model'=>$model,
            'model0'=>$model0,
            'check'=>$check,
            'depId'=>$_POST['depId'],
            'dates'=>$dates
        ));*/
    }
    
    public function actionAjaxBalance(){
        $dates = $_POST['dates'];
        $expense = new Expense();
        if(isset($_POST)){
            $command = Yii::app()->db->createCommand();
            if($_POST['types'] == 0){
                foreach ($_POST['prod_id'] as $key => $val) {
                    $command->update('balance', array(
                        'CurEndCount'=>$expense->changeToFloat($_POST['count'][$key]),
                    ), 'b_date=:dates AND prod_id = :prod_id', array(':dates'=>$dates,':prod_id'=>$val));
                }
            }
            elseif($_POST['types'] == 1){
                $depId = $_POST['depId'];
                foreach ($_POST['prod_id'] as $key => $val) {
                    $command->update('dep_balance', array(
                        'CurEndCount'=>$expense->changeToFloat($_POST['pcount'][$key]),
                    ), 'b_date=:dates AND prod_id = :prod_id AND type = :types AND department_id = :depId', array(':dates'=>$dates,':prod_id'=>$val,':types'=>1,':depId'=>$depId));
                }
                foreach ($_POST['stuff_id'] as $key => $val) {
                    $command->update('dep_balance', array(
                        'CurEndCount'=>$expense->changeToFloat($_POST['hcount'][$key]),
                    ), 'b_date=:dates AND prod_id = :prod_id AND type = :types AND department_id = :depId', array(':dates'=>$dates,':prod_id'=>$val,':types'=>2,':depId'=>$depId));
                }

            }
        }
    }
    
    public function actionProdPrice(){
        $command = Yii::app()->db->createCommand();
        $dates = date('Y-m-d');
        $model = $command
            ->select('')
            ->from('products')
            ->order('name')
            ->queryAll();
        if(isset($_POST['prod'])){
            foreach($_POST['prod'] as $key => $val){
                $command->update('products',array(
                    'price'=>$val
                ),'product_id = :prod_id',array(':prod_id'=>$key));
            }
            
            $this->redirect(array('site/index'));
        }
        $this->render('prodPrice',array(
            'model'=>$model,
            'dates'=>$dates
        ));
    }

    public function actionCalculate(){
        $dep = CHtml::listData(Department::model()->findAll(),'department_id','name');
        $this->render('calculate',array('dep'=>$dep));
    }

    public function actionCalculateList(){
        $id = $_POST['id'];
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->where('d.department_id = :depId',array(':depId'=>$id))
            ->queryAll();
        foreach($model as $val){
            $dishStruct = $dish->getStruct($val['dish_id']);
                $result['dish'][$val['dish_id']] = $dishStruct;
            $dishStuff = $dish->getStuff($val['dish_id']);
            if(!empty($dishStuff)){
                foreach($dishStuff as $key => $value){
                    $stuffStruct = $stuff->getStruct($key);
                    $result['stuff'][$key] = $stuffStruct;
                    $stuffStuff = $stuff->getStuffStuff($key);
                    if(!empty($stuffStuff)){
                        foreach($stuffStuff as $keys => $values){
                            $stuffsStruct = $stuff->getStruct($keys);
                            $result['stuff'][$keys] = $stuffsStruct;   
                        }
                    }
                    
                }
            }
        }
        $models = Yii::app()->db->createCommand()
            ->select('')
            ->from('halfstaff h')
            ->where('h.department_id = :depId',array(':depId'=>$id))
            ->queryAll();

        if(!empty($models)){
            foreach($models as $val){
                $dishStruct = $stuff->getStruct($val['halfstuff_id']);
                    $result['stuff'][$val['halfstuff_id']] = $dishStruct;
                $dishStuff = $stuff->getStuffStuff($val['halfstuff_id']);
                if(!empty($dishStuff)){
                    foreach($dishStuff as $key => $value){
                        $stuffStruct = $stuff->getStruct($key);
                        $result['stuff'][$key] = $stuffStruct;
                        $stuffStuff = $stuff->getStuffStuff($key);
                        if(!empty($stuffStuff)){
                            foreach($stuffStuff as $keys => $values){
                                $stuffsStruct = $stuff->getStruct($keys);
                                $result['stuff'][$keys] = $stuffsStruct;   
                                $stuffStuffs = $stuff->getStuffStuff($keys);
                                if(!empty($stuffsStruct['stuff'])){
                                    foreach($stuffsStruct['stuff'] as $keyss => $valuess){
                                        $stuffsStructs = $stuff->getStruct($keyss);
                                        $result['stuff'][$keyss] = $stuffsStructs;  

                                    }
                                }   
                            }
                        }
                    }
                }
            }
        }
        $this->renderPartial('calculateList',array(
            'result'=>$result,
            'model'=>$model,
            'id'=>$id
        ));
    }

    public function actionAjaxPrintCalculate($id){
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->where('d.department_id = :depId',array(':depId'=>$id))
            ->queryAll();
        $this->renderPartial('ajaxPrintCalculate',array(
            'model'=>$model,
            'id'=>$id
        ));
    }

    public function actionDebtRefresh(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('debt')
            ->queryAll();
            foreach($model as $val){
                Yii::app()->db->createCommand()->update('expense',array(
                    'debt'=>0
                ),'expense_id = :id',array(':id'=>$val['expense_id']));
                
            }
    }
/*
    public function actionOverWriteDep($dates,$dep){
        $dayBefore = date('Y-m-d',strtotime($dates)-86400);
        if($dep != 0){
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from('dep_balance')
                ->where('b_date = :id AND department_id = :depId',array(':id'=>$dayBefore,':depId'=>$dep))
                ->queryAll();

                Yii::app()->db->createCommand()->update('dep_balance',array(
                        'startCount'=>0
                    ),'b_date = :dates AND department_id = :depId',array(':dates'=>$dates,':depId'=>$dep));

            foreach($model as $val){
                Yii::app()->db->createCommand()->update('dep_balance',array(
                        'startCount'=>$val['CurEndCount']
                    ),'b_date = :dates AND prod_id = :prodId AND type = :types AND department_id = :depId',array(':dates'=>$dates,':prodId'=>$val['prod_id'],':types'=>$val['type'],':depId'=>$dep));
            }
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from('dep_balance')
                ->where('b_date = :id',array(':id'=>$dayBefore))
                ->queryAll();

                Yii::app()->db->createCommand()->update('dep_balance',array(
                        'startCount'=>0
                    ),'b_date = :dates',array(':dates'=>$dates));

            foreach($model as $val){
                Yii::app()->db->createCommand()->update('dep_balance',array(
                        'startCount'=>$val['CurEndCount']
                    ),'b_date = :dates AND prod_id = :prodId AND type = :types AND department_id = :depId',array(':dates'=>$dates,':prodId'=>$val['prod_id'],':types'=>$val['type'],':depId'=>$val['department_id']));
            }
        }
    }

    public function actionOverWrite($dates){
        $dayBefore = date('Y-m-d',strtotime($dates)-86400);

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('balance')
            ->where('b_date = :id',array(':id'=>$dayBefore))
            ->queryAll();
            Yii::app()->db->createCommand()->update('balance',array(
                    'startCount'=>0
                ),'b_date = :dates ',array(':dates'=>$dates));
        foreach($model as $val){
            Yii::app()->db->createCommand()->update('balance',array(
                    'startCount'=>$val['CurEndCount']
                ),'b_date = :dates AND prod_id = :prodId',array(':dates'=>$dates,':prodId'=>$val['prod_id']));
        }
    }

    public function actionChangeBalance(){
        if(!empty($_POST)){
            $text = '';
            $function = new Functions();
            $dates = $_POST['dates'];
            $depId = $_POST['depId'];
            $text .= $dates."->";
            if(isset($_POST['types'])){
                if($_POST['types'] == 0){
                    if(!empty($_POST['count'])){
                        foreach ($_POST['count'] as $key => $val) {
                            if($key == 1){
                                foreach ($val as $keys => $value) {
                                    $text .= 'depId:'.$depId.',key:'.$key.',prod_id:'.$keys.',value:'.$value."|";
                                    Yii::app()->db->createCommand()->update('balance',array(
                                        'CurEndCount'=>$function->changeToFloat($value)
                                    ),'b_date = :dates AND prod_id = :id ',array(':dates'=>$dates,':id'=>$keys));
                                }
                            }
                        }

                    }
                }
                if($_POST['types'] == 1){
                    if(!empty($_POST['count'])){
                        foreach ($_POST['count'] as $key => $val) {
                            if($key == 1){
                                foreach ($val as $keys => $value) {
                                    $text .= 'depId:'.$depId.',key:'.$key.',prod_id:'.$keys.',value:'.$value."|";
                                    Yii::app()->db->createCommand()->update('dep_balance',array(
                                        'CurEndCount'=>$function->changeToFloat($value)
                                    ),'b_date = :dates AND prod_id = :id AND type = :types AND department_id = :depId',array(':dates'=>$dates,':id'=>$keys,':types'=>$key,':depId'=>$depId));
                                }
                            }
                            if($key == 2){
                                foreach ($val as $keys => $value) {
                                    $text .= 'depId:'.$depId.',key:'.$key.',prod_id:'.$keys.',value:'.$value."|";
                                    Yii::app()->db->createCommand()->update('dep_balance',array(
                                        'CurEndCount'=>$function->changeToFloat($value)
                                    ),'b_date = :dates AND prod_id = :id AND type = :types AND department_id = :depId',array(':dates'=>$dates,':id'=>$keys,':types'=>$key,':depId'=>$depId));
                                }
                            }
                        }
                    }
                }
            }
            $text .= "\r";
            $file = fopen('logs/changeBalance.txt',a);
            fwrite($file,$text);
            fclose($file);
        }
        $this->render('changeBalance');
    }

    public function actionAjaxChangeBalance(){
        $function = new Functions();
        $dates = $_POST['dates'];
        $types = $_POST['types'];
        $depId = $_POST['depId'];
        $pType = $_POST['pType'];
        $pId = $_POST['pId'];
        $pVal = $function->changeToFloat($_POST['pVal']);
        if(!empty($dates)){
            if ($types == 0) {
                $model = Yii::app()->db->createCommand()
                    ->select('b.balance_id,b.CurEndCount,p.name as pName,m.name as mName,b.prod_id')
                    ->from('balance b')
                    ->join('products p','p.product_id = b.prod_id')
                    ->join('measurement m','m.measure_id = p.measure_id')
                    ->where('b.b_date = :dates AND prod_id = :id', array(':dates' => $dates, ':id' => $pId))
                    ->queryRow();
                if(!empty($model)){
                    $model['CurEndCount'] = $pVal;
                    $model['type'] = 1;
                    $return = $model;
                }
            }
            else{
                if($pType == 1) {
                    $model = Yii::app()->db->createCommand()
                        ->select('b.dep_balance_id,b.CurEndCount,p.name as pName,m.name as mName,b.prod_id,b.type')
                        ->from('dep_balance b')
                        ->join('products p', 'p.product_id = b.prod_id')
                        ->join('measurement m','m.measure_id = p.measure_id')
                        ->where('b.b_date = :dates AND b.prod_id = :id AND b.type = :types AND b.department_id =:depId', array(':dates' => $dates, ':id' => $pId, ':types' => $pType, ':depId' => $depId))
                        ->queryRow();
                }
                if($pType == 2){
                    $model = Yii::app()->db->createCommand()
                        ->select('b.dep_balance_id,b.CurEndCount,h.name as pName,m.name as mName,b.prod_id,b.type')
                        ->from('dep_balance b')
                        ->join('halfstaff h', 'h.halfstuff_id = b.prod_id')
                        ->join('measurement m','m.measure_id = h.stuff_type')
                        ->where('b.b_date = :dates AND b.prod_id = :id AND b.type = :types AND b.department_id =:depId', array(':dates' => $dates, ':id' => $pId, ':types' => $pType, ':depId' => $depId))
                        ->queryRow();
                }
                if(!empty($model)){
                    $model['CurEndCount'] = $pVal;
                    $return = $model;
                }
            }
        }
        if(isset($return)){
            $this->renderPartial('ajaxChangeBalance', array(
                'result' => $return
            ));
        }
    }
*/
    public function actionCountExpSum(){
        $expense = new Expense();

        $from = '2017-01-19';
        $to = '2017-01-31';
        $days = strtotime($to)-strtotime($from);
        for ($i = 0; $i <= $days/(3600*24); $i++) {

            $expense->ExpSumCounter(date('Y-m-d',strtotime($from)+(3600*24*$i)));

        }
    }

    public function actionCountDepFakturaSum(){
        $faktura = new Faktura();

        $from = '2017-02-01';
        $to = '2017-02-06';
        $days = strtotime($to)-strtotime($from);
        for ($i = 0; $i <= $days/(3600*24); $i++) {

            $faktura->getDepFakturaSum(date('Y-m-d',strtotime($from)+(3600*24*$i)));

        }
    }

    public function actionCountFakturaSum(){
        $faktura = new Faktura();

        $from = '2017-01-01';
        $to = '2017-02-06';
        $days = strtotime($to)-strtotime($from);
        for ($i = 0; $i <= $days/(3600*24); $i++) {

            $faktura->getFakturaSum(date('Y-m-d',strtotime($from)+(3600*24*$i)));

        }
    }

    public function actionCountInexpenseSum(){
        $faktura = new Faktura();

        $from = '2017-02-01';
        $to = '2017-02-06';
        $days = strtotime($to)-strtotime($from);
        for ($i = 0; $i <= $days/(3600*24); $i++) {

            $faktura->getDepInexpenseSum(date('Y-m-d',strtotime($from)+(3600*24*$i)));

        }
    }

    public function actionCountCostPrice(){$start = microtime(true);
        $expense = new Expense();
        $from = '2017-02-07';
        $to = '2017-02-07';
        $days = strtotime($to)-strtotime($from);
        for ($i = 0; $i <= $days/(3600*24); $i++) {
            $dates = date('Y-m-d',strtotime($from)+(3600*24*$i));
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("expense ex")
                ->where("date(ex.order_date) = :dates",array(':dates'=>$dates))
                ->queryAll();
            foreach ($model as $val) {
                $expense->getExpenseCostPrice($val['expense_id'],$dates);
            }

//            $faktura->getDepInexpenseSum(date('Y-m-d',strtotime($from)+(3600*24*$i)));


        }
        $time = microtime(true) - $start;
        printf('Скрипт выполнялся %.4F сек.', $time);
    }

    public function actionOffCostPrice(){
        $start = microtime(true);
        $expense = new Expense();
        $from = '2017-04-01';
        $to = '2017-04-20';
        $days = strtotime($to)-strtotime($from);
        for ($i = 0; $i <= $days/(3600*24); $i++) {
            $dates = date('Y-m-d',strtotime($from)+(3600*24*$i));
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("off o")
                ->where("date(o.off_date) = :dates",array(':dates'=>$dates))
                ->queryAll();
            foreach ($model as $val) {
                $expense->getOffCostPrice($val['off_id'],$dates);
            }

//            $faktura->getDepInexpenseSum(date('Y-m-d',strtotime($from)+(3600*24*$i)));


        }
        $time = microtime(true) - $start;
        printf('Скрипт выполнялся %.4F сек.', $time);
    }

    public function actionWifi(){

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("settings")
            ->where("setting_name = 'wifi'")
            ->queryRow();

        if($_POST['wifi']){
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $messageType='warning';
                $message = "There are some errors ";
                Yii::app()->db->createCommand()->update("settings",array(
                    "setting_value"=>$_POST["wifi"]
                    ),"setting_id = :id",array(":id"=>$model["setting_id"]));
                    $transaction->commit();
                    $this->redirect( array( 'site/index' ) );
            }
            catch (Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', "{$e->getMessage()}");
                //$this->refresh();
            }
        }

        $this->render('wifi',array(
            'model'=>$model,

        ));
    }

    public function actionCopyToStorage(){
      $func = new Storage();
        $dates = '2018-09-03';
        $storage = Yii::app()->db->createCommand()
            ->select()
            ->from("balance")
            ->where("b_date = :dates",array(":dates"=>$dates))
            ->queryAll();

        foreach ($storage as $val) {
           $func->addToStorage($val["prod_id"],$val["CurEndCount"]);
        }

        $depstorage = Yii::app()->db->createCommand()
            ->select()
            ->from("dep_balance")
            ->where("b_date = :dates",array(":dates"=>$dates))
            ->queryAll();

        foreach ($depstorage as $val) {
            $func->addToStorageDep($val["prodId"],$val["CurEndCount"],$val["type"],$val["departmen_id"]);
            /*
            Yii::app()->db->createCommand()->insert("storage_dep",array(
                'prod_id'=>$val["prod_id"],
                'cnt'=>$val["CurEndCount"],
                'department_id'=>$val["department_id"],
                'prod_type'=>$val["type"],
            ));*/
        }

    }
}