<?
require_once Yii::app()->basePath . '/library/printer/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
class Functions {


    public function UniqueMachineID($drive) {
//        $drive = shell_exec("wmic diskdrive get serialnumber");
//
//        $lines = explode("\n",$drive);
//        $result = $lines[1]." ".$lines[2];
        if (preg_match('#Volume Serial Number is (.*)\n#i',shell_exec('dir '.$drive.':'), $m)) {
            $volname = ' ('.$m[1].')';
        } else {
            $volname = '';
        }
        $result = $volname;
        return $result;
    }

    public function GetHash($placeName,$secretKey,$sn){
        return sha1($placeName.$secretKey.md5($sn));
    }


    public $recurseLimit = 1;
    public function multToSumProd($array,$dates){
        $result = array();
        $prod = new Products();
        if(!empty($array))
            foreach ($array as $key => $val) {
                $result[$key] = $prod->getCostPrice($key,$dates)*$val;
            }
        return array_sum($result);
    }

    public function multToSumStuff($array,$dates){
        $result = array();
        $stuff = new Halfstaff();
        if(!empty($array))
            foreach ($array as $key => $val) {
                $result[$key] = $stuff->getCostPrice($key,$dates)*$val;
            }
        return array_sum($result);
    }

    public function changeToFloat($number){
        $ss = $number;
        $arr = NULL;
        $arr = str_split($ss);
        $k = 0;
        while($k != strlen($ss))
        {
            if ($arr[$k] == ',')
                $arr[$k] = '.';
            $k++;
        }
        $ss = implode($arr);
        return $ss;
    }

    public function depMoveIn($depId,$dates,$fromDate){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $departMoveIn = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('df.real_date <= :till AND df.real_date > :from AND df.department_id = :depId AND df.fromDepId != :fromDepId ',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();

        foreach($departMoveIn as $value){
            $depIn[$value['prod_id']] = $depIn[$value['prod_id']] + $value['count'];
        }
        return $depIn;
    }

    public function depMoveOut($depId,$dates,$fromDate){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $departMoveOut = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('df.real_date <= :till AND df.real_date > :from AND df.department_id != :depId AND df.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>$depId))
            ->queryAll();
        foreach($departMoveOut as $key => $value){
            $depOut[$value['prod_id']] = $depOut[$value['prod_id']] + $value['count'];
        }
        return $depOut;
    }

    public function depInProducts($depId,$dates,$fromDate){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $inProducts = array();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('df.real_date <= :till AND df.real_date > :from AND df.department_id = :depId AND df.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
        foreach($model as $key => $val){
            $inProducts[$val['prod_id']] = $inProducts[$val['prod_id']] + $val['count'];
        }
        $Depfaktura1 = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('df.real_date BETWEEN :from AND :till AND df.fromDepId = :fromDepId AND df.department_id = 0',array(':till'=>$dates,':from'=>$fromDate,'fromDepId'=>$dep))
            ->queryAll();

        foreach($Depfaktura1 as $val){
            $inProducts[$val['prod_id']] = $inProducts[$val['prod_id']] - $val['count'];
        }

        return $inProducts;
    }

    public function depInStuff($depId,$dates,$fromDate){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $models = Yii::app()->db->createCommand()
            ->select('ino.stuff_id,ino.count as inCount')
            ->from('inexpense inexp')
            ->join('inorder ino','ino.inexpense_id = inexp.inexpense_id')
            ->where('inexp.inexp_date <= :till AND inexp.inexp_date > :from AND inexp.department_id = :depId AND inexp.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
        foreach ($models as $val) {
            $instuff[$val['stuff_id']] = $instuff[$val['stuff_id']] + $val['inCount'];
        }
        return $instuff;
    }

    public function depOutStuffProd($depId,$dates,$fromDate){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $models2 = Yii::app()->db->createCommand()
            ->select('hs.prod_id,((hs.amount/h.count)*ino.count) as count')
            ->from('inexpense inexp')
            ->join('inorder ino','ino.inexpense_id = inexp.inexpense_id')
            ->join('halfstaff h','h.halfstuff_id = ino.stuff_id')
            ->join('halfstuff_structure hs','hs.halfstuff_id = h.halfstuff_id')
            ->where('inexp.inexp_date <= :till AND inexp.inexp_date > :from AND inexp.department_id = :depId AND hs.types = :types AND inexp.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':types'=>1,':fromDepId'=>0))
            ->queryAll();

        foreach($models2 as $val){
            $outStuffProd[$val['prod_id']] = $outStuffProd[$val['prod_id']] + $val['count'];
        }
        return $outStuffProd;
    }

    public function OutStuffProd($depId,$stuff,$cnt){
        $storage = new Storage();
        $models2 = Yii::app()->db->createCommand()
            ->select('hs.prod_id,(hs.amount/h.count) as count')
            ->from('halfstaff h')
            ->join('halfstuff_structure hs','hs.halfstuff_id = h.halfstuff_id')
            ->where('h.halfstuff_id = :id',array(':id'=>$stuff))
            ->queryAll();

        foreach($models2 as $val){

            $storage->removeToStorageDep($val['prod_id'],number_format($val['count']*$cnt,2,".",""),1,$depId);
            //$outStuffProd[$val['prod_id']] = $outStuffProd[$val['prod_id']] + $val['count'];
        }
    }

    public function depOutStuff($depId,$dates,$fromDate){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $model3 = Yii::app()->db->createCommand()
            ->select('hs.prod_id,((hs.amount/h.count)*ino.count) as count')
            ->from('inexpense inexp')
            ->join('inorder ino','ino.inexpense_id = inexp.inexpense_id')
            ->join('halfstaff h','h.halfstuff_id = ino.stuff_id')
            ->join('halfstuff_structure hs','hs.halfstuff_id = h.halfstuff_id')
            ->where('inexp.inexp_date <= :till AND inexp.inexp_date > :from AND inexp.department_id = :depId AND hs.types = :types AND inexp.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':types'=>2,':fromDepId'=>0))
            ->query();
        foreach($model3 as $val){
            $outStuff[$val['prod_id']] = $outStuff[$val['prod_id']] + $val['count'];
        }
        return $outStuff;
    }

    public function stuffOtherOut($dates,$fromDate,$dep = 0){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('off o')
            ->join('offList ol','ol.off_id = o.off_id')
            ->where('o.off_date <= :dates AND o.off_date > :fromDate AND o.department_id = :depId AND ol.type = :types',array(':dates'=>$dates,':fromDate'=>$fromDate,':depId'=>$dep,':types'=>2))
            ->queryAll();
        foreach($model as $val){
            $result[$val['prod_id']] = $result[$val['prod_id']] + $val['count'];
        }
        return $result;
    }

    public function prodOtherOut($dates,$fromDate,$dep = 0){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('off o')
            ->join('offList ol','ol.off_id = o.off_id')
            ->where('o.off_date <= :dates AND o.off_date > :fromDate AND o.department_id = :depId AND ol.type = :types',array(':dates'=>$dates,':fromDate'=>$fromDate,':depId'=>$dep,':types'=>3))
            ->queryAll();
        foreach($model as $val){
            $result[$val['prod_id']] = $result[$val['prod_id']] + $val['count'];
        }
        return $result;
    }

    public function getBackingProd($dates,$fromDate,$dep = 0){
        $timeShift = $this->getTime($fromDate,$dates);
        $fromDate = $timeShift[0];
        $dates = $timeShift[1];
        $Depfaktura1 = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('df.real_date BETWEEN :from AND :till AND df.fromDepId == :fromDepId AND df.department_id = 0',array(':till'=>$dates,':from'=>$fromDate,'fromDepId'=>$dep))
            ->queryAll();

        foreach($Depfaktura1 as $val){
            $inProducts[$val['prod_id']] = $inProducts[$val['prod_id']] + $val['count'];
        }
        return $inProducts;
    }

    public function getRefuseTimes($type,$id,$dates){
        $model = Yii::app()->db->createCommand()
            ->select('unix_timestamp(orr.status_time)-unix_timestamp(orr.refuse_time) as dates')
            ->from('orders o')
            ->join('orderRefuse orr','orr.order_id = o.order_id')
            ->where('date(orr.refuse_time) = :dates AND o.type = :type AND o.just_id = :id',array(':dates'=>$dates,':type'=>$type,':id'=>$id))
            ->order('dates desc')
            ->limit(5)
            ->queryAll();

        return $model;
    }

    public function getStorageCount($dates){
        $Products = array();

        $balanceModel = Yii::app()->db->createCommand()
            ->select()
            ->from("storage s")
            ->join("products p","s.prod_id = p.product_id")
            ->queryAll();
        // баланс на утро указанного
            foreach($balanceModel as $val){
                $products[$val["prod_id"]] = $val["name"];
                $Products[$val["prod_id"]] = $Products[$val["prod_id"]] + $val["cnt"];
            }
/*
        //Приход на уквзвнную дату
        $realizedProd = Faktura::model()->with('realize.products')->findAll('date(realize_date) = :realize_date',array('realize_date'=>$dates));
        foreach($realizedProd as $value){
            foreach($value->getRelated('realize') as $val){
                $Products[$val->prod_id] = $Products[$val->prod_id] + $val->count;
            }
        }
        // перемещенные продукты по отделам на указанную дату
        $realizeStorageProd = DepFaktura::model()->with('realizedProd')->findAll('date(real_date) = :real_date AND fromDepId = :fromDepId',array(':real_date'=>$dates,':fromDepId'=>0));

        foreach($realizeStorageProd as $value){
            foreach($value->getRelated('realizedProd') as $val){
                $Products[$val->prod_id] = $Products[$val->prod_id] - $val->count;
            }
        }
        $realizeInStorageProd = DepFaktura::model()->with('realizedProd')->findAll('date(real_date) = :real_date AND fromDepId != 0 AND department_id = 0 ',array(':real_date'=>$dates));

        foreach($realizeInStorageProd as $value){
            foreach($value->getRelated('realizedProd') as $val){
                $Products[$val->prod_id] = $Products[$val->prod_id] + $val->count;
            }
        }
        // Списанные продукты на указаннуюдату
        $expBalance = Yii::app()->db->createCommand()
            ->select('ord.just_id,ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ex.kind = :kind ',array(':dates'=>$dates,':kind'=>1))
            ->queryAll();
        foreach ($expBalance as $val) {
            $Products[$val['just_id']] = $Products[$val['just_id']] - $val['count'];
        }
        // Обмен продуктов на указанную дату
        $exRec = Yii::app()->db->createCommand()
            ->select()
            ->from('exchange ex')
            ->join('exList el','el.exchange_id = ex.exchange_id')
            ->where('date(ex.exchange_date) = :dates AND ex.recived = 0',array(':dates'=>$dates))
            ->queryAll();
        foreach ($exRec as $val) {
            $Products[$val['prod_id']] = $Products[$val['prod_id']] + $val['count'];
        }

        $exSend = Yii::app()->db->createCommand()
            ->select()
            ->from('exchange ex')
            ->join('exList el','el.exchange_id = ex.exchange_id')
            ->where('date(ex.exchange_date) = :dates AND ex.recived = 1',array(':dates'=>$dates))
            ->queryAll();
        foreach ($exSend as $val) {
            $Products[$val['prod_id']] = $Products[$val['prod_id']] - $val['count'];
        }
*/
        $prod['name']=$products; $prod['id'] = $Products;
        return $prod;
    }



    public function getCurProdCount($id,$dates){
      $time = microtime();
        $count = 0;

        $Products = array();
        $storageModel = Storage::model()->findAll();
        $balanceModel = Balance::model()->find('b_date = :b_date AND prod_id = :id',array(':b_date'=>$dates,':id'=>$id));
        $Products = $balanceModel->startCount;
        // баланс на утро указанного
        //Приход на уквзвнную дату

        $realizedProd = Yii::app()->db->createCommand()
          ->select('sum(r.count) as count')
          ->from('realize r')
          ->join('faktura f','f.faktura_id = r.faktura_id')
          ->where('date(f.realize_date) = :realize_date AND r.prod_id = :id',array('realize_date'=>$dates,':id'=>$id))
          ->queryRow();
                $Products = $Products + $realizedProd['count'];
        // перемещенные продукты по отделам на указанную дату
        // Списанные продукты на указаннуюдату
        $expBalance = Yii::app()->db->createCommand()
            ->select('o.just_id,sum(o.count) as count')
            ->from('orders o')
            ->join('expense ex','o.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ex.kind = :kind AND o.just_id = :id',array(':dates'=>$dates,':kind'=>1,':id'=>$id))
            ->queryRow();
            $Products = $Products - $expBalance['count'];
        // Обмен продуктов на указанную дату
        $exRec = Yii::app()->db->createCommand()
            ->select('sum(el.count) as count')
            ->from('exList el')
            ->join('exchange ex','el.exchange_id = ex.exchange_id')
            ->where('date(ex.exchange_date) = :dates AND ex.recived = 0 AND el.prod_id = :id' ,array(':dates'=>$dates,':id'=>$id))
            ->queryRow();
            $Products = $Products + $exRec['count'];

        $exSend = Yii::app()->db->createCommand()
            ->select('sum(el.count) as count')
            ->from('exList el')
            ->join('exchange ex','el.exchange_id = ex.exchange_id')
            ->where('date(ex.exchange_date) = :dates AND ex.recived = 1 AND el.prod_id = :id',array(':dates'=>$dates,':id'=>$id))
            ->queryRow();

            $Products = $Products - $exSend['count'];
                      //
                      // echo "<pre>";
                      // print_r($Products);
                      // echo "</pre>";

        // кол-во по отделам

        $balanceDep = Yii::app()->db->createCommand()
            ->select('sum(startCount) as count')
            ->from('dep_balance')
            ->where('b_date = :dates AND prod_id = :id AND type = 1',array(':id'=>$id,':dates'=>$dates))
            ->queryRow();
        $Products = $Products + $balanceDep['count'];


        $off = Yii::app()->db->createCommand()
            ->select('sum(ol.count) as count')
            ->from('offList ol')
            ->join('off o','o.off_id = ol.off_id')
            ->where('date(o.off_date) = :dates AND ol.prod_id = :id AND ol.type = 3',array(':dates'=>$dates,':id'=>$id))
            ->queryRow();
        $Products = $Products - $off['count'];
        $count = $Products;
        // echo microtime()-$time."<br>";
        return $count;
    }

    public function getDebtorName($debtorType,$id){
        if($debtorType == 1){
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from('contractor')
                ->where('contractor_id = :id',array(':id'=>$id))
                ->queryRow();
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from('employee')
                ->where('employee_id = :id',array(':id'=>$id))
                ->queryRow();
        }
        return $model['name'];
    }

    public function getExpenseCostPrice($id,$dates){
        $summ = 0;
        $dish = new Dishes();
        $stuff = new Halfstaff();
        $prod = new Products();
        $model = Yii::app()->db->createCommand()
            ->select('ord.just_id, ord.order_id, ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('ex.expense_id = :id AND ord.type = :types AND ord.deleted != 1',array(':id'=>$id,':types'=>1))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select('ord.just_id, ord.order_id, ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('ex.expense_id = :id AND ord.type = :types AND ord.deleted != 1',array(':id'=>$id,':types'=>2))
            ->queryAll();
        $model3 = Yii::app()->db->createCommand()
            ->select('ord.just_id, ord.order_id, ord.count')
            ->from('expense ex')
            ->join('orders ord','ord.expense_id = ex.expense_id')
            ->where('ex.expense_id = :id AND ord.type = :types AND ord.deleted != 1',array(':id'=>$id,':types'=>3))
            ->queryAll();

        foreach ($model as $val) {
            $temp = $dish->getCostPrice($val['just_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('orders',array('costPrice'=>$temp),'order_id = :id',array(':id'=>$val['order_id']));
        }

        foreach ($model2 as $val) {
            $temp = $stuff->getCostPrice($val['just_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('orders',array('costPrice'=>$temp),'order_id = :id',array(':id'=>$val['order_id']));
        }

        foreach ($model3 as $val) {
            $temp = $prod->getCostPrice($val['just_id'],$dates)*$val['count'];
            $summ = $summ + $temp;
            Yii::app()->db->createCommand()->update('orders',array('costPrice'=>$temp),'order_id = :id',array(':id'=>$val['order_id']));
        }
        Yii::app()->db->createCommand()->update('expense',array('costPrice'=>$summ),'expense_id = :id',array(':id'=>$id));
        return $summ;
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
                // echo "<pre>";
                // print_r($_GET);
                // echo "</pre>";
                foreach ($id as $key => $val) {
                    $expl = explode('_',$val);
                    if($expl[0] == 'dish') {
                        $model = Yii::app()->db->createCommand()
                            ->select('d.name as dName, dep.name as depName, dep.printer as printer')
                            ->from('dishes d')
                            ->join('department dep', 'dep.department_id = d.department_id')
                            ->where('d.dish_id = :id', array(':id' => $expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $count[$key];
                        $print[$model['depName']] = $model['printer'];
                    }
                    if($expl[0] == 'stuff'){
                        $model = Yii::app()->db->createCommand()
                            ->select('h.name as dName, dep.name as depName, dep.printer as printer')
                            ->from('halfstaff h')
                            ->join('department dep','dep.department_id = h.department_id')
                            ->where('h.halfstuff_id = :id',array(':id'=>$expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $count[$key];
                        $print[$model['depName']] = $model['printer'];
                    }
                    if($expl[0] == 'product'){
                        $model = Yii::app()->db->createCommand()
                            ->select('p.name as dName, dep.name as depName, dep.printer as printer')
                            ->from('products p')
                            ->join('department dep','dep.department_id = p.department_id')
                            ->where('p.product_id = :id',array(':id'=>$expl[1]))
                            ->queryRow();
                        $result[$model['depName']][$model['dName']] = $count[$key];
                        $print[$model['depName']] = $model['printer'];
                    }
                }
        }
        if($action == 'update'){
            $archive = Yii::app()->db->createCommand()
                ->select('')
                ->from('archiveorder ao')
                ->where('ao.expense_id = :id',array(':id'=>$expId))
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
                            $core=explode(':', $val);
                            $model=Yii::app()->db->createCommand()
                                ->select('d.name as dName, dep.name as depName')
                                ->from('dishes d')
                                ->join('department dep', 'dep.department_id = d.department_id')
                                ->where('d.dish_id = :id', array(':id'=>$core[0]))
                                ->queryRow();
                            $resultArchive[$model['depName']][$model['dName']]=$core[1];
                        }
                    }
                    if ($temporary[0] == 'stuff') {
                        $dishes=explode(',', $temporary[1]);
                        foreach ($dishes as $val) {
                            $core=explode(':', $val);
                            $model=Yii::app()->db->createCommand()
                                ->select('h.name as dName, dep.name as depName')
                                ->from('halfstaff h')
                                ->join('department dep', 'dep.department_id = h.department_id')
                                ->where('h.halfstuff_id = :id', array(':id'=>$val))
                                ->queryRow();
                            $resultArchive[$model['depName']][$model['dName']]=$core[1];
                        }
                    }
                    if ($temporary[0] == 'prod') {
                        $dishes=explode(',', $temporary[1]);
                        foreach ($dishes as $val) {
                            $core=explode(':', $val);
                            $model=Yii::app()->db->createCommand()
                                ->select('p.name as dName, dep.name as depName')
                                ->from('products p')
                                ->join('department dep', 'dep.department_id = p.department_id')
                                ->where('p.product_id = :id', array(':id'=>$val))
                                ->queryRow();
                            $resultArchive[$model['depName']][$model['dName']]=$core[1];
                        }
                    }
                }
            }
            if(!empty($id))
                foreach ($id as $key => $val) {
                    $expl = explode('_',$val);
                    switch ($expl[0]){
                        case "dish":
                            $model = Yii::app()->db->createCommand()
                                ->select('d.name as dName, dep.name as depName, dep.printer as printer')
                                ->from('dishes d')
                                ->join('department dep', 'dep.department_id = d.department_id')
                                ->where('d.dish_id = :id', array(':id' => $expl[1]))
                                ->queryRow();
                            $result[$model['depName']][$model['dName']] = $count[$key];
                            $print[$model['depName']] = $model['printer'];
                            break;
                        case 'stuff':
                            $model = Yii::app()->db->createCommand()
                                ->select('h.name as dName, dep.name as depName, dep.printer as printer')
                                ->from('halfstaff h')
                                ->join('department dep','dep.department_id = h.department_id')
                                ->where('h.halfstuff_id = :id',array(':id'=>$expl[1]))
                                ->queryRow();
                            $result[$model['depName']][$model['dName']] = $count[$key];
                            $print[$model['depName']] = $model['printer'];
                            break;
                        case 'product':
                            $model = Yii::app()->db->createCommand()
                                ->select('p.name as dName, dep.name as depName, dep.printer as printer')
                                ->from('products p')
                                ->join('department dep','dep.department_id = p.department_id')
                                ->where('p.product_id = :id',array(':id'=>$expl[1]))
                                ->queryRow();
                            $result[$model['depName']][$model['dName']] = $count[$key];
                            $print[$model['depName']] = $model['printer'];
                            break;
                    }
                }


            $result = $this->ShowChange($result,$resultArchive);

        }
        foreach($result as $key => $val) {
            $date=date("Y-m-d H:i:s");
            Yii::app()->db->createCommand()->insert("print",array(
                'waiter' => Yii::app()->user->getId(),
                'table' => 0,
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
                $profile = CapabilityProfile::load("simple");
                //              $connector = new NetworkPrintConnector("XP-58", 9100);
                if(Yii::app()->config->get("printer_interface") == "usb")
                    $connector = new WindowsPrintConnector($print[$key]);
                if(Yii::app()->config->get("printer_interface") == "ethernet")
                    $connector=new NetworkPrintConnector($print[$key],9100);
                $printer=new Printer($connector,$profile);
            }

            //          $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->setTextSize(2, 1);
            $printer->text($this->transliterate(mb_convert_encoding($key , "utf-8", "auto"). "\n"));
            $printer->selectPrintMode();
            $printer->setTextSize(2, 1);
            foreach ($val as $keys=>$value) {
                Yii::app()->db->createCommand()->insert("printdetail", array(
                    'name'=>$keys,
                    'cnt'=>$value,
                    'printId'=>$lastId,
                ));
                $order = new item($keys, $value);
                $printer -> text($this->transliterate(mb_convert_encoding($order, "utf-8", "auto")));
            }
            $printer->feed();


            //          $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $footer = new item($user["name"],"stol " . $table);

            $printer->setTextSize(1, 1);
            //$printer->text($this->transliterate(mb_convert_encoding($footer, "utf-8", "auto")));
            $printer->text($this->transliterate(mb_convert_encoding("Na vinos", "utf-8", "auto")));
            $printer->selectPrintMode();


            /* Footer */
            $printer->feed(1);
            $printer->text($date . "\n");

            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();
            $printer -> getPrintConnector() -> write(PRINTER::ESC . "B" . chr(4) . chr(1));
            $printer->close();
        }
        catch (Exception $exception){
            if($limit != 3) {
                Yii::app()->db->createCommand()->insert("logs", array(
                    "log_date"=>date("Y-m-d H:i:s"),
                    "actions"=>"printException",
                    "table_name"=>"",
                    "curId"=>0,
                    "message"=>$exception->getMessage(),
                    "count"=>0
                ));
                $limit++;
                $this->PrintChecks($print,$val,$lastId,$user,$table,$key,$date,$limit);
            }
            else{
                Yii::app()->db->createCommand()->insert("logs", array(
                    "log_date"=>date("Y-m-d H:i:s"),
                    "actions"=>"printException",
                    "table_name"=>"",
                    "curId"=>0,
                    "message"=>$exception->getMessage(),
                    "count"=>0
                ));
            }
        }
    }

    public static function transliterate($textcyr = null, $textlat = null) {
        $cyr = array(
            'ё',  'ж',  'х',  'ц',  'ч',  'щ','ш',  'ъ',  'э',  'ю',  'я',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ь', 'ы',
            'Ё',  'Ж',  'Х',  'Ц',  'Ч',  'Щ','Ш',  'Ъ',  'Э',  'Ю',  'Я',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ь', 'Ы');
        $lat = array(
            'yo', 'j', 'x', 'ts', 'ch', 'sh', 'sh', '`', 'eh', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', '', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', '', 'i',
            'Yo', 'J', 'X', 'Ts', 'Ch', 'Sh', 'Sh', '`', 'Eh', 'Yu', 'Ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', '', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', '', 'I');
        if($textcyr)
            return str_replace($cyr, $lat, $textcyr);
        else if($textlat)
            return str_replace($lat, $cyr, $textlat);
        else
            return null;
    }

    public function ShowChange($array1,$array2){
        $result=array();
        if(empty($array2)){
            $result = $array1;
        }
        else {
            if (!empty($array2)) {
                foreach ($array1 as $key => $value) {
                    foreach ($value as $keys => $val) {
                        $temp = $val - $array2[$key][$keys];
                        if ($temp != 0) {
                            $result[$key][$keys] = $temp;
                        }
                    }
                }
                foreach ($array2 as $key => $value) {
                    foreach ($value as $keys => $val) {
                        $temp = $val - $array1[$key][$keys];
                        if ($temp != 0) {
                            $result[$key][$keys] = -$temp;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function getTime($from,$to){

        $fromDate = date("Y-m-d H:i:s",strtotime($from." 00:00:00")+3600);
        $toDate = date("Y-m-d H:i:s",strtotime($to." 23:59:59")+3600);

        return array($fromDate,$toDate);
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
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}