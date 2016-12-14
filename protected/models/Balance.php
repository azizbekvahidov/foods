<?php

/**
 * This is the model class for table "balance".
 *
 * The followings are the available columns in table 'balance':
 * @property integer $balance_id
 * @property string $b_date
 * @property integer $prod_id
 * @property double $startCount
 * @property double $endCount
 */
class Balance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'balance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prod_id', 'numerical', 'integerOnly'=>true),
			array('startCount, endCount', 'numerical'),
			array('b_date', 'safe'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('balance_id, b_date, prod_id, startCount, endCount', 'safe', 'on'=>'search'),
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
            'products'=>array(self::BELONGS_TO,'Products','prod_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'balance_id' => 'Balance',
			'b_date' => 'B Date',
			'prod_id' => 'Prod',
			'startCount' => 'Start Count',
			'endCount' => 'End Count',
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

		$criteria->compare('balance_id',$this->balance_id);
		$criteria->compare('b_date',$this->b_date,true);
		$criteria->compare('prod_id',$this->prod_id);
		$criteria->compare('startCount',$this->startCount);
		$criteria->compare('endCount',$this->endCount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Balance the static model class
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

        
        	// NOT SURE RUN PLEASE HELP ME -> 
        	//$from=DateTime::createFromFormat('d/m/Y',$this->b_date);
        	//$this->b_date=$from->format('Y-m-d');
        	
        return parent::beforeSave();
    }

    public function beforeDelete () {
		$userId=0;
		if(null!=Yii::app()->user->id) $userId=(int)Yii::app()->user->id;
                                
        return false;
    }

    public function afterFind()    {
         
        	// NOT SURE RUN PLEASE HELP ME -> 
        	//$from=DateTime::createFromFormat('Y-m-d',$this->b_date);
        	//$this->b_date=$from->format('d/m/Y');
        	
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

    public function getBalanceSumm($dates){
        $summ = array();
        $prod = new Products();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('balance b')
            ->where('b_date = :dates',array(':dates'=>$dates))
            ->queryAll();
        foreach ($model as $val) {
            $summ[0] = $summ[0] + $val['startCount']*$prod->getCostPrice($val['prod_id'],$dates);
            $summ[1] = $summ[1] + $val['endCount']*$prod->getCostPrice($val['prod_id'],$dates);
            $summ[2] = $summ[2] + $val['CurEndCount']*$prod->getCostPrice($val['prod_id'],$dates);
        }
        return $summ;
    }

    public function getDepBalanceSumm($from,$till,$depId){
        $summ = array();
        $prod = new Products();
        $stuff = new Halfstaff();
        $startProd = Yii::app()->db->createCommand()
            ->select('prod_id,startCount')
            ->from('dep_balance b')
            ->where('b_date = :dates AND b.department_id = :depId AND b.type = :types',array(':dates'=>$from,':depId'=>$depId,':types'=>1))
            ->queryAll();
        /*$model = Yii::app()->db->createCommand()
            ->select('sum(SELECT * FROM faktura f
JOIN realize r ON r.faktura_id = f.faktura_id
WHERE date(f.realize_date) < b.b_date AND r.prod_id = b.prod_id
ORDER BY f.realize_date DESC)*b.startCount)')
            ->from('dep_balance b')
            ->where('b.b_date = :dates AND b.department_id = :depId AND b.type = :types',array(':dates'=>$from,':depId'=>$depId,':types'=>1))
            ->query();
echo "<pre>";
print_r($model);
echo "</pre>";*/
        foreach ($startProd as $val) {
            $summ[0] = $summ[0] + $val['startCount']*$prod->getCostPrice($val['prod_id'],date('Y-m-d',strtotime($from)-86400));
            $summ[4] = $summ[4] + $val['startCount']*$prod->getCostPrice($val['prod_id'],$from);
        }

        $endProd = Yii::app()->db->createCommand()
            ->select('prod_id,endCount,CurEndCount')
            ->from('dep_balance b')
            ->where('b_date = :dates AND b.department_id = :depId AND b.type = :types',array(':dates'=>$till,':depId'=>$depId,':types'=>1))
            ->queryAll();

        foreach ($endProd as $val) {
            $summ[1] = $summ[1] + $val['endCount']*$prod->getCostPrice($val['prod_id'],$till);
            $summ[2] = $summ[2] + $val['CurEndCount']*$prod->getCostPrice($val['prod_id'],$till);
        }

        $startStuff = Yii::app()->db->createCommand()
            ->select('prod_id,startCount')
            ->from('dep_balance b')
            ->where('b_date = :dates AND b.department_id = :depId AND b.type = :types',array(':dates'=>$from,':depId'=>$depId,':types'=>2))
            ->queryAll();

        foreach ($startStuff as $val) {
            $summ[0] = $summ[0] + $val['startCount']*$stuff->getCostPrice($val['prod_id'],date('Y-m-d',strtotime($from)-86400));
            $summ[4] = $summ[4] + $val['startCount']*$stuff->getCostPrice($val['prod_id'],$from);
        }

        $endStuff = Yii::app()->db->createCommand()
            ->select('prod_id,endCount,CurEndCount')
            ->from('dep_balance b')
            ->where('b_date = :dates AND b.department_id = :depId AND b.type = :types',array(':dates'=>$till,':depId'=>$depId,':types'=>2))
            ->queryAll();

        foreach ($endStuff as $val) {
            $summ[1] = $summ[1] + $val['endCount']*$stuff->getCostPrice($val['prod_id'],$till);
            $summ[2] = $summ[2] + $val['CurEndCount']*$stuff->getCostPrice($val['prod_id'],$till);
        }
        return $summ;

    }
    
    public function getExpBalance($from,$till){
        $summ = 0;
        $prod = new Products();
        $model = Yii::app()->db->createCommand()
            ->select('ex.order_date,ord.just_id,ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) > :from AND date(ex.order_date) <= :till AND ex.kind = :kind',array(':from'=>$from,':till'=>$till,':kind'=>1))
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $val['count']*$prod->getCostPrice($val['just_id'],$val['order_date']);
        }
        return $summ;
    }
    
}
