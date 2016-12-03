<?php

/**
 * This is the model class for table "inexpense".
 *
 * The followings are the available columns in table 'inexpense':
 * @property integer $inexpense_id
 * @property string $inexp_date
 * @property integer $department_id
 */
class Inexpense extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'inexpense';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('department_id, fromDepId', 'numerical', 'integerOnly'=>true),
			array('inexp_date', 'safe'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('inexpense_id, inexp_date, deaprtment_id', 'safe', 'on'=>'search'),
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
            'department'=>array(self::BELONGS_TO,'Department','department_id'),
            'inorder'=>array(self::HAS_MANY,'Inorder','inexpense_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'inexpense_id' => 'Inexpense',
			'inexp_date' => 'Inexp Date',
			'department_id' => 'Provider',
            'fromDepId' => 'From Dep Id'
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

		$criteria->compare('inexpense_id',$this->inexpense_id);
		$criteria->compare('inexp_date',$this->inexp_date,true);
		$criteria->compare('department_id',$this->department_id);
        $criteria->compare('fromDepId',$this->fromDepId);


        return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Inexpense the static model class
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

    public function getDepIn($depId,$dates,$fromDate){
        $model = Yii::app()->db->createCommand()
            ->select('inor.stuff_id,inor.count')
            ->from('inexpense inex')
            ->join('inorder inor','inor.inexpense_id = inex.inexpense_id')
            ->where('date(inex.inexp_date) <= :dates AND date(inex.inexp_date) > :from AND inex.department_id = :depId AND inex.fromDepId != :fromDepId',array(':dates'=>$dates,':depId'=>$depId,':fromDepId'=>0,':from'=>$fromDate))
            ->queryAll();

        $result = array();
        foreach ($model as $val) {
            $result[$val['stuff_id']] = $result[$val['stuff_id']] + $val['count'];
        }
        return $result;
    }

    public function getDepOut($depId,$dates,$fromDate){
        $model = Yii::app()->db->createCommand()
            ->select('inor.stuff_id,inor.count')
            ->from('inexpense inex')
            ->join('inorder inor','inor.inexpense_id = inex.inexpense_id')
            ->where('date(inex.inexp_date) <= :dates AND date(inex.inexp_date) > :from AND inex.department_id != :depId AND inex.fromDepId = :fromDepId',array(':dates'=>$dates,':depId'=>0,':fromDepId'=>$depId,':from'=>$fromDate))
            ->queryAll();
        $result = array();
        foreach ($model as $val) {
            $result[$val['stuff_id']] = $result[$val['stuff_id']] + $val['count'];
        }
        return $result;
    }

    
}
