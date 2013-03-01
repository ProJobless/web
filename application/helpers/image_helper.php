<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('THUMBNAIL_X')) define('THUMBNAIL_X', 48);
if (!defined('THUMBNAIL_Y')) define('THUMBNAIL_Y', 48);


if ( ! function_exists('crop_image')) {

	function crop_image($src, $filename, $coords) {
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
		imagecopyresampled($thumb,
		                   $image,
		                   0,
		                   0,
		                   $coords[0], $coords[1],
		                   THUMBNAIL_X, THUMBNAIL_Y,
		                   $coords[2], $coords[3]);

		switch($type){
			case 'bmp': imagewbmp($thumb, $filename, 80); break;
			case 'gif': imagegif($thumb, $filename, 80); break;
			case 'jpg': imagejpeg($thumb, $filename, 80); break;
			case 'png': imagepng($thumb, $filename, 80); break;
			default : return "Unsupported picture type!";
		}

		return $filename;
		
	}

	

}