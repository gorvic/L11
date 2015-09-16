<?php

class DatabaseObject {

  public $id;

  /**
   * build an object and populate it
   * 
   * @param array $values
   * @return class_name' object
   */
  public static function build(array $values) {

	$class_name = get_called_class();
	$object = new $class_name;
	foreach ($values as $property_name => $property_value) {
	  if (property_exists($class_name, $property_name)) {
		$object->$property_name = $property_value;
	  }
	}
	return $object;
  }

  //Searching in SQL
  /**
   * Find all records in table 
   * 
   * @return mixed
   */
  public static function find_all() {
	return static::find_by_sql("SELECT * FROM " . static::$table_name);
  }

  /**
   * Find record by id
   * 
   * @param int $id 
   * @return mixed
   */
  public static function find_by_id($id = 0) {

	$result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE id=" . (int) $id . " LIMIT 1");
	return !empty($result_array) ? array_shift($result_array) : false;
  }

  /**
   * Select records from SQL, put them into array of object
   * 
   * @global type $database Database object
   * @param string $sql any sql query
   * @return classname' object
   */
  public static function find_by_sql($sql = "") {

	global $database;

	$result_set = $database->select($sql);

	$object_array = array();
	foreach ($result_set as $row) {
	  $object_array[$row['id']] = static::instantiate($row); //add by id of record
	}
	return $object_array;
  }

  /**
   * Get array of column' value
   * 
   * @global type $database Database object
   * @param string $column_name 
   * @return array
   */
  public static function get_column_values($column_name) {

	global $database;

	$result_array = static::find_all();

	$column_values_array = array();
	foreach ($result_array as $object) {
	  $column_values_array[$object->id] = $object->$column_name;
	}
	return $column_values_array;
  }

  /**
   * Create new object and populate it with $record values
   * 
   * @param array $record
   * @return class_name's object
   */
  private static function instantiate($record) {
	// Could check that $record exists and is an array
	$class_name = get_called_class();
	$object = new $class_name;
	foreach ($record as $attribute => $value) {
	  if ($object->has_attribute($attribute)) {
		$object->$attribute = $value;
	  }
	}
	return $object;
  }

  //Working with attributes
  /**
   * Check if $attribute exists in class properties
   * 
   * @param string $attribute
   * @return boolean
   */
  private function has_attribute($attribute) {
	return array_key_exists($attribute, $this->attributes());
  }

  /**
   * Return assoc array filled with properties' values
   * 
   * @return array
   */
  protected function attributes() {
	$attributes = array();
	foreach (static::$db_fields as $field) {
	  if (property_exists($this, $field)) {
		$attributes[$field] = $this->$field;
	  }
	}
	return $attributes;
  }

  //CRUD
  /**
   * Save object into database, updating or creating it
   * 
   * @return boolean
   */
  public function save() {
	return isset($this->id) ? $this->update() : $this->create();
  }

  /**
   * Create new record in database
   * 
   * @global type $database Database' object
   * @return boolean
   */
  protected function create() {

	global $database;

	$attributes = $this->attributes();
	$sql = 'INSERT INTO ' . static::$table_name . ' (?#) VALUES(?a)';
	$last_insert_id = $database->query($sql, array_keys($attributes), array_values($attributes));

	if ($last_insert_id) {
	  $this->id = $last_insert_id;
	  return true;
	} else {
	  return false;
	}
  }

  /**
   * Update record in database
   * 
   * @global type $database Database' object
   * @return boolean
   */
  public function update() {

	global $database;

	$attributes = $this->attributes();

	$sql = "UPDATE " . static::$table_name . " SET ?a ";
	$sql .= "WHERE id = ? ";
	$sql .= "LIMIT 1";

	$affected_rows = $database->query($sql, $attributes, $this->id);
	return ($affected_rows == 1) ? true : false;
  }

  /**
   * Delete record from database
   * 
   * @global type $database Database' object
   * @return boolean
   */
  public function delete() {

	global $database;
	$sql = "DELETE FROM " . static::$table_name;
	$sql .= " WHERE id=" . intval($this->id);
	$sql .= " LIMIT 1";
	$affected_rows = $database->query($sql);
	return ($affected_rows == 1) ? true : false;

  }

}
