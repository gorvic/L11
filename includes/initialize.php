<?php

//roots and paths constants
require_once ('config.php');

//Smarty
require_once(SMARTY_PATH.'/libs/Smarty.class.php');
//Smarty initialization
$smarty = new Smarty();
$smarty->compile_check = true;
$smarty->debugging = false;

$smarty->template_dir = SMARTY_PATH.'/templates';
$smarty->compile_dir = SMARTY_PATH.'/templates_c';
$smarty->cache_dir = SMARTY_PATH.'/cache';
$smarty->config_dir = SMARTY_PATH.'/configs';

//firephp
require_once(LIB_PATH.'/FirePHPCore/FirePHP.class.php');
$firePHP = FirePHP::getInstance(true);
$firePHP->setEnabled(true);


//db credentials
require_once ('db_config.php');

//DBSimple
require_once(LIB_PATH.'/dbsimple/DbSimple/Generic.php');
require_once(LIB_PATH.'/dbsimple/config.php');

//db connection
require_once(LIB_PATH.'/db_connection.php');


//common functionality
//require_once("./includes/first_fill_of_tables.php");
//require_once(LIB_PATH.'/dbs_functions.php');
require_once(LIB_PATH.'/functions.php');
