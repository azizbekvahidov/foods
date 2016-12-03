<?php

/**
 * This is the model class for table "costs".
 *
 * The followings are the available columns in table 'costs':
 * @property integer $cost_id
 * @property string $comment
 * @property integer $user_id
 * @property string $cost_date
 * @property integer $summ
 * @property integer $contractor_id
 *
 * The followings are the available model relations:
 * @property Employee $user
 * @property Contractor $contractor
 */
class Costs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'costs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, summ, contractor_id, employee_id', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>100),
			array('cost_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cost_id, comment, user_id, cost_date, summ, contractor_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Employee', 'user_id'),
			'contractor' => array(self::BELONGS_TO, 'Contractor', 'contractor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cost_id' => '#',
			'comment' => 'Комментарий',
			'user_id' => 'Пользователь',
			'cost_date' => 'Дата расхода',
			'summ' => 'Сумма',
			'contractor_id' => 'Контрагент',
            'employee_id'=> 'Сотрудник'
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

		$criteria->compare('cost_id',$this->cost_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('cost_date',$this->cost_date,true);
		$criteria->compare('summ',$this->summ);
		$criteria->compare('contractor_id',$this->contractor_id);
        $criteria->compare('employee_id',$this->employee_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Costs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
