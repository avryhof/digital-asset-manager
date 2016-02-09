<?
	require_once("config.inc.php");
	
	$action = trim(strtolower($_REQUEST['action']));
	
	if ($action == "edit" || $action == "delete") {
		$id = intval($_REQUEST['id']);
		$where  = "`id` = $id";
	}
	
	if ($action == "add" || $action == "edit") {
		$fname = $_FILES['file']['name'];
		if (isset($fname) && !empty($fname)) {
			$file = $basepath."/content/uploaded/".$fname;
			if (!move_uploaded_file($_FILES['file']['tmp_name'],$file)) {
				die("<pre>Failed to upload file.</pre>");
			} else {
				$f_info = pathinfo($file);
				$f_ext = trim(strtolower($f_info['extension']));
				
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
					"name" => $_POST['name'],
					"time" => date('Y-m-d H:i:s', $filedate),
					"type" => $f_ext,
					"mimetype" => $imagesize['mime_type'],
					"copyright" => $_POST['copyright'],
					"photographer" => $_POST['photographer'],
					"description" => $_POST['description'],
					"categories" => $_POST['categories'],
					"tags" => $_POST['tags'],
					"width" => $imagesize['width'],
					"height" => $imagesize['height'],
					"xresolution" => $imagesize['resolution_x'],
					"yresolution" => $imagesize['resolution_y'],
					"updated" => date("Y-m-d H:i:s")
				);
				if ($action == "add") {
					$image_info['created'] = date("Y-m-d H:i:s");
				}
			}
		} else {
			$image_info = array(
				"name" => $_POST['name'],
				"copyright" => $_POST['copyright'],
				"photographer" => $_POST['photographer'],
				"description" => $_POST['description'],
				"categories" => $_POST['categories'],
				"tags" => $_POST['tags'],
				"updated" => date("Y-m-d H:i:s")
			);
			if ($action == "add") {
				$image_info['created'] = date("Y-m-d H:i:s");
			}
		}
	}
	
	if ($action == "add") { $db->insert("photos", $image_info); }
	
	if ($action == "add") {
		$photo = $db->query("SELECT * FROM `photos` WHERE `fullpath` = '$image_info[fullpath]' ORDER BY `id` DESC LIMIT 1")->fetch_assoc();
	} else {
		$photo = $db->query("SELECT * FROM `photos` WHERE $where")->fetch_assoc();
	}
	
	if ($action == "edit") { $db->update("photos", $image_info, $where); }
	if ($action == "delete") { 
		unlink($photo['fullpath']);
		$db->delete("photos", $where);
	}
	
	if ($db->errno > 0) {
		echo "<pre>" . $db->error . " in query: " . $db->last_query . "</pre>";
	}
	
	if ($debug) {
		die("<pre>" . print_r($_POST,true) . "\n\n" . print_r($_FILES,true) . "</pre>");
	} else {
		if ($action !== "delete") {
			header("Location: image.php?id=".$photo['id']);
		} else {
			header("Location: index.php");
		}
	}
?>