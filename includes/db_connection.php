<?php

	// Устанавливаем соединение.
	$connection = DbSimple_Generic::connect('mysqli://'. DB_USER. ':' . DB_PASS.'@'.DB_SERVER .'/'.DB_NAME);
	$connection->query("SET names utf8");
	$connection->setErrorHandler('dbErrorHandler');
	$connection->setLogger('dbLogger');
	
	// Код обработчика ошибок SQL.
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

function dbLogger($db, $sql, $caller)
{
	global $firePHP;
	
	//echo "LOGGER START<br>";
	
	if (isset($caller['file'])) {
		$tip = "at ".$caller['file'].' line '.$caller['line'];
		$firePHP->group($tip);
	}
	$firePHP->log($sql);
	//echo $sql;
	if (isset($caller['file'])) {
		$firePHP->groupEnd();
	}
}

$database = & $connection;