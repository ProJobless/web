<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('create_share_html')) {

	function create_share_html($data) {

		$CI = get_instance();
		if ($data['link_title'] == '') $data['link_title'] = $data['link_url'];

		switch($data['link_type']) {
			case 'image':
				
				$html = $CI->load->view('shares/image', $data, TRUE);

			break;
			case 'player':

				if ($data['link_media_height'] == 0 || $data['link_media_width'] == 0) {

					$data['link_media_height'] = 360;
					$data['link_media_width'] = 640;

				} else {

					$ratio = $data['link_media_height'] / $data['link_media_width'];

					if ($data['link_media_width'] > 690) {
						$data['link_media_width'] = 690;
						$data['link_media_height'] = $ratio * $data['link_media_width'];
					}

				}

				if ($data['link_base_url'] == 'http://www.twitch.tv' || $data['link_base_url'] == 'http://www.justin.tv') {

					$html = $CI->load->view('shares/object_player', $data, TRUE);

				} else {

					$html = $CI->load->view('shares/iframe_player', $data, TRUE);

				}

				

			break;
			case 'summary':
			default:

				if (isset($data['link_image']) && $data['link_image'] != '') {

					$html = $CI->load->view('shares/summary_image', $data, TRUE);

				} else {

					$html = $CI->load->view('shares/summary_noimage', $data, TRUE);

				}

				

		}

		return $html;

	}

}