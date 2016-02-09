<?php

function get_page_metadata($url) {
	$html = curl_get($url);
	$title_ex = '/<title>(?P<title>.*?)<\/title>/is';
	$desc_ex = '/<meta name="description" content="(?P<desc>.*?)"\/?>/is';
	preg_match($title_ex, $html, $titles);
	preg_match($desc_ex, $html, $descs);
	
	$retn = array();
	if (count($titles) > 0) { $retn['title'] = $titles['title']; }
	if (count($descs) > 0) { $retn['desc'] = $descs['desc']; }
	return $retn;	
}

function getAllFiles($path) {
	global $ignore_extensions;
	$array = array();
	
	$targetpath = realpath($path);
	
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($targetpath,RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST);
	
	foreach ($iterator as $item) {
		$toAdd = $targetpath . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
		$fi = pathinfo($toAdd);
		$fext = trim(strtolower($fi['extension']));
		if (!is_dir($toAdd) && substr_count($toAdd, 'AppleDouble') == 0 && !in_array($fext,$ignore_extensions)) {
			$array[] = $toAdd;
		}
		if (in_array($fext,$ignore_extensions) || substr($fi['basename'],0,1) == '.' && !is_dir($toAdd)) {
			unlink($toAdd);
		}
	}
	return $array;
}

function getImageFiles($path) {
	$directory = new RecursiveDirectoryIterator($path);
	$iterator = new RecursiveIteratorIterator($directory);
	$regex = new RegexIterator($iterator, '/^.+\.(jpg|jpeg|gif|tif|tiff|png|eps|psd|ai)$/i', RecursiveRegexIterator::GET_MATCH);
	return $regex;
}

function m_type($filename) {
	$filename = escapeshellcmd($filename);
    $command = "file -b --mime-type -m /usr/share/misc/magic {$filename}";

    $mimeType = shell_exec($command);
           
    return trim($mimeType);
}

function ext_from_mimetype($mimetype) {
	$types = array(
		'hqx'	=>	'application/mac-binhex40',
		'cpt'	=>	'application/mac-compactpro',
		'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv'),
		'bin'	=>	array('application/macbinary', 'application/mac-binary', 'application/x-binary', 'application/x-macbinary'),
		'psd'	=>	array('application/x-photoshop', 'image/vnd.adobe.photoshop'),
		'pdf'	=>	'application/pdf',
		'eps'	=>	'application/postscript',
		'ps'	=>	'application/postscript',
		'smi'	=>	'application/smil',
		'smil'	=>	'application/smil',
		'mif'	=>	'application/vnd.mif',
		'xls'	=>	array('application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel'),
		'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
		'pptx'	=> 	'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'wbxml'	=>	'application/wbxml',
		'wmlc'	=>	'application/wmlc',
		'dcr'	=>	'application/x-director',
		'dir'	=>	'application/x-director',
		'dxr'	=>	'application/x-director',
		'dvi'	=>	'application/x-dvi',
		'gtar'	=>	'application/x-gtar',
		'gz'	=>	'application/x-gzip',
		'gzip'  =>	'application/x-gzip',
		'php'	=>	array('application/x-httpd-php', 'application/php', 'application/x-php', 'text/php', 'text/x-php'),
		'phps'	=>	'application/x-httpd-php-source',
		'js'	=>	'application/x-javascript',
		'swf'	=>	'application/x-shockwave-flash',
		'sit'	=>	'application/x-stuffit',
		'tar'	=>	'application/x-tar',
		'tgz'	=>	array('application/x-tar', 'application/x-gzip-compressed'),
		'z'	=>	'application/x-compress',
		'xhtml'	=>	'application/xhtml+xml',
		'xht'	=>	'application/xhtml+xml',
		'zip'	=>	array('application/x-zip', 'application/zip', 'application/x-zip-compressed', 'application/s-compressed', 'multipart/x-zip'),
		'rar'	=>	array('application/x-rar', 'application/rar', 'application/x-rar-compressed'),
		'mid'	=>	'audio/midi',
		'midi'	=>	'audio/midi',
		'mpga'	=>	'audio/mpeg',
		'mp2'	=>	'audio/mpeg',
		'mp3'	=>	array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
		'aif'	=>	array('audio/x-aiff', 'audio/aiff'),
		'aiff'	=>	array('audio/x-aiff', 'audio/aiff'),
		'aifc'	=>	'audio/x-aiff',
		'ram'	=>	'audio/x-pn-realaudio',
		'rm'	=>	'audio/x-pn-realaudio',
		'rpm'	=>	'audio/x-pn-realaudio-plugin',
		'ra'	=>	'audio/x-realaudio',
		'rv'	=>	'video/vnd.rn-realvideo',
		'wav'	=>	array('audio/x-wav', 'audio/wave', 'audio/wav'),
		'bmp'	=>	array('image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap', 'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp', 'application/x-bmp', 'application/x-win-bitmap'),
		'gif'	=>	'image/gif',
		'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
		'jp2'	=>	array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		'png'	=>	array('image/png',  'image/x-png'),
		'tiff'	=>	'image/tiff',
		'tif'	=>	'image/tiff',
		'css'	=>	'text/css',
		'html'	=>	'text/html',
		'txt'	=>	'text/plain',
		'log'	=>	'text/x-log',
		'rtx'	=>	'text/richtext',
		'rtf'	=>	'text/rtf',
		'xml'	=>	array('application/xml', 'text/xml'),
		'xsl'	=>	'text/xsl',
		'mpg'	=>	'video/mpeg',
		'mov'	=>	'video/quicktime',
		'avi'	=>	array('video/x-msvideo', 'video/msvideo', 'video/avi', 'application/x-troff-msvideo'),
		'doc'	=>	'application/msword',
		'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'eml'	=>	'message/rfc822',
		'json'  =>	array('application/json', 'text/json'),
		'pem'   =>	array('application/x-x509-user-cert', 'application/x-pem-file'),
		'p10'   =>	array('application/x-pkcs10', 'application/pkcs10'),
		'p12'   =>	'application/x-pkcs12',
		'p7a'   =>	'application/x-pkcs7-signature',
		'p7c'   =>	array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
		'p7r'   =>	'application/x-pkcs7-certreqresp',
		'p7s'   =>	'application/pkcs7-signature',
		'crt'   =>	array('application/x-x509-ca-cert', 'application/x-x509-user-cert', 'application/pkix-cert'),
		'crl'   =>	array('application/pkix-crl', 'application/pkcs-crl'),
		'der'   =>	'application/x-x509-ca-cert',
		'kdb'   =>	'application/octet-stream',
		'pgp'   =>	'application/pgp',
		'gpg'   =>	'application/gpg-keys',
		'sst'   =>	'application/octet-stream',
		'csr'   =>	'application/octet-stream',
		'rsa'   =>	'application/x-pkcs7',
		'cer'   =>	array('application/pkix-cert', 'application/x-x509-ca-cert'),
		'3g2'   =>	'video/3gpp2',
		'3gp'   =>	array('video/3gp', 'video/3gpp'),
		'mp4'   =>	'video/mp4',
		'm4a'   =>	'audio/x-m4a',
		'f4v'   =>	'video/x-f4v',
		'flv'	=>	'video/x-flv',
		'webm'	=>	'video/webm',
		'aac'   =>	'audio/x-acc',
		'm4u'   =>	'application/vnd.mpegurl',
		'm3u'   =>	'text/plain',
		'xspf'  =>	'application/xspf+xml',
		'vlc'   =>	'application/videolan',
		'wmv'   =>	array('video/x-ms-wmv', 'video/x-ms-asf'),
		'au'    =>	'audio/x-au',
		'ac3'   =>	'audio/ac3',
		'flac'  =>	'audio/x-flac',
		'ogg'   =>	array('audio/ogg', 'video/ogg', 'application/ogg'),
		'kmz'	=>	array('application/vnd.google-earth.kmz', 'application/zip', 'application/x-zip'),
		'kml'	=>	array('application/vnd.google-earth.kml+xml', 'application/xml', 'text/xml'),
		'ics'	=>	'text/calendar',
		'zsh'	=>	'text/x-scriptzsh',
		'7zip'	=>	array('application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'),
		'cdr'	=>	array('application/cdr', 'application/coreldraw', 'application/x-cdr', 'application/x-coreldraw', 'image/cdr', 'image/x-cdr', 'zz-application/zz-winassoc-cdr'),
		'wma'	=>	array('audio/x-ms-wma', 'video/x-ms-asf'),
		'jar'	=>	array('application/java-archive', 'application/x-java-application', 'application/x-jar', 'application/x-compressed'),
		'svg'	=>	array('image/svg+xml', 'application/xml', 'text/xml'),
		'vcf'	=>	'text/x-vcard',
		'srt'	=>	array('text/srt', 'text/plain'),
		'vtt'	=>	array('text/vtt', 'text/plain'),
		'ico'	=>	array('image/x-icon', 'image/x-ico', 'image/vnd.microsoft.icon')
	);
	foreach($types as $ext => $mimes) {
		if (is_array($mimes)) {
			if (in_array($mimetype, $mimes)) {
				return $ext;
			}
		} else {
			if ($mimetype == $mimes) {
				return $ext;
			}
		}
	}
}

function decode_imagetype($imagetype) {
	switch($imagetype) {
		case IMAGETYPE_GIF:
			return "gif"; break;
		case IMAGETYPE_JPEG:
			return "jpg"; break; 
		case IMAGETYPE_PNG:
			return "png"; break;
		case IMAGETYPE_SWF:
			return "swf"; break;
		case IMAGETYPE_PSD:
			return "psd"; break;
		case IMAGETYPE_BMP:
			return "bmp"; break;
		case IMAGETYPE_TIFF_II || IMAGETYPE_TIFF_MM:
			return "tif"; break;
		case IMAGETYPE_JPC:
			return "jpc"; break;
		case IMAGETYPE_JP2:
			return "jp2"; break;
		case IMAGETYPE_JPX:
			return "jpx"; break;
		case IMAGETYPE_JB2:
			return "jb2"; break;
		case IMAGETYPE_SWC:
			return "swc"; break;
		case IMAGETYPE_IFF:
			return "iff"; break;
		case IMAGETYPE_WBMP:
			return "wbmp"; break;
		case IMAGETYPE_XBM:
			return "xbm"; break;
		case IMAGETYPE_ICO:
			return "ico"; break;
	}
}

function thumb($pdffile, $jpegfile, $width, $output = false) {
	global $enable_imagick;
	$src = imgetimagesize($pdffile);
	$tgt = setimagesize($pdffile, $width);
	$ii = pathinfo($pdffile);
	
	$input = trim(strtolower(str_replace('.','',$ii['extension'])));
	$output = (!$output ? $input : $output);

	if ($enable_imagick && class_exists('Imagick')) {
		$im = new Imagick();
		$im->setResolution( 300, 300 ); 
		if ($input == "pdf") {
			$im->readImage($pdffile.'[0]');
			$im->cropImage(($src['width'] - 60),($src['height'] - 60),30,30);
		} else {
			$im->readImage($pdffile);
		}
		// $im->setImageColorspace(Imagick::COLORSPACE_SRGB);
		$im->adaptiveResizeImage($tgt['width'], $tgt['height'], true);
		$im->setImageFormat($output);
		//$im->setImageCompressionQuality(100);
		$im->writeImage($jpegfile);
	} else {
		if ($input == "gif") {
			$im = imagecreatefromgif($pdffile);
			$it = imagecreate($tgt['width'], $tgt['height']);
		} elseif ($input == "png") {
			$im = imagecreatefrompng($pdffile);
			$it = imagecreatetruecolor($tgt['width'], $tgt['height']);
			imagealphablending($it, false);
			imagesavealpha($it,true);
			$transparent = imagecolorallocatealpha($it, 255, 255, 255, 127);
			imagefilledrectangle($it, 0, 0, $tgt['width'], $tgt['height'], $transparent);
		} else {
			$im = imagecreatefromjpeg($pdffile);
			$it = imagecreatetruecolor($tgt['width'], $tgt['height']);
		}
		
		imagecopyresampled($it, $im, 0, 0, 0, 0, $tgt['width'], $tgt['height'], $src['width'], $src['height']);
		
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
	global $enable_imagick;
	if ($enable_imagick && class_exists('Imagick')) {
		$im = new Imagick();
		$im->setResolution( 300, 300 );
		$im->readImage($imagefile);
	}
	$attr = imgetimagesize($imagefile);
	if (!$width && !$height) { return $attr; }
	if (!$height) { $height = round(($attr['height'] * $width) / $attr['width']); }
	if (!$width) { $width = round(($attr['width'] * $height) / $attr['height']); }
	return array("width" => $width, "height" => $height, "type" => $attr['type'], "attr" => "width=\"$width\" height=\"$height\"");
}

function imgetimagesize($imagefile) {
	global $enable_imagick;
	if ($enable_imagick && class_exists('Imagick')) {
		$im = new Imagick();
		// $im->setResolution( 300, 300 );
		$im->readImage($imagefile);
		$width = $im->getImageWidth();
		$height = $im->getImageHeight();
		$type = $im->getImageType();
		$imageResolution = $im->getImageResolution();
		$resolutionX = round($imageResolution['x'] * 2.54, 2);
		$resolutionY = round($imageResolution['y'] * 2.54, 2);
		$mime = $im->getImageMimeType();
		$params = $im->getImageProperties("*");
		$attr = "width=\"$width\" height=\"$height\"";
	} else {
		list($width, $height, $type, $attr) = getimagesize($imagefile, $imageinfo);
	}
	return array(
		"width" => $width, 
		"height" => $height, 
		"type" => $type, 
		"attr" => $attr,
		"resolution_x" => $resolutionX,
		"resolution_y" => $resolutionY,
		"mime_type" => $mime,
		"properties" => $params
	);
}

function imageHeight($newwidth, $oldwidth, $oldheight) {
	return round((($newwidth * $oldheight) / $oldwidth),0);
}

function imageWidth($newheight, $oldwidth, $oldheight) {
	return round((($newheight * $oldwidth) / $oldheight),0);
}

function getSlug($text) { 
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
  // trim
  $text = trim($text, '-');
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // lowercase
  $text = strtolower($text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);
  if (empty($text)) {
    return 'n-a';
  } else {
	  return $text;
  }
}

function pureName($text) {
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', ' ', $text);
  // trim
  $text = trim($text, '-');
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // lowercase
  // $text = strtolower($text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', ' ', $text);
  if (empty($text)) {
    return 'n-a';
  } else {
	  return trim($text);
  }
}

function filesize_human($filename) {
	$bytes = filesize($filename);
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = strval(round($result, 2))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
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

/**
* Send a GET requst using cURL
* @param string $url to request
* @param array $get values to send
* @param array $options for cURL
* @return string
*/
function curl_get($url, $get = false, $options = array()) {   
    $defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : '') . (is_array($get) ? http_build_query($get) : ''),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4,
		CURLOPT_FOLLOWLOCATION => true
    );
   
    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch)) {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
} 

?>