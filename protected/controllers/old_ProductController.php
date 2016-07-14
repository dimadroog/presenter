<?php

class ProductController extends Controller
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
				'actions'=>array('index', 'test'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('form','upload','delete','admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


    public function actionIndex()
    {
        $prods = Product::model()->findAll();
        $this->render('index', array('prods' => $prods));
    }

	public function actionAdmin()
	{
        $cats = Category::model()->findAll();
        $criteria=new CDbCriteria();
        if ($_GET['cat']) {
            $cat = Category::model()->findByPk($_GET['cat']);
            $criteria->addCondition('category=:category');
            $criteria->params = array(':category'=>$_GET['cat']);
        }
        // $criteria->addCondition('price=:price');
        // $criteria->params = array(':price'=>500, ':category'=>3, );

        $count=Product::model()->count($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $prods=Product::model()->findAll($criteria);

        $this->render('admin', array(
            'prods' => $prods,
            'pages' => $pages,
            'cats' => $cats,
            'current_cat' => $cat,
        ));

	}

	public function actionForm()
	{

		$sizes = Size::model()->findAll();
		$size_arr = array();
		foreach ($sizes as $key => $size) {
			$size_arr[] = $size->name;
		}
		$size_spans = '<div class="help-block"><span class="size-span" onclick="AddSizeToField(this)">' . implode('</span> <span  class="size-span" onclick="AddSizeToField(this)">', $size_arr) . '</span></div>';
		$this->render('form', array('size_spans' => $size_spans));
	}



	public function actionUpload()
	{
// функция для ресайза
        function imageresize($im, $maximagewidth = 600) {
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
        $resource_file = imageresize($resource_file); //ресайз
        $file_width = imagesx($resource_file);
        $file_height = imagesy($resource_file);

//создаем файл вод знака
        $wm_width = $file_width; // ширина файла
        $wm_height = 120+$file_height; // высота файла
        // создаем изображение, на котором будем рисовать
        $img = imagecreatetruecolor($wm_width, $wm_height);
        // цвет заливки фона
        $bgColor = 0x337AB7;
        // заливаем холст цветом $bgColor
        imagefill($img, 0, 0, $bgColor);
        // путь к шрифту
        $fontName = "static/font/verdana.ttf";
        // размер шрифта
        $fontSise = 12;
        $x = 20; // отступ слева
        $y = 30+$file_height; // отступ сверху
        // текст, который будем наносить на картинку
        $id = 55;
        $price = $_POST['price'];
        $note = $_POST['note'];
        $text = "Артикул: ".$id."\nЦена: ".$price."\nПрим.: ".$note;
        $textColor = 0xffffff; // цвет шрифта
        // нанесение текста
        imagettftext(
        $img, $fontSise, 0, $x, $y,
        $textColor, $fontName, $text
        );
        $file_text = imagepng($img, 'images/wm_'.$file);
        $resource_file_text = imagecreatefrompng('images/wm_'.$file); //ресурс


//соединяем
        imagecopymerge($resource_file_text, $resource_file, 0, 0, 0, 0, $file_width, $file_height, 100);
        imagepng($resource_file_text, 'images/wm_'.$file);


// очищаем память
        imagedestroy($img);
        imagedestroy($resource_file_text);
        imagedestroy($resource_file);


        // echo '<img src="'.$file.'"> ';
        // echo '<img src="wm_'.$file.'"> ';


//сохраняем экз.
		$prod = new Product;
		$prod->image = "/images/".$file;
		$prod->image_wm = "/images/wm_".$file;
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
				"image": "/'.$prod->image.'", 
				"image_wm": "/'.$prod->image_wm.'", 
				"note": "'.$prod->note.'", 
				"price": "'.$prod->price.'",
				"category": "'.$prod->categ->name.'"
			}';
		}
	}


	public function actionTest()
	{
        $str  = "M,2:XL,1:"; 
        $var = substr($str, 0, -1);
        $exp = explode(':',$var);
        $cnt = 0;
        $lab = '';
        foreach ($exp as $value) {
            $ex = explode(',',$value);
            $lab .= $ex[0].'; ';
            $cnt += $ex[1];
            // var_dump($ex);
        }
        // echo $cnt;
        echo $lab;
	}





}