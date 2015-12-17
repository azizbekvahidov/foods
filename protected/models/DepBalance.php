<?php

/**
 * This is the model class for table "dep_balance".
 *
 * The followings are the available columns in table 'dep_balance':
 * @property integer $dep_balance_id
 * @property string $b_date
 * @property integer $prod_id
 * @property double $startCount
 * @property double $endCount
 * @property integer $department_id
 */
class DepBalance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dep_balance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prod_id, department_id', 'numerical', 'integerOnly'=>true),
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
			array('dep_balance_id, b_date, prod_id, startCount, endCount, department_id', 'safe', 'on'=>'search'),
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
            'stuff'=>array(self::BELONGS_TO,'Halfstaff','prod_id'),
            'dish'=>array(self::BELONGS_TO,'Dishes','prod_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dep_balance_id' => 'Dep Balance',
			'b_date' => 'B Date',
			'prod_id' => 'Prod',
			'startCount' => 'Start Count',
			'endCount' => 'End Count',
			'department_id' => 'Department',
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

		$criteria->compare('dep_balance_id',$this->dep_balance_id);
		$criteria->compare('b_date',$this->b_date,true);
		$criteria->compare('prod_id',$this->prod_id);
		$criteria->compare('startCount',$this->startCount);
		$criteria->compare('endCount',$this->endCount);
		$criteria->compare('department_id',$this->department_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DepBalance the static model class
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
}
