<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property integer $menu_id
 * @property integer $just_id
 * @property integer $type
 */
class Menu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('just_id, mType, type, type_id', 'numerical', 'integerOnly'=>true),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('menu_id, just_id, type', 'safe', 'on'=>'search'),
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
        'dish'=>array(self::BELONGS_TO,'Dishes','just_id','condition'=>'type =:type', 'params'=>array(':type'=>1)),
        'halfstuff'=>array(self::BELONGS_TO,'Halfstaff','just_id','condition'=>'type =:type', 'params'=>array(':type'=>2)),
        'products'=>array(self::BELONGS_TO,'Products','just_id','condition'=>'type =:type', 'params'=>array(':type'=>3)),
        'stuff'=>array(self::BELONGS_TO,'Halfstaff','just_id'),
        'dishType'=>array(self::BELONGS_TO,'DishType','type_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'menu_id' => 'Menu',
			'just_id' => 'Just',
			'type' => 'Type',
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

		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('just_id',$this->just_id);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
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

    public function getMenuList(){
        $menuList = array();
        $price = new Prices();
        $dates = date('Y-m-d');
        $dishModel = Menu::model()->with('dish')->findAll();
        $stuffModel = Menu::model()->with('halfstuff')->findAll();
        $prodModel = Menu::model()->with('products')->findAll();
        foreach ($dishModel as $val) {
            $menuList['dish_'.$val->just_id] = $val->getRelated('dish')->name."_".$price->getPrice($val->just_id,$val->mType,$val->type,$dates);
        }
        foreach ($stuffModel as $val) {
            $menuList['stuff_'.$val->just_id] = $val->getRelated('halfstuff')->name."_".$price->getPrice($val->just_id,$val->mType,$val->type,$dates);
        }
        foreach ($prodModel as $val) {
            $menuList['product_'.$val->just_id] = $val->getRelated('products')->name."_".$price->getPrice($val->just_id,$val->mType,$val->type,$dates);
        }
        return $menuList;

    }


}
