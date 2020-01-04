<?
class CounterController extends SetupController{
 
    // no layouts here
    public $layout = '';

    public function filters()
    {
        return array(
            'accessControl',
            'postOnly + delete',
            array('ext.yiibooster.filters.BootstrapFilter - delete')
        );
    }
    public function actionDishProd($depId,$dates)
    {   
        
    }
    public function Prod($depId,$dates)
    {   
        $dishProd = Expense::model()->with('order.dish.dishStruct.Struct')->findAll('date(order_date) = :dates AND dish.department_id = :department_id',array(':dates'=>$dates,':department_id'=>$depId));
        if(!empty($dishProd)){
            foreach($dishProd as $value){
                foreach($value->getRelated('order') as $val){
                    foreach($val->getRelated('dish')->getRelated('dishStruct') as $vals){
                        $outProduct[$vals->prod_id] = $outProduct[$vals->prod_id] + $vals->amount/$val->getRelated('dish')->count*$val->count;   
                    }
                }
            }
        }
        
        $Prod = Expense::model()->with('order.products')->findAll('date(order_date) = :dates AND products.department_id = :department_id',array(':dates'=>$dates,':department_id'=>$depId));
        if(!empty($Prod)){
            foreach($Prod as $value){
                foreach($value->getRelated('order') as $val){
                    $outProduct[$val->just_id] = $outProduct[$val->just_id] + $val->count;
                }
            }
        }
        return $outProduct;
    }
    
}