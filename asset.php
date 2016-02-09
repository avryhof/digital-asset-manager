<?
	require_once("config.inc.php");
	
	$web_friendly = array("jpg","png","gif");
	$not_web_fiendly = array('tif' => 'jpg', 'eps' => 'png', 'ai' => 'png', 'svg' => 'png', 'pdf' => 'jpg', 'psd' => 'jpg');
	
	$action = trim(strtolower((isset($_GET['action']) ? $_GET['action'] : "thumbnail")));
	
	if ($action == "thumbnail" || $action == "web") {
		$width = (isset($_GET['width']) ? intval($_GET['width']) : 400);
	} else {
		$width = intval($_GET['width']);
	}
	
	$cache = $basepath."/cache";
	
	$id = intval($_GET['id']);
	$photo = $db->query("SELECT * FROM `photos` WHERE `id` = $id")->fetch_assoc();
	
	$f_info = pathinfo($photo['fullpath']);
	$f_ext = trim(strtolower($f_info['extension']));
	$output = (!empty($_GET['output']) ? $_GET['output'] : $f_ext);
	
	if ($action == "web" && !in_array($f_ext,$web_friendly)) { $output = $not_web_fiendly[$f_ext]; }
	if ($output == "jpeg") { $output = "jpg"; }
	if ($output == "ai") { $output = "pdf"; }
	
	$cachefile = $cache."/".$photo['id']."-".$width.".".$output;
	
	if ($debug) {
		echo "<pre>" . print_r($_GET,true) . "\n\n" . print_r($photo,true) . "\n\nOutput: $output\n\nCacheFile: $cachefile</pre>";
	}
	
	if (file_exists($cachefile) && (filesize($cachefile) > 0) && (filemtime($cachefile) > strtotime($photo['updated'])) ) {
		if ($debug) { echo("<pre>Cache File Exists</pre>"); }
		
		if ($action == "download") {
			download_file($photo['file'], $cachefile);
		} else {
			display_file($cachefile);
		}
		
	} elseif ($photo['type'] == $output && $width == 0) {
		if ($debug) { die("<pre>Display or Download original file.</pre>"); }
		
		if ($action == "download") {
			download_file($photo['file'], $photo['fullpath'], $photo['mimetype']);
		} else {
			display_file($photo['fullpath']);
		}
		
	} else {
		if ($debug) { echo("<pre>Create Thumbnail</pre>"); }
		
		if (!file_exists($cachefile)) {
			if ($photo['type'] == "ai") {
				copy($photo['fullpath'],$cache."/".$photo['id'].".pdf");
				thumb($cache."/".$photo['id'].".pdf",$cachefile,$width,$output);
			} else {
				thumb($photo['fullpath'],$cachefile,$width,$output);
			}
		} elseif (filesize($cachefile) == 0 || (filemtime($cachefile) < strtotime($photo['updated']))) {
			unlink($cachefile);
			if ($photo['type'] == "ai") {
				copy($photo['fullpath'],$cache."/".$photo['id'].".pdf");
				thumb($cache."/".$photo['id'].".pdf",$cachefile,$width,$output);
			} else {
				thumb($photo['fullpath'],$cachefile,$width,$output);
			}
		}
		
		if ($action == "download") {
			download_file($photo['file'], $cachefile);
		} else {
			display_file($cachefile);
		}
		
	}

	function download_file($filename, $filesource, $mimetype = false) {
		global $debug;
		$f_info = pathinfo($filename);
		$s_info = pathinfo($filesource);
		$f_ext = trim(strtolower($f_info['extension']));
		$s_ext = trim(strtolower($s_info['extension']));
		if ($f_ext !== $s_ext) { $filename = $f_info['filename'].".".$s_ext; }
		if (!$mimetype) {
			$mimetype = ext_from_mimetype($s_ext, true);
			if (empty($mimetype)) {
				$isize = imgetimagesize($filesource);
				$mimetype = $isize['mimetype'];
			}
		}
		if ($debug) {
			echo "<pre>Filename: $filename\nFilesource: $filesource\nSource Extension: $s_ext\nFile Extension: $f_ext\nMimeType: $mimetype</pre>";
		} else {
			header('Content-Type: '. $mimetype);
			header('Content-Length: '.filesize($filesource));
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			readfile($filesource);
		}
	}
	
	function display_file($filesource, $mimetype = false) {
		global $debug;
		$f_info = pathinfo($filesource);
		$f_ext = trim(strtolower($f_info['extension']));
		if (!$mimetype) {
			$mimetype = ext_from_mimetype($s_ext, true);
			if (empty($mimetype)) {
				$isize = imgetimagesize($filesource);
				$mimetype = $isize['mimetype'];
			}
		}
		if ($debug) {
			echo "<pre>Filesource: $filesource\nSource Extension: $s_ext\nMimeType: $mimetype</pre>";
		} else {
			header('Content-Type: '. $mimetype);
			readfile($filesource);
		}
	}
?>	