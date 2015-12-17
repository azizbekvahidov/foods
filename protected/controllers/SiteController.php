<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

        $dates = date('Y-m-d');
        $coockieModel = Yii::app()->db->createCommand()
            ->select('')
            ->from('coockie t')
            ->where('date(t.coockie_date) = :dates',array(':dates'=>$dates))
            ->queryAll();
        $beforeCoockie = Yii::app()->db->createCommand()
            ->select('')
            ->from('coockie t')
            ->order('t.coockie_date DESC')
            ->queryRow();
        //$coockieModel = Coockie::model()->find('date(t.coockie_date) = :dates',array(':dates'=>$dates));

        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->createUrl('site/login'));
        else {

            if(Yii::app()->user->checkAccess('1')){
                $this->redirect(Yii::app()->createUrl('order/expense/create'));
            }
            elseif(Yii::app()->user->checkAccess('0')){
                $this->redirect(Yii::app()->createUrl('/cooking'));
            }
            else {
                $this->render('index', array(
                    'coockieModel' => $coockieModel,
                    'beforeCoockie' => $beforeCoockie
                ));
            }
        }
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
    public function actionProceed(){
        $realize = '';
        $realizeSum = 0;
        $curRealize = '';
        $curRealizeSum = 0;
        $curProceed = '';
        $curAverSum = 0;
        $count = 0;
        $summ = '';
        $summP = '';
        $dateList = '';
        $averSum = 0;
        $averProcSum = 0;
        $monthRus = array(
            1=>'Янв',2=>'Фев',3=>'Мар',4=>'Апр',5=>'Май',6=>'Июн',7=>'Июл',8=>'Авг',9=>'Сен',10=>'Окт',11=>'Ноя',12=>'Дек',
        );
            $temp = explode('-',$_POST['dates']);//$_POST['month'];
            $month = $temp[1];
            $year = $temp[0];
            $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for($i = 1; $i <= $number; $i++){
            $model = Yii::app()->db->createCommand()
                ->select('procProceeds,proceeds')
                ->from('mBalance')
                ->where('b_date = :dates',array(':dates'=>$year."-".$month."-".$i))
                ->queryRow();
            $dateList .= "'" .$i."-".$monthRus[$month]."',";
            if(!empty($model)) {
                $summP .= $model["procProceeds"] . ",";
                $summ .= $model["proceeds"] . ",";
                $averSum = $averSum + $model["proceeds"];
                $averProcSum = $averProcSum + $model["procProceeds"];
            }
            else{
                $summP .= 0 . ",";
                $summ .= 0 . ",";
                $averSum = $averSum + 0;
                $averProcSum = $averProcSum + 0;
            }
            $model2 = Yii::app()->db->createCommand()
                ->select('proceed,parish')
                ->from('mInfo')
                ->where('info_date = :dates',array(':dates'=>$year."-".$month."-".$i))
                ->queryRow();

            if(!empty($model2)) {
                $curProceed .= $model2['proceed'] . ",";
                $curAverSum = $curAverSum + $model2['proceed'];
                $curRealize .= $model2['parish'].",";
                $curRealizeSum = $curRealizeSum + $model2['parish'];
            }
            else{
                $curProceed .= 0 .",";
                $curAverSum = $curAverSum + 0;
                $curRealize .= 0 .",";
                $curRealizeSum = $curRealizeSum + 0;
            }
            $model3 = Yii::app()->db->createCommand()
                ->select('fa.realize_date,sum(re.price*re.count) as Summ')
                ->from('faktura fa')
                ->join('realize re','re.faktura_id = fa.faktura_id')
                ->where('date(fa.realize_date) = :dates',array(':dates'=>$year."-".$month."-".$i))
                ->group('date(fa.realize_date)')
                ->queryRow();
            if(!empty($model3)) {
                $realize .= number_format($model3['Summ'],0,',','').",";
                $realizeSum = $realizeSum + $model3['Summ'];
            }
            else{
                $realize .= 0 . ",";
                $realizeSum = $realizeSum + 0;
            }
            $count++;
        }
            $this->renderPartial('proceed', array(
                'dateList' => $dateList,
                'averSum' => $averSum,
                'averProcSum' => $averProcSum,
                'count' => $count,
                'summP' => $summP,
                'summ' => $summ,
                'curProceed' => $curProceed,
                'curAverSum' => $curAverSum,
                'realize' => $realize,
                'curRealize' => $curRealize,
                'realizeSum' => $realizeSum,
                'curRealizeSum' => $curRealizeSum,
            ));

    }

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->renderPartial('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}