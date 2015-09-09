<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
require_once './includes/config.php'; 
require_once './includes/functions.php';

function _e($string) {
	echo $string;
}

function _x($text, $context) {
	return $text;
}

if (isset($_POST['submit'])) {

	$dbname = trim( $_POST[ 'dbname' ]  );
	$uname = trim(  $_POST[ 'uname' ]  );
	$pwd = trim(  $_POST[ 'pwd' ]  );
	$dbhost = trim( $_POST[ 'dbhost' ] );
	$drop_tables = (bool) ($_POST['droptables']);
	//$prefix = trim( $_POST[ 'prefix' ] ) );
	
	$connection = mysqli_connect($dbhost, $uname, $pwd);
  // Test if connection succeeded
  if(mysqli_connect_errno()) {
    echo("Подключение к MySQL прошло неудачно: " . 
         mysqli_connect_error() . 
         " (" . mysqli_connect_errno() . ")<br>"
    );
	echo "<a href=\"install.php\">Попробовать ещё раз</a><br>";
	exit;
  }
  
  if (!mysqli_set_charset($connection, "utf8")) {
    die('Ошибка при загрузке набора символов utf8:'.mysqli_error($connection));
  }
  
  //проверим, есть ли база
//  $query = "CREATE DATABASE IF NOT EXISTS {$dbname} CHARACTER SET utf8 COLLATE utf8_general_ci";
//  var_dump($query);
//  $result = mysqli_query($connection, $query);
//  if (!$result) {
//	  die('Ошибка создания БД:'.mysqli_error($connection));
//  }
//  
//  $query = "SHOW DATABASES LIKE '{$dbname}'";
//  $db_set = mysqli_query($connection, $query);
//if (!$row = mysqli_fetch_assoc( $db_set)) {
//	die ("Не удалось создать БД".mysqli_error($connection));
//}
  
  $result = mysqli_select_db($connection, $dbname);
  if (!$result) {
	  die('Ошибка подключения к БД:'.mysqli_error($connection));
  }

  $filename = APP_ROOT."/mysql/mysqldump.sql";
if (!file_exists($filename)) {
	echo 'Mysql dump '.$filename.' doesn\'t exist';
	exit;
}

if ($drop_tables) {
	$query = "SHOW TABLES FROM {$dbname}";
    $tables_set = mysqli_query($connection, $query);
	if ($tables_set) {
		while ($table = mysqli_fetch_row( $tables_set)) {
			$query = "DROP TABLE {$table[0]}";
			$result = mysqli_query($connection, $query);
			if (!$result) {
				die("Не смогли удалить таблицу {$table[0]}");
			}
		}
	} else {
		echo 'Не смогли выбрать таблицы из БД для удаления';
	}

}


$handle = fopen($filename, "r");
if ($handle) {
	
	$templine = '';
    while (($line = fgets($handle)) !== false) {
     
		if (substr($line, 0, 2) == '--' || $line == '')
			continue;

		$templine .= $line;
		if (substr(trim($line), -1, 1) == ';') {
			// Perform the query
			mysqli_query($connection, $templine) 
				or print('Ошибка выполнения запроса \'<strong>' . $templine . '\': ' . mysqli_error($connection) . '<br /><br />');
			// Reset temp variable to empty
			$templine = '';
		}
	}

    fclose($handle);
} else {
    die ("Не смогли открыть файл!");
} 

// в случае успеха перезапишем файл
$db_connection_file = APP_ROOT."/includes/db_config.php";
$file_content = '<?php '.PHP_EOL;
$file_content .=' define("DB_SERVER", "'.$dbhost.'");'.PHP_EOL;
$file_content .=' define("DB_USER", "'.$uname.'");'.PHP_EOL;
$file_content .=' define("DB_PASS", "'.$pwd.'");'.PHP_EOL;
$file_content .=' define("DB_NAME", "'.$dbname.'");'.PHP_EOL;
$result = file_put_contents($db_connection_file, $file_content);

if (!$result) {
	die("Не смогли обновить файл данных конфигурации БД");
}

mysqli_close($connection);
$_SESSION['Success'] = true; //устанавливаем флаг успеха
redirect_to("install.php"); //перегружаем

}
if (isset($_SESSION['Success'])) {
	echo "<a href=\"index.php\">Установка прошла успешно. Переход на главную страницу сайта</a>";
}
session_destroy(); //если попали сюда - значит уничтожаем сессию

?>

<form method="post" action="install.php">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname"><?php _e( 'Имя БД' ); ?></label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="" /></td>
			<td><?php //_e( 'The name of the database you want to run WP in.' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="uname"><?php _e( 'Пользователь' ); ?></label></th>
			<td><input name="uname" id="uname" type="text" size="25" value="<?php echo htmlspecialchars( _x( '', 'example username' ), ENT_QUOTES ); ?>" /></td>
			<td><?php //_e( 'Your MySQL username' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd"><?php _e( 'Пароль' ); ?></label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" value="<?php echo htmlspecialchars( _x( '', 'example password' ), ENT_QUOTES ); ?>" autocomplete="off" /></td>
			<td><?php //_e( '&hellip;and your MySQL password.' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost"><?php _e( 'Хост' ); ?></label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" value="localhost" /></td>
			<td> <?php //_e( 'You should be able to get this info from your web host, if <code>localhost</code> does not work.' ); ?></td>
		</tr>
<!--		<tr>
			<th scope="row"><label for="prefix"><?php // _e( 'Table Prefix' ); ?></label></th>
			<td><input name="prefix" id="prefix" type="text" value="" size="25" /></td>
			<td><?php //_e( 'If you want to run multiple WordPress installations in a single database, change this.' ); ?></td>
		</tr>-->
	</table>
	<?php if ( isset( $_GET['noapi'] ) ) { ?><input name="noapi" type="hidden" value="1" /><?php } ?>
	<input type="hidden" name="language" value="ru_RU />
	<p class="step"><input name="submit" type="submit" value="Submit" class="button button-large" /></p>
	<label><input type='checkbox' name='droptables' value='1' checked>Очищать все таблицы БД</label>
</form>

