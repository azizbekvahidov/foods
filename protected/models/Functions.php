<?
class Functions {
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
        $departMoveIn = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('date(df.real_date) <= :till AND date(df.real_date) > :from AND df.department_id = :depId AND df.fromDepId != :fromDepId ',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
    
        foreach($departMoveIn as $value){
            $depIn[$value['prod_id']] = $depIn[$value['prod_id']] + $value['count'];
        }
        return $depIn;
    } 
    
    public function depMoveOut($depId,$dates,$fromDate){
        $departMoveOut = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('date(df.real_date) <= :till AND date(df.real_date) > :from AND df.department_id != :depId AND df.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>$depId))
            ->queryAll();
        foreach($departMoveOut as $key => $value){
            $depOut[$value['prod_id']] = $depOut[$value['prod_id']] + $value['count'];
        }
 
        return $depOut;        
    }
    
    public function depInProducts($depId,$dates,$fromDate){
        $inProducts = array();
        $model = Yii::app()->db->createCommand()
            ->select('')
            ->from('dep_faktura df')
            ->join('dep_realize dr','dr.dep_faktura_id = df.dep_faktura_id')
            ->where('date(df.real_date) <= :till AND date(df.real_date) > :from AND df.department_id = :depId AND df.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
        foreach($model as $key => $val){
            $inProducts[$val['prod_id']] = $inProducts[$val['prod_id']] + $val['count'];
        }
        return $inProducts;
    }
    
    public function depInStuff($depId,$dates,$fromDate){
        $models = Yii::app()->db->createCommand()
            ->select('ino.stuff_id,ino.count as inCount')
            ->from('inexpense inexp')
            ->join('inorder ino','ino.inexpense_id = inexp.inexpense_id')
            ->where('date(inexp.inexp_date) <= :till AND date(inexp.inexp_date) > :from AND inexp.department_id = :depId AND inexp.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':fromDepId'=>0))
            ->queryAll();
        foreach ($models as $val) {
            $instuff[$val['stuff_id']] = $instuff[$val['stuff_id']] + $val['inCount'];
        }
        return $instuff;
    }
    
    public function depOutStuffProd($depId,$dates,$fromDate){
        $models2 = Yii::app()->db->createCommand()
            ->select('hs.prod_id,((hs.amount/h.count)*ino.count) as count')
            ->from('inexpense inexp')
            ->join('inorder ino','ino.inexpense_id = inexp.inexpense_id')
            ->join('halfstaff h','h.halfstuff_id = ino.stuff_id')
            ->join('halfstuff_structure hs','hs.halfstuff_id = h.halfstuff_id')
            ->where('date(inexp.inexp_date) <= :till AND date(inexp.inexp_date) > :from AND inexp.department_id = :depId AND hs.types = :types AND inexp.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':types'=>1,':fromDepId'=>0))
            ->queryAll();

        foreach($models2 as $val){
            $outStuffProd[$val['prod_id']] = $outStuffProd[$val['prod_id']] + $val['count'];
        }
        return $outStuffProd;
    }
    
    public function depOutStuff($depId,$dates,$fromDate){
        $model3 = Yii::app()->db->createCommand()
            ->select('hs.prod_id,((hs.amount/h.count)*ino.count) as count')
            ->from('inexpense inexp')
            ->join('inorder ino','ino.inexpense_id = inexp.inexpense_id')
            ->join('halfstaff h','h.halfstuff_id = ino.stuff_id')
            ->join('halfstuff_structure hs','hs.halfstuff_id = h.halfstuff_id')
            ->where('date(inexp.inexp_date) <= :till AND date(inexp.inexp_date) > :from AND inexp.department_id = :depId AND hs.types = :types AND inexp.fromDepId = :fromDepId',array(':till'=>$dates,':from'=>$fromDate,':depId'=>$depId,':types'=>2,':fromDepId'=>0))
            ->query();
        foreach($model3 as $val){
            $outStuff[$val['prod_id']] = $outStuff[$val['prod_id']] + $val['count'];
        }
        return $outStuff;
    }
    
    public function stuffOtherOut($dates,$fromDate,$dep = 0){
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('off o')
            ->join('offList ol','ol.off_id = o.off_id')
            ->where('date(o.off_date) <= :dates AND date(o.off_date) > :fromDate AND o.department_id = :depId AND ol.type = :types',array(':dates'=>$dates,':fromDate'=>$fromDate,':depId'=>$dep,':types'=>2))
            ->queryAll();
        foreach($model as $val){
            $result[$val['prod_id']] = $result[$val['prod_id']] + $val['count'];
        }
        return $result;
    }
    
    public function prodOtherOut($dates,$fromDate,$dep = 0){
        $result = array();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('off o')
            ->join('offList ol','ol.off_id = o.off_id')
            ->where('date(o.off_date) <= :dates AND date(o.off_date) > :fromDate AND o.department_id = :depId AND ol.type = :types',array(':dates'=>$dates,':fromDate'=>$fromDate,':depId'=>$dep,':types'=>3))
            ->queryAll();
        foreach($model as $val){
            $result[$val['prod_id']] = $result[$val['prod_id']] + $val['count'];
        }
        return $result;
    }
    
    public function getRefuseTimes($type,$id,$dates){
        $model = Yii::app()->db->createCommand()
            ->select('unix_timestamp(orr.status_time)-unix_timestamp(orr.refuse_time) as dates')
            ->from('orders o')
            ->join('orderRefuse orr','orr.order_id = o.order_id')
            ->where('date(orr.refuse_time) = :dates AND o.type = :type AND o.just_id = :id',array(':dates'=>$dates,':type'=>$type,':id'=>$id))
            ->order('dates')
            ->limit(5)
            ->queryAll();
        return $model;
    }

    public function getStorageCount($dates){
        $Products = array();
        $storageModel = Storage::model()->findAll();
        $balanceModel = Balance::model()->with('products')->findAll('b_date = :b_date',array(':b_date'=>$dates));
        // баланс на утро указанного
        if(!empty($balanceModel)){
            foreach($balanceModel as $val){
                $products[$val->prod_id] = $val->getRelated('products')->name;
                $Products[$val->prod_id] = $Products[$val->prod_id] + $val->startCount;
            }
        }
        else{
            foreach($storageModel as $val){
                $Products[$val->prod_id] = $Products[$val->prod_id] + $val->curCount;
            }

        }
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

        $prod['name']=$products; $prod['id'] = $Products;
        return $prod;
    }

    public function getCurProdCount($id,$dates){
        $count = 0;

        $Products = array();
        $storageModel = Storage::model()->findAll();
        $balanceModel = Balance::model()->find('b_date = :b_date AND prod_id = :id',array(':b_date'=>$dates,':id'=>$id));
        $Products = $balanceModel->startCount;
        // баланс на утро указанного
        //Приход на уквзвнную дату
        $realizedProd = Realize::model()->with('fakture')->findAll('date(fakture.realize_date) = :realize_date AND prod_id = :id',array('realize_date'=>$dates,':id'=>$id));
        foreach($realizedProd as $value){
                $Products = $Products + $value->count;
        }
        // перемещенные продукты по отделам на указанную дату
        $realizeStorageProd = DepRealize::model()->with('faktura')->findAll('date(faktura.real_date) = :real_date AND faktura.fromDepId = :fromDepId AND prod_id = :id',array(':real_date'=>$dates,':fromDepId'=>0,':id'=>$id));

        foreach($realizeStorageProd as $value){
                $Products = $Products - $value->count;
        }
        // Списанные продукты на указаннуюдату
        $expBalance = Yii::app()->db->createCommand()
            ->select('o.just_id,o.count')
            ->from('orders o')
            ->join('expense ex','o.expense_id = ex.expense_id')
            ->where('date(ex.order_date) = :dates AND ex.kind = :kind AND o.just_id = :id',array(':dates'=>$dates,':kind'=>1,':id'=>$id))
            ->queryAll();
        foreach ($expBalance as $val) {
            $Products = $Products - $val['count'];
        }
        // Обмен продуктов на указанную дату
        $exRec = Yii::app()->db->createCommand()
            ->select()
            ->from('exList el')
            ->join('exchange ex','el.exchange_id = ex.exchange_id')
            ->where('date(ex.exchange_date) = :dates AND ex.recived = 0 AND el.prod_id = :id' ,array(':dates'=>$dates,':id'=>$id))
            ->queryAll();
        foreach ($exRec as $val) {
            $Products = $Products + $val['count'];
        }

        $exSend = Yii::app()->db->createCommand()
            ->select()
            ->from('exList el')
            ->join('exchange ex','el.exchange_id = ex.exchange_id')
            ->where('date(ex.exchange_date) = :dates AND ex.recived = 1 AND el.prod_id = :id',array(':dates'=>$dates,':id'=>$id))
            ->queryAll();

        foreach ($exSend as $val) {
            $Products = $Products - $val['count'];
        }


        // кол-во по отделам

        $balanceDep = Yii::app()->db->createCommand()
            ->select('sum(startCount) as count')
            ->from('dep_balance')
            ->where('b_date = :dates AND prod_id = :id',array(':id'=>$id,':dates'=>$dates))
            ->queryRow();
        $Products = $Products + $balanceDep['count'];

        $depRealize = Yii::app()->db->createCommand()
            ->select('sum(count) as count')
            ->from('dep_realize dr')
            ->join('dep_faktura df','df.dep_faktura_id = dr.dep_faktura_id')
            ->where('date(df.real_date) = :dates AND dr.prod_id = :id AND df.fromDepId = 0',array(':id'=>$id,':dates'=>$dates))
            ->queryRow();
        $Products = $Products + $depRealize['count'];

        $off = Yii::app()->db->createCommand()
            ->select('sum(ol.count) as count')
            ->from('offList ol')
            ->join('off o','o.off_id = ol.off_id')
            ->where('date(o.off_date) = :dates AND ol.prod_id = :id AND ol.type = 3',array(':dates'=>$dates,':id'=>$id))
            ->queryRow();
        $Products = $Products - $off['count'];
        $count = $Products;
        return $count;
    }
}