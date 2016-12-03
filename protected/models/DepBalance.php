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

    public function refreshBalance($depId){
$max_date = Yii::app()->db->createCommand()
            ->select('b_date')
            ->from('dep_balance')
            ->order('b_date DESC')
            ->group('b_date')
            ->queryRow();
        $prod = array();
        $stuffs = array();
        $dishes = new Dishes();
        $pBalance = array();
        $sBalance = array();
        $prodTemp = array();
        $stuffTemp = array();
        $dish = $dishes->getDishProd($depId);
        $prod['prod'] .= $dish['prod'];
        $prod['stuff'] .= $dish['stuff'];
        $halfStuff = new Halfstaff();
        $stuff = $halfStuff->getStuffProd($depId);
        $prod['prod'] .= $stuff['prod'];
        $prod['stuff'] .= $stuff['stuff'];
        $product = Yii::app()->db->createCommand()
            ->select('')
            ->from('products p')
            ->where('p.department_id = :depId',array(':depId'=>$depId))
            ->queryAll();
        foreach ($product as $val) {
            $prod['prod'] .= $val['product_id'].":";
        }
        $prodTemp = array_unique(explode(':',$prod['prod']));

        $stuffTemp = array_unique(explode(':',$prod['stuff']));
        $ProdBalance = Yii::app()->db->createCommand()
            ->select('db.dep_balance_id,db.prod_id')
            ->from('dep_balance db')
            ->where('db.b_date = :dates AND db.department_id = :id AND db.type = :types ',array(':dates'=>$max_date['b_date'],':id'=>$depId,':types'=>1))
            ->group('db.prod_id')
            ->queryAll();
        if(!empty($ProdBalance)) {
            foreach ($ProdBalance as $key => $val) {
                $pBalance[$val['dep_balance_id']] = $val['prod_id'];
                if (!in_array($val['prod_id'], $prodTemp)) {
                    Yii::app()->db->createCommand()->delete('dep_balance', 'b_date = :dates AND department_id = :depId AND prod_id = :id AND type = :types', array(':dates' => $max_date['b_date'], ':depId' => $depId, ':id' => $val['prod_id'], ':types' => 1));
                }
                if ($val['prod_id'] == 0) {
                    Yii::app()->db->createCommand()->delete('dep_balance', 'b_date = :dates AND department_id = :depId AND prod_id = :id AND type = :types', array(':dates' => $max_date['b_date'], ':depId' => $depId, ':id' => $val['prod_id'], ':types' => 1));
                }
            }
        }

        $StuffBalance = Yii::app()->db->createCommand()
            ->select('db.dep_balance_id,db.prod_id')
            ->from('dep_balance db')
            ->where('db.b_date = :dates AND db.department_id = :id AND db.type = :types',array(':dates'=>$max_date['b_date'],':id'=>$depId,':types'=>2))
            ->group('db.prod_id')
            ->queryAll();
        if(!empty($StuffBalance)) {
            foreach ($StuffBalance as $key => $val) {
                $sBalance[$val['dep_balance_id']] = $val['prod_id'];
                if (!in_array($val['prod_id'], $stuffTemp)) {
                    Yii::app()->db->createCommand()->delete('dep_balance', 'b_date = :dates AND department_id = :depId AND prod_id = :id AND type = :types', array(':dates' => $max_date['b_date'], ':depId' => $depId, ':id' => $val['prod_id'], ':types' => 2));
                }
                if ($val['prod_id'] == 0) {
                    Yii::app()->db->createCommand()->delete('dep_balance', 'b_date = :dates AND department_id = :depId AND prod_id = :id AND type = :types', array(':dates' => $max_date['b_date'], ':depId' => $depId, ':id' => $val['prod_id'], ':types' => 2));
                }
            }
        }
        if(!empty($prodTemp)) {
            foreach ($prodTemp as $val) {
                if ($val != 0) {
                    if (!in_array($val, $pBalance)) {
                        Yii::app()->db->createCommand()->insert('dep_balance', array(
                                'b_date' => $max_date['b_date'],
                                'department_id' => $depId,
                                'prod_id' => $val,
                                'type' => 1)
                        );
                    }
                }
            }
        }
        if(!empty($stuffTemp)) {
            foreach ($stuffTemp as $val) {
                if ($val != 0) {
                    if (!in_array($val, $sBalance)) {

                        Yii::app()->db->createCommand()->insert('dep_balance', array(
                                'b_date' => $max_date['b_date'],
                                'department_id' => $depId,
                                'prod_id' => $val,
                                'type' => 2)
                        );
                    }
                }
            }
        }

        /*

                $dish = Yii::app()->db->createCommand()
                    ->select('dish_id')
                    ->from('dishes d')
                    ->where('d.department_id = :depId',array(':depId'=>$depId))
                    ->queryAll();
               foreach ($dish as $value) {
                    $temp = $dishes->DishProd($max_date,$value['dish_id']);
                    $prod = $prod + $temp['prod'];
                    if(!empty($temp['stuff'])){
                        $stuffs = $stuffs + $temp['stuff'];

                    }
                    //$this->addDish($value['dish_id'],$depId,$max_date['b_date']);
                }

                $stuff = Yii::app()->db->createCommand()
                    ->select('h.halfstuff_id')
                    ->from('halfstaff h')
                    ->where('h.department_id = :depId',array(':depId'=>$depId))
                    ->queryAll();
                foreach ($stuff as $value) {
                    $this->addStuff($value['halfstuff_id'],$depId,$max_date['b_date']);
                }

                $prod = Yii::app()->db->createCommand()
                    ->select('p.product_id')
                    ->from('products p')
                    ->where('p.department_id = :depId',array(':depId'=>$depId))
                    ->queryAll();
                foreach ($prod as $value) {
                    $this->addProd($value['product_id'],$depId,$max_date['b_date']);
                }*/

    }

    public function deleteDublicate($depId){
        $max_date = Yii::app()->db->createCommand()
            ->select('b_date')
            ->from('dep_balance')
            ->order('b_date DESC')
            ->group('b_date')
            ->queryRow();
        $ProdBalance = Yii::app()->db->createCommand()
            ->select('db.dep_balance_id,db.prod_id')
            ->from('dep_balance db')
            ->where('db.b_date = :dates AND db.department_id = :id AND db.type = :types ',array(':dates'=>$max_date['b_date'],':id'=>$depId,':types'=>1))
            ->group('db.prod_id')
            ->queryAll();
        if(!empty($ProdBalance)) {
            foreach ($ProdBalance as $key => $val) {
                $pBalance[$val['dep_balance_id']] = $val['prod_id'];

            }
        }

        $StuffBalance = Yii::app()->db->createCommand()
            ->select('db.dep_balance_id,db.prod_id')
            ->from('dep_balance db')
            ->where('db.b_date = :dates AND db.department_id = :id AND db.type = :types',array(':dates'=>$max_date['b_date'],':id'=>$depId,':types'=>2))
            ->group('db.prod_id')
            ->queryAll();
        if(!empty($StuffBalance)) {
            foreach ($StuffBalance as $key => $val) {
                $sBalance[$val['dep_balance_id']] = $val['prod_id'];

            }
        }
        foreach ($pBalance as $key => $val) {
            Yii::app()->db->createCommand()->delete('dep_balance','dep_balance_id != :id AND prod_id = :prod_id AND type = :types',array(':id'=>$key,':prod_id'=>$val,':types'=>1));
        }
        foreach ($sBalance as $key => $val) {
            Yii::app()->db->createCommand()->delete('dep_balance','dep_balance_id != :id AND prod_id = :prod_id AND type = :types',array(':id'=>$key,':prod_id'=>$val,':types'=>2));
        }

    }


    public function checkProd($id,$depId,$max_date){

        $curDepProd = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_balance t')
            ->where('date(t.b_date) = :dates AND t.type = :types AND t.department_id = :depId',array(':dates'=>$max_date,':types'=>1,':depId'=>$depId))
            ->queryAll();
        foreach($curDepProd as $value){
            if($value['prod_id'] == $id){
                $result = true;
                break;
            }
            else{
                $result = false;
            }
        }
        return $result;
    }

    public function checkStuff($id,$depId,$max_date){
        $curDepProd = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_balance t')
            ->where('date(t.b_date) = :dates AND t.type = :types AND t.department_id = :depId',array(':dates'=>$max_date,':types'=>2,':depId'=>$depId))
            ->queryAll();

        foreach($curDepProd as $value){

            if($value['prod_id'] == $id){
                $result = true;
                break;
            }
            else{
                $result = false;
            }
        }
        return $result;
    }

    public function addProd($id,$depId,$max_date){
        if($this->checkProd($id,$depId,$max_date) != true){
            Yii::app()->db->createCommand()
                ->insert('dep_balance',array(
                    'b_date'=>$max_date,
                    'prod_id'=>$id,
                    'startCount'=>0,
                    'endCount'=>0,
                    'department_id'=>$depId,
                    'type'=>1
                ));
        }
    }

    public function addStuff($id,$depId,$max_date){
        if($this->checkStuff($id,$depId,$max_date) != true) {
            Yii::app()->db->createCommand()
                ->insert('dep_balance',array(
                    'b_date'=>$max_date,
                    'prod_id'=>$id,
                    'startCount'=>0,
                    'endCount'=>0,
                    'department_id'=>$depId,
                    'type'=>2
                ));
        }
        //Список полуфабрикатов и их продуктов
        //$dishStruct = Halfstaff::model()->with('stuffStruct.Struct')->findByPk($id,'stuffStruct.types = :types',array(':types'=>1));

        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('halfstuff_structure hs')
            ->where('hs.halfstuff_id = :id',array(':id'=>$id))
            ->queryAll();
        foreach ($model as $val) {
            if($val->types == 2) {
                $this->addStuff($val['prod_id'],$depId,$max_date);
            }
            else{
                $this->addProd($val['prod_id'],$depId,$max_date);
            }
        }


    }
    public function addDish($id,$depId,$max_date){
        //Корневые продукты блюда выбранного отдела
        $dishProducts = Yii::app()->db->createCommand()
            ->select('')
            ->from('dish_structure ds')
            ->where('ds.dish_id = :Id',array(':Id'=>$id))
            ->queryAll();
        if(!empty($dishProducts))
            foreach($dishProducts as $val){
                $this->addProd($val['prod_id'],$depId,$max_date);
            }

        //Корневые полуфабрикаты блюда выбранного отдела
        $DishStuff = Yii::app()->db->createCommand()
            ->select('')
            ->from('dish_structure2 ds')
            ->where('ds.dish_id = :Id',array(':Id'=>$id))
            ->queryAll();
        if(!empty($DishStuff))
            foreach($DishStuff as $val){
                $this->addStuff($val['halfstuff_id'],$depId,$max_date);
            }
    }
}
