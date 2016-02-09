<?php
	date_default_timezone_set('America/New_York');
	
	$debug = (!empty($_GET['debug']));
	
	$basepath = "/home/multiagd/public_html/art";
	$baseurl = "http://art.multiagmedia.com";
	
	$pagename = basename($_SERVER['PHP_SELF']);
	
	$enable_imagick = true;

	require_once("vendor/autoload.php");
	require_once("lib/functions.php");

	define('DB_HOST', 'localhost');
    define('DB_USER', 'multiagd_art');
    define('DB_NAME', 'multiagd_art');
    define('DB_PASS', '~XgUIJ56Ir^5');
    
    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

?>