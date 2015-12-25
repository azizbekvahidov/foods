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
			array('price,percent', 'numerical', 'integerOnly'=>true),
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
			array('dish_id, name, price,percent,count', 'safe', 'on'=>'search'),
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
        $prod_id = '';
        $model = Dishes::model()->with('stuff','products')->findAll('t.department_id = :depId',array(':depId'=>$depId));

        foreach ($model as $value) {
            foreach ($value->getRelated('stuff') as $val) {
                $stuff = new Halfstaff();
                $prod_id .= $stuff->getProdId($val->halfstuff_id);
            }

            foreach ($value->getRelated('products') as $val) {
                $prod_id .= $val->product_id.":";
            }

        }

        return $prod_id;
    }

    public function getProd($id){
        $result = array();
        $result2 = array();
        $model = Dishes::model()->with('dishStruct')->findByPk($id);

        foreach ($model->getRelated('dishStruct') as $val) {
            $result[$val->prod_id] = $result[$val->prod_id] + $val->amount/$model->count;
        }


        return $result;

    }

    public function getStuff($id){
        $result = array();
        $result2 = array();
        $model = Dishes::model()->with('halfstuff')->findByPk($id);

        foreach ($model->getRelated('halfstuff') as $val) {
            $result[$val->halfstuff_id] = $result[$val->halfstuff_id] + $val->amount;

            //$result = $stuff->sumArray($result,$result2);
        }

        return $result;

    }



    public function getCostPrice($id,$order_date){
        $stuff = new Halfstaff();

        $costPrice = array();
        $products = new Products();

        //$modela = $this->model()->with('dishStruct','halfstuff')->findByPk(292);

        $model = $this->model()->with('dishStruct','halfstuff')->findByPk($id);
        if(!empty($model)) {
            foreach ($model->getRelated('dishStruct') as $value) {
                $costPrice[$value->prod_id] = $products->getCostPrice($value->prod_id, $order_date) * $value->amount / $model->count;

            }
            foreach ($model->getRelated('halfstuff') as $value) {
                $costPrice[$value->halfstuff_id] = $stuff->getCostPrice($value->halfstuff_id, $order_date) * $value->amount / $model->count;

            }
        }


        return array_sum($costPrice);
    }
    
    
}
