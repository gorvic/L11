<?php

class Category extends DatabaseObject {

  protected static $table_name = "categories";
  protected static $db_fields = array('id', 'name', 'parent_id'); //SHOW COLUMNS FROM sometable
  protected $name;
  protected $parent_id;

  /**
   * Find all categories
   * 
   * @global type $database Database' object
   * @return array
   */
  public static function find_all_categories() {

	global $database;

	$query = "SELECT id AS ARRAY_KEY, name ";
	$query .= "FROM " . static::$table_name;
	$query .= " WHERE parent_id IS NULL";

	$item_set = $database->selectCol($query);

	return $item_set;
  }

  /**
   * Find all subcategories
   * 
   * @global type $database Database' object
   * @return array
   */
  public static function find_all_subcategories() {

	global $database;

	$query = "SELECT id, parent_id, name ";
	$query .= "FROM " . static::$table_name;
	$query .= " WHERE parent_id IS NOT NULL";

	$item_set = $database->select($query);

	return $item_set;
  }

  /**
   * Return array of subcategories in appropriate manner
   * 
   * @return array
   */
  public static function get_array_of_subcategories() {

	$arr_subcategories_raw = static::find_all_subcategories();

	$arr_subcategories = array();
	foreach ($arr_subcategories_raw as $value) {
	  $arr_subcategories[$value['parent_id']][$value['id']] = $value['name'];
	}
	return $arr_subcategories;
  }

}
