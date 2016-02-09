<?
	require_once('config.inc.php');
	
	$file = $_GET['file'];
	
	$retn = array_merge(pathinfo($file), imgetimagesize($file));
	
	echo json_encode($retn);
?>