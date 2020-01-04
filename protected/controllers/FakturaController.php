<?php

class FakturaController extends SetupController
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
				'actions'=>array('index','view',),
				'roles'=>array('3'),
			),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('viewFaktura','ajaxView','admin','editable','toggle','providerProd','ajaxProviderProd','provProdList','request','ajaxRequest','ajaxSetReqList','setRequest','ajaxSetRequest'),
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
	public function actionView($id)
	{

        $dates = date('Y-m-d');
        $dep = CHtml::listData(Department::model()->findAll(),'department_id','name');
        $model = Yii::app()->db->createCommand()
            ->select('r.request_id,r.provider_id,rp.prod_id,p.name as Pname,m.name as Mname')
            ->from('request r')
            ->join('request_prod rp','rp.request_id = r.request_id')
            ->join('products p','p.product_id = rp.prod_id')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->where('r.request_id = :id',array(':id'=>$id))
            ->group('rp.prod_id')
            ->queryAll();
        $this->render('view',array(
            'id'=>$id,
            'dates'=>$dates,
            'model'=>$model,
            'dep'=>$dep
        ));
	}

    public function actionViewFaktura($dates)
    {
        $dep = CHtml::listData(Department::model()->findAll(),'department_id','name');
        $model = Yii::app()->db->createCommand()
            ->select('f.realize_date,pr.name as PRname,r.prod_id,p.name as Pname,m.name as Mname,r.price,r.count')
            ->from('faktura f')
            ->join('provider pr','pr.provider_id = f.provider_id')
            ->join('realize r','r.faktura_id = f.faktura_id')
            ->join('products p','p.product_id = r.prod_id')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->where('date(f.realize_date) = :dates',array(':dates'=>$dates))
            ->queryAll();
        $this->render('viewFaktura',array(
            'dates'=>$dates,
            'model'=>$model,
            'dep'=>$dep
        ));
    }

    public function actionAjaxPrint($id){
        $dates = date('Y-m-d');
        $dep = CHtml::listData(Department::model()->findAll(),'department_id','name');
        $model = Yii::app()->db->createCommand()
            ->select('r.request_id,r.provider_id,rp.prod_id,p.name as Pname,m.name as Mname')
            ->from('request r')
            ->join('request_prod rp','rp.request_id = r.request_id')
            ->join('products p','p.product_id = rp.prod_id')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->where('r.request_id = :id',array(':id'=>$id))
            ->group('rp.prod_id')
            ->queryAll();
        $this->renderPartial('ajaxPrint',array(
            'id'=>$id,
            'dates'=>$dates,
            'model'=>$model,
            'dep'=>$dep
        ));
    }



	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		/*
		$dataProvider=new CActiveDataProvider('Faktura');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
		
		$model=new Faktura('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Faktura']))
			$model->attributes=$_GET['Faktura'];

		$this->render('index',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$model=new Faktura('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Faktura']))
			$model->attributes=$_GET['Faktura'];

		$this->render('admin',array(
			'model'=>$model,
					));
		
			}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Faktura the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Faktura::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Faktura $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='faktura-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionEditable(){
		Yii::import('bootstrap.widgets.TbEditableSaver'); 
	    $es = new TbEditableSaver('Faktura'); 
			    $es->update();
	}

	public function actions()
	{
    	return array(
        		'toggle' => array(
                	'class'=>'bootstrap.actions.TbToggleAction',
                	'modelName' => 'Faktura',
        		)
    	);
	}

    public function actionProviderProd(){
        $dates = date('Y-m-d');
        $this->render('providerProd',array('dates'=>$dates));
    }

    public function actionAjaxProviderProd(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $model = Yii::app()->db->createCommand()
            ->select('pro.provider_id,pro.name as name, sum(price*count) as summ')
            ->from('faktura fa')
            ->join('provider pro','pro.provider_id = fa.provider_id')
            ->join('realize re','re.faktura_id = fa.faktura_id')
            ->where('date(fa.realize_date) BETWEEN :from AND :to',array(':from'=>$from,':to'=>$to))
            ->group('pro.name')
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('sum(price*count) as summ')
            ->from('faktura fa')
            ->join('provider pro','pro.provider_id = fa.provider_id')
            ->join('realize re','re.faktura_id = fa.faktura_id')
            ->where('date(fa.realize_date) BETWEEN :from AND :to',array(':from'=>$from,':to'=>$to))
            ->queryRow();

        $this->renderPartial('ajaxProviderProd',array(
            'model'=>$model,
            'model2'=>$model2,
            'from'=>$from,
            'to'=>$to
        ));
    }

    public function actionProvProdList(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $provId = $_POST['provId'];
        $model = Yii::app()->db->createCommand()
            ->select('m.name as mName,p.name as name,re.price as price,sum(re.count) as count')
            ->from('faktura fa')
            ->where('date(fa.realize_date) BETWEEN :from AND :to AND fa.provider_id = :provId',array('from'=>$from,':to'=>$to,':provId'=>$provId))
            ->join('realize re','re.faktura_id = fa.faktura_id')
            ->join('products p','p.product_id = re.prod_id')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->group("re.prod_id")
            ->queryAll();
        $summ = Yii::app()->db->createCommand()
            ->select('sum(re.price*re.count) as summ')
            ->from('faktura fa')
            ->where('date(fa.realize_date) BETWEEN :from AND :to AND fa.provider_id = :provId',array('from'=>$from,':to'=>$to,':provId'=>$provId))
            ->join('realize re','re.faktura_id = fa.faktura_id')
            ->join('products p','p.product_id = re.prod_id')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->queryRow();
        $this->renderPartial('provProdList',array(
            'model'=>$model,
            'summ'=>$summ,
            'allSumm'=>$_POST["allSumm"]
        ));
    }

    public function actionRequest(){
        $products = new Products();
            $provider = new Provider();
            $prodList = $products->getUseProdList();
            $provList = $provider->getProvList();

        $depId = Department::model()->findAll();
        $dates = date('Y-m-d H:i:s');
        $command = Yii::app()->db->createCommand();
        $expense = new Expense();
        if(isset($_POST['request'])){
            $command->insert('request', array(
                'req_date'=>$dates,
                'provider_id'=>$_POST['provider']
            ));
            $lastId = Yii::app()->db->lastInsertID;
            foreach ($_POST['request'] as $key => $val) {
                foreach ($val as $keys => $value) {
                    $count = $expense->changeToFloat($value['count']);
                        $command->insert('request_prod', array(
                            'request_id' => $lastId,
                            'prod_id' => $keys,
                            'depId' => $key,
                            'count' => $count
                        ));
                }

            }
            $this->redirect(array('view','id'=>$lastId));
        }
        $this->render('request',array(
            'depId'=>$depId,
            'prodList'=>$prodList,
            'provList'=>$provList
        ));
    }

    public function actionAjaxRequest(){
        $dates = date('Y-m-d');
        $id = $_POST['id'];
        $line = intval($_POST['line']);
        if($line == 0){$line = 1;}
        else{$line++;}

        $model = Yii::app()->db->createCommand()
            ->select('p.product_id,p.name as Pname,m.name as Mname')
            ->from('products p')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->where('p.product_id = :id',array(':id'=>$id))
            ->queryRow();
        $depId = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();
        $this->renderPartial('ajaxRequest',array(
            'line'=>$line,
            'depId'=>$depId,
            'model'=>$model,
            'dates'=>$dates
        ));
    }

    public function actionAjaxSetReqList(){
        $cnt = intval($_POST['cnt']);
        $dates = date('Y-m-d');
        $id = $_POST['id'];
        $line = intval($_POST['line']);
        if($line == 0){$line = 1;}
        else{$line++;}

        $model = Yii::app()->db->createCommand()
            ->select('p.product_id,p.name as Pname,m.name as Mname')
            ->from('products p')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->where('p.product_id = :id',array(':id'=>$id))
            ->queryRow();
        $depId = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();
        $this->renderPartial('ajaxSetReqList',array(
            'cnt'=>$cnt,
            'line'=>$line,
            'depId'=>$depId,
            'model'=>$model,
            'dates'=>$dates
        ));
    }

    public function actionSetRequest(){
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('request r')
            ->join('provider p','p.provider_id = r.provider_id')
            ->where('r.status = :status',array(':status'=>0))
            ->queryAll();
        foreach ($model as $val) {
            $List[$val['request_id']] = $val['req_date']." - ".$val['name'];
        }
        if(isset($_POST['request'])) {
            $storage = new Storage();
            $expense = new Expense();
            $dates = date('Y-m-d H:i:s');
            $prodCount = array();

            $provId = Yii::app()->db->createCommand()
                ->select('provider_id')
                ->from('request')
                ->where('request_id = :id', array(':id' => $_POST['list']))
                ->queryRow();

            foreach ($_POST['request'] as $keys => $value) {
                if($keys != 0) {
                    Yii::app()->db->createCommand()->insert('dep_faktura', array(
                        'real_date' => $dates,
                        'department_id' => $keys,
                        'fromDepId' => 0
                    ));
                    $lastDepId = Yii::app()->db->lastInsertID;
                    foreach ($value as $key => $val) {
                        $prodCount[$key] = floatval($prodCount[$key]) + floatval($expense->changeToFloat($val['count']));
                        if ($val['count'] != 0) {
                            $cnt = $expense->changeToFloat($val['count']);
                            Yii::app()->db->createCommand()->insert('dep_realize', array(
                                'dep_faktura_id' => $lastDepId,
                                'prod_id' => $key,
                                'price' => $_POST['price'][$key],
                                'count' => $cnt
                            ));

                            $expense->addExpenseList($key, 3, $dates = date('Y-m-d', strtotime($dates)), $cnt*(-1),$keys);
//                            $storage->addToStorageDep($key,$expense->changeToFloat($val['count']),1,$keys);
                        }
                    }
                }

                else{
                    Yii::app()->db->createCommand()->insert('expense', array(
                        'order_date' => $dates,
                        'employee_id'=>Yii::app()->user->getId(),
                        'table'=>0,
                        'status'=>0,
                        'kind'=>1,
                        'debt'=>0,
                        'comment'=>'',
                        'mType'=>0
                    ));
                    $lastExpId = Yii::app()->db->lastInsertID;
                    foreach ($value as $key => $val) {
                        $prodCount[$key] = floatval($prodCount[$key]) + floatval($expense->changeToFloat($val['count']));
                        if ($val['count'] != 0) {
                            $cnt = $expense->changeToFloat($val['count']);
                            Yii::app()->db->createCommand()->insert('orders', array(
                                'expense_id' => $lastExpId,
                                'just_id' => $key,
                                'type'=>3,
                                'count' => $cnt
                            ));
                            $expense->addExpenseList($key, 3, $dates = date('Y-m-d', strtotime($dates)), $cnt);
//                            $storage->addToStorageDep($key,$expense->changeToFloat($val['count']),1,$keys);
                        }
                    }
                }
            }
            Yii::app()->db->createCommand()->insert('faktura', array(
                'realize_date' => $dates,
                'provider_id' => $provId['provider_id']
            ));
            $lastId = Yii::app()->db->lastInsertID;
            foreach ($prodCount as $key => $val) {
                $count = $expense->changeToFloat($val);
                Yii::app()->db->createCommand()->insert('realize', array(
                    'faktura_id' => $lastId,
                    'prod_id' => $key,
                    'price' => $_POST['price'][$key],
                    'count' => $count
                ));
//                $storage->addToStorage($key,$count);
//                $storage->removeToStorage($key,$count);

            }

            Yii::app()->db->createCommand()->update('request',array(
                'status'=>1
            ),'request_id = :id',array(':id'=>$_POST['list']));
            $this->redirect(array('site/index'));

        }
        $this->render('setRequest',array('List'=>$List));
    }

    public function actionAjaxSetRequest(){
        $dates = date('Y-m-d');
        $id = $_POST['listId'];
        $dep = CHtml::listData(Department::model()->findAll(),'department_id','name');
            $model = Yii::app()->db->createCommand()
                ->select('r.request_id,r.provider_id,rp.prod_id,p.name as Pname,m.name as Mname')
                ->from('request r')
                ->join('request_prod rp','rp.request_id = r.request_id')
                ->join('products p','p.product_id = rp.prod_id')
                ->join('measurement m','m.measure_id = p.measure_id')
                ->where('r.request_id = :id',array(':id'=>$id))
                ->group('rp.prod_id')
                ->queryAll();


        $this->renderPartial('ajaxSetRequest',array('dep'=>$dep,'model'=>$model,'dates'=>$dates));
    }






}
