<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('THUMBNAIL_X')) define('THUMBNAIL_X', 48);
if (!defined('THUMBNAIL_Y')) define('THUMBNAIL_Y', 48);

if (!defined('LARGE_THUMBNAIL_X')) define('LARGE_THUMBNAIL_X', 300);
if (!defined('LARGE_THUMBNAIL_Y')) define('LARGE_THUMBNAIL_Y', 300);

if (!defined('NORMAL_IMAGE')) define('NORMAL_IMAGE', 0);
if (!defined('THUMBNAIL')) define('THUMBNAIL', 1);
if (!defined('BIG_THUMBNAIL')) define('BIG_THUMBNAIL', 2);


if ( ! function_exists('crop_small_thumbnail')) {

	function crop_small_thumbnail($src, $filename, $coords) {
		if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

  		$type = strtolower(substr(strrchr($src,"."),1));
		
		if($type == 'jpeg') $type = 'jpg';
		switch($type){
			case 'bmp': $image = imagecreatefromwbmp($src); break;
			case 'gif': $image = imagecreatefromgif($src); break;
			case 'jpg': $image = imagecreatefromjpeg($src); break;
			case 'png': $image = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		}

		$filename = $filename . "." . $type;

		$width = imagesx($image);
		$height = imagesy($image);

		$thumb = imagecreatetruecolor( THUMBNAIL_X, THUMBNAIL_Y );

		// preserve transparency
		if($type == "gif" or $type == "png") {
			imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);
		}



		// Resize and crop
		imagecopyresampled($thumb, $image,
		                   0, 0,
		                   (int)$coords[0], (int)$coords[1],
		                   THUMBNAIL_X, THUMBNAIL_Y,
		                   (int)$coords[2], (int)$coords[3]);

		switch($type){
			case 'bmp': imagewbmp($thumb, $filename, 80); break;
			case 'gif': imagegif($thumb, $filename); break;
			case 'jpg': imagejpeg($thumb, $filename, 100); break;
			case 'png': imagepng($thumb, $filename, 9); break;
			default : return "Unsupported picture type!";
		}

		return $filename;
		
	}

}

if ( ! function_exists('serve_image')) {

	function serve_image($image_data, $type=NORMAL_IMAGE) {

		switch($image_data['file_type']) {

			case 'jpg': $content_type = "Content-Type: image/jpeg"; break;
			case 'png': $content_type = "Content-Type: image/png"; break;
			case 'gif': $content_type = "Content-Type: image/gif"; break;
			default   : $content_type = FALSE;

		}

		if ($type == NORMAL_IMAGE) {
			$content_length = filesize($image_data['path']);
			$content = $image_data['path'];
		} else if ($type == THUMBNAIL) {
			$content_length = filesize($image_data['thumbnail_path']);
			$content = $image_data['thumbnail_path'];
		} else if ($type == BIG_THUMBNAIL) {
			$content_length = filesize($image_data['big_thumbnail_path']);
			$content = $image_data['big_thumbnail_path'];
		} else {
			$content = NULL;
		}
		

		$last_modified = "Last-Modified: " . date("D, j M Y H:i:s T", (int) $image_data['created']);
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && ( strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $image_data['created'] ) ) {
			header("HTTP/1.1 304 Not Modified");
		} else {
			header($content_type);
			header($content_length);
			header($last_modified);
			readfile($content);
		}


	}

}

if ( ! function_exists('big_thumbnail_crop') ) {

	function big_thumbnail_crop($filename, $path) {

		$src = $path . $filename;

		$type = strtolower(substr(strrchr($src,"."),1));
		
		if($type == 'jpeg') $type = 'jpg';
		switch($type){
			case 'bmp': $image = imagecreatefromwbmp($src); break;
			case 'gif': $image = imagecreatefromgif($src); break;
			case 'jpg': $image = imagecreatefromjpeg($src); break;
			case 'png': $image = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		}

		$CI = get_instance();

		$preview_filename = $path . 'bigthumbnail~' . $filename;
		$width = imagesx($image);
		$height = imagesy($image);

		//*****Lets first crop the thumbnail*****
		$thumb = imagecreatetruecolor( LARGE_THUMBNAIL_X, LARGE_THUMBNAIL_Y );

		// preserve transparency
		if($type == "gif" or $type == "png") {
			imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);
		}

		//Pixel arithmetic (yay!)

		if ($width == $height) {
			$thumbnail_width = $height;
			$crop_start_y = 0;
			$crop_start_x = 0;
		} else if ($width > $height) {
			$thumbnail_width = $height;
			$crop_start_y = 0;
			$crop_start_x = ($width - $height) / 2;
		} else {
			$thumbnail_width = $width;
			$crop_start_y = ($height - $width) / 2;
			$crop_start_x = 0;
		}

		// Resize and crop
		imagecopyresampled($thumb, $image,
		                   0, 0,
		                   $crop_start_x, $crop_start_y,
		                   LARGE_THUMBNAIL_X, LARGE_THUMBNAIL_Y,
		                   $thumbnail_width, $thumbnail_width);

		switch($type){
			case 'bmp': imagewbmp($thumb, $preview_filename, 80); break;
			case 'gif': imagegif($thumb, $preview_filename); break;
			case 'jpg': imagejpeg($thumb, $preview_filename, 100); break;
			case 'png': imagepng($thumb, $preview_filename, 9); break;
			default : return "Unsupported picture type!";
		}

	}

}

if ( ! function_exists('thumbnail_crop') ) {

	function thumbnail_crop($filename, $path) {

		$src = $path . $filename;

		if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

  		$type = strtolower(substr(strrchr($src,"."),1));
		
		if($type == 'jpeg') $type = 'jpg';
		switch($type){
			case 'bmp': $image = imagecreatefromwbmp($src); break;
			case 'gif': $image = imagecreatefromgif($src); break;
			case 'jpg': $image = imagecreatefromjpeg($src); break;
			case 'png': $image = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		}

		$CI = get_instance();


		$thumb_filename = $path . 'thumbnail~' . $filename;
		$width = imagesx($image);
		$height = imagesy($image);

		//*****Lets first crop the thumbnail*****
		$thumb = imagecreatetruecolor( THUMBNAIL_X, THUMBNAIL_Y );

		// preserve transparency
		if($type == "gif" or $type == "png") {
			imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);
		}

		//Pixel arithmetic (yay!)

		if ($width == $height) {
			$thumbnail_width = $height;
			$crop_start_y = 0;
			$crop_start_x = 0;
		} else if ($width > $height) {
			$thumbnail_width = $height;
			$crop_start_y = 0;
			$crop_start_x = ($width - $height) / 2;
		} else {
			$thumbnail_width = $width;
			$crop_start_y = ($height - $width) / 2;
			$crop_start_x = 0;
		}

		// Resize and crop
		imagecopyresampled($thumb, $image,
		                   0, 0,
		                   $crop_start_x, $crop_start_y,
		                   THUMBNAIL_X, THUMBNAIL_Y,
		                   $thumbnail_width, $thumbnail_width);

		switch($type){
			case 'bmp': imagewbmp($thumb, $thumb_filename, 80); break;
			case 'gif': imagegif($thumb, $thumb_filename); break;
			case 'jpg': imagejpeg($thumb, $thumb_filename, 100); break;
			case 'png': imagepng($thumb, $thumb_filename, 9); break;
			default : return "Unsupported picture type!";
		}

	}

}