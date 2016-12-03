<?php

/**
 * This is the model class for table "prices".
 *
 * The followings are the available columns in table 'prices':
 * @property integer $prices_id
 * @property string $price_date
 * @property double $price
 * @property integer $menu_type
 * @property integer $just_id
 * @property integer $types
 */
class Prices extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menu_type, just_id, types', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('price_date', 'safe'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prices_id, price_date, price, menu_type, just_id, types', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prices_id' => 'Prices',
			'price_date' => 'Price Date',
			'price' => 'Price',
			'menu_type' => 'Menu Type',
			'just_id' => 'Just',
			'types' => 'Types',
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

		$criteria->compare('prices_id',$this->prices_id);
		$criteria->compare('price_date',$this->price_date,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('menu_type',$this->menu_type);
		$criteria->compare('just_id',$this->just_id);
		$criteria->compare('types',$this->types);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Prices the static model class
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

    public function getPrice($id,$mType,$types,$dates){
        //echo $id." => ".$mType." => ".$types." => ".$dates;
        $dates = date('Y-m-d',strtotime($dates));
        $model = Yii::app()->db->createCommand()
            ->select('p.price')
            ->from('prices p')
            ->where('date(p.price_date) <= :dates AND p.just_id = :id AND p.menu_type = :mType AND p.types = :types',array(':id'=>$id,':mType'=>$mType,':types'=>$types,':dates'=>$dates))
            ->order('p.price_date DESC')
            ->queryRow();
        if(empty($model)){
            $model2 = Prices::model()->find(
                array(
                    'condition'=>'just_id = :id AND menu_type = :mType AND types = :types',
                    'order'=>'price_date DESC',
                    'limit'=>1,
                    "together" => true,
                    'params'=>array(':id'=>$id,':mType'=>$mType,':types'=>$types),
                )
            );
            return $model2->price;
        }
        else{
            return $model['price'];
        }
    }
    
    public function getIntervalMargin($id,$dates){
        
    }
    
}
