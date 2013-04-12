<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {	
        
		parent::__construct();
		
    }
	
	public function index() {
	
		if($u = Current_User::user()) {		
		
			$data = array('main_content' => 'settings_account', 'user_info' => $u);
			$this->load->view('includes/template', $data);
			
		}
		else {
			
			redirect('/');
			
		}
		
	
	}
	
	public function password() {
	
		if($u = Current_User::user()) {		
		
			$data = array('main_content' => 'settings_password', 'user_info' => $u);
			$this->load->view('includes/template', $data);
			
		}
		else {
			
			redirect('/');
			
		}
	
	}
	
	public function profile() {
	
		if($u = Current_User::user()) {		
		
			$data = array('main_content' => 'settings_profile', 'user_info' => $u);
			$this->load->view('includes/template', $data);
			
		}
		else {
			
			redirect('/');
			
		}
	
	}
	
	public function submit() {
	
		$this->output->enable_profiler(TRUE);
	
		if($u = Current_User::user()) {
		
			if ( $this->input->post('hidden') == "account" )  {
				
				$data = array();
				$data['username'] = $this->input->post('username');
				$data['email'] = $this->input->post('email');
				$this->User_model->update(array('username' => $u['username']), $data);
				redirect('settings');
				
			}
			
			if ( $this->input->post('hidden') == "password" ) {
			
				$this->User_model->change_password(array("username" => $u['username'], "old_password" => $this->input->post('current_password'), "new_password" => $this->input->post('new_password')));
				redirect('settings');
			
			}
			
			if ( $this->input->post('hidden') == "profile" ) {

				$this->load->helper("id_gen_helper");
				$data = array();
				$data['full_name'] = $this->input->post('full_name');
				$data['website'] = $this->input->post('website');
				$url_pattern = '/^[a-zA-Z]+:\/\//';
				if($data['website'] == 'http://') {
					$data['website'] = '';
				} else if (!preg_match($url_pattern, $data['website'])) {
					$data['website'] = 'http://' . $data['website'];
				}
				$data['location'] = $this->input->post('location');
				$data['blurb'] = $this->input->post('blurb');

				//Update the user object with the changes so they show up on the profile settings page when we submit
				$u['full_name'] = $data['full_name'];
				$u['website'] = $data['website'];
				$u['location'] = $data['location'];
				$u['blurb'] = $data['blurb'];

				$this->User_model->update(array('username' => $u['username']), $data);

				if (($this->input->post("hidden-avatar-flag") == "false") && ($this->input->post("hidden-thumbnail-flag") == "true") && $this->input->post("hidden-thumbnail-coords")) {

					$thumbnail_name = $u['username'] . "_avatar_thumbnail_" . get_unique_image_id();
					$thumbnail_path = 'avatars/' . $thumbnail_name;
					$coords = $this->input->post("hidden-thumbnail-coords");
					$coords = explode(",", $coords);
					if (count($coords) == 4) {
						$this->load->helper("image_helper");
						$u['avatar_thumbnail'] = $thumbnail_path;
						$this->User_model->change_avatar_thumbnail(
							$u['username'],
							crop_small_thumbnail($u['avatar'], $thumbnail_path, $coords)
						);

					}	

				} else {

					if (isset($_FILES['userfile']) && isset($_FILES['userfile']['name'])) {

						$type = substr($_FILES['userfile']['name'], -3, 3);
						
						$config = array();
						$config['file_name'] = $u['username'] . '_' . get_unique_image_id() . '.' . $type;
						$config['upload_path'] = './avatars';
						$config['allowed_types'] = 'gif|jpg|png';
						$config['overwrite'] = TRUE;

						$file_path = 'avatars/' . $config['file_name'];

						$this->load->library('upload', $config);

						if ( ! $this->upload->do_upload()) {
							$error = array('error' => $this->upload->display_errors());
						} else {
							$u['avatar'] = $file_path;
							$this->User_model->change_avatar($u['username'], $file_path);
						}

						if ($this->input->post("hidden-avatar-flag") == "true" && $this->input->post("hidden-thumbnail-flag") == "true" && $this->input->post("hidden-thumbnail-coords")) {
		
							$thumbnail_name = $u['username'] . "_avatar_thumbnail_" . get_unique_image_id();
							$thumbnail_path = 'avatars/' . $thumbnail_name;
							$coords = $this->input->post("hidden-thumbnail-coords");
							$coords = explode(",", $coords);
							if (count($coords) == 4) {
								$this->load->helper("image_helper");
								$u['avatar_thumbnail'] = $thumbnail_path;
								$this->User_model->change_avatar_thumbnail(
									$u['username'],
									crop_small_thumbnail($file_path, $thumbnail_path, $coords)
								);
							}

						}

					}

				}
				
				redirect('settings/profile');
				
			}
			
		} else {
			
			redirect('/');
			
		}
		
	
	}

}

?>