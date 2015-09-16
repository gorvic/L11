<?php
require_once 'config.php';


class Database {
	
  private $connection;
  
// Store the single instance.
  private static $instance = NULL;
  
  /**
   * Get an instance of the Database.
   * @return Database 
   */
  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new self();
    }
    return self::$instance;
  }
  
  /**
   * Constructor 
   */
  private function __construct() {
   
	$connection = DbSimple_Generic::connect('mysqli://'. DB_USER. ':' . DB_PASS.'@'.DB_SERVER .'/'.DB_NAME);
	$connection->query("SET names utf8");
	$connection->setErrorHandler('dbErrorHandler');
	$connection->setLogger('dbLogger');
	  
  }
  
  function dbErrorHandler($message, $info) {
	
	//echo "ERROR HANDLER START<br>";
	// Если использовалась @, ничего не делать.
	if (!error_reporting())
		return;
	// Выводим подробную информацию об ошибке.
	echo "SQL Error: $message<br><pre>";
	print_r($info);
	echo "</pre>";
	echo "<a href=\"install.php\">Повторить инсталляцию БД</a><br>";
	exit;	
}
  
  
  /**
   * Empty clone magic method to prevent duplication. 
   */
  private function __clone() {}
  
  /**
   * Get the mysqli connection. 
   */
  public function getConnection() {
    return $this->connection;
  }
}

