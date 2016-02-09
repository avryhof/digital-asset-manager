<?
	function thumb($pdffile, $jpegfile, $width, $output = false) {
		list($src_width, $src_height, $src_type, $src_attr) = imgetimagesize($pdffile);
		list($tgt_width, $tgt_height, $tgt_type, $tgt_attr) = setimagesize($pdffile, $width);
		$ii = pathinfo($pdffile);
		
		$input = trim(strtolower(str_replace('.','',$ii['extension'])));
		$output = (!$output ? $input : $output);

		if (class_exists('Imagick')) {
			$im = new Imagick();
			$im->setResolution( 300, 300 ); 
			if ($input == "pdf") {
				$im->readImage($pdffile.'[0]');
				$im->cropImage(($src_width - 60),($src_height - 60),30,30);
			} else {
				$im->readImage($pdffile);
			}
			// $im->setImageColorspace(Imagick::COLORSPACE_SRGB);
			$im->adaptiveResizeImage($tgt_width, $tgt_height, true);
			$im->setImageFormat("jpg");
			//$im->setImageCompressionQuality(100);
			$im->writeImage($jpegfile);
		} else {
			if ($input == "gif") {
				$im = imagecreatefromgif($pdffile);
				$it = imagecreate($tgt_width, $tgt_height);
			} elseif ($input == "png") {
				$im = imagecreatefrompng($pdffile);
				$it = imagecreatetruecolor($tgt_width, $tgt_height);
				imagealphablending($it, false);
 				imagesavealpha($it,true);
 				$transparent = imagecolorallocatealpha($it, 255, 255, 255, 127);
	 			imagefilledrectangle($it, 0, 0, $tgt_width, $tgt_height, $transparent);
			} else {
				$im = imagecreatefromjpeg($pdffile);
				$it = imagecreatetruecolor($tgt_width, $tgt_height);
			}
			
			imagecopyresampled($it, $im, 0, 0, 0, 0, $tgt_width, $tgt_height, $src_width, $src_height);
			
			if ($output == "gif") {
				imagegif($it, $jpegfile);
			} elseif ($output == "png") {
				imagepng($it, $jpegfile);
			} else {
				imagejpeg($it, $jpegfile);
			}
		}
	}

	function setimagesize($imagefile, $width = false, $height = false) {
		if (class_exists('Imagick')) {
			$im = new Imagick();
			$im->setResolution( 300, 300 );
			$im->readImage($imagefile);
		}
		$attr = imgetimagesize($imagefile);
		list($image_width, $image_height, $image_type, $image_attr) = $attr;
		if (!$width && !$height) { return $attr; }
		if (!$height) { $height = round(($image_height * $width) / $image_width); }
		if (!$width) { $width = round(($image_width * $height) / $image_height); }
		return array($width, $height, $image_type, "width=\"$width\" height=\"$height\"");
	}
	
	function imgetimagesize($imagefile) {
		if (class_exists('Imagick')) {
			$im = new Imagick();
			$im->setResolution( 300, 300 );
			$im->readImage($imagefile);
			$width = $im->getImageWidth();
			$height = $im->getImageHeight();
			$type = $im->getImageType();
			$attr = "width=\"$width\" height=\"$height\"";
		} else {
			list($width, $height, $type, $attr) = getimagesize($imagefile);
		}
		return array($width, $height, $type, $attr);
	}
	
	/* This function will only return web friendly mime types. If the extension isn't web friendly, we return image/jpeg */
	function image_mimetype($extension) {
		$extension = trim(strtolower(str_replace('.','',$extension)));
		if ($extension == "gif") {
			return 'image/gif';
		} elseif ($extension == "png") {
			return 'image/png';
		} else {
			return 'image/jpeg';
		}
	}
	
	/* If the file is there and less than 1 day old... */
	function is_cached($filename, $cachetime = false) {
		$cachetime = (!$cachetime ? strtotime("-1 day") : $cachetime);
		return (file_exists($filename) && filemtime($filename) > $cachetime);
	}
?>