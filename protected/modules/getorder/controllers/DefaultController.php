<?php

class DefaultController extends Controller
{
    public $layout='/layouts/main';
	public function actionIndex()
	{
        $menuModel = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>0));
        $expModel = Yii::app()->db->CreateCommand()
            ->select()
            ->from("expense")
            ->where('`table` = 0 and `status` != 0 and debt != 1')
            ->queryAll();
        $model=new Expense;
        $this->render('index',array(
            'model'=>$model,
            'menuModel'=>$menuModel,
            'expModel'=>$expModel
        ));
	}
}