<?php

class OrderController extends Controller
{
	public function actionIndex()
	{
		if (Yii::app()->user->name == 'admin') {
	        $users = Customer::model()->findAll();
	        $criteria=new CDbCriteria(array('order'=>'date DESC'));
	        if ($_GET['user']) {
	            $user = Customer::model()->findByPk($_GET['user']);
	            $criteria->addCondition('customer_id=:customer_id');
	            $criteria->params = array(':customer_id'=>$_GET['user']);
	        }

	        $count=Order::model()->count($criteria);
	        $pages=new CPagination($count);
	        $pages->pageSize=10;
	        $pages->applyLimit($criteria);
	        $orders=Order::model()->findAll($criteria);

	        $this->render('index', array(
	            'orders' => $orders,
	            'pages' => $pages,
	            'users' => $users,
	            'current_user' => $user,
	        ));
		} else {
			throw new CHttpException(403, 'У Вас нет прав для просмотра этой страницы.');
		}
	}


	public function actionAddtocart()
	{
		$prod = Product::model()->findByPk($_POST['id']);
		$str  = $_POST['size']; 
		$var = substr($str, 0, -1);
		$exp = explode(':',$var);
        $cnt = 0;
        $lab = '';
		foreach ($exp as $value) {
			$ex = explode(',',$value);
            $lab = $ex[0];
            $cnt = $ex[1];
			Yii::app()->LavrikShoppingCart->put($prod, $cnt, $lab);
		}

		echo '{
			"pos": "'.Yii::app()->LavrikShoppingCart->count_in_basket.'",
			"sum": "'.Yii::app()->LavrikShoppingCart->sum.'",
			"itm": "'.Yii::app()->LavrikShoppingCart->count_of_different_products.'"
		}';
	}



	public function actionCartlist()
	{
		$ShoppingList = Yii::app()->LavrikShoppingCart->getShoppingList();
		$user = Customer::model()->findByPk(Yii::app()->user->id);
		$users = Customer::model()->findAll(array('order'=>'name ASC'));
		$this->render('cartlist', array('cart' => $ShoppingList, 'user' => $user, 'users' => $users, ));
	}


	public function actionClear()
	{
		Yii::app()->LavrikShoppingCart->clear();
		$this->redirect(array('order/cartlist')); 
	}

	public function actionDeleteItem($key)
	{
		Yii::app()->LavrikShoppingCart->DDelFromBasket($key);
		$this->redirect(array('order/cartlist')); 
	}

	public function actionOrder()
	{
		function generatePassword(){
			$consonants = 'bcdfghkmnprstvxz';
			$vowels ='aeiou';
			$arr_vowels = str_split($vowels);
			$arr_consonants = str_split($consonants);
			$str = '';
			for ($i=0; $i<5; $i++) {
				$condition = $i%2;
				if ($condition) {
					$str .= $arr_vowels[array_rand($arr_vowels)];
				} else {
					$str .= $arr_consonants[array_rand($arr_consonants)];
				}
			}
			$str .= rand(1, 9).rand(1, 9);
			return $str;
		}

		// найти или созд. клиента 
			$user = Customer::model()->findByPk($_POST['id']);
		if (!$user){
			$user = new Customer;
			$user->name = $_POST['name'];
			$user->phone = $_POST['phone'];
			$user->note = $_POST['note'];
			$user->password = generatePassword();
			$user->save();
		} else {
			$user->name = $_POST['name'];
			$user->phone = $_POST['phone'];
			$user->note = $_POST['note'];
			$user->save();
		}

		//собираем заказ в json
		$shop_arr = Yii::app()->LavrikShoppingCart->getShoppingList();
		$arr_for_json = array();
		$sum = 0;
		foreach ($shop_arr as $item) {
			$prod = Product::model()->findByPk($item['id']);
			$size = Size::model()->findByAttributes(array('name' => $item['size']));
			$amount = Amount::model()->findByAttributes(array('size_id' => $size->id, 'product_id' => $prod->id));
			if ($amount->amount < $item['count']) {
				$item['count'] = $amount->amount;
			}
			$amount->amount = $amount->amount-$item['count'];
			$amount->save();
			$arr_for_json[] = array(
				'id'=>$prod->id,
				'price'=>$prod->price,
				'size'=>$item['size'],
				'count'=>$item['count'],
				'sum'=>$item['count']*$prod->price,
				'note'=>$prod->note,
				);
			$sum += $item['count']*$prod->price;
		}

		// созд. заказ
		$order = new Order;
		$order->json = json_encode($arr_for_json);
		$order->customer_id = $user->id;
		// $order->sum = Yii::app()->LavrikShoppingCart->sum;  //!!!!!!@@@
		$order->sum = $sum;  //!!!!!!@@@
		$order->date = time();
		$order->save();

		session_start();
		$_SESSION['order'] = $order->id;
		
		Yii::app()->LavrikShoppingCart->clear();

		$this->redirect(array('order/report'));
	}

	function actionReport(){
		session_start();
		$order = Order::model()->findByPk($_SESSION['order']);
		unset($_SESSION['order']);
		$this->render('report', array('order' => $order));
	}


	public function actionChangeStatus(){
		if (Yii::app()->user->name == 'admin') {
			$order = Order::model()->findByPk($_POST['id']);
			if ($order->status == 0) {
				$order->status = 1;
			} else {
				$order->status = 0;
			}
			$order->save();
			echo $order->status;
		} else {
			throw new CHttpException(403, 'У Вас нет прав для просмотра этой страницы.');
		}
	}



}

