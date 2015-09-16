<?php

define('APP_ROOT', dirname(dirname(__FILE__)));
define('LIB_PATH', APP_ROOT.'/includes');
define('CLASS_PATH', APP_ROOT.'/classes');
define('SMARTY_PATH', LIB_PATH.'/smarty');

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);