<?php

/**
 * This is the model class for table "dep_faktura".
 *
 * The followings are the available columns in table 'dep_faktura':
 * @property integer $dep_faktura_id
 * @property string $real_date
 * @property integer $department_id
 */
class DepFaktura extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dep_faktura';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('department_id', 'numerical', 'integerOnly'=>true),
			array('real_date', 'safe'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dep_faktura_id, real_date, department_id', 'safe', 'on'=>'search'),
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
            'realizedProd'=>array(self::HAS_MANY,'DepRealize','dep_faktura_id'),
            'department'=>array(self::BELONGS_TO,'Department','department_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dep_faktura_id' => 'Dep Faktura',
			'real_date' => 'Real Date',
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

		$criteria->compare('dep_faktura_id',$this->dep_faktura_id);
		$criteria->compare('real_date',$this->real_date,true);
		$criteria->compare('department_id',$this->department_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DepFaktura the static model class
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
    
    public function getDepRealizeSumm($dates){
        $prod = new Products();
        $summ = 0;
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('date(df.real_date) = :dates AND df.fromDepId = :fromDepId',array(':dates'=>$dates,':fromDepId'=>0))
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $val['count']*$prod->getCostPrice($val['prod_id'],$dates);
        }
        return $summ;
    }

    public function getDepRealizesSumm($from,$till,$depId){
        $prod = new Products();
        $summ = 0;
        $model = Yii::app()->db->createCommand()
            ->select('df.real_date,dr.count,dr.prod_id')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('date(df.real_date) <= :till AND date(df.real_date) >= :from AND df.department_id = :depId AND df.fromDepId = :fromDepId',array(':from'=>$from,':till'=>$till,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $val['count']*$prod->getCostPrice($val['prod_id'],$val['real_date']);
        }

        return $summ;
    }

    public function getDepInRealizesSumm($from,$till,$depId){
        $prod = new Products();
        $stuff = new Halfstaff();
        $summ = 0;
        $model = Yii::app()->db->createCommand()
            ->select('df.real_date,dr.count,dr.prod_id')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('date(df.real_date) <= :till AND date(df.real_date) >= :from AND df.department_id = :depId AND df.fromDepId != :fromDepId',array(':from'=>$from,':till'=>$till,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $val['count']*$prod->getCostPrice($val['prod_id'],$val['real_date']);
        }

        $model2 = Yii::app()->db->createCommand()
            ->select('inexp.inexp_date,inord.count,inord.stuff_id')
            ->from('inexpense inexp')
            ->join('inorder inord','inord.inexpense_id = inexp.inexpense_id')
            ->where('date(inexp.inexp_date) <= :till AND date(inexp.inexp_date) >= :from AND inexp.department_id = :depId AND inexp.fromDepId != :fromDepId',array(':from'=>$from,':till'=>$till,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
        foreach ($model2 as $val) {
            $summ = $summ + $val['count']*$stuff->getCostPrice($val['stuff_id'],$val['inexp_date']);
        }
        return $summ;
    }

    public function getDepInExp($from,$till,$depId){
        $prod = new Products();
        $stuff = new Halfstaff();
        $summ = 0;
        $model = Yii::app()->db->createCommand()
            ->select('df.real_date,dr.count,dr.prod_id')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('date(df.real_date) <= :till AND date(df.real_date) >= :from AND df.department_id != :depId AND df.fromDepId = :fromDepId',array(':from'=>$from,':till'=>$till,':depId'=>$depId,':fromDepId'=>$depId))
            ->queryAll();
        foreach ($model as $val) {
            $summ = $summ + $val['count']*$prod->getCostPrice($val['prod_id'],$val['real_date']);
        }

        $model2 = Yii::app()->db->createCommand()
            ->select('inexp.inexp_date,inord.count,inord.stuff_id')
            ->from('inexpense inexp')
            ->join('inorder inord','inord.inexpense_id = inexp.inexpense_id')
            ->where('date(inexp.inexp_date) <= :till AND date(inexp.inexp_date) >= :from AND inexp.department_id != :depId AND inexp.fromDepId = :fromDepId',array(':from'=>$from,':till'=>$till,':depId'=>$depId,':fromDepId'=>$depId))
            ->queryAll();
        foreach ($model2 as $val) {
            $summ = $summ + $val['count']*$stuff->getCostPrice($val['stuff_id'],$val['inexp_date']);
        }
        return $summ;
    }

}
