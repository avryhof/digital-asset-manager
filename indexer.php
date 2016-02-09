<?php
	require_once("config.inc.php");
	
	$filetypes = array('jpg','jpeg','tif','tiff','raw','ai','pdf','eps','svg');
	$ignore_extensions = array('db','dmg','xls','exe');
	$ignore_filenames = array('DS_Store');
	
	$files = getAllFiles("./content/");
	
	$starttime = time();
	echo "<pre>Started at: " . date("Y-m-d H:i:s",$starttime) . ' ' . count($files) . " files</pre>";
	
	if ($debug) {
		$ftcount = count($filetypes);
		$shown = array();
		foreach($filetypes as $ftype) {
			$shown[$ftype] = 0;
		}
	}
	
	foreach($files as $key => $file) {
	 	$f_info = pathinfo($file);
		$f_ext = trim(strtolower($f_info['extension']));
		
		if (substr($f_info['basename'],0,1) !== '.' && in_array($f_ext,$filetypes) && !in_array($f_info['basename'],$ignore_filenames)) {
			
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
			
			if ($debug) {
				if ($shown[$f_ext] == 0) {
					$shown[$f_ext] = 1;
					echo "<pre>ADD\n$file\nMeta:".print_r($imagesize,true)."\nDate:".date("Y-m-d H:i:s",$filedate)."</pre>";
				}
				if (array_sum($shown) == count($shown)) {
					die();
				}
			}
			
			$image_info = array(
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
		
			$existings = $db->query("SELECT * FROM `photos` WHERE `path` = '".$f_info['dirname']."' AND `file` = '".$f_info['basename']."'");
			if ($existings->num_rows < 1) {
				$db->insert("photos", $image_info); 
			} else {
				$db->update("photos", $image_info, "`id` = ".$existing['id']);
			}
			
		}
	}
	
	$endtime = time();
	echo "<pre>Done at ".date("Y-m-d H:i:s",$endtime).". Operation took ".($endtime-$starttime)." seconds.</pre>";
	echo '<p><a href="index.php">Continue</a></p>';
	
?>