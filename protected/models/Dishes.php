<?php

/**
 * This is the model class for table "dishes".
 *
 * The followings are the available columns in table 'dishes':
 * @property integer $dish_id
 * @property string $name
 */
class Dishes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dishes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('price,percent, distrib', 'numerical', 'integerOnly'=>true),
            array('percent, count', 'numerical'),

			array('name', 'length', 'max'=>100),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dish_id, name, price,percent,count,distrib', 'safe', 'on'=>'search'),
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
            'products'=>array(self::MANY_MANY, 'Products', 'dish_structure(dish_id,prod_id)'),
            'stuff'=>array(self::MANY_MANY, 'Halfstaff', 'dish_structure2(dish_id,halfstuff_id)'),
            'stuffs'=>array(self::MANY_MANY, 'Halfstaff', 'dish_structure2(dish_id,halfstuff_id)'),
            'dishStruct'=>array(self::HAS_MANY, 'DishStructure', 'dish_id'),
            'halfstuff'=>array(self::HAS_MANY, 'DishStructure2', 'dish_id'),
//            'dishesStruct'=>array(self::HAS_MANY,'DishStructure3', 'dish_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dish_id' => 'Dish',
			'name' => 'Название',
			'price' => 'Цена',
			'percent' => 'Процент',
            'department_id' => 'Отдел',
			'count' => 'Количество порций',
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

		$criteria->compare('dish_id',$this->dish_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('percent',$this->percent);
		$criteria->compare('count',$this->count);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dishes the static model class
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

    public function getDishProd($depId){
        $prod_id['prod']  = '';
        $prod_id['stuff']  = '';
//        $model = Dishes::model()->with('stuff','products')->findAll('t.department_id = :depId',array(':depId'=>$depId));
        $modelStuff = Yii::app()->db->CreateCommand()
            ->select()
            ->from("dishes d")
            ->join("dish_structure2 ds","ds.dish_id = d.dish_id")
            ->where('d.department_id = :depId',array(':depId'=>$depId))
            ->queryAll();

//        foreach ($model as $value) {
            foreach ($modelStuff as $val) {
                $stuff = new Halfstaff();
                $prod_id['stuff'] .= $val["halfstuff_id"].":";
                $temp = $stuff->getProdId($val["halfstuff_id"]);
                $prod_id['stuff'] .= $temp['stuff'];
                $prod_id['prod'] .= $temp['prod'];

            }
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("dishes d")
            ->join("dish_structure ds","ds.dish_id = d.dish_id")
            ->where('d.department_id = :depId',array(':depId'=>$depId))
            ->queryAll();
            foreach ($model as $val) {
                $prod_id['prod'] .= $val["product_id"].":";
            }

//        }

        return $prod_id;
    }

    public function getProd($id){
        $result = array();
        $result2 = array();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->join('dish_structure ds','ds.dish_id = d.dish_id')
            ->where('d.dish_id = :id',array(':id'=>$id))
            ->queryAll();
        foreach ($model as $val) {
            $result[$val['prod_id']] = $result[$val['prod_id']] + $val['amount']/$val['count'];

        }

        return $result;

    }

    public function getStuff($id){
        $result = array();
        $result2 = array();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->join('dish_structure2 ds','ds.dish_id = d.dish_id')
            ->where('d.dish_id = :id',array(':id'=>$id))
            ->queryAll();

        foreach ($model as $val) {
            $result[$val['halfstuff_id']] = $result[$val['halfstuff_id']] + $val['amount']/$val['count'];

            //$result = $stuff->sumArray($result,$result2);
        }

        return $result;

    }

    public function getCostPrice($id,$order_date){
        $log = new Logs();
        $stuffSum = 0;
        $prodSum = 0;
        $model = $log->getStructure($order_date,$id,$this->tableName());
//        echo "<pre>";
//        print_r($model);
//        echo "</pre>";
        if(!empty($model['prod']) && !empty($model['stuff']) && !empty($model['count'])) {
            if ($model['count'] == 0) {
                $dish = Yii::app()->db->createCommand()
                    ->select('count')
                    ->from('dishes')
                    ->where('dish_id = :id', array(':id' => $id))
                    ->queryRow();
                $model['count'] = $dish['count'];
            }
        }
        else{
            $model = $this->getStruct($id);
        }
        $stuff = new Halfstaff();

        $costPrice = array();
        $products = new Products();
        if(!empty($model)) {
            if(!empty($model['prod']))
                foreach ($model['prod'] as $key => $value) {
                    $costPrice['prod'][$key] = $products->getCostPrice($key, $order_date) * $value / $model['count'];
                }
            if(!empty($model['stuff']))
                foreach ($model['stuff'] as $key => $value) {
                    $costPrice['stuff'][$key] = $stuff->getCostPrice($key, $order_date) * $value / $model['count'];
                }
        }
        if(!empty($costPrice['prod'])){
            $prodSum = array_sum($costPrice['prod']);
        }
        if(!empty($costPrice['stuff'])){
            $stuffSum = array_sum($costPrice['stuff']);
        }
        return $prodSum + $stuffSum;
    }

    public function getUseDishList(){
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select('dish_id,name')
            ->from('dishes')
            ->where('status = :status',array(':status'=>0))
            ->queryAll();
        foreach ($model as $val) {
            $result[$val['dish_id']] = $val['name'];
        }

        return $result;
    }
    
    public function getStruct($id){
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->join('dish_structure dsh','dsh.dish_id = d.dish_id')
            ->where('d.dish_id = :id',array(':id'=>$id))
            ->queryAll();
        foreach ($model as $val) {
            $result['prod'][$val['prod_id']] = $val['amount'];
        }
        $model2 = Yii::app()->db->createCommand()
            ->select('')
            ->from('dishes d')
            ->join('dish_structure2 dsh','dsh.dish_id = d.dish_id')
            ->where('d.dish_id = :id',array(':id'=>$id))
            ->queryAll();
        foreach ($model2 as $val) {
            $result['stuff'][$val['halfstuff_id']] = $val['amount'];
        }
        $dish = Yii::app()->db->createCommand()
            ->select('count')
            ->from('dishes')
            ->where('dish_id = :id', array(':id' => $id))
            ->queryRow();
        $result['count'] = $dish['count'];
        return $result;
    }

    public function DishProd($dates,$id){
        $log = new Logs();
        $result['prod'] = array();
        $result['stuff'] = array();
        $stuff = new Halfstaff();
        $model = $log->getStructure($dates,$id,$this->tableName());
        if(!empty($model['prod']) or !empty($model['stuff'])) {
            if (!empty($model['prod']))
                foreach ($model['prod'] as $key => $val) {
                    $result['prod'][$key] = $val;
                }
            if (!empty($model['stuff'])) {
                foreach ($model['stuff'] as $key => $val) {
                    $result['stuff'][$key] = $val;
                    $result['prod'] = $stuff->sumArray($result['prod'], $stuff->multiplyArray($stuff->stuffProd($dates, $key), $val));
                }
            }
        }
        else{
            $model = $this->getStruct($id);
            if (!empty($model['prod']))
                foreach ($model['prod'] as $key => $val) {
                    $result['prod'][$key] = $val;
                }
            if (!empty($model['stuff']))
                foreach ($model['stuff'] as $key => $val) {
                    $result['stuff'][$key] = $val;
                    $result['prod'] = $stuff->sumArray($result['prod'], $stuff->multiplyArray($stuff->stuffProd($dates, $key), $val));
                }
        }
        $result['prod'] = $stuff->splitArray($result['prod'],$model['count']);
        $result['stuff'] = $stuff->splitArray($result['stuff'],$model['count']);
        return $result;

    }
    
    public function getName($id){
        $model = Yii::app()->db->createCommand()
            ->select('d.name')
            ->from('dishes d')
            ->where('d.dish_id = :id',array(':id'=>$id))
            ->queryRow();
        return $model['name'];
    }


}
