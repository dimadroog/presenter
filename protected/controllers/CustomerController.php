<?php

class CustomerController extends Controller
{

	public function actionIndex()
	{
		if (Yii::app()->user->name == 'admin') {
	        // $criteria=new CDbCriteria();
	        $criteria=new CDbCriteria(array('order'=>'name ASC'));
	        $count=Customer::model()->count($criteria);
	        $pages=new CPagination($count);
	        $pages->pageSize=30;
	        $pages->applyLimit($criteria);
	        $users = Customer::model()->findAll($criteria);

	        $this->render('index', array(
	            'pages' => $pages,
	            'users' => $users,
	        ));
		} else {
			throw new CHttpException(403, 'У Вас нет прав для просмотра этой страницы.');
		}
	}




	public function actionProfile($id){
		if ((Yii::app()->user->name != 'Guest') || ($id == Yii::app()->user->id) || (Yii::app()->user->name == 'admin')) {
			$user = Customer::model()->findByPk($id);
			if (!$user) {
				throw new CHttpException(404, 'Такого пользователя не существует.');
			}
			
			// orders criteria
			$attribs = array('customer_id' => $user->id);
			$criteria = new CDbCriteria(array('order'=>'date DESC'));
			$orders = Order::model()->FindAllByAttributes($attribs, $criteria);

			// total_sum criteria
			$criteria=new CDbCriteria;
			$criteria->select='sum(sum) as sum'; 
			$criteria->condition='customer_id=:customer_id';
			$criteria->params=array(':customer_id'=>$user->id);
			$total_sum = Order::model()->find($criteria)->getAttribute('sum'); 

			$this->render('profile', array('user' => $user, 'total_sum' => $total_sum, 'orders' => $orders));
		} else {
			throw new CHttpException(403, 'Доступ к этой страце запрещен. Страница пользователя не соответствует Вашим авторизацонным данным.');
		}
	}

	public function actionLogin(){
		$message = '';
		if ($_POST) {			
	        $pass = $_POST['pass'];
	        $user = Customer::model()->findByAttributes(array('password' => $pass));
	        if ($user) {
	        	Yii::app()->user->id = $user->id;
	      	    $this->redirect(array('customer/profile/'.$user->id));
	        } else {
    			$message = 'Пароль не верен.';
	        }
		}
        // echo $user->name;

        $this->render('login', array('message' => $message));
    }


	public function actionChangeData($id){
        if ((Yii::app()->user->name != 'Guest') || ($id == Yii::app()->user->id) || (Yii::app()->user->name == 'admin')) {
	        $user = Customer::model()->findByPk($id);
			if ($_POST) {
				$user->name = $_POST['name'];
				$user->phone = $_POST['phone'];
				$user->note = $_POST['note'];
				$user->save();
				Yii::app()->user->setFlash('changedata', 'Данные успешно изменены! Имя - '.$user->name.'; Телефон - '.$user->phone.';');
				$this->redirect(array('customer/profile/'.$user->id));
			}		
	        $this->render('changedata', array('user' => $user));
		} else {
			throw new CHttpException(403, 'Доступ к этой страце запрещен. Страница пользователя не соответствует Вашим авторизацонным данным.');
		}
    }

	public function actionChangePass($id){
        if ((Yii::app()->user->name != 'Guest') || ($id == Yii::app()->user->id) || (Yii::app()->user->name == 'admin')) {
	        $user = Customer::model()->findByPk($id);
			if ($_POST) {
				if ($_POST['pass'] == $user->password) {
					$user->password = $_POST['pass2'];
					$user->save();
					Yii::app()->user->setFlash('changepass', 'Пароль успешно изменен!');
					$this->redirect(array('customer/profile/'.$user->id));
				} else {
					$message = 'Старый пароль не верен.';
				}
			}		
	        $this->render('changepass', array('user' => $user, 'message' => $message));
		} else {
			throw new CHttpException(403, 'Доступ к этой страце запрещен. Страница пользователя не соответствует Вашим авторизацонным данным.');
		}
    }

        public function actionDelete(){
		if (Yii::app()->user->name == 'admin') {
	        Customer::model()->deleteByPk($_POST['id']);
            Order::model()->deleteAllByAttributes(array('customer_id' => $_POST['id']));
	        echo 'ok';
		} else {
			throw new CHttpException(403, 'У Вас нет прав для просмотра этой страницы.');
		}
    }


}