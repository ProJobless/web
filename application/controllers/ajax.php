<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function add_post() {
		if($u = Current_User::user()) {
			$data['author'] = $u['username'];
			$data['url'] = $this->input->post('url');
			$data['title'] = $this->input->post('title');
			$data['body'] = $this->input->post('body');
			$data['tags'] = explode(' ', $this->input->post('tags'));
			$data['published'] = $this->input->post('published');
			$data['type'] = $this->input->post('type');
			$data['parent'] = $this->input->post('parent');
			$data['root'] = $this->input->post('root');
			$this->Post_model->create($data);
		}
	}
	
	public function add_comment() {
		if($u = Current_User::user()) {
			$data['author'] = $u['username'];
			$data['url'] = $this->input->post('url');
			$data['root'] = $this->input->post('root');
			$root_post = $this->Post_model->get_by_sid($data['root']);
			$data['title'] = $root_post['title'];
			$data['body'] = $this->input->post('body');
			$data['tags'] = explode(' ', $this->input->post('tags'));
			$data['published'] = $this->input->post('published');
			$data['type'] = $this->input->post('type');
			$data['parent'] = $this->input->post('parent');
			$data['odd'] = $this->input->post('odd');
			$data['children'] = array();
			$this->Post_model->increment($data['parent'], 'comments_count');
			if ($data['parent'] != $data['root']) {
				$this->Post_model->increment($data['root'], 'comments_count');
			}
			$data['sid'] = $this->Post_model->create($data);
			$comment = $this->Post_model->get_by_sid($data['sid']);
			$comment['odd'] = $data['odd'];
			$comment['save'] = false;

			$this->Post_model->generate_comment_html($comment);
		}
	}
	
	public function upvote() {
		if($u = Current_User::user()) {
			$this->Vote_model->upvote($this->input->post("sid"), $u["username"]);
		}
	}
	
	public function downvote() {
		if($u = Current_User::user()) {
			$this->Vote_model->downvote($this->input->post("sid"), $u["username"]);
		}
	}
	
	public function username_check() {
		
		$username = $this->input->post('username');
		if ( ($this->User_model->username_exists($username)) || ($this->Post_model->id_exists($username)) ) {
			echo "exists";
		} else {
			echo "does_not_exist";
		}
			
	}
	
	public function email_check() {
		
		$email = $this->input->post('email');
		if ($this->User_model->email_exists($email)) {
			echo "exists";
		} else {
			echo "does_not_exist";
		}
		
	}

	public function follow() {
		if($u = Current_User::user()) {

			$data['followee'] = $this->input->post('username');
			$data['follower'] = $u['username'];
			$this->Follow_model->add_follower($data);

		}
	}

	public function unfollow() {
		if($u = Current_User::user()) {

			$data['followee'] = $this->input->post('username');
			$data['follower'] = $u['username'];
			$this->Follow_model->subtract_follower($data);
			
		}
	}

	public function save_post() {
		if ($u = Current_User::user()) {

			$this->Save_model->save($u['username'], $this->input->post("post_id"));

		}
	}

	public function unsave_post() {
		if ($u = Current_User::user()) {

			$this->Save_model->unsave($u['username'], $this->input->post("post_id"));

		}
	}

	public function big_upload() {
		if ($u = Current_User::user()) {

			var_dump($_POST);

		}
	}
	
	public function website_scrape() {
		if($u = Current_User::user()) {
		
			$url = $this->input->post('url');

			$url = str_replace("https", "http", $url, $temp = 1);
			
			if  ( preg_match( '/^http:\/\//', $url) == 0) {
			
				$url = "http://" . $url;
			
			}

			preg_match('/^http:\/\/[a-zA-Z0-9\-\.]+\//', $url, $matches);

			if (isset($matches[0])) {

				$base_url = substr($matches[0], 0, strlen($matches[0])-1);
				$ch = curl_init();
				$timeout = 5;
		  		curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			 	$data = curl_exec($ch);

				curl_close($ch);
				
				libxml_use_internal_errors(true);
				$doc = new DomDocument();
				$doc->loadHTML($data);
				$xpath = new DOMXPath($doc);

				//Check SEO metas:
				$title = $xpath->query('//title');
				if ($title->length > 0) {
					$title = $title->item(0)->textContent;
				} else {
					$title = '';
				}
				$description = $xpath->query('/html/head/meta[@name="description"]/@content');
				if ($description->length > 0) {
					$description = $description->item(0)->textContent;
				} else {
					$description = '';
				}

				$seo_meta = array(
					'title' => $title,
					'description' => $description
				);

				//Check open graph (facebook) metas:
				$query = '//*/meta[starts-with(@name, \'og:\')] | //*/meta[starts-with(@property, \'og:\')]';
				$metas = $xpath->query($query);
				$facebook_metas = array();
				foreach ($metas as $meta) {
				    if ($meta->hasAttribute('property')) {
						$property = substr($meta->getAttribute('property'), 3);	
					} else {
						$property = substr($meta->getAttribute('name'), 3);
					}
					$property = str_replace(":", "_", $property);
				    if ($meta->hasAttribute('content')) {
				    	$content = $meta->getAttribute('content');	
				    } else {
				    	$content = $meta->getAttribute('value');	
				    }
				    $facebook_metas[$property] = $content;
				}

				//Check twitter metas:
				$query = '//*/meta[starts-with(@name, \'twitter:\')] | //*/meta[starts-with(@property, \'twitter:\')]';
				$metas = $xpath->query($query);
				$twitter_metas = array();
				foreach ($metas as $meta) {
					if ($meta->hasAttribute('property')) {
						$property = substr($meta->getAttribute('property'), 8);	
					} else {
						$property = substr($meta->getAttribute('name'), 8);	
					}
					$property = str_replace(":", "_", $property);
				    if ($meta->hasAttribute('content')) {
				    	$content = $meta->getAttribute('content');	
				    } else {
				    	$content = $meta->getAttribute('value');	
				    }
				    $twitter_metas[$property] = $content;
				}

				//Create return array
				$return = array();
				$return['base_url'] = $base_url;
				$return['type'] = 'summary';
				$twitter_fail = FALSE;
				$facebook_fail = FALSE;

				//Now we must decide what is best to send, facebook? twitter? a mix? We prefer twitter because it seems to be the most robust.
				if (count($twitter_metas) > 0) {

					//Check integrity of the twitter metas, card, image, etc.
					if( isset($twitter_metas['card']) && isset($twitter_metas['image'])) {

						$return['type'] = $twitter_metas['card'];
						if ($return['type'] == 'photo') $return['type'] = 'image';
						$return['image'] = $twitter_metas['image'];

						if ( isset($twitter_metas['description'])) {
							$return['description'] = $twitter_metas['description'];
						} else if ( strlen($seo_meta['description']) > 0 ) {
							$return['description'] = $seo_meta['description'];
						} else if ( isset($facebook_metas['description'])) {
							$return['description'] = $facebook_metas['description'];
						} else {
							$return['description'] = '';
						}

						if ( isset($twitter_metas['title'])) {
							$return['title'] = $twitter_metas['title'];
						} else if ( strlen($seo_meta['title']) > 0 ) {
							$return['title'] = $seo_meta['title'];
						} else if ( isset($facebook_metas['title'])) {
							$return['title'] = $facebook_metas['title'];
						} else {
							$return['title'] = $url;
						}

						if ( isset($twitter_metas['image'])) {
							$return['image'] = $twitter_metas['image'];
						} else if ( isset($facebook_metas['image']) ) {
							$return['image'] = $seo_meta['image'];
						} else {
							$return['image'] = base_url() . 'assets/link.jpg';
						}

						if ($return['type'] == 'player') {

							if ( isset($twitter_metas['player']) && isset($twitter_metas['player_height']) && isset($twitter_metas['player_width']) ) {

								$return['player'] = $twitter_metas['player'];
								$return['player_height'] = $twitter_metas['player_height'];
								$return['player_width'] = $twitter_metas['player_width'];

							} else {

								$return['type'] = 'summary';

							}

						}

					} else {

						$twitter_fail = TRUE;

					}

				} else {

					$twitter_fail = TRUE;

				}

				if ((count($facebook_metas) > 0) && $twitter_fail) {

					if (isset($facebook_metas['type']) && isset($facebook_metas['image'])) {

						$return['type'] = 'summary';

						if ( strlen($facebook_metas['description']) > 0 ) {
							$return['description'] = $facebook_metas['description'];
						} else if ( isset($seo_metas['description'])) {
							$return['description'] = $seo_metas['description'];
						} else {
							$return['description'] = '';
						}

						if ( isset($facebook_metas['title'])) {
							$return['title'] = $facebook_metas['title'];
						} else if ( strlen($seo_meta['title']) > 0 ) {
							$return['title'] = $seo_meta['title'];
						} else {
							$return['title'] = $url;
						}

						if ( isset($facebook_metas['image']) ) {
							$return['image'] = $facebook_metas['image'];
							$image_size = getimagesize($return['image']);
							if ( $image_size[0] > 300 && $image_size[1] > 300) {
								$return['type'] = 'image';
							}
						} else {

							$return['image'] = base_url() . 'assets/link.jpg';

						}

						if ( isset($facebook_metas['video']) && isset($facebook_metas['video_height']) && isset($facebook_metas['video_width']) ) {

							$return['player'] = $facebook_metas['video'];
							$return['player_height'] = $facebook_metas['video_height'];
							$return['player_width'] = $facebook_metas['video_width'];
							$return['type'] = 'player';
							if ( isset($facebook_metas['video_type']) ) {

								$return['player_type'] = $facebook_metas['video_type'];

							}

						}

					} else {

						$facebook_fail = TRUE;

					}

				} else {

					$facebook_fail = TRUE;

				}

				if ( $twitter_fail && $facebook_fail) {

					$return['blah'] = 'blah';

					if ( isset($seo_metas['description'])) {
						$return['description'] = $seo_metas['description'];
					} else {
						$return['description'] = $base_url;
					}

					if ( strlen($seo_meta['title']) > 0 ) {
						$return['title'] = $seo_meta['title'];
					} else  {
						$return['title'] = $url;
					}

					$return['type'] = 'summary';

				}
				
				echo json_encode($return);

			} else {

				echo json_encode(array("error" => "FAIL"));

			}
			
			
		}
		
	}
}