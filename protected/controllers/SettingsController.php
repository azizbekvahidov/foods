<?

class SettingsController extends Controller{
    public $layout='//layouts/column1';
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
                'actions'=>array('calculateList','prodPrice','refresh','MbalanceRefresh','percent','exportList','prodRelation','prodRelList','setPrice','setInfo'),
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

    public function actionRefresh(){
        $department = Department::model()->findAll();
        foreach ($department as $val) {
            $dish = Dishes::model()->findAll('t.department_id = :depId',array(':depId'=>$val->department_id));
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
            }

        }

        $this->redirect(array('site/index'));
    }
    public function actionInterval(){


    }
    public function actionMbalanceRefresh(){

        if(!empty($_POST)) {
            $from = $_POST['from'];
            $to = $_POST['to'];
            $days = strtotime($to)-strtotime($from);
            $newModel = Expense::model()->findAll(array('condition' => 'kind = 0', 'group' => 'date(order_date)'));
            $count = 0;
            $expense = new Expense();

            $summ = array();
            $summP = array();
            $dateList = array();
            for ($i = 0; $i < $days/(3600*24); $i++) {

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

            $this->redirect('/');
        }

        $this->render('interval',array(

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

	public function actionPercent(){
		$model = Percent::model()->find(array('order'=>'t.percent_date DESC'));

		if($_POST['percent']){
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$messageType='warning';
				$message = "There are some errors ";
				$model = new Percent();
				$model->percent_date = date('Y-m-d H:i:s');
				$model->percent = $_POST['percent'];
				if($model->save()) {
					$transaction->commit();
					Yii::app()->user->setFlash( $messageType, $message );
					$this->redirect( array( 'site/index' ) );
				}
			}
			catch (Exception $e){
				$transaction->rollBack();
				Yii::app()->user->setFlash('error', "{$e->getMessage()}");
				//$this->refresh();
			}
		}

		$this->render('percent',array(
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

    public function actionBalanceList(){
        $dates = $_POST['dates'];

        if(isset($_POST['types'])){
            if($_POST['types'] == 0){
                $model = Yii::app()->db->createCommand()
                    ->select('b.prod_id,p.name as Pname,m.name as Mname')
                    ->from('balance b')
                    ->join('products p','p.product_id = b.prod_id')
                    ->join('measurement m','m.measure_id = p.measure_id')
                    ->where('b.b_date = :dates',array(':dates'=>$dates))
                    ->order('p.name')
                    ->queryAll();
                $check = Yii::app()->db->createCommand()
                    ->select('')
                    ->from('balance b')
                    ->where('b.b_date = :dates',array(':dates'=>$dates))
                    ->queryRow();
            }
            elseif($_POST['types'] == 1){

                $model0 = Yii::app()->db->createCommand()
                    ->select('b.prod_id,h.name as Pname,m.name as Mname')
                    ->from('dep_balance b')
                    ->join('halfstaff h','h.halfstuff_id = b.prod_id')
                    ->join('measurement m','m.measure_id = h.stuff_type')
                    ->where('b.b_date = :dates AND b.department_id = :depId AND b.type = :types',array(':dates'=>$dates,':depId'=>$_POST['depId'],':types'=>2))
                    ->order('h.name')
                    ->queryAll();
                $check = Yii::app()->db->createCommand()
                    ->select('CurEndCount')
                    ->from('dep_balance b')
                    ->where('b.b_date = :dates AND b.department_id = :depId',array(':dates'=>$dates,':depId'=>$_POST['depId']))
                    ->queryRow();

                $model = Yii::app()->db->createCommand()
                    ->select('b.prod_id,p.name as Pname,m.name as Mname')
                    ->from('dep_balance b')
                    ->join('products p','p.product_id = b.prod_id')
                    ->join('measurement m','m.measure_id = p.measure_id')
                    ->where('b.b_date = :dates AND b.department_id = :depId AND b.type = :types',array(':dates'=>$dates,':depId'=>$_POST['depId'],':types'=>1))
                    ->order('p.name')
                    ->queryAll();

            }
        }
        $this->renderPartial('balanceList',array(
            'types'=>$_POST['types'],
            'model'=>$model,
            'model0'=>$model0,
            'check'=>$check,
            'depId'=>$_POST['depId'],
            'dates'=>$dates
        ));
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
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->where('d.department_id = :depId',array(':depId'=>$id))
            ->queryAll();
        $this->renderPartial('calculateList',array(
            'model'=>$model,
        ));
    }




}