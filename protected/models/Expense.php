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
			array('employee_id, table, status, kind, debt, mType', 'numerical', 'integerOnly'=>true),
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
			array('expense_id, order_date, employee_id, table, status', 'safe', 'on'=>'search'),
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
    
    public function getDishProd($depId,$dates){
        //$dishProd = Expense::model()->with('order.dish')->findAll('date(order_date) = :dates AND dish.department_id = :department_id AND t.kind = :kind AND order.type =:types',array(':kind'=>0,':dates'=>$dates,':department_id'=>$depId,':types'=>1));
        $dishes = new Dishes();
        $stuff = new Halfstaff();
        $result = array();
        $model = Yii::app()->db->createCommand()
                ->select('ord.just_id,ord.count')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->join('dishes d','d.dish_id = ord.just_id')
                ->where('date(ex.order_date) = :dates AND ord.type = :types AND d.department_id = :depId',array(':dates'=>$dates,':types'=>1,':depId'=>$depId))
                ->queryAll();
                foreach($model as $val){
                    $temp = $dishes->getProd($val['just_id']);
                    $temporary = [$stuff->multiplyArray($temp,$val['count']),$val['count']];
                    $result = $stuff->sumArray($result,$temporary);
                }
/*
        $result = array();
        if(!empty($dishProd)){
            foreach($dishProd as $value){
                foreach($value->getRelated('order') as $val) {
                    $model = new Dishes();
                    $stuff = new Halfstaff();

                    $temp = $model->getProd($val->just_id);
                    $temp2 = $stuff->multiplyArray($temp,$val->count);

                    $result = $stuff->sumArray($result,$temp2);
                }
        //                $outProduct[$vals->prod_id] = $outProduct[$vals->prod_id] + $vals->amount / $val->getRelated('dish')->count * $val->count;
             }
        }*/


        $Prod = Expense::model()->with('order.products')->findAll('date(order_date) = :dates AND products.department_id = :department_id AND t.kind = :kind AND order.type',array(':kind'=>0,':dates'=>$dates,':department_id'=>$depId));
        if(!empty($Prod)){
            foreach($Prod as $value){
                foreach($value->getRelated('order') as $val){
                    $result[$val->just_id] = $result[$val->just_id] + $val->count;
                    //$outProduct[$val->just_id] = $outProduct[$val->just_id] + $val->count;
                }
            }
        }

        $stuffProd = Expense::model()->with('order.halfstuff')->findAll('date(order_date) = :dates AND halfstuff.department_id = :department_id AND t.kind = :kind',array(':kind'=>0,':dates'=>$dates,':department_id'=>$depId));

        return $result;
    }



    public function getDishStuff($depId,$dates){
        $dishStuffProd = Expense::model()->with('order.dish.halfstuff.Structs')->findAll('date(order_date) = :dates AND dish.department_id = :department_id AND t.kind = :kind',array(':kind'=>0,':dates'=>$dates,':department_id'=>$depId));
        $outStuff = array();
        if(!empty($dishStuffProd)){
            foreach($dishStuffProd as $value){
                foreach($value->getRelated('order') as $val){
                    foreach($val->getRelated('dish')->getRelated('halfstuff') as $vals){
                        $outStuff[$vals->getRelated('Structs')->halfstuff_id] = $outStuff[$vals->getRelated('Structs')->halfstuff_id] + $vals->amount/$val->getRelated('dish')->count*$val->count;

                    }
                }
            }
        }
        $dishStuff = Expense::model()->with('order.halfstuff.stuffStruct')->findAll('date(order_date) = :dates AND halfstuff.department_id = :department_id AND t.kind = :kind',array(':kind'=>0,':dates'=>$dates,':department_id'=>$depId));

        if(!empty($dishStuff)){
            foreach($dishStuff as $value){
                foreach($value->getRelated('order') as $val) {
                    $outStuff[$val->just_id] = $outStuff[$val->just_id] + $val->count;
                    /*$stuff = new Halfstaff();
                    $temp = $stuff->getStuff($val->just_id);
                    $result = $stuff->sumArray($result,$temp);*/
                }
                //                $outProduct[$vals->prod_id] = $outProduct[$vals->prod_id] + $vals->amount / $val->getRelated('dish')->count * $val->count;
            }
        }
        return $outStuff;
    }

    public function getExpenseSum($id,$dates){
        $summ = 0;
        $prices = new Prices();
        $model = Expense::model()->with('order.dish')->findByPk($id);

        $model2 = Expense::model()->with('order.halfstuff')->findByPk($id);

        $model3 = Expense::model()->with('order.products')->findByPk($id);
        if(!empty($model))
            foreach ($model->getRelated('order') as $val) {
                $summ = $summ + $prices->getPrice($val->just_id,1,1,$dates)*$val->count;
            }
        if(!empty($model2))
            foreach ($model2->getRelated('order') as $val) {
                $summ = $summ + $prices->getPrice($val->just_id,1,2,$dates)*$val->count;
            }
        if(!empty($model3))
            foreach ($model3->getRelated('order') as $val) {
                $summ = $summ + $prices->getPrice($val->just_id,1,3,$dates)*$val->count;
            }


        return $summ;
    }
    public function getSum($dates){
	    $percent = new Percent();
        $summa = 0;
        $summaP = 0;
        $curPercent = 0;
	    $employee = Employee::model()->findAll();
        $stuff = new Halfstaff();
        $debt = Debt::model()->findAll('t.d_date = :dates',array(':dates'=>$dates));
        $debts = array();
	    foreach ( $employee as $vals ) {
            $summ = 0;
            if ( $vals->check_percent == 1 ) {
                $curPercent = $percent->getPercent($dates);
            }
            else{
                $curPercent = 0;
            }

            $model = Yii::app()->db->CreateCommand()
                ->select('t.expense_id')
                ->from('expense t')
                ->where(
                    'date(t.order_date) = :dates AND t.kind = :kind AND t.employee_id = :empId AND t.status != :status AND t.debt != :debt', array(
                    ':dates' => $dates,
                    ':kind'  => 0,
                    ':empId' => $vals->employee_id,
                    ':status'=> 1,
                    ':debt' => 1
                ))
                ->queryAll();
		    /*$model = Expense::model()->with()->findAll( 'date(t.order_date) = :dates AND t.kind = :kind AND t.employee_id = :empId AND t.status != :status AND t.debt != :debt', array(

				    ':dates' => $dates,
				    ':kind'  => 0,
			        ':empId' => $vals->employee_id,
                    ':status'=> 1,
                    ':debt' => 1
			    ) );*/
		    if(!empty($model))
			    foreach ( $model as $value ) {
                    $summ = $summ + $this->getExpenseSum($value['expense_id'],$dates);

                }
            $tempSumm = ($summ/100*$curPercent + $summ);
            $summaP = round($tempSumm/100)*100 + $summaP;
            $summa = round(($summ + $summa)/100)*100;
	    }

        if(!empty($debt))
            foreach ($debt as $value) {
                $debts = $stuff->sumArray($debts,$this->getExpenseProcSum($value->expense_id,$dates));
            }
        return array(1=>($summaP+$debts[1]),2=>($summa+$debts[2]));
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

    public function getDepIncome($depId,$dates){
        $model = Yii::app()->db->createCommand()
            ->select('sum((select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1)*ord.count) as sum')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types AND d.department_id = :depId',array(':dates'=>$dates,':types'=>1,':depId'=>$depId))
            ->queryRow();
        $model2 = Yii::app()->db->createCommand()
            ->select('sum((select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1)*ord.count) as sum')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types AND h.department_id = :depId',array(':dates'=>$dates,':types'=>2,':depId'=>$depId))
            ->queryRow();
        $model3 = Yii::app()->db->createCommand()
            ->select('sum((select pr.price from prices pr where pr.price_date <= ex.order_date AND pr.menu_type = ex.mType AND pr.types = ord.type AND pr.just_id = ord.just_id order by pr.price_date desc limit 1)*ord.count) as sum')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types AND p.department_id = :depId',array(':dates'=>$dates,':types'=>3,':depId'=>$depId))
            ->queryRow();

        $sum = $model['sum']+$model2['sum']+$model3['sum'];

        return $sum == null?0:$sum;
    }

    public function getDepCost($depId,$dates){
        $summ = 0;
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $model = Yii::app()->db->createCommand()
            ->select('ord.just_id,sum(ord.count) as count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('dishes d','d.dish_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types AND ex.status = :status AND d.department_id = :depId',array(':dates'=>$dates,':types'=>1,':status'=>0,':depId'=>$depId))
            ->group('ord.just_id')
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $dish->getCostPrice($val['just_id'],$dates)*$val['count'];
        }
        $model2 = Yii::app()->db->createCommand()
            ->select('ord.just_id,sum(ord.count) as count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('halfstaff h','h.halfstuff_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types AND ex.status = :status AND h.department_id = :depId',array(':dates'=>$dates,':types'=>2,':status'=>0,':depId'=>$depId))
            ->group('ord.just_id')
            ->queryAll();
        foreach ($model2 as $val) {
            $summ = $summ + $stuff->getCostPrice($val['just_id'],$dates)*$val['count'];
        }

        $model3 = Yii::app()->db->createCommand()
            ->select('ord.just_id,sum(ord.count) as count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->join('products p','p.product_id = ord.just_id')
            ->where('date(ex.order_date) = :dates AND ord.type = :types AND ex.status = :status AND p.department_id = :depId',array(':dates'=>$dates,':types'=>3,':status'=>0,':depId'=>$depId))
            ->group('ord.just_id')
            ->queryAll();
        foreach ($model3 as $val) {
            $summ = $summ + $prod->getCostPrice($val['just_id'],$dates)*$val['count'];
        }
        return $summ;
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
}
