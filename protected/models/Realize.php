<?php

/**
 * This is the model class for table "realize".
 *
 * The followings are the available columns in table 'realize':
 * @property integer $realize_id
 * @property integer $faktura_id
 * @property integer $prod_id
 * @property double $price
 * @property integer $count
 */
class Realize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'realize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('faktura_id, prod_id', 'numerical', 'integerOnly'=>true),
			array('price, count', 'numerical'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('realize_id, faktura_id, prod_id, price, count', 'safe', 'on'=>'search'),
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
            'fakture'=>array(self::BELONGS_TO,'Faktura','faktura_id'),
            'faktures'=>array(self::BELONGS_TO,'Faktura','faktura_id','order'=>'faktures.realize_date DESC',),
            'provider'=>array(self::BELONGS_TO,'Provider','provider_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'realize_id' => 'Realize',
			'faktura_id' => 'Faktura',
			'prod_id' => 'Prod',
			'price' => 'Price',
			'count' => 'Count',
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

		$criteria->compare('realize_id',$this->realize_id);
		$criteria->compare('faktura_id',$this->faktura_id);
		$criteria->compare('prod_id',$this->prod_id);
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
	 * @return Realize the static model class
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

    public function checkProd($array,$id){
        foreach ($array as $key => $val) {
            if($key == $id){
                return true;
            }
            else{
                return false;
            }
        }

    }

    public function getRealizeSumm($dates){
        $summ = 0;
        $model = Yii::app()->db->createCommand()
            ->select('(re.count*re.price) as summ')
            ->from('faktura fa')
            ->join('realize re','re.faktura_id = fa.faktura_id')
            ->where('date(fa.realize_date) = :dates',array(':dates'=>$dates))
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $val['summ'];
        }
        return $summ;
    }

}
