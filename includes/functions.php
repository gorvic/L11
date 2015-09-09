<?php

	function redirect_to($new_location) {
	  header("Location: " . $new_location);
	  exit;
	}
	
	function request_is_get() {
		return $_SERVER['REQUEST_METHOD'] === 'GET';
	}

	function request_is_post() {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

/*encode-escape functions
	 * 
	 */
	function h($string) {
		return htmlspecialchars($string);
	}

	// Sanitize for JavaScript output
	function j($string) {
		return json_encode($string);
	}

	// Sanitize for use in a URL
	function u($string) {
		return urlencode($string);
	}

/*
	MySQL functions
	 * 
	 */
	function mysql_prep($string) {
		global $connection;
		
		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}
	
	function confirm_query($result_set) {
		global $connection;
		if (!$result_set) {
			die("Ошибка запроса к базе данных. ".mysqli_error($connection));
		}
	}

	function fill_simple_table($array, $table_name, $simple_field) {
	
		global $connection;

		$query = "TRUNCATE TABLE {$table_name};";
		
		$result = mysqli_query($connection, $query);
		if (!$result) {
			echo 'Не удалось удалить таблицу '.$table_name;
			exit;
		}
		
		$query  = "INSERT INTO {$table_name} (";
		$query .= " {$simple_field} ";
		$query .= ") VALUES ";
		foreach ($array as  $city) {
			$query .= " ( '{$city}' ),";
		}
		$query = substr($query, 0, strlen($query)-1).';';
//		var_dump($query);
		
		$result = mysqli_query($connection, $query);

		if ($result) {
			echo "Таблица {$table_name} создана<br />";
		} else {
			echo "Не удалось создать таблицу {$table_name} <br />";
		}	
		
	}
	
	
	function form_errors($errors=array()) {
		$output = "";
		if (!empty($errors)) {
		  $output .= "<div class=\"error\">";
		  $output .= "Please fix the following errors:";
		  $output .= "<ul>";
		  foreach ($errors as $key => $error) {
		    $output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
		  }
		  $output .= "</ul>";
		  $output .= "</div>";
		}
		return $output;
	}
	
	function find_all_items($table_name, $order_field = '') {
		global $connection;
		
		$query  = "SELECT * ";
		$query .= "FROM {$table_name} ";
		if (!empty($order_field)) {
			$query .= " ORDER BY {$order_field} ASC";	
		}
		
		$item_set = mysqli_query($connection, $query);
		confirm_query($item_set);
		return $item_set;
	}
	
	function find_item_by_id($table_name, $id) {
		global $connection;
		
		$safe_id = mysql_prep($id);
		$query  = " SELECT * ";
		$query .= " FROM {$table_name} ";
		$query .= " WHERE id = {$safe_id}";
		$query .= " LIMIT 1";
		
//		var_dump($query);
		$item_set = mysqli_query($connection, $query);
		confirm_query($item_set);
		if($ad = mysqli_fetch_assoc($item_set)) {
			mysqli_free_result($item_set);
			return $ad;
		}	
		return array(); //[];
	}
	
	function create_new_ad($post_array) {
		global $connection;
	
		
		$columns = implode(", ",array_keys($post_array));
		foreach ($post_array as $key => $value) {
			$post_array[$key] = mysql_prep($value);
		}
		$escaped_values = array_values($post_array);
		$values  = "'".implode("','", $escaped_values)."'";
		$query = "INSERT INTO ads({$columns}) VALUES ({$values})";
		
//		var_dump($query);
		$result = mysqli_query($connection, $query);
		if (!$result) {
			die("Ошибка запроса к базе данных. ".mysqli_error($connection));
		}
	}
	
	function update_ad($id, $post_array) {
		global $connection;
		
		$query = " UPDATE ads SET ";
		foreach ($post_array as $key => $value) {
			$escaped_value = mysql_prep($value);
			$query .= "{$key} = '{$escaped_value}',"; 	
		}
		$query = substr($query, 0, strlen($query)-1);
		
		$query .= " WHERE id = {$id}";
		$query .= " LIMIT 1";
//		var_dump($query);
		$result = mysqli_query($connection, $query);
		if (!$result || mysqli_affected_rows($connection) != 1) {
			die ("При сохранении объявления произошла ошибка".mysqli_error($connection));
		}
		
	}
	
	function delete_ad($id) {
		global $connection;
		
		$safe_id = mysql_prep($id);
		$query = "DELETE FROM ads WHERE id = {$safe_id} LIMIT 1";
	
		$result = mysqli_query($connection, $query);
		if (!$result || mysqli_affected_rows($connection) != 1) {
			die ("При удаление объявления произошла ошибка".mysqli_error($connection));
		}
		
	}
	
	/*
	 * End MySQL functions
	 */
	
?>
