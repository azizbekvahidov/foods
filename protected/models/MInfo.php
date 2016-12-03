<?php

/**
 * This is the model class for table "minfo".
 *
 * The followings are the available columns in table 'minfo':
 * @property integer $info_id
 * @property string $info_date
 * @property integer $proceed
 * @property integer $parish
 * @property integer $term
 * @property integer $azizTerm
 * @property integer $tortShams
 * @property integer $meat
 * @property integer $other
 * @property integer $kassa
 * @property integer $gosBank
 * @property integer $waitor
 */
class Minfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'minfo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('proceed, parish, term, azizTerm, tortShams, meat, other, kassa, gosBank, waitor', 'numerical', 'integerOnly'=>true),
			array('info_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('info_id, info_date, proceed, parish, term, azizTerm, tortShams, meat, other, kassa, gosBank, waitor', 'safe', 'on'=>'search'),
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
			'info_id' => 'Info',
			'info_date' => 'Info Date',
			'proceed' => 'Proceed',
			'parish' => 'Parish',
			'term' => 'Term',
			'azizTerm' => 'Aziz Term',
			'tortShams' => 'Tort Shams',
			'meat' => 'Meat',
			'other' => 'Other',
			'kassa' => 'Kassa',
			'gosBank' => 'Gos Bank',
			'waitor' => 'Waitor',
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

		$criteria->compare('info_id',$this->info_id);
		$criteria->compare('info_date',$this->info_date,true);
		$criteria->compare('proceed',$this->proceed);
		$criteria->compare('parish',$this->parish);
		$criteria->compare('term',$this->term);
		$criteria->compare('azizTerm',$this->azizTerm);
		$criteria->compare('tortShams',$this->tortShams);
		$criteria->compare('meat',$this->meat);
		$criteria->compare('other',$this->other);
		$criteria->compare('kassa',$this->kassa);
		$criteria->compare('gosBank',$this->gosBank);
		$criteria->compare('waitor',$this->waitor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Minfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
