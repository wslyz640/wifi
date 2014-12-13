<?php
//zskj0328
header("Content-type: text/html; charset=utf-8");
define('APP_NAME', 'WIFI');
define('CONF_PATH','./Conf/');
define('RUNTIME_PATH','./Runtime/');
define('TMPL_PATH','./UI/');
define('APP_PATH','./APP/');
define('CORE','./Core/');
define('UPLOAD_PATH','./Upload/');
define('PUBLIC_PATH','./Public/');
define('DATA_PATH','./Data/');
if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value){ $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	return $value;
}
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

require(CORE.'/ThinkPHP.php');
?>