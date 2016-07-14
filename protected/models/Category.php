<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 */
class Category extends CActiveRecord
{

	function Tree($parent_id){
	    function getCategory(){
	        $categories = Category::model()->findAll();
	        $return = array(); 
	        foreach ($categories as $category) {  
	            $return[$category->parent_id][] = array(
	                'id' => $category->id, 
	                'parent_id' => $category->parent_id, 
	                'name' => $category->name, 
	                ); 
	        } 
	        return $return; 
	    }
	    // $category_arr = getCategory();
	    function outTree($parent_id, $level, $category_arr) { 
	        if (isset($category_arr[$parent_id])) {
	            foreach ($category_arr[$parent_id] as $value) {
	                echo '<div class="cat-tem" id="'.$value["id"].'" style="margin-left:'.($level * 25).'px" onmouseenter="ShowManageLink(this)"><span class="name-category">'.$value["name"].'</span></div>';
	                $level = $level + 1; 
	                outTree($value["id"], $level, $category_arr);
	                $level = $level - 1;
	            } 
	        }
	    } 
	    outTree($parent_id, 0, getCategory());
	}


	function Select(){
		$categories = Category::model()->findAll();
		$str = '<option value="0">Корень каталога</option>';
		foreach ($categories as $cat) {
			$str .= '<option value="'.$cat->id.'">';
			$arr = array();
			$arr[]= $cat->name;
			do {
				$cat = Category::model()->findByPk($cat->parent_id);
				if ($cat){
					$arr[]= $cat->name; 
				}
			}
			while ($cat->parent_id > 0);
			$str .= implode($arr, ' < ');		
			$str .= '</option>';	
		}
		echo '<select class="form-control sel-cat" name="parent_category" id="id_parent_category">'.$str.'</select>';
	}


	function SelectReverse(){
		$categories = Category::model()->findAll();
		$str = '<option value="0">Корень каталога</option>';
		foreach ($categories as $cat) {
			$str .= '<option value="'.$cat->id.'">';
			$arr = array();
			$arr[]= $cat->name;
			do {
				$cat = Category::model()->findByPk($cat->parent_id);
				if ($cat){
					$arr[]= $cat->name; 
				}
			}
			while ($cat->parent_id > 0);
			$str .= implode(array_reverse($arr), ' > ');		
			$str .= '</option>';		
		}
		echo '<select class="form-control sel-cat" name="parent_category" id="id_parent_category">'.$str.'</select>';
	}


	function UpdateSelectReverse($category_id){
		$categories = Category::model()->findAll();
		$str = '<option value="0">Корень каталога</option>';
		foreach ($categories as $cat) {
			$str .= '<option value="'.$cat->id.'"';
			if ($category_id == $cat->id) {
				$str .= 'selected';
			}
			$str .= '>';	
			$arr = array();
			$arr[]= $cat->name;
			do {
				$cat = Category::model()->findByPk($cat->parent_id);
				if ($cat){
					$arr[]= $cat->name; 
				}
			}
			while ($cat->parent_id > 0);
			$str .= implode(array_reverse($arr), ' > ');		
			$str .= '</option>';		
		}
		echo '<select class="form-control sel-cat" name="parent_category" id="id_parent_category">'.$str.'</select>';
	}

	function FilterTree($page){
		$parent_id = 0;
	    function getCategory(){
	        $categories = Category::model()->findAll();
	        $return = array(); 
	        foreach ($categories as $category) {  
	            $return[$category->parent_id][] = array(
	                'id' => $category->id, 
	                'parent_id' => $category->parent_id, 
	                'name' => $category->name, 
	                'count' => count($category->products), 
	                // 'count' => Category::getCountWithChilds($category->id), 
	                ); 
	        } 
	        return $return; 
	    }
	    // $category_arr = getCategory();
	    function outTree($page, $parent_id, $level, $category_arr) { 
	        if (isset($category_arr[$parent_id])) {
	            foreach ($category_arr[$parent_id] as $value) {
	            	$params = array('cat' => $value["id"]);
	            	// if ($value["count"]){
	                	echo '<div class="cat-page" id="'.$value["id"].'" style="margin-left:'.($level * 25).'px"><a href="'.Yii::app()->createUrl($page, $params).'" class="name-category">'.$value["name"].'</a></div>';
	                	// echo '<div class="cat-page" id="'.$value["id"].'" style="margin-left:'.($level * 25).'px"><a href="'.Yii::app()->createUrl($page, $params).'" class="name-category">'.$value["name"].' ('.$value["count"].')</a></div>';
	            	// }
	                $level = $level + 1; 
	                outTree($page, $value["id"], $level, $category_arr); 
	                $level = $level - 1;
	            } 
	        }
	    } 
	    echo '<div class="cat-head"><a href="'.Yii::app()->createUrl($page).'" class="name-category"><b>Все категории</b></a></div>';
	    outTree($page, $parent_id, 0, getCategory());
	}

	function Path($id){
		if ($id > 0) {
			$cat = Category::model()->findByPk($id);
			$str = '';
			$current_cat = $cat->name;
			$arr = array();
			do {
				$cat = Category::model()->findByPk($cat->parent_id);
				if ($cat){
					$arr[] = $cat->name; 
				}
			}
			while ($cat->parent_id > 0);
			if (count($arr) > 0) {
				$str .= '<span class="text-muted">';
				$str .= implode(array_reverse($arr), ' > ');
				$str .= ' > </span>';
			}
			echo $str.$current_cat;
		} else {
			echo '<span class="text-muted">Все категории</span>';
		}
	}

    function getChild($id){        
	    function getChildCategories($id, $arr = array()){        
	        static $arr; //лочим arr 
	        $cats = Category::model()->findAllByAttributes(array('parent_id' => $id));
            $cat = Category::model()->findByPk($id);
            $arr[] = $cat->id;
            if ($cats) {
                if (count($cats) > 0) {
                    foreach ($cats as $cat){
                        getChildCategories($cat->id, $arr);
                    }
                }
                return $arr;
            } else {
                return array('0' => $id);
            }
	    }
	    return getChildCategories($id, $arr);
    }

    function getCountWithChilds($id){
	        function getCnt($id, $cnt = 0){        
	            static $cnt; //лочим cnt 
	            $cat = Category::model()->findByPk($id);
	            $cnt += count($cat->products);
	            $cats = Category::model()->findAllByAttributes(array('parent_id' => $id));
	            if ($cats) {
	                if (count($cats) > 0) {
	                    foreach ($cats as $cat){
                        	getCnt($cat->id, $cnt);
	                    }
	                }
	                return $cnt;
	            } else {
	                return count($cat->products);
	            }
	        }
	    return getCnt($id, $cnt);
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, name', 'required'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, name', 'safe', 'on'=>'search'),
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
			'products' => array(self::HAS_MANY, 'Product', 'category'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Parent',
			'name' => 'Name',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
