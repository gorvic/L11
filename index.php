<?php

header("Content-Type: text/html; charset=utf-8");
require_once ('./includes/initialize.php');

function __autoload($class_name) {

  if ($class_name != 'DbSimple_Mysqli' && $class_name != 'Smarty') {
	require_once CLASS_PATH . '/class.' . $class_name . '.php';
  }
}

$smarty->assign('lesson_number', 10);
$smarty->assign('organization_form', array('0' => 'Частное лицо', '1' => 'Организация'));
$smarty->assign('cities', City::get_column_values('name'));
$smarty->assign('labels', Category::find_all_categories());
$smarty->assign('subcategories', Category::get_array_of_subcategories());

$object_storage = Ad::find_all();

$edit_id = '';
if (request_is_post()) {

  $tmp_post = $_POST;

  //escape POST array; can be more complex
  foreach ($tmp_post as $key => $value) {
    $tmp_post[$key] = strip_tags($value);
  } 

  //checking checkbox. if it's not checked then there is no value in POST array
  if (!isset($tmp_post['allow_mails'])) {
	$tmp_post['allow_mails'] = "";
  }
  
  $ad = Ad::build($tmp_post); //create new object 
  $ad->save(); //save object in database
  $object_storage[$ad->id] = $ad; //add object to storage
  
} elseif (request_is_get()) { //пришло из ссылок
  
  if (isset($_GET['id']) && isset($_GET['mode'])) {

	$id = (int) $_GET['id'];
	$mode = strip_tags($_GET['mode']);

	if ($mode == "show") { 
	  
	  $ad = $object_storage[$id];
	  
	  $smarty->assign('ad_object', $ad);
	  $smarty->assign('is_allow_mail', $ad->allow_mails == 1 ? 'checked' : '');
	  $smarty->assign('city_selected_id', $ad->location_id);
	  $smarty->assign('category_selected_id', $ad->category_id);
	  
	  $edit_id = $ad->id;
	 
	  
	} elseif ($mode == "delete") { 
	
	  if (array_key_exists($id, $object_storage)) {
		
		$object_storage[$id]->delete();
		unset($object_storage[$id]);
		
	  } else {
		
		echo "Передан неверный ID объявления";
		
	  }
	}
  }
}

//button

$smarty->assign('object_storage', $object_storage);

if ($edit_id) {
   
  $smarty->assign('button_name', 'edit');
  $smarty->assign('button_value', 'Записать изменения');
  $smarty->assign('default_edit_id', $edit_id);
  
} else {
  
   $smarty->assign('button_name', 'submit');
   $smarty->assign('button_value', 'Добавить');
   $smarty->assign('default_edit_id', '');
  
}

$smarty->display('index.tpl');
?>