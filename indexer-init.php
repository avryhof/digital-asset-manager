<?php
	require_once("config.inc.php");
	
	$limit = intval($_GET['limit']);
	
	$filetypes = array('jpg','jpeg','tif','tiff','raw','ai','pdf','eps','svg');
	$ignore_extensions = array('db','dmg','xls','exe');
	$ignore_filenames = array('DS_Store');
	
	$existings = $db->query("SELECT * FROM `photos`");
	$existing_files = array();
	while($existing = $existings->fetch_assoc()) {
		$existing_files[] = $existing['fullpath'];
	}

	$files = getAllFiles("./content/");
	
	$starttime = time();
	echo "<pre>Started at: " . date("Y-m-d H:i:s",$starttime) . ' ' . count($files) . " files</pre>";
	
	$i = 0;

	foreach($files as $key => $file) {
	 	$f_info = pathinfo($file);
		$f_ext = trim(strtolower($f_info['extension']));
		
		if (!in_array($file,$existing_files) && substr($f_info['basename'],0,1) !== '.' && in_array($f_ext,$filetypes) && !in_array($f_info['basename'],$ignore_filenames)) {
			
			try {
				$imagesize = imgetimagesize($file);
			} catch (Exception $e) {
    			echo "<pre>Error with file: $file\nSize:".filesize($file)."\nCaught exception: " .  $e->getMessage() . "</pre>";
			}
			
			if (isset($imagesize['properties']['tiff:timestamp'])) {
				$filedate = strtotime($imagesize['properties']['tiff:timestamp']);
			} elseif (isset($imagesize['properties']['xap:CreateDate'])) {
				$filedate = strtotime($imagesize['properties']['xap:CreateDate']);
			} elseif (isset($imagesize['properties']['xmp:CreateDate'])) {
				$filedate = strtotime($imagesize['properties']['xmp:CreateDate']);
			} elseif (isset($imagesize['properties']['date:create'])) {
				$filedate = strtotime($imagesize['properties']['date:create']);
			} else {
				$filedate = filemtime($file);
			}
			
			$image_info = array(
				"fullpath" => $file,
				"file" => $f_info['basename'],
				"path" => $f_info['dirname'],
				"name" => $f_info['filename'],
				"time" => date('Y-m-d H:i:s', $filedate),
				"type" => $f_ext,
				"mimetype" => $imagesize['mime_type'],
				"copyright" => '',
				"photographer" => '',
				"width" => $imagesize['width'],
				"height" => $imagesize['height'],
				"xresolution" => $imagesize['resolution_x'],
				"yresolution" => $imagesize['resolution_y'],
				"created" => date("Y-m-d H:i:s"),
				"updated" => date("Y-m-d H:i:s")
			);
		
			$db->insert("photos", $image_info); 
			if ($db->errno > 0) {
				die("<pre>" . $db->error . "\n" . $db->last_query . "</pre>");
			}
			$i++;
		}
		if ($i++ == $limit) {
			break;
		}
	}
	
	$endtime = time();
	echo "<pre>Done at ".date("Y-m-d H:i:s",$endtime).". Operation took ".($endtime-$starttime)." seconds.</pre>";
	echo '<p><a href="index.php">Continue</a></p>';
	
?>