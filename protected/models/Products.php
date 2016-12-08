<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $product_id
 * @property string $name
 * @property integer $measure_id
 */
class Products extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('measure_id,groupProd_id,price', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_id, name, measure_id,groupProd_id,price', 'safe', 'on'=>'search'),
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
            'measure'=> array(self::BELONGS_TO,'Measurement','measure_id'),
            'dishStruct'=>array(self::MANY_MANY, 'DishStructure', 'dish_structure(dish_id,prod_id)'),
            'Struct'=> array(self::HAS_MANY,'DishStructure','prod_id'),
            'stuffStruct'=>array(self::HAS_MANY,'HalfstuffStructure','prod_id'),
            'realize'=>array(self::HAS_ONE,'Realize','prod_id'),
            //'groups'=>array(self::BELONGS_TO,'GroupProd','groupProd_id'),
            'storageProd'=>array(self::HAS_ONE,'Storage','prod_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Продукт',
			'name' => 'Название',
			'measure_id' => 'Ед.Измерения',
            'groupProd_id'=>'Группа',
            'price'=>'Цена',
            'department_id' => 'Отдел',

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

		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('measure_id',$this->measure_id);
		$criteria->compare('groupProd_id',$this->groupProd_id);
		$criteria->compare('price',$this->price);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Products the static model class
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
    //получить ед.измерения по id
    public function getMeasure($id){
        $model = Yii::app()->db->createCommand()
            ->select('m.name')
            ->from('products p')
            ->join('measurement m','m.measure_id = p.measure_id')
            ->where('p.product_id = :id',array(':id'=>$id))
            ->queryRow();
        return $model['name'];
    }
    //получить id и name продуктов по их отношению тому или иному отделу
    public function getProdName($depId){
        $dish = new Dishes();
        $result = array();
        $stuff = new Halfstaff();
        $temps  = $stuff->getStuffProd($depId);
        $temp2 = explode(":",$temps['prod']);
        foreach ($temp2 as $val) {
            $model = Products::model()->findByPk($val);
            if(!empty($model))
                $result[$model->product_id] = $model->name;
        }
        $temps = $dish->getDishProd($depId);
        $temp = explode(":",$temps['prod']);
        foreach ($temp as $val) {
            $model = Products::model()->findByPk($val);
            if(!empty($model))
                $result[$model->product_id] = $model->name;
        }
        foreach (Products::model()->findAll('t.department_id = :depId', array(':depId' => $depId)) as $val) {
            $result[$val->product_id] = $val->name;
        }


        return $result;
    }
    //получить название продукта по id
    public function getName($id){
        $model = $this->model()->findByPk($id);
        return $model->name;
    }
    //получить приходную сумму продукта по его id и дате прихода
    public function getNewCostPrice($id,$dates){
        $time = microtime();
        $func = new Functions();
        $costPrice = 0;
        $dates = date('Y-m-d',strtotime($dates));
        // $curCount = $func->getCurProdCount($id,$dates);
        // $i = 0;
        // $count = 0;
        // $price = 0;
        // do{
        //     $doDate = date('Y-m-d',strtotime($dates)-86400*$i);
        //     $model = Yii::app()->db->createCommand()
        //         ->select('sum(r.count) as count, sum(r.count*r.price) as price')
        //         ->from('faktura f')
        //         ->join('realize r','r.faktura_id = f.faktura_id')
        //         ->where('date(f.realize_date) = :dates AND r.prod_id = :prod_id',array(':dates'=>$doDate,':prod_id'=>$id))
        //         ->queryRow();
        //     $count = $count + $model['count'];
        //     $price = $price + $model['price'];
        //     $i++;
        // }
        // while($count < $curCount);
        //
        // if($count != 0) {
        //     $costPrice = $price / $count;
        // }
        $model = Yii::app()->db->createCommand()
              ->select('')
              ->from('faktura f')
              ->join('realize r','r.faktura_id = f.faktura_id')
              ->where('date(f.realize_date) <= :dates AND r.prod_id = :prod_id',array(':dates'=>$dates,':prod_id'=>$id))
              ->order('f.realize_date DESC')
              ->queryRow();
              $costPrice = $model['price'];
        if($costPrice == 0){
            $model = Yii::app()->db->createCommand()
                ->select('price')
                ->from('products')
                ->where('product_id = :prod_id',array(':prod_id'=>$id))
                ->queryRow();
                $costPrice = $model['price'];
        }
        return microtime()-$time;//number_format($costPrice,0,',','');
    }

    public function getCostPrice($id,$dates){
        $time = microtime();
      $func = new Functions();
      $costPrice = 0;
      $dates = date('Y-m-d',strtotime($dates));
      $curCount = $func->getCurProdCount($id,$dates);
      $i = 0;
      $count = 0;
      $price = 0;
								// echo "<pre>";
								// print_r($curCount);
								// echo "</pre>";
								// echo "asdas";
      while($count < $curCount){
          $doDate = date('Y-m-d',strtotime($dates)-86400*$i);
          $model = Yii::app()->db->createCommand()
              ->select('sum(r.count) as count, sum(r.count*r.price) as price')
              ->from('faktura f')
              ->join('realize r','r.faktura_id = f.faktura_id')
              ->where('date(f.realize_date) = :dates AND r.prod_id = :prod_id',array(':dates'=>$doDate,':prod_id'=>$id))
              ->queryRow();
							// echo "<pre>";
							// print_r($model);
							// echo "</pre>";
          $count = $count + $model['count'];
          $price = $price + $model['price'];
          $i++;
					// echo "<pre>";
					// print_r($count);
					// echo "</pre>";
					//
					// echo "<pre>";
					// print_r($curCount);
					// echo "</pre>";
					// if($i == 10){
					// 	break;
					// }
      }

      if($count != 0) {
          $costPrice = $price / $count;
      }
			if($costPrice == 0){
					$model = Yii::app()->db->createCommand()
							->select('price')
							->from('products')
							->where('product_id = :prod_id',array(':prod_id'=>$id))
							->queryRow();
							$costPrice = $model['price'];
			}
      return number_format($costPrice,0,',','');
    }

    // получить лист используемых продуктов
    public function getUseProdList(){
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select('product_id,name')
            ->from('products')
            ->where('status = :status',array(':status'=>0))
            ->queryAll();
        foreach ($model as $val) {
            $result[$val['product_id']] = $val['name'];
        }

        return $result;
    }

    public function getNotUseProdList(){
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select('product_id,name')
            ->from('products')
            ->where('status = :status',array(':status'=>1))
            ->queryAll();
        foreach ($model as $val) {
            $result[$val['product_id']] = $val['name'];
        }

        return $result;
    }

}
