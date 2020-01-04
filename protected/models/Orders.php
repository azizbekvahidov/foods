<?php

require_once Yii::app()->basePath . '/library/printer/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $order_id
 * @property integer $expense_id
 * @property integer $just_id
 * @property integer $type
 * @property double $count
 */
class Orders extends CActiveRecord
{
    public $recurseLimit = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expense_id, just_id, type', 'numerical', 'integerOnly'=>true),
			array('count, deleted', 'numerical'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric 
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('order_id, expense_id, just_id, type, count, deleted', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'order_id' => 'Order',
			'expense_id' => 'Expense',
			'just_id' => 'Just',
			'type' => 'Type',
			'count' => 'Count',
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

		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('expense_id',$this->expense_id);
		$criteria->compare('just_id',$this->just_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
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



    public function getDish($id,$action,$expId){
        try {
            if ($id != null||$id != "") {
                $model=Yii::app()->db->createCommand()
                    ->select('d.name as dName, dep.name as depName, dep.printer as printer')
                    ->from('dishes d')
                    ->join('department dep', 'dep.department_id = d.department_id')
                    ->where('d.dish_id = :id', array(':id'=>$id))
                    ->queryRow();
                if ($model['printer'] != null||$model['printer'] != "") {
                    return $model;
                } else {
                    Yii::app()->db->createCommand()->insert("logs", array(
                        "log_date"=>date("Y-m-d H:i:s"),
                        "actions"=>"dishPrintSelect",
                        "table_name"=>"",
                        "curId"=>$id,
                        "message"=>$action."=>".$expId." No Printer name",
                        "count"=>0
                    ));
                    return 0;
                }
            } else {
                Yii::app()->db->createCommand()->insert("logs", array(
                    "log_date"=>date("Y-m-d H:i:s"),
                    "actions"=>"dishPrintSelect",
                    "table_name"=>"",
                    "curId"=>$id,
                    "message"=>$action."=>".$expId." No dishId",
                    "count"=>0
                ));
                return 0;
            }
        }
        catch (Exception $ex){
            Yii::app()->db->createCommand()->insert("logs", array(
                "log_date"=>date("Y-m-d H:i:s"),
                "actions"=>"dishPrintSelectException",
                "table_name"=>"",
                "curId"=>$id,
                "message"=>$action."=>".$expId." ".$ex->getMessage(),
                "count"=>0
            ));
        }
    }

    public function getStuff($id,$action,$expId){
        try{
            if($id != null || $id != "") {
                $model = Yii::app()->db->createCommand()
                    ->select('h.name as dName, dep.name as depName, dep.printer as printer')
                    ->from('halfstaff h')
                    ->join('department dep','dep.department_id = h.department_id')
                    ->where('h.halfstuff_id = :id',array(':id'=>$id))
                    ->queryRow();
                if ($model['printer'] != null||$model['printer'] != "") {
                    return $model;
                } else {
                    Yii::app()->db->createCommand()->insert("logs", array(
                        "log_date"=>date("Y-m-d H:i:s"),
                        "actions"=>"stuffPrintSelect",
                        "table_name"=>"",
                        "curId"=>$id,
                        "message"=>$action."=>".$expId." No Printer name",
                        "count"=>0
                    ));
                    return 0;
                }
            }
            else{
                Yii::app()->db->createCommand()->insert("logs", array(
                    "log_date"=>date("Y-m-d H:i:s"),
                    "actions"=>"stuffPrintSelect",
                    "table_name"=>"",
                    "curId"=>$id,
                    "message"=>$action."=>".$expId." No stuffId",
                    "count"=>0
                ));
                return 0;
            }
        }
        catch (Exception $ex){
            Yii::app()->db->createCommand()->insert("logs", array(
                "log_date"=>date("Y-m-d H:i:s"),
                "actions"=>"stuffPrintSelectException",
                "table_name"=>"",
                "curId"=>$id,
                "message"=>$action."=>".$expId." ".$ex->getMessage(),
                "count"=>0
            ));
        }
    }

    public function getProd($id,$action,$expId){
        try {
            if ($id != null||$id != "") {
                $model=Yii::app()->db->createCommand()
                    ->select('p.name as dName, dep.name as depName, dep.printer as printer')
                    ->from('products p')
                    ->join('department dep', 'dep.department_id = p.department_id')
                    ->where('p.product_id = :id', array(':id'=>$id))
                    ->queryRow();
                if ($model['printer'] != null||$model['printer'] != "") {
                    return $model;
                } else {
                    Yii::app()->db->createCommand()->insert("logs", array(
                        "log_date"=>date("Y-m-d H:i:s"),
                        "actions"=>"prodPrintSelect",
                        "table_name"=>"",
                        "curId"=>$id,
                        "message"=>$action."=>".$expId." No Printer name",
                        "count"=>0
                    ));
                    return 0;
                }
            } else {
                Yii::app()->db->createCommand()->insert("logs", array(
                    "log_date"=>date("Y-m-d H:i:s"),
                    "actions"=>"prodPrintSelect",
                    "table_name"=>"",
                    "curId"=>$id,
                    "message"=>$action."=>".$expId." No prodId",
                    "count"=>0
                ));
                return 0;
            }
        }
        catch (Exception $ex){
            Yii::app()->db->createCommand()->insert("logs", array(
                "log_date"=>date("Y-m-d H:i:s"),
                "actions"=>"prodPrintSelectException",
                "table_name"=>"",
                "curId"=>$id,
                "message"=>$action."=>".$expId." ".$ex->getMessage(),
                "count"=>0
            ));
        }
    }


    public function PrintCheck($expId,$action,$id,$user,$count,$table){
        $result = array();
        $depId = array();
        $archive = new ArchiveOrder();
        $resultArchive = array();
        $user = Yii::app()->db->createCommand()
            ->select('')
            ->from('employee e')
            ->where('e.employee_id = :id',array(':id'=>$user))
            ->queryRow();
        if($action == 'create'){
            if(!empty($id))
                foreach ($id as $key => $val) {
                    $expl = explode('_',$val);
                    if($expl[0] == 'dish') {
                        $model = $this->getDish($expl[1],$action,$expId);
                        if($model != 0) {
                            $result[$model['depName']][$model['dName']]=$count[$key];
                            $print[$model['depName']]=$model['printer'];
                        }
                    }
                    if($expl[0] == 'stuff'){
                        $model = $this->getStuff($expl[1],$action,$expId);
                        if($model != 0) {
                            $result[$model['depName']][$model['dName']]=$count[$key];
                            $print[$model['depName']]=$model['printer'];
                        }
                    }
                    if($expl[0] == 'product'){
                        $model = $this->getProd($expl[1],$action,$expId);
                        if($model != 0) {
                            $result[$model['depName']][$model['dName']]=$count[$key];
                            $print[$model['depName']]=$model['printer'];
                        }
                    }
                }
        }
        if($action == 'update'){
            $archive = Yii::app()->db->createCommand()
                ->select('')
                ->from('archiveorder ao')
                ->where('ao.expense_id = :id AND archive_action != "print"',array(':id'=>$expId))
                ->order('ao.archive_date DESC')
                ->limit(1,1)
                ->queryRow();
            if(!empty($archive)) {
                $temp=explode('*', $archive['archive_message']);
                foreach ($temp as $key=>$value) {
                    $temporary=explode('=>', $value);

                    if ($temporary[0] == 'dish') {
                        $dishes=explode(',', $temporary[1]);
                        foreach ($dishes as $val) {
                            if($val != "") {
                                $core=explode(':', $val);
                                $model=$this->getDish($val, $action, $expId);
                                if ($model != 0) {
                                    $resultArchive[$model['depName']][$model['dName']]=$core[1];
                                    $print[$model['depName']]=$model['printer'];
                                }
                            }
                        }
                    }
                    if ($temporary[0] == 'stuff') {
                        $dishes=explode(',', $temporary[1]);
                        foreach ($dishes as $val) {
                            if($val != "") {
                                $core=explode(':', $val);
                                $model=$this->getStuff($val, $action, $expId);
                                if ($model != 0) {
                                    $resultArchive[$model['depName']][$model['dName']]=$core[1];
                                    $print[$model['depName']]=$model['printer'];
                                }
                            }
                        }
                    }
                    if ($temporary[0] == 'prod') {
                        $dishes=explode(',', $temporary[1]);
                        foreach ($dishes as $val) {
                            if($val != "") {
                                $core=explode(':', $val);
                                $model=$this->getProd($val, $action, $expId);
                                if ($model != 0) {
                                    $resultArchive[$model['depName']][$model['dName']]=$core[1];
                                    $print[$model['depName']]=$model['printer'];
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($id))
                foreach ($id as $key => $val) {
                    $expl = explode('_',$val);
                    switch ($expl[0]){
                        case "dish":
                            $model = $this->getDish($expl[1],$action,$expId);
                            if($model != 0) {
                                $result[$model['depName']][$model['dName']]=$count[$key];
                                $print[$model['depName']]=$model['printer'];
                            }
                            break;
                        case 'stuff':
                            $model = $this->getStuff($expl[1],$action,$expId);
                            if($model != 0) {
                                $result[$model['depName']][$model['dName']]=$count[$key];
                                $print[$model['depName']]=$model['printer'];
                            }
                            break;
                        case 'product':
                            $model = $this->getProd($expl[1],$action,$expId);
                            if($model != 0) {
                                $result[$model['depName']][$model['dName']]=$count[$key];
                                $print[$model['depName']]=$model['printer'];
                            }
                            break;
                    }
                }
            $result = $this->ShowChange($result,$resultArchive);
        }
        foreach($result as $key => $val) {

            $date=date("Y-m-d H:i:s");
            Yii::app()->db->createCommand()->insert("print",array(
                'waiter' => $user["name"],
                'table' => $table,
                'printTime' => $date,
                'department' => $key." - ".$action,
                'printer' => $print[$key],
            ));
            $lastId = Yii::app()->db->getLastInsertID();
            $this->PrintChecks($print,$val,$lastId,$user,$table,$key,$date, $this->recurseLimit);

        }

    }
    public function PrintChecks($print,$val,$lastId,$user,$table,$key,$date, $limit){
        try {
            if (!empty($print[$key])) {

//                $profile = CapabilityProfile::load("simple");
                //              $connector = new NetworkPrintConnector("XP-58", 9100);
                if(Yii::app()->config->get("printer_interface") == "usb")
                    $connector = new WindowsPrintConnector($print[$key]);
                if(Yii::app()->config->get("printer_interface") == "ethernet")
                    $connector=new NetworkPrintConnector($print[$key],9100);
                $printer=new Printer($connector);
                //          $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer->setTextSize(2, 2);
                $printer->text($this->transliterate($key . "\n"));
                $printer->selectPrintMode();

                $printer->setTextSize(1, 1);
                foreach ($val as $keys=>$value) {
                    Yii::app()->db->createCommand()->insert("printdetail", array(
                        'name'=>$keys,
                        'cnt'=>$value,
                        'printId'=>$lastId,
                    ));
                    $order = new item($keys, $value);
                    $printer -> text($this->transliterate($order));
                }
                $printer->feed();


                //          $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $footer = new item($user["name"],"stol " . $table);

                $printer->setTextSize(1, 1);
                $printer->text($this->transliterate($footer));
                $printer->selectPrintMode();


                /* Footer */
                $printer->feed(1);
                $printer->text($date . "\n");
                //$printer->text("------------------" . "\n");
                $printer->feed(2);

                /* Cut the receipt and open the cash drawer */
                $printer->cut();
                $printer->pulse();
                $printer -> getPrintConnector() -> write(PRINTER::ESC . "B" . chr(4) . chr(1));
                $printer->close();
            }
            else{
                Yii::app()->db->createCommand()->insert("logs", array(
                    "log_date"=>date("Y-m-d H:i:s"),
                    "actions"=>"printException",
                    "table_name"=>"",
                    "curId"=>0,
                    "message"=>"Printer name  is empty",
                    "count"=>0
                ));
            }

        }
        catch (Exception $exception){
//            if($limit != 3) {
//                Yii::app()->db->createCommand()->insert("logs", array(
//                    "log_date"=>date("Y-m-d H:i:s"),
//                    "actions"=>"printException",
//                    "table_name"=>"",
//                    "curId"=>0,
//                    "message"=>$exception->getMessage(),
//                    "count"=>0
//                ));
//                $limit++;
//                $this->PrintChecks($print,$val,$lastId,$user,$table,$key,$date,$limit);
//            }
//            else{
//                Yii::app()->db->createCommand()->insert("logs", array(
//                    "log_date"=>date("Y-m-d H:i:s"),
//                    "actions"=>"printException",
//                    "table_name"=>"",
//                    "curId"=>0,
//                    "message"=>$exception->getMessage(),
//                    "count"=>0
//                ));
//            }
        }
    }

    public static function transliterate($textcyr = null, $textlat = null) {
        $cyr = array(
            'ё',  'ж',  'х',  'ц',  'ч',  'щ','ш',  'ъ',  'э',  'ю',  'я',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ь','ы',
            'Ё',  'Ж',  'Х',  'Ц',  'Ч',  'Щ','Ш',  'Ъ',  'Э',  'Ю',  'Я',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ь','Ы');
        $lat = array(
            'yo', 'j', 'x', 'ts', 'ch', 'sh', 'sh', '`', 'eh', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', '','i',
            'Yo', 'J', 'X', 'Ts', 'Ch', 'Sh', 'Sh', '`', 'Eh', 'Yu', 'Ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', '','I');
        if($textcyr)
            return str_replace($cyr, $lat, $textcyr);
        else if($textlat)
            return str_replace($lat, $cyr, $textlat);
        else
            return null;
    }

    public function ShowChange($array1,$array2){
        $result=array();
        if(!empty($array2)) {
            foreach ($array1 as $key=>$value) {
                foreach ($value as $keys=>$val) {
                    $temp=$val - $array2[$key][$keys];
                    if ($temp != 0) {
                        $result[$key][$keys]=$temp;
                    }
                }
            }
            foreach ($array2 as $key=>$value) {
                foreach ($value as $keys=>$val) {
                    $temp=$val - $array1[$key][$keys];
                    if ($temp != 0) {
                        $result[$key][$keys]=-$temp;
                    }
                }
            }
        }
        return $result;
    }



    public function setArchive($action,$id,$message,$emp){
        $dates = date('Y-m-d H:i:s');
        $model = new ArchiveOrder();
        $model->archive_date = $dates;
        $model->archive_action  = $action;
        $model->expense_id = $id;
        $model->archive_message = $message;
        $model->empId =  $emp;
        $model->save();
    }

}

class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 30;
//        $rightCols = 10;
//        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
