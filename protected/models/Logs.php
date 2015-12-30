<?php

/**
 * This is the model class for table "logs".
 *
 * The followings are the available columns in table 'logs':
 * @property integer $logs_id
 * @property string $actions
 * @property string $table_name
 */
class Logs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('logs_id, curId', 'numerical', 'integerOnly'=>true),
			array('actions, table_name, message', 'length', 'max'=>500),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('logs_id, actions, table_name, curId, message', 'safe', 'on'=>'search'),
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
			'logs_id' => 'Logs',
			'actions' => 'Actions',
			'table_name' => 'Table Name',
            'curId' => 'ID',
            'messsage' => 'Message',
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

		$criteria->compare('logs_id',$this->logs_id);
		$criteria->compare('actions',$this->actions,true);
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('curId',$this->curId,true);
		$criteria->compare('message',$this->message,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Logs the static model class
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

    public function getStructure($dates,$id,$table){
        $result = array();
        
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('logs')
            ->where('log_date <= :dates AND curId = :id AND table_name = :table',array(':dates'=>$dates,':id'=>$id,':table'=>$table))
            ->order('log_date DESC')
            ->queryRow();

        $name = explode('->',$model['message']);
        $struct = explode('=>',$name[1]);
        $prod = explode('>',$struct[0]);
        $stuff = explode('>',$struct[1]);

        $prodStruct = explode(',',$prod[1]);
        foreach ($prodStruct as $val) {
            $temp = explode(':',$val);
            if(!empty($val)){
                $result[str_replace(" ","",$prod[0])][$temp[0]] = floatval($temp[1]);
            }
        }
        $stufStruct = explode(',',$stuff[1]);
        foreach ($stufStruct as $val) {
            $temp = explode(':',$val);
            if(!empty($val)){
                $result[str_replace(" ","",$stuff[0])][$temp[0]] = floatval($temp[1]);
            }
        }

        return $result;

    }


}
