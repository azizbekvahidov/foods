<?php

/**
 * This is the model class for table "archiveOrder".
 *
 * The followings are the available columns in table 'archiveOrder':
 * @property integer $archive_id
 * @property string $archive_date
 * @property string $archive_action
 * @property integer $expense_id
 * @property string $archive_message
 */
class ArchiveOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'archiveOrder';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('archive_id, expense_id, empId', 'numerical', 'integerOnly'=>true),
			array('archive_action', 'length', 'max'=>100),
			array('archive_message', 'length', 'max'=>255),
			array('archive_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('archive_id, archive_date, archive_action, expense_id, archive_message, empId', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'archive_id' => 'Archive',
			'archive_date' => 'Archive Date',
			'archive_action' => 'Archive Action',
			'expense_id' => 'Expense',
			'archive_message' => 'Archive Message',
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

		$criteria->compare('archive_id',$this->archive_id);
		$criteria->compare('archive_date',$this->archive_date,true);
		$criteria->compare('archive_action',$this->archive_action,true);
		$criteria->compare('expense_id',$this->expense_id);
		$criteria->compare('archive_message',$this->archive_message,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function setArchive($action,$id,$message){
        $dates = date('Y-m-d H:i:s');
        $model = new $this;
        $model->archive_date = $dates;
        $model->archive_action  = $action;
        $model->expense_id = $id;
        $model->archive_message = $message;
        $model->empId =  Yii::app()->user->getId();
        $model->save();
    }
}
