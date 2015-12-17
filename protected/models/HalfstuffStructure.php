<?php

/**
 * This is the model class for table "halfstuff_structure".
 *
 * The followings are the available columns in table 'halfstuff_structure':
 * @property integer $halfstruct_id
 * @property integer $halfstuff_id
 * @property integer $prod_id
 */
class HalfstuffStructure extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'halfstuff_structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('halfstuff_id, prod_id, types', 'numerical', 'integerOnly'=>true),
            array('amount','numerical'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('halfstruct_id, halfstuff_id, prod_id, amount, types', 'safe', 'on'=>'search'),
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
            'Struct'=>array(self::BELONGS_TO, 'Products', 'prod_id'),
            'stuffStruct'=>array(self::BELONGS_TO, 'Halfstaff', 'halfstaff_id'),
            'stuff'=>array(self::BELONGS_TO, 'Halfstaff', 'prod_id'),
            'podstuff'=>array(self::BELONGS_TO, 'Halfstaff', 'prod_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'halfstruct_id' => 'Halfstruct',
			'halfstuff_id' => 'Halfstuff',
			'prod_id' => 'Prod',
			'amount' => 'Amount',
            'types' => 'types'
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

		$criteria->compare('halfstruct_id',$this->halfstruct_id);
		$criteria->compare('halfstuff_id',$this->halfstuff_id);
		$criteria->compare('prod_id',$this->prod_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('types',$this->types);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HalfstuffStructure the static model class
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
}
