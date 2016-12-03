<?php

class DefaultController extends Controller
{
    public $layout='/layouts/column1';
	public function actionIndex()
	{
        $this->render('index');
	}
    public function actionAjaxData(){
        $empId = Yii::app()->user->getId();
        $depId = Employee::model()->findByPk($empId);
        $dates = date('Y-m-d');
        /*$model = Yii::app()->db->createCommand()
            ->select('mType.name as mName,emp.name as empName,dish.name,order.status')
            ->from('expense t')
            ->join('menu_type mType','mType.mType_id = t.mType')
            ->join('employee emp','emp.employee_id = t.employee_id')
            ->join('orders order','order.expense_id = t.expense_id')
            ->join('dishes dish','dish.dish_id = order.just_id')
            ->where('date(t.order_date) = :dates AND dish.department_id = :depId AND order.type = :types',array(':dates'=>$dates,':depId'=>$depId->depId,':types'=>1))
            ->queryAll();
        //*/$model = Expense::model()->with('employee','mType','order.dish')->findAll('date(t.order_date) = :dates AND dish.department_id = :depId',array(':dates'=>$dates,':depId'=>$depId->depId));
        $model2 = Expense::model()->with('employee','mType','order.halfstuff')->findAll('date(t.order_date) = :dates AND halfstuff.department_id = :depId',array(':dates'=>$dates,':depId'=>$depId->depId));
        $model3 = Expense::model()->with('employee','mType','order.products')->findAll('date(t.order_date) = :dates AND products.department_id = :depId',array(':dates'=>$dates,':depId'=>$depId->depId));

        $this->render('ajaxData',array(
            'model'=>$model,
            'model2'=>$model2,
            'model3'=>$model3
        ));
    }

    public function actionBegin($id,$empId){
        $id = explode('-',$id);
        $dates = date('Y-m-d H:i:s');
        $model = Orders::model()->findByPk($id[1]);
        $model->status = 1;
        $model->save();
        $archive = new ArchiveCook();
        $archive->action_begin = $dates;
        $archive->employee_id = $empId;
        $archive->expense_id = $id[0];
        $archive->order_id = $id[1];
        $archive->save();
    }

    public function actionEnd($id,$empId){
        $dates = date('Y-m-d H:i:s');
        $id = explode('-',$id);
        $model = Orders::model()->findByPk($id[1]);
        $model->status = 2;
        $model->save();
        $archive = ArchiveCook::model()->find('expense_id = :expId AND order_id = :order_id',array(':expId'=>$id[0],':order_id'=>$id[1]));
        $archive->action_end = $dates;
        $archive->save();
    }


}