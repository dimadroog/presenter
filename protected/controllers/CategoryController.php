<?php

class CategoryController extends Controller
{

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

		public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','change','create','delete','rename'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionIndex()
	{
		$categories = Category::model()->findAll();
		$this->render('index', array('categories' => $categories));
	}


	public function actionChange()
	{
		$id = $_POST['id'];
		$parent_id = $_POST['par_id'];
		$category = Category::model()->findByPk($id);
		$category->parent_id = $parent_id;
		$category->save();

		$all_child = Category::model()->findAllByAttributes(array('parent_id' => $category->id));
		foreach ($all_child as $child) {
			if ($category->id == $child->parent_id){
				$category->parent_id = 0;
				$category->save();
				break;
			}
		}
	}

	public function actionCreate()
	{
		$name = $_POST['name'];
		$parent_id = $_POST['parent_category'];
		$category = new Category;
		$category->name = $name;
		$category->parent_id = $parent_id;
		$category->save();

		if($category->save()){
			echo '{
				"parent_id": "'.$category->parent_id.'", 
				"name": "'.$category->name.'", 
				"id": "'.$category->id.'"
			}';
		}
	}

	public function actionDelete()
	{
		$id = $_POST['id'];
		$category = Category::model()->findByPk($id);
		// $category->name = 'del';
		$category->delete();

		$childs = Category::model()->findAllByAttributes(array('parent_id' => $category->id));

		function fn($childs){
			
			foreach ($childs as $child) {
				$childs = Category::model()->findAllByAttributes(array('parent_id' => $child->id));
				// $child->name = 'del too';
				$child->delete();
				fn($childs);
			}
		}
		fn($childs);
	}


	public function actionRename()
	{
		$id = $_POST['id'];
		$category = Category::model()->findByPk($id);
		$category->name = $_POST['name'];;
		$category->save();

		echo $category->name;
	}




}