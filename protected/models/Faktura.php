<?php

/**
 * This is the model class for table "faktura".
 *
 * The followings are the available columns in table 'faktura':
 * @property integer $faktura_id
 * @property string $realize_date
 * @property integer $provider_id
 */
class Faktura extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'faktura';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('provider_id', 'numerical', 'integerOnly'=>true),
			array('realize_date', 'safe'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('faktura_id, realize_date, provider_id', 'safe', 'on'=>'search'),
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
            'provider'=>array(self::BELONGS_TO,'Provider','provider_id'),
            'realize'=>array(self::HAS_MANY,'Realize','faktura_id',),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'faktura_id' => 'Faktura',
			'realize_date' => 'Realize Date',
			'provider_id' => 'Provider',
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

		$criteria->compare('faktura_id',$this->faktura_id);
		$criteria->compare('realize_date',$this->realize_date,true);
		$criteria->compare('provider_id',$this->provider_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Faktura the static model class
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
        	//$from=DateTime::createFromFormat('d/m/Y',$this->realize_date);
        	//$this->realize_date=$from->format('Y-m-d');
        	
        return parent::beforeSave();
    }

    public function beforeDelete () {
		$userId=0;
		if(null!=Yii::app()->user->id) $userId=(int)Yii::app()->user->id;
                                
        return false;
    }

    public function afterFind()    {
         
        	// NOT SURE RUN PLEASE HELP ME -> 
        	//$from=DateTime::createFromFormat('Y-m-d',$this->realize_date);
        	//$this->realize_date=$from->format('d/m/Y');
        	
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

    public function getReqCount($reqId,$depId,$prod_id){
        $model = Yii::app()->db->createCommand()
            ->select('rp.count')
            ->from('request r')
            ->join('request_prod rp','rp.request_id = r.request_id')
            ->where('r.request_id = :id AND rp.depId = :depId AND rp.prod_id = :prod_id',array(':id'=>$reqId,':depId'=>$depId,':prod_id'=>$prod_id))
            ->queryRow();
        return $model['count'];
    }

    public function getReqSumCount($prod_id,$id){
        $prodCount = 0;
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('request r')
            ->join('request_prod rp','rp.request_id = r.request_id')
            ->where('r.request_id = :id AND rp.prod_id = :prod_id',array(':id'=>$id,':prod_id'=>$prod_id))
            ->queryAll();

        foreach ($model as $val) {
            $prodCount = $prodCount + $val['count'];
        }
        return $prodCount;

    }

    public function getFakCount($dates,$depId,$prod_id){
        $dates = date('Y-m-d',strtotime($dates));
        if($depId != 0) {
            $model = Yii::app()->db->createCommand()
                ->select('sum(dr.count) as summ')
                ->from('dep_faktura df')
                ->join('dep_realize dr', 'dr.dep_faktura_id = df.dep_faktura_id')
                ->where('date(df.real_date) = :dates AND df.department_id = :depId AND df.fromDepId = :fromDepId AND dr.prod_id = :prod_id',
                    array(':dates' => $dates, ':depId' => $depId, ':fromDepId' => 0, ':prod_id' => $prod_id))
                ->queryRow();
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select('sum(ord.count) as summ')
                ->from('expense ex')
                ->join('orders ord','ord.expense_id = ex.expense_id')
                ->where('date(ex.order_date) = :dates AND ex.kind = :kind AND ord.just_id = :prod_id',
                    array(':dates' => $dates, ':kind' => 1, ':prod_id' => $prod_id))
                ->queryRow();
        }
        return $model['summ'];
    }



}
