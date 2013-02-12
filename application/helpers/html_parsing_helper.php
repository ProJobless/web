<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('parse_title')) {

	function parse_title($html_string) {

		preg_match_all('|<p>(.*?)</p>|', $html_string, $output);
		if (isset($output[1][0])) {
			$title = $output[1][0];
			$title = trim(strip_tags($title));
			if (strlen($title) > 50) {
				return substr($title, 0, 50) . '...';	
			} else {
				return $title;
			}
			
		} else {
			return "";
		}

	}

}