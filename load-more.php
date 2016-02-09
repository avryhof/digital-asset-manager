<?
	require_once("config.inc.php");
	
	$lastId = intval($_GET['lastId']);
	
	$table = "photos";
	$columns = array('name','file','description','tags');
	
	$qty = 7;
	$photos = $db->query("SELECT * FROM `$table` WHERE `id` > $lastId ORDER BY `id` LIMIT $qty");
	
	$retn = array();
	while($photo = $photos->fetch_assoc()) {
		$photo['filesize'] = filesize_human($photo['path'] . DIRECTORY_SEPARATOR . $photo['file']);
		$retn[] = $photo;
	}
	
	echo json_encode($retn);
?>