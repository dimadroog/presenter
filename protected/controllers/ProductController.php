<?php

class ProductController extends Controller
{


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
		public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index', 'test'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('form', 'upload', 'delete', 'update', 'admin', 'manageproduct'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


    public function actionIndex()
    {
        $cats = Category::model()->findAll();
        $criteria=new CDbCriteria();
        
         if ($_GET['cat']) {
            $cat = Category::model()->findByPk($_GET['cat']);
            $criteria->addInCondition('category', Category::getChild($_GET['cat']));
        }

        $count=Product::model()->count($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $prods=Product::model()->findAll($criteria);

        $this->render('index', array(
            'prods' => $prods,
            'pages' => $pages,
            'cats' => $cats,
            'current_cat' => $cat,
        ));
    }

    public function actionManageProduct()
    {
        $cats = Category::model()->findAll();
        $criteria=new CDbCriteria();
        if ($_GET['cat']) {
            $cat = Category::model()->findByPk($_GET['cat']);
            $criteria->addInCondition('category', Category::getChild($_GET['cat']));
        }

        $count=Product::model()->count($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $prods=Product::model()->findAll($criteria);

        $this->render('manageproduct', array(
            'prods' => $prods,
            'pages' => $pages,
            'cats' => $cats,
            'current_cat' => $cat,
        ));

    }


	public function actionAdmin()
	{
        $this->render('admin');
	}

	public function actionForm()
	{
		$sizes = Size::model()->findAll();
		$size_arr = array();
		foreach ($sizes as $key => $size) {
			$size_arr[] = $size->name;
		}
		$size_spans = '<div class="help-block"><span class="size-span" onclick="AddSizeToField(this)">' . implode('</span> <span class="size-span" onclick="AddSizeToField(this)">', $size_arr) . '</span></div>';
		$this->render('form', array('size_spans' => $size_spans));
	}



	public function actionUpload()
	{
// функция для ресайза
        function imageresize($im, $maximagewidth) {
            $width = imagesx($im);
            $height = imagesy($im);
            if ( $width>$maximagewidth || $height>$maximagewidth ) {
                $imagewidth = ( $width>$height ) ? $width : $height;
                $new_width = floor($width * $maximagewidth / $imagewidth);
                $new_height = floor($height * $maximagewidth / $imagewidth);
                $im2 = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($im2, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                return $im2;
            } else return $im;
        }

//создаем файл из Url-data
		$file = md5(time().rand()).'.png'; //generate file name
		$fp = fopen("images/".$file,"w"); 
		fwrite($fp, file_get_contents($_POST['img']));
		fclose($fp);

        $resource_file = imagecreatefromstring(file_get_contents("images/".$file)); //ресурс 
        $resource_sm = imageresize($resource_file, 100); //ресайз
        imagepng($resource_sm, 'images/sm_'.$file);
        $resource_md = imageresize($resource_file, 600); //ресайз
        imagepng($resource_md, 'images/md_'.$file);

// очищаем память;
        imagedestroy($resource_file);

        // echo '<img src="'.$file.'"> ';

//сохраняем экз.
		$prod = new Product;
		$prod->image = $file;
		$prod->price = $_POST['price'];
		$prod->note = $_POST['note'];
		$prod->category = $_POST['category'];
		$prod->save();

		$str  = $_POST['size']; 
		$var = substr($str, 0, -1);
		$exp = explode(':',$var);
		foreach ($exp as $value) {
			$ex = explode(',',$value);
			$size = Size::model()->findByAttributes(array('name' => $ex[0]));
			$amount = new Amount;
			$amount->product_id = $prod->id;
			$amount->size_id = $size->id;
			$amount->amount = $ex[1];
			$amount->save();
		}


		if($prod->save()){
			echo '{
				"id": "'.$prod->id.'", 
				"image": "/images/'.$prod->image.'", 
				"note": "'.$prod->note.'", 
				"price": "'.$prod->price.'",
				"category": "'.$prod->categ->name.'"
			}';
		}
	}


    public function actionUpdate($id){
        $prod = Product::model()->findByPk($id);
        $amounts = Amount::model()->findAllByAttributes(array('product_id' => $prod->id));

        if ($_POST){
            if (!empty($_POST['img'])){
                $old_filename = $prod->image;
                unlink('images/sm_'.$old_filename);
                unlink('images/md_'.$old_filename);
                unlink('images/'.$old_filename);
// функция для ресайза
                function imageresize($im, $maximagewidth) {
                    $width = imagesx($im);
                    $height = imagesy($im);
                    if ( $width>$maximagewidth || $height>$maximagewidth ) {
                        $imagewidth = ( $width>$height ) ? $width : $height;
                        $new_width = floor($width * $maximagewidth / $imagewidth);
                        $new_height = floor($height * $maximagewidth / $imagewidth);
                        $im2 = imagecreatetruecolor($new_width, $new_height);
                        imagecopyresampled($im2, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        return $im2;
                    } else return $im;
                }

//создаем файл из Url-data
                $file = md5(time().rand()).'.png'; //generate file name
                $fp = fopen("images/".$file,"w"); 
                fwrite($fp, file_get_contents($_POST['img']));
                fclose($fp);

                $resource_file = imagecreatefromstring(file_get_contents("images/".$file)); //ресурс 
                $resource_sm = imageresize($resource_file, 100); //ресайз
                imagepng($resource_sm, 'images/sm_'.$file);
                $resource_md = imageresize($resource_file, 600); //ресайз
                imagepng($resource_md, 'images/md_'.$file);

// очищаем память
                imagedestroy($resource_file);
//свойство $prod->image
                $prod->image = $file;
            }

            $prod->category = $_POST['parent_category'];
            $prod->price = $_POST['price'];
            $prod->note = $_POST['note'];
            $prod->price = $_POST['price'];
            $prod->save();

            Amount::model()->deleteAllByAttributes(array('product_id' => $id));
            $str  = $_POST['size_str']; 
            $var = substr($str, 0, -1);
            $exp = explode(':',$var);
            foreach ($exp as $value) {
                $ex = explode(',',$value);
                $size = Size::model()->findByAttributes(array('name' => $ex[0]));
                $amount = new Amount;
                $amount->product_id = $prod->id;
                $amount->size_id = $size->id;
                $amount->amount = $ex[1];
                $amount->save();
            }
            if ($prod->save()){
                $this->redirect(array('product/manageproduct'));  
            }
        } else {        

            $sizes = Size::model()->findAll();
            $size_arr = array();
            foreach ($sizes as $key => $size) {
                $size_arr[] = $size->name;
            }
            $size_spans = '<div class="help-block"><span class="size-span" onclick="AddSizeToField(this)">' . implode('</span> <span class="size-span" onclick="AddSizeToField(this)">', $size_arr) . '</span></div>';
            $this->render('update', array('product' => $prod, 'size_spans' => $size_spans, 'amounts' => $amounts));
        }
    }


    public function actionDelete($id){
        $prod = Product::model()->findByPk($id);
        $filename = $prod->image;
        Amount::model()->deleteAllByAttributes(array('product_id' => $id));
        Product::model()->deleteByPk($id);
        unlink('images/sm_'.$filename);
        unlink('images/md_'.$filename);
        unlink('images/'.$filename);
        $this->redirect(array('product/manageproduct')); 
    }



    // public function actionTest(){
    //     $id = 5;
    //     function getChildCategories($id, $cnt = 0){        
    //         static $cnt; //лочим cnt 
    //         $cat = Category::model()->findByPk($id);
    //         $cnt += count($cat->products);
    //         $cats = Category::model()->findAllByAttributes(array('parent_id' => $id));
    //         if ($cats) {
    //             if (count($cats) > 0) {
    //                 foreach ($cats as $cat){
    //                     getChildCategories($cat->id, $cnt);
    //                 }
    //             }
    //             return $cnt;
    //         } else {
    //             return count($cat->products);
    //         }
    //     }
    //     print_r(getChildCategories($id, $cnt));
    // }




    // public function actionTest(){
    //     function generatePassword(){
    //         $s = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
    //         $str = substr($s, rand(1, strlen($s)),  rand(1, strlen($s))/2);
    //         return ucfirst($str);
    //     }
    //     $us = Customer::model()->findAll();
    //     foreach ($us as $u) {
    //         $u->note = generatePassword();
    //         $u->save();
    //     }
    //     echo 1111;
    // }



	// public function actionTest(){
 //        function generatePassword(){
 //            $consonants = 'bcdfghkmnprstvxz';
 //            $vowels ='aeiou';
 //            $arr_vowels = str_split($vowels);
 //            $arr_consonants = str_split($consonants);
 //            $str = '';
 //            for ($i=0; $i<6; $i++) {
 //                $condition = $i%2;
 //                if ($condition) {
 //                    $str .= $arr_vowels[array_rand($arr_vowels)];
 //                } else {
 //                    $str .= $arr_consonants[array_rand($arr_consonants)];
 //                }
 //            }
 //            return ucfirst($str);
 //        }
 //        $us = Customer::model()->findAll();
 //        foreach ($us as $u) {
 //            $u->name = generatePassword().' '.generatePassword().'ov';
 //            $u->save();
 //        }
 //        echo 2222;
	// }





}