<?php

/**
 * This is the model class for table "expense".
 *
 * The followings are the available columns in table 'expense':
 * @property integer $expense_id
 * @property string $order_date
 * @property integer $employee_id
 * @property integer $table
 */
class Expense extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expense';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, table, status, kind, debt, mType, debtor_id, expSum', 'numerical', 'integerOnly'=>true),
			array('order_date', 'safe'),
            array('comment', 'length', 'max'=>100),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('expense_id, order_date, employee_id, table, status, debtor_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'order'=>array(self::HAS_MANY,'Orders','expense_id'),
            'employee'=>array(self::BELONGS_TO,'Employee','employee_id'),
            'mType'=>array(self::BELONGS_TO,'MenuType','mType'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'expense_id' => 'Expense',
			'order_date' => 'Order Date',
			'employee_id' => 'Employee',
			'table' => 'Table',
            'status' => 'Status',
            'debt'=>'Долг'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('expense_id',$this->expense_id);
		$criteria->compare('order_date',$this->order_date,true);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('table',$this->table);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Expense the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
    {
        $userId=0;
		if(null!=Yii::app()->user->id) $userId=(int)Yii::app()->user->id;

		if($this->isNewRecord)
        {

        }else{

        }


        return parent::beforeSave();
    }

    public function beforeDelete () {
		$userId=0;
		if(null!=Yii::app()->user->id) $userId=(int)Yii::app()->user->id;

        return false;
    }

    public function afterFind()    {

        parent::afterFind();
    }


	public function defaultScope()
    {
    	/*
    	//Example Scope
    	return array(
	        'condition'=>"deleted IS NULL ",
            'order'=>'create_time DESC',
            'limit'=>5,
        );
        */
        $scope=array();


        return $scope;
    }

    public function getDepCostPrice($depId,$dates,$fromDate){
        $result = 0;
        $prodModel = new Products();
        $stuffModel = new Halfstaff();
        $temp = $this->getDishProd($depId,$dates,$dates);
        foreach ($temp as $key => $val) {
            $result = $result + $prodModel->getCostPrice($key,$dates)*$val;
        }

        $temp2 = $this->getDishStuff($depId,$dates,$dates);
        $count = 0;
        foreach ($temp2 as $key => $val) {
            $result = $result + $stuffModel->getCostPrice($key,$dates)*$val;
        }

        /*
        $dish = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count,ord.costPrice')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('date(ex.order_date) BETWEEN :from AND :dates AND ord.type = :types AND d.department_id = :depId AND ex.kind = :kind AND ord.deleted != 1',array(':dates'=>$dates,':types'=>1,':kind'=>0,':depId'=>$depId,':from'=>$fromDate))
            ->queryAll();
        foreach($dish as $val){

            $result = $result + $val['costPrice'];
        }

        $Stuff = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count,ord.costPrice')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('date(ex.order_date) BETWEEN :from AND :dates AND h.department_id = :department_id AND ord.type = :types  AND ex.kind = :kind AND ord.deleted != 1',array(':types'=>2,':kind'=>0,':dates'=>$dates,':department_id'=>$depId,':from'=>$fromDate))
            ->queryAll();
        if(!empty($Stuff)){
            foreach($Stuff as $val){
                $result = $result + $val['costPrice'];
            }
        }

        $Prod = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count,ord.costPrice')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('date(ex.order_date) BETWEEN :from AND :dates AND p.department_id = :department_id AND ord.type = :types  AND ex.kind = :kind AND ord.deleted != 1',array(':types'=>3,':kind'=>0,':dates'=>$dates,':department_id'=>$depId,':from'=>$fromDate))
            ->queryAll();
        if(!empty($Prod)){
            foreach($Prod as $val){
                $result = $result + $val['costPrice'];
            }
        }
        $off = Yii::app()->db->createCommand()
            ->select()
            ->from('off o')
            ->join('offList ol','ol.off_id = o.off_id')
            ->where('date(o.off_date) BETWEEN :fromDate AND :dates AND o.department_id = :depId AND ol.type = :types',array(':dates'=>$dates,':fromDate'=>$fromDate,':depId'=>$depId,':types'=>3))
            ->queryAll();
        foreach($off as $val){
            $result = $result + $val['costPrice'];
        }*/

        return $result;
    }

    public function getDishProd($depId,$dates,$fromDate){
//        if($fromDate == $dates){
//            $fromDate = date("Y-m-d",strtotime($dates)-86400);
//        }
        $func = new Functions();
        $timeShift = $func->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $dishes = new Dishes();
        $stuff = new Halfstaff();
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('ex.order_date BETWEEN :from AND :dates AND ord.type = :types AND d.department_id = :depId AND ex.kind = :kind AND ord.deleted != 1',array(':dates'=>$dates,':types'=>1,':kind'=>0,':depId'=>$depId,':from'=>$fromDate))
            ->queryAll();
        foreach($model as $val){

            $temp = $dishes->getProd($val['just_id']);
            $temp2 = $stuff->multiplyArray($temp,$val['count']);

            $result = $stuff->sumArray($result,$temp2);
        }
        $Prod = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('ex.order_date BETWEEN :from AND :dates AND p.department_id = :department_id AND ord.type = :types  AND ex.kind = :kind AND ord.deleted != 1',array(':types'=>3,':kind'=>0,':dates'=>$dates,':department_id'=>$depId,':from'=>$fromDate))
            ->queryAll();

        if(!empty($Prod)){
            foreach($Prod as $val){
                $result[$val['just_id']] = $result[$val['just_id']] + $val['count'];
            }
        }
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('off o')
            ->join('offList ol','ol.off_id = o.off_id')
            ->where('o.off_date BETWEEN :fromDate AND :dates AND o.department_id = :depId AND ol.type = :types',array(':dates'=>$dates,':fromDate'=>$fromDate,':depId'=>$depId,':types'=>3))
            ->queryAll();
        foreach($model as $val){
            $result[$val['prod_id']] = $result[$val['prod_id']] + $val['count'];
        }

        return $result;
    }



    public function getDishStuff($depId,$dates,$fromDate){
//        if($fromDate == $dates){
//            $fromDate = date("Y-m-d",strtotime($dates)-86400);
//        }
        $func = new Functions();
        $timeShift = $func->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $result = array();
        $Stuff = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('ex.order_date BETWEEN :from AND :dates AND ord.type = :types AND d.department_id = :depId AND ex.kind = :kind  AND ord.deleted != 1',array(':dates'=>$dates,':from'=>$fromDate,':types'=>1,':kind'=>0,':depId'=>$depId))
            ->queryAll();
        if(!empty($Stuff)){
            foreach($Stuff as $val){
                $temp = $dish->getStuff($val['just_id']);
                $temp2 = $stuff->multiplyArray($temp,$val['count']);
                $result = $stuff->sumArray($result,$temp2);
            }
        }
        $dishStuff = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('ex.order_date BETWEEN :from AND :dates AND ord.type = :types AND h.department_id = :depId AND ex.kind = :kind  AND ord.deleted != 1',array(':dates'=>$dates,':from'=>$fromDate,':types'=>2,':kind'=>0,':depId'=>$depId))
            ->queryAll();

        if(!empty($dishStuff)){
            foreach($dishStuff as $val){
                $result[$val['just_id']] = $result[$val['just_id']] + $val['count'];
            }
        }

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('off o')
            ->join('offList ol','ol.off_id = o.off_id')
            ->where('o.off_date BETWEEN :fromDate AND :dates AND o.department_id = :depId AND ol.type = :types',array(':dates'=>$dates,':fromDate'=>$fromDate,':depId'=>$depId,':types'=>2))
            ->query();
        foreach($model as $val){
            $result[$val['prod_id']] = $result[$val['prod_id']] + $val['count'];
        }

        return $result;
    }

    public function getExpenseSum($id){
        $model = Expense::model()->findByPk($id);

        return $model->expSum;
    }

    public function getExpenseSumReal($id,$dates){
        $summ = 0;
        $prices = new Prices();
        $model = Expense::model()->with('order.dish')->findByPk($id,'order.deleted != 1');

        $model2 = Expense::model()->with('order.halfstuff')->findByPk($id,'order.deleted != 1');

        $model3 = Expense::model()->with('order.products')->findByPk($id,'order.deleted != 1');
        if(!empty($model))
            foreach ($model->getRelated('order') as $val) {
                $temp = $prices->getPrice($val->just_id,1,1,$dates)*$val->count;
                $summ = $summ + $temp;
            }
        if(!empty($model2))
            foreach ($model2->getRelated('order') as $val) {
                $temp = $prices->getPrice($val->just_id,1,2,$dates)*$val->count;
                $summ = $summ + $temp;
            }
        if(!empty($model3))
            foreach ($model3->getRelated('order') as $val) {
                $temp = $prices->getPrice($val->just_id,1,3,$dates)*$val->count;
                $summ = $summ + $temp;
            }

        return $summ;

    }
    public function getSum($dates){
        $summa = 0;
        $debt = Debt::model()->findAll('t.d_date = :dates',array(':dates'=>$dates));
        $av = Yii::app()->db->createCommand()
            ->select("expSum")
            ->from("prepaid")
            ->where("date(prepDate) = :dates",array(":dates"=>$dates))
            ->queryAll();
        $debts = 0;
        $avans = 0;
        $summ = 0;

        $model = Yii::app()->db->CreateCommand()
            ->select('t.expense_id')
            ->from('expense t')
            ->where(
                'date(t.order_date) = :dates AND t.kind = :kind AND t.status != :status AND t.debt != :debt AND t.prepaid != :prepaid', array(
                    ':dates' => $dates,
                    ':kind'  => 0,
                    ':status'=> 1,
                    ':debt' => 1,
                    ':prepaid' => 1,
                ))
            ->queryAll();
        $model2 = Yii::app()->db->CreateCommand()
            ->select('t.expense_id')
            ->from('expense t')
            ->where(
                'date(t.order_date) = :dates AND t.debtor_type = 1 AND t.kind = :kind AND t.status = :status AND t.debt = :debt AND t.prepaid = :prepaid', array(
                    ':dates' => $dates,
                    ':kind'  => 0,
                    ':status'=> 0,
                    ':debt' => 1,
                    ':prepaid' => 0,
                ))
            ->queryAll();
        if(!empty($model))
            foreach ( $model as $value ) {
                $temp = $this->getExpenseSum($value['expense_id']);
                $summa = $summa + $temp;

            }
        if(!empty($model2))
            foreach ( $model2 as $value ) {
                $temp = $this->getExpenseSum($value['expense_id']);
                $summa = $summa + $temp;

            }

        if(!empty($debt))
            foreach ($debt as $value) {
                $temp = $this->getExpenseSum($value->expense_id);
                $debts = $temp;
            }
        if(!empty($av))
            foreach ($av as $value) {
                $temp = $value["expSum"];
                $avans = $avans + $temp;
            }
        return $summa+$debts + $avans;
    }


    public function ExpSumCounter($dates){
        $percent = Yii::app()->db->createCommand()
            ->select()
            ->from('settings')
            ->where('setting_name = :val',array(':val'=>'percent'))
            ->queryRow();
        $employee = Employee::model()->findAll();
        foreach ( $employee as $vals ) {
            $curPercent = 0;
             if($vals->check_percent == 1)
                $curPercent = $percent['setting_value'];
            else
                $curPercent = 0;

            $model = Yii::app()->db->CreateCommand()
                ->select('t.expense_id')
                ->from('expense t')
                ->where(
                    'date(t.order_date) = :dates AND t.kind = :kind AND t.employee_id = :empId', array(
                        ':dates' => $dates,
                        ':kind'  => 0,
                        ':empId' => $vals->employee_id
                    ))
                ->queryAll();
            if(!empty($model))
                foreach ( $model as $value ) {
                    $temp = $this->getExpenseSumReal($value['expense_id'],$dates);
                    $sum = number_format((($temp/100*$curPercent + $temp)/100),0,',','')*100;
                    Yii::app()->db->createCommand()->update('expense',array('expSum'=>$sum),'expense_id = :id',array(':id'=>$value['expense_id']));

                }
        }

    }

    public function getExpenseProcSum($id,$dates){

        $percent = new Percent();
        $summa = 0;
        $summaP = 0;
        $curPercent = 0;
            $summ = 0;

            $model = Expense::model()->with('employee')->findByPk($id);

            if(!empty($model)) {

                if ( $model->getRelated('employee')->check_percent == 1 ) {
                    $curPercent = $percent->getPercent(date('Y-m-d',strtotime($model->order_date)));
                }
                else{
                    $curPercent = 0;
                }

                    $summ = $summ + $this->getExpenseSum($model->expense_id,$dates);


            }
            $summaP = ($summ/100*$curPercent + $summ) + $summaP;
            $summa = $summ + $summa;

        return array(1=>$summaP,2=>$summa);
    }

    public function getOrderNumber($expId){
        $model = Yii::app()->db->createCommand()
            ->select('order_id')
            ->from('orders')
            ->where('expense_id = :expId AND status = :status',array(':expId'=>$expId,':status'=>2))
            ->queryAll();
        return count($model);
    }

    public function getOlrderNumber($expId,$depId){
        $model = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.order_id  ')
            ->from('orders ord')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('ord.expense_id = :expId AND ord.status = :status AND ord.type = :types AND d.department_id = :depId',array(':expId'=>$expId,':status'=>2,':types'=>1,':depId'=>$depId))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.order_id  ')
            ->from('orders ord')
            ->join('products p','p.product_id = ord.just_id')
            ->where('ord.expense_id = :expId AND ord.status = :status AND ord.type = :types AND p.department_id = :depId',array(':expId'=>$expId,':status'=>2,':types'=>2,':depId'=>$depId))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.order_id  ')
            ->from('orders ord')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('ord.expense_id = :expId AND ord.status = :status AND ord.type = :types AND h.department_id = :depId',array(':expId'=>$expId,':status'=>2,':types'=>3,':depId'=>$depId))
            ->queryAll();

        return count($model)+count($model2)+count($model3);
    }

    public function getRealOrderNumber($expid){
        $model = Yii::app()->db->createCommand()
            ->select('order_id')
            ->from('orders')
            ->where('expense_id = :expId',array(':expId'=>$expid))
            ->queryAll();
        return count($model);
    }

    public function getExpenseCostPrice($id,$dates){
        $summ = 0;
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $model = Yii::app()->db->createCommand()
            ->select('ord.just_id, ord.order_id, ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('ex.expense_id = :id AND ord.type = :types AND ord.deleted != 1',array(':id'=>$id,':types'=>1))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('ord.just_id, ord.order_id, ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('ex.expense_id = :id AND ord.type = :types AND ord.deleted != 1',array(':id'=>$id,':types'=>2))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('ord.just_id, ord.order_id, ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('ex.expense_id = :id AND ord.type = :types AND ord.deleted != 1',array(':id'=>$id,':types'=>3))
            ->queryAll();

        foreach ($model as $val) {
            $temp = $dish->getCostPrice($val['just_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('orders',array('costPrice'=>$temp),'order_id = :id',array(':id'=>$val['order_id']));
        }

        foreach ($model2 as $val) {
            $temp = $stuff->getCostPrice($val['just_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('orders',array('costPrice'=>$temp),'order_id = :id',array(':id'=>$val['order_id']));
        }

        foreach ($model3 as $val) {
            $temp = $prod->getCostPrice($val['just_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('orders',array('costPrice'=>$temp),'order_id = :id',array(':id'=>$val['order_id']));
        }
        Yii::app()->db->createCommand()->update('expense',array('costPrice'=>$summ),'expense_id = :id',array(':id'=>$id));
        return $summ;
    }

    public function getOffCostPrice($id,$dates){
        $summ = 0;
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $model2 = Yii::app()->db->createCommand()
            ->select()
            ->from('offList ol')
            ->where('ol.off_id = :id AND ol.type = :types',array(':id'=>$id,':types'=>2))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select()
            ->from('offList ol')
            ->where('ol.off_id = :id AND ol.type = :types',array(':id'=>$id,':types'=>3))
            ->queryAll();

        foreach ($model2 as $val) {
            $temp = $stuff->getCostPrice($val['prod_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('offList',array('costPrice'=>$temp),'offList_id = :id',array(':id'=>$val['offList_id']));
        }
        foreach ($model3 as $val) {
            $temp = $prod->getCostPrice($val['prod_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('offList',array('costPrice'=>$temp),'offList_id = :id',array(':id'=>$val['offList_id']));
        }
        Yii::app()->db->createCommand()->update('off',array('costPrice'=>$summ),'off_id = :id',array(':id'=>$id));
    }

    public function getStructExpenseSumm($dates){
        $summ = 0;
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $model = Yii::app()->db->createCommand()
            ->select('ord.just_id')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types',array(':dates'=>$dates,':types'=>1))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('ord.just_id')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types',array(':dates'=>$dates,':types'=>2))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('ord.just_id')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types',array(':dates'=>$dates,':types'=>3))
            ->queryAll();

        foreach ($model as $val) {
            $summ = $summ + $dish->getCostPrice($val['just_id'],$dates);
        }

        foreach ($model2 as $val) {
            $summ = $summ + $stuff->getCostPrice($val['just_id'],$dates);
        }

        foreach ($model3 as $val) {
            $summ = $summ + $prod->getCostPrice($val['just_id'],$dates);
        }
        return $summ;
    }

    public function getInExp($dates){
        $summ = 0;
        $prod = new Products();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ex.kind = :kind',array(':dates'=>$dates,':kind'=>1))
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $val['count']*$prod->getCostPrice($val['just_id'],$dates);
        }
        return $summ;
    }

    public function depIncome($depId,$from,$till){
        $sum = 0;

        $model = Yii::app()->db->createCommand()
            ->select('sum(expSum) as expSum')
            ->from('mDepBalance')
            ->where('b_date BETWEEN :from AND :till AND department_id = :id', array(':from'=>$from,':till'=>$till,':id'=>$depId))
            ->queryRow();

        $sum = $sum + $model['expSum'];
        return $sum == null?0:$sum;
    }

    public function getDepIncome($depId,$from,$till){
        $sum = 0;
        $func = new Functions();
        $timeShift = $func->getTime($from,$till);
        $from = $timeShift[0];
        $till = $timeShift[1];
        //old summary script
        $model = Yii::app()->db->createCommand()
            ->select('sum((select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1)*ord.count) as sum')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND d.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>1,':depId'=>$depId))
            ->queryRow();
        $model2 = Yii::app()->db->createCommand()
            ->select('sum((select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1)*ord.count) as sum')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND h.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>2,':depId'=>$depId))
            ->queryRow();
        $model3 = Yii::app()->db->createCommand()
            ->select('sum((select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1)*ord.count) as sum')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('ex.order_date <= :till AND ex.order_date >= :from AND ord.type = :types AND p.department_id = :depId AND ord.deleted != 1',array(':from'=>$from,':till'=>$till,':types'=>3,':depId'=>$depId))
            ->queryRow();

        $sum = $model['sum']+$model2['sum']+$model3['sum'];

        return $sum == null?0:$sum;
    }

    public function depCost($depId,$fromDate,$tillDate){
        $timeStpfrom = strtotime($fromDate);
        $timeStptill = strtotime($tillDate);
        $cnt = ($timeStptill-$timeStpfrom)/86400;

        $function = new Functions();
        $from = $fromDate;
        $till = $fromDate;
        $summs = 0;
        for($i = 0; $i <= $cnt; $i++) {
        //newer summaryscript
            $model = Yii::app()->db->createCommand()
            ->select('sum(costPrice) as costPrice')
            ->from('mDepBalance')
            ->where('b_date BETWEEN :from AND :till AND department_id = :id', array(':from'=>$from,':till'=>$till,':id'=>$depId))
            ->queryRow();

            $summs = $summs + $model['costPrice'];

            $from = date('Y-m-d',strtotime($from)+86400);
            $till = date('Y-m-d',strtotime($till)+86400);
        }
        return $summs;
    }

    public function getDepCost($depId,$fromDate,$tillDate){
        $timeStpfrom = strtotime($fromDate);
        $timeStptill = strtotime($tillDate);
        $cnt = ($timeStptill-$timeStpfrom)/86400;

        $function = new Functions();
        $from = $fromDate;
        $till = $fromDate;
        $summs = 0;
//        $dish = new Dishes();
//        $stuff = new Halfstaff();
//        $prod = new Products();
        for($i = 0; $i <= $cnt; $i++) {
            $summ = 0;

            //new summary script
                $summs = $summs + $this->getDepCostPrice($depId,$till,$from);

            //old summary script
//            $temp = $this->getDishProd($depId,$till,$from);
//            foreach ($temp as $key => $val) {
//                $summ = $summ + $prod->getCostPrice($key,$from)*$val;
//            }
//
//            $temp2 = $this->getDishStuff($depId,$till,$from);
//            foreach ($temp2 as $key => $val) {
//                $summ = $summ + $stuff->getCostPrice($key,$from)*$val;
//            }
            $from = date('Y-m-d',strtotime($from)+86400);
            $till = date('Y-m-d',strtotime($till)+86400);
//            $summs = $summs + $summ;
        }
        return $summs;
    }
    public function getFactCostPrice($from,$till,$depId){
        $function = new Functions();
        $outProduct = array();
        $outStuff = array();
        $depIn = array();
        $depOut = array();
        $prod = 0;

        $depIn = $function->depMoveIn($depId,$till,$from);

        $depOut = $function->depMoveOut($depId,$till,$from);

        $curProd = DepBalance::model()->with('products')->findAll(
            'date(t.b_date) = :dates AND t.department_id = :department_id AND t.type = :type',
            array(
                ':dates'=>$till,
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
                    ':dates'=>$till,
                    ':department_id'=>$depId,
                    ':type'=>2,
                ))
            ->queryAll();


        $dish = new Expense();

        $stuff = new Halfstaff();

        $outProduct = $dish->getDishProd($depId,$till,$from);

        $outDishStuff = $dish->getDishStuff($depId,$till,$from);


        $inProducts = $function->depInProducts($depId,$till,$from);
        //Приход загатовок в отдел и расход их продуктов
        $instuff = $function->depInStuff($depId,$till,$from);

        $outStuffProd = $function->depOutStuffProd($depId,$till,$from);

        //Приход и расход загатовок в отдел, расход их продуктов
        $outStuff = $function->depOutStuff($depId,$till,$from);

        $inexpense = new Inexpense();
        $depStuffIn = $inexpense->getDepIn($depId,$till,$from);
        $depStuffOut = $inexpense->getDepOut($depId,$till,$from);
        return 0;
    }

    public function changeToFloat($number){
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

    public function getDebt($from){
		$PERSENT = new Percent();
		$expense = new Expense();

        $contractor = Yii::app()->db->CreateCommand()
            ->select()
            ->from('expense ex')
            ->join('contractor c','c.contractor_id = ex.debtor_id')
            ->join('employee e','e.employee_id = ex.employee_id')
            ->where('date(ex.order_date) = :from AND ex.status != :status AND ex.debt = :debt AND ex.kind != 1 AND ex.debtor_id != 0 AND ex.debtor_type = 1',array(':from'=>$from,':status'=>1,':debt'=>1))
            ->queryAll();
        $empPersum = 0;
        $empPersum1 = 0;
        foreach($contractor as $vale){
            if($val['check_percent'] == 1){
                $percent = $PERSENT->getPercent(date('Y-m-d',strtotime(['$vale->order_date'])));
            }
            if($vale['types'] == 0){
                $temp = $expense->getExpenseSum($vale['expense_id'],date('Y-m-d',strtotime($vale['order_date'])));
                $empPersum = $empPersum + ($temp + $temp*$percent/100);
            }
            if($vale['types'] == 1){
                $temp = $expense->getExpenseSum($vale['expense_id'],date('Y-m-d',strtotime($vale['order_date'])));
                $empPersum1 = $empPersum1 + ($temp + $temp*$percent/100);
            }
        }
        $perSumm['cont'] = $perSumm['cont'] + $empPersum;

        $perSumm['mag'] = $perSumm['mag'] + $empPersum1;


        $debtor = Expense::model()->findAll('date(t.order_date) = :from AND t.status != :status AND t.debt = :debt AND t.kind != 1 AND debtor_id != 0 AND debtor_type = 0',array(':from'=>$from,':status'=>1,':debt'=>1));

        $empPersum = 0;
        foreach($debtor as $vale){
            if($val->check_percent == 1){
                $percent = $PERSENT->getPercent(date('Y-m-d',strtotime($vale->order_date)));
            }
            $temp = $expense->getExpenseSum($vale->expense_id,date('Y-m-d',strtotime($vale->order_date)));
            $empPersum = $empPersum + ($temp + $temp*$percent/100);
        }
        $perSumm['perDebt'] = $perSumm['perDebt'] + $empPersum;
				return $perSumm;
    }

    public function getEmpCost($empId,$from,$to){
        $model = Yii::app()->db->createCommand()
            ->select("sum(t.costPrice) as summ")
            ->from("expense t")
            ->where('t.debtor_id = :id AND date(t.order_date) BETWEEN :from AND :to AND t.status != :status AND t.debt = :debt AND t.kind != 1 AND t.prepaid != 1',array(':id'=>$empId,':from'=>$from,':to'=>$to,':status'=>1,':debt'=>1))
            ->queryRow();
        return (!empty($model["summ"])) ? $model["summ"] : 0;
    }

    public function addExpenseList($prodId,$type,$date,$cnt,$dep = 0){
        if($type == 1){
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("dishes")
                ->where("dish_id = :id",array(":id"=>$prodId))
                ->queryRow();
            $prod = Yii::app()->db->createCommand()
                ->select()
                ->from("dish_structure")
                ->where("dish_id = :id",array(":id"=>$prodId))
                ->queryAll();
            $depId = 0;
            if($dep == 0)
                $depId = $model["department_id"];
            else
                $depId = $dep;
            foreach ($prod as $item) {
                $this->addProd($item["prod_id"],1,$date,$depId,($cnt*$item["amount"])/$model["count"]);
            }
            $stuff = Yii::app()->db->createCommand()
                ->select()
                ->from("dish_structure2")
                ->where("dish_id = :id",array(":id"=>$prodId))
                ->queryAll();

            foreach ($stuff as $item) {
                $this->addProd($item["halfstuff_id"],2,$date,$model["department_id"],($cnt*$item["amount"])/$model["count"]);
            }
        }
        else if($type == 2){
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("halfstaff")
                ->where("halfstuff_id = :id",array(":id"=>$prodId))
                ->queryRow();
            $depId = 0;
            if($dep == 0)
                $depId = $model["department_id"];
            else
                $depId = $dep;
            $this->addProd($prodId,2,$date,$depId,$cnt);

        }
        else if($type == 3){
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("products")
                ->where("product_id = :id",array(":id"=>$prodId))
                ->queryRow();
            $depId = 0;
            if($dep == 0)
                $depId = $model["department_id"];
            else
                $depId = $dep;
            $this->addProd($prodId,1,$date,$depId,$cnt);
        }
    }

    public function addProd($prodId,$type,$date,$depId,$cnt){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("expense_list")
            ->where("prod_id = :id AND department_id = :depId AND expense_date = :exDate AND prod_type = :types",
                array(
                    ":id"=>$prodId,
                    ":types"=>$type,
                    ":depId"=>$depId,
                    ":exDate"=>$date
                )
            )
            ->queryRow();
        if(!empty($model)){
            Yii::app()->db->createCommand()->update("expense_list",array(
                "cnt" => $model["cnt"] + number_format($cnt,2,".","")
            ),"prod_id = :id AND department_id = :depId AND expense_date = :exDate AND prod_type = :types",
                array(
                    ":id"=>$prodId,
                    ":types"=>$type,
                    ":depId"=>$depId,
                    ":exDate"=>$date
                ));
        }
        else{
            Yii::app()->db->createCommand()->insert("expense_list",array(
                "cnt" => number_format($cnt,2,".",""),
                "prod_id" => $prodId,
                "prod_type"=>$type,
                "department_id"=>$depId,
                "expense_date"=>$date
            ));
        }
    }


}
