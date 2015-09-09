<?php
require_once 'config.php';

function dbs_find_all_items($table_name, $assoc_key, $columns ) {
	global $connection;

	$query = "SELECT id AS ARRAY_KEY, {$columns} ";
	$query .= "FROM {$table_name} ";

	if ($table_name == 'ads') {
		$item_set = $connection->select($query);
	} else {
		$item_set = $connection->selectCol($query);
	}
	
	return $item_set;
}

function dbs_find_all_subcategories() {
	global $connection;

	$query = "SELECT id, parent_id, name ";
	$query .= "FROM categories ";
	$query .= "WHERE parent_id IS NOT NULL";
	
	$item_set = $connection->select($query);

	return $item_set;
}

function dbs_find_all_categories() {
	global $connection;

	$query = "SELECT id AS ARRAY_KEY, name ";
	$query .= "FROM categories ";
	$query .= "WHERE parent_id IS NULL";
	
	$item_set = $connection->selectCol($query);

	return $item_set;
}

function dbs_find_item_by_id($table_name, $id) {
	global $connection;

	$query = " SELECT * ";
	$query .= "FROM {$table_name} ";
	$query .= "WHERE id = ?d ";
	$query .= "LIMIT 1";
	
	return $connection->selectRow($query, $id);
	
}

function dbs_create_new_ad($post_array) {
	global $connection;

	$connection->query('INSERT INTO ads (?#) VALUES(?a)', array_keys($post_array), array_values($post_array));
}

function dbs_get_max_id($table_name){
	global $connection;

	$query = " SELECT MAX(id) ";
	$query .= "FROM {$table_name} ";
	
	return (int) $connection->selectCell($query);
	
		
}

function dbs_update_ad($id, $post_array) {
	global $connection;

	$query = "UPDATE ads SET ?a ";
	$query .= "WHERE id = ? ";
	$query .= "LIMIT 1";

	$result = $connection->query($query, $post_array, $id);
	
}

function dbs_delete_ad($id) {
	global $connection;

	$query = "DELETE FROM ads WHERE id = ?d LIMIT 1";

	$result = $connection->query($query, $id);
	
}
