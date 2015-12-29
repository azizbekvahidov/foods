<?php

/**
 * This is the model class for table "halfstaff".
 *
 * The followings are the available columns in table 'halfstaff':
 * @property integer $halfstuff_id
 * @property integer $name
 * @property integer $stuff_type
 */
class Halfstaff extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'halfstaff';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stuff_type,price', 'numerical', 'integerOnly'=>true),
            array('count','numerical'),
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
			array('halfstuff_id, name, stuff_type,price,count', 'safe', 'on'=>'search'),
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
            'halfstuffType'=>array(self::BELONGS_TO, 'Measurement', 'stuff_type'),
            'stuffStruct'=>array(self::HAS_MANY, 'HalfstuffStructure', 'halfstuff_id'),
            'podstuffStruct'=>array(self::HAS_MANY, 'HalfstuffStructure', 'halfstuff_id'),
            'Struct'=>array(self::HAS_MANY,'DishStructure2','halfstuff_id'),
            'products'=>array(self::MANY_MANY, 'Products', 'halfstuff_structure(halfstuff_id,prod_id)'),
            'product'=>array(self::MANY_MANY, 'Products', 'halfstuff_structure(halfstuff_id,prod_id)'),
            'podstuff'=>array(self::MANY_MANY, 'Halfstaff', 'halfstuff_structure(halfstuff_id,prod_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'halfstuff_id' => 'Полуфабрикат',
			'name' => 'Название',
			'stuff_type' => 'Ед.Измерения',
            'price' => 'Цена',
            'count'=>'Количество порций',
            'department_id' => 'Отдел',
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

		$criteria->compare('halfstuff_id',$this->halfstuff_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('stuff_type',$this->stuff_type);
		$criteria->compare('price',$this->price);
        $criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Halfstaff the static model class
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
    public function getStuffId($id){
        $model = Halfstaff::model()->with('stuffStruct')->findByPk($id);
        $stuff = $model->halfstuff_id.":";
        foreach ($model->getRelated('stuffStruct') as $val) {
            if($val->types == 2) {
                $stuff .= $this->getStuffId($val->prod_id);
            }
        }
        return $stuff;
    }

    public function getStuffProd($depId){
        $model = Halfstaff::model()->with('stuffStruct')->findAll('t.department_id = :depId',array(':depId'=>$depId));
        $prod = '';
        foreach ($model as $value) {
            foreach ($value->getRelated('stuffStruct') as $val) {
                if($val->types == 2) {
                    $prod .= $this->getStuffProd($val->prod_id);
                }
                else{
                    $prod .= $val->prod_id.":";
                }
            }
        }
        return $prod;
    }

    public function getProdId($id){
        $model = $this::model()->with('stuffStruct')->findByPk($id);
        $prod = '';
        foreach ($model->getRelated('stuffStruct') as $val) {
            if($val->types == 2) {
                $prod .= $this->getProdId($val->prod_id);
            }
            else{
                $prod .= $val->prod_id.":";
            }
        }
        return $prod;
    }


    public function getStuffName($depId){
        //.podstuff.podstuffStruct.Struct
        $models = Dishes::model()->with('stuff')->findAll('t.department_id = :depId',array(':depId'=>$depId));
        $stuff = '';
        if(!empty($models))
            foreach($models as $values){
                foreach($values->getRelated('stuff') as $value){
                    $stuff .= $this->getStuffId($value->halfstuff_id);
                }
            }
        $model = Halfstaff::model()->findAll('t.department_id = :depId',array(':depId'=>$depId));
        foreach ($model as $value) {
            $stuff .= $this->getStuffId($value->halfstuff_id);
        }

        $temp = explode(':',$stuff);
        $result = array();
        foreach ($temp as $val) {
            $model = Halfstaff::model()->findByPk($val);
            if(!empty($model))
                $result[$model->halfstuff_id] = $model->name;
        }

        return $result;
    }
    public function getStuffProdName($depId){
        $models = Dishes::model()->with('stuff')->findAll('t.department_id = :depId',array(':depId'=>$depId));
        $stuff = '';
        if(!empty($models))
            foreach($models as $values){
                foreach($values->getRelated('stuff') as $value){
                    $stuff .= $this->getProdId($value->halfstuff_id);
                }
            }
        $model = Halfstaff::model()->findAll('t.department_id = :depId',array(':depId'=>$depId));
        foreach ($model as $value) {
            $stuff .= $this->getProdId($value->halfstuff_id);
        }
        
        $temp = explode(':',$stuff);
        $result = array();
        foreach ($temp as $val) {
            $model = Products::model()->findByPk($val);
            if(!empty($model))
                $result[$model->product_id] = $model->name;
        }

        return $result;
    }

    public function getName($id){
        $model = $this->model()->findByPk($id);
        return $model->name;
    }

    public function getStuff($id){
        $result = array();
        $result2 = array();
        $model = $this::model()->with('stuffStruct')->findByPk($id);
        foreach ($model->getRelated('stuffStruct') as $val) {
            if($val->types == 1){
                $result[$val->prod_id] = $result[$val->prod_id] + $val->amount;
            }
            elseif($val->types == 2){
                $result2 = $this->multiplyArray($this->getStuff($val->prod_id),$val->amount);
                $result = $this->sumArray($result,$result2);
            }
        }
        return $result = $this->splitArray($result,$model->count);

    }

    public function sumArray($array1,$array2){
        $result = array();
        foreach ($array1 as $key => $val) {
            $result[$key] = $val + $array2[$key];
        }
        $result = $result + $array2;
        return $result;
    }

    public function splitArray($array,$index){
        $result = array();
        foreach ($array as $key => $val) {
            $result[$key] = $val/$index;
        }
        return $result;
    }

    public function multiplyArray($array,$index){
        $result = array();
        foreach ($array as $key => $val) {
            $result[$key] = $val*$index;
        }
        return $result;
    }

    public function getCostPrice($id,$order_date){
        $log = new Logs();
        $model = $log->getStructure($order_date,$id,$this->tableName());

        $products = new Products();
        $costPrice = array();
        $stuff = Yii::app()->db->createCommand()
            ->select('count')
            ->from('halfstaff')
            ->where('halfstuff_id = :id',array(':id'=>$id))
            ->queryRow();
        if(!empty($model)) {
            if(!empty($model['prod']))
                foreach ($model['prod'] as $key => $value) {
                    $costPrice[$key] = $products->getCostPrice($key,$order_date)*$value/$stuff['count'];
                }
            if(!empty($model['stuff']))
                foreach ($model['stuff'] as $key => $value) {
                    $costPrice[$key] = $this->getCostPrice($key,$order_date)*$value/$stuff['count'];
                }
        }
        return array_sum($costPrice);
    }

    public function getUseStuffList(){
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select('halfstuff_id,name')
            ->from('Halfstaff')
            ->where('status = :status',array(':status'=>0))
            ->queryAll();
        foreach ($model as $val) {
            $result[$val['halfstuff_id']] = $val['name'];
        }

        return $result;
    }

    public function getNotUseStuffList(){
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select('halfstuff_id,name')
            ->from('Halfstaff')
            ->where('status = :status',array(':status'=>1))
            ->queryAll();
        foreach ($model as $val) {
            $result[$val['halfstuff_id']] = $val['name'];
        }

        return $result;
    }
}
