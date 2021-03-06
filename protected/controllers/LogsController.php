<?php

class LogsController extends SetupController
{
	public function actionIndex()
	{
		$this->render('index');
	}

    public function filters()
    {
        return array(
            'accessControl',
            'postOnly + delete',
            array('ext.yiibooster.filters.BootstrapFilter - delete')
        );
    }
    public function Create()
    {
        $actions = "action";
        $tableName = "tabName";
        $transaction = Yii::app()->db->beginTransaction();
        $model = new Logs;
        $model->actions = $actions;
        $model->table_name = $tableName;
        $transaction->commit();
        
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}