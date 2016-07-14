<?php

/**
 * This is the model class for table "amount".
 *
 * The followings are the available columns in table 'amount':
 * @property integer $id
 * @property integer $product_id
 * @property integer $size_id
 * @property integer $amount
 */
class Amount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */

	function Amoun($prod_id, $siz_id){
		$amount = Amount::model()->findByAttributes(array('product_id' => $prod_id, 'size_id' => $siz_id, ));
		echo $amount->amount;
	}


	function LstAdm($prod_id){
		$amounts = Amount::model()->findAllByAttributes(array('product_id' => $prod_id));
		foreach ($amounts as $val) {
			echo '<span class="size-list">'.$val->size->name.'-'.$val->amount.'</span>';
		}
	}


	public function tableName()
	{
		return 'amount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, size_id, amount', 'required'),
			array('product_id, size_id, amount', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, size_id, amount', 'safe', 'on'=>'search'),
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
			'size' => array(self::BELONGS_TO, 'Size', 'size_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'size_id' => 'Size',
			'amount' => 'Amount',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('size_id',$this->size_id);
		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Amount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
