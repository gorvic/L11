<?php
header("Content-Type: text/html; charset=utf-8");
require_once ('./includes/initialize.php');

class Ad {
	
//	public $id;
	public $seller_name;
	public $phone;
	public $allow_mails;
	public $category_id;
	public $location_id;
	public $title;
	public $description;
	public $price;
	public $email;
	public $organization_form_id;
	
	function __construct(array $ad) {
		
//		$this->id = $ad->id;
		$this->setAdProperties($ad);
		
	}

	function __destruct() {
		$test = 1;
	}

	private function setAdProperties(array $ad) {
		
		$this->seller_name = $ad['seller_name'];
		$this->phone = $ad['phone'];
		$this->allow_mails = $ad['allow_mails'];
		$this->category_id = $ad['category_id'];
		$this->location_id = $ad['location_id'];
		$this->title = $ad['title'];
		$this->description = $ad['description'];
		$this->price = $ad['price'];
		$this->email = $ad['email'];
		$this->organization_form_id = $ad['organization_form_id'];
		
	}

	public function edit(array $ad) {
		$this->setAdProperties($ad);
	}
	
}


//header
$smarty->assign('lesson_number', 10);

//cities
$arr_cities = dbs_find_all_items('cities', 'id', 'name');
$smarty->assign('cities', $arr_cities);

//subcategories
$arr_subcategories_raw  = dbs_find_all_subcategories();
$arr_subcategories = array();
foreach ($arr_subcategories_raw as $value) {
	$arr_subcategories[$value['parent_id']][$value['id']] = $value['name'];
}
$smarty->assign('subcategories', $arr_subcategories);

//categories
$arr_categories = dbs_find_all_categories();
$smarty->assign('labels', $arr_categories);

//organization form
$arr_organization_form = array('0'=>'Частное лицо','1'=>'Организация');
$smarty->assign('organization_form', $arr_organization_form);


//имя кнопки по умолчанию
$default_button_value = 'Отправить';
$default_button_name = 'submit';
$default_edit_id = '';


$columns = 'seller_name, 
			phone,
			allow_mails,
			category_id, 
			location_id, 
			title, 
			description,
			price,
			email,
			organization_form_id';

//get advertises
$ads_array = dbs_find_all_items('ads','id', $columns);

$object_storage  = [];
foreach ($ads_array as $key => $ad) {
	$object_storage[$key] = new Ad($ad); //ключ = id для идетификации
}


if (request_is_post()) {

    $from_submit = isset($_POST['submit']);
    $from_edit = isset($_POST['edit']);
    
    

    if ($from_submit || $from_edit) {

        $tmp_post = $_POST; //можно ли сразу перезаписывать в пост или лучше через буферную переменную?

        //проверим чекбокс. Если нет галки - в ПОСТ не приходит
        if (!isset($tmp_post['allow_mails'])) {
			$tmp_post['allow_mails'] = "";
        }

        //обработка значений ПОСТ; может быть значительно сложнее
        /*foreach ($tmp_post as $key => $value) {
			$tmp_post[$key] = strip_tags($value);
        }*/

        if ($from_submit) {
			//убираем ключи, для согласования колонок в таблице sql
			unset($tmp_post['submit']); //убираем, для согласования колонок в таблице sql
			unset($tmp_post['edit_id']); //убираем, для согласования колонок в таблице sql
			
			dbs_create_new_ad($tmp_post);
			$added_id = dbs_get_max_id('ads');
			$object_storage[$added_id] = new Ad($tmp_post);
	    
		} elseif ($from_edit) {
			$id = (int)$_POST['edit_id'];
			//убираем ключи, для согласования колонок в таблице sql
			unset($tmp_post['edit_id']);
			unset($tmp_post['edit']);
			unset($tmp_post['submit']); //убираем, для согласования колонок в таблице sql
			dbs_update_ad($id, $tmp_post);
			$object_storage[$id]->edit($tmp_post) ;
        }
	
		//перезапрос GET
		//пока уберём	
		//redirect_to($_SERVER["PHP_SELF"]);
		

    }

} elseif (request_is_get()) { //пришло из ссылок

    if (isset($_GET['id']) && isset($_GET['mode'])) {

        //проверяем параметры
        $id = (int) $_GET['id'];
        $mode = strip_tags($_GET['mode']);

        if ($mode == "show") { //проставить

            //заполним массив для вывода html
			//берём объявление по id из хранилища объектов. Данные там актуальны
			$ad = $object_storage[$id];
			$smarty->assign('ad_object', $ad);
			
			//email, значение для checked
			$smarty->assign('is_allow_mail', $ad->allow_mails == 1 ? 'checked' :'');

			//город, значение для selected
			$smarty->assign('city_selected_id', $ad->location_id);

			//category, if exists selected value
			$smarty->assign('category_selected_id', $ad->category_id);

            $default_button_value = 'Записать изменения';
            $default_button_name = 'edit';
            $default_edit_id = $id; //для прописи в хидден

        } elseif ($mode == "delete") { //удалить

            //проверим, существует ли ключ в соответствии
            if (array_key_exists($id,  $ads_array)) {
				dbs_delete_ad($id);
				unset($object_storage[$id]);
				//$object_storage[$id]->delete();
				//redirect_to($_SERVER["PHP_SELF"]);
			} else {
                echo "Передан неверный ID объявления";
            }
        }
    }
}

//button
$smarty->assign('button_name', $default_button_name);
$smarty->assign('button_value', $default_button_value);
$smarty->assign('default_edit_id',$default_edit_id);

$smarty->assign('object_storage', $object_storage);
$smarty->display('index.tpl');
	
?>