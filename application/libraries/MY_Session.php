<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session {


	public function __construct() {
	
		parent::__construct();

		//flash seems to use this user agent, so we can identify if the request is coming from uploadify
		if ($this->userdata('user_agent') == "Shockwave Flash") {

			//a custom session variable, if you're already "logged in" on your flash session, than just make sure the session is still valid.
			if ($this->userdata('logged_in')) {

				//make sure parent is still active
				if(!$this->userdata('parent_session')) {
					$this->sess_destroy();
					return;
				}

				$this->CI->mongo_db->where(array(
					'session_id' => $this->userdata('parent_session'),
					'ip_address' => $this->userdata('ip_address'),
				));
				$this->CI->mongo_db->select("last_activity");

				$query = $this->CI->db->get($this->sess_table_name);

				// couldnt find session
				if ($query->num_rows() == 0) {
					$this->sess_destroy();
					return;
				}

				$row = $query->row();
				$last_activity = $row->last_activity;
				//check that the session hasnt expired
				if (($last_activity + $this->sess_expiration) < $this->now) {
					$this->sess_destroy();
					return;
				}

			//not yet logged in
			} else {

				$sessdata = $this->CI->input->post('sessdata');
				if ($sessdata) {
					//decode the sess data
					$sessdata = $this->CI->encrypt->decode($sessdata);
					$sessdata = unserialize($sessdata);

					if (empty($sessdata['session_id']) || empty($sessdata['timestamp'])) {
						$this->sess_destroy();
						return;
					}

					//attempt to clone the session...
					$parent_session['session_id'] = $sessdata['session_id'];
					$parent_session['ip_address'] = $this->userdata('ip_address');

					$this->CI->mongo_db->where(array(
						'session_id' => $parent_session['session_id'],
						'ip_address' => $parent_session['ip_address']
					));

					$query = $this->CI->mongo_db->get($this->sess_table_name);

					// couldn't find session
					if ($query->num_rows() == 0) {
						$this->sess_destroy();
						return;
					}

					//retrieve data
					$row = $query->row();
					$parent_session['last_activity'] = $row->last_activity;
					if (isset($row->user_data) AND $row->user_data != '') {
						$custom_data = $this->_unserialize($row->user_data);

						if (is_array($custom_data)) {
							foreach ($custom_data as $key => $val) {
								$parent_session[$key] = $val;
							}
						}
					}

					//check that the session hasn't expired
					if (($parent_session['last_activity'] + $this->sess_expiration) < $this->now) {
						$this->sess_destroy();
						return;
					}

					if ($parent_session['logged_in']) {

						//DO STUFF HERE
						//You want to mimic the values of the parent session. But for better security flash will still maintain its own session id. if you use a logged_in flag and user_id in your session vars to check if a user is signed on and identify them, you'll want to set that up here.

						$this->set_userdata('parent_session', $parent_session['session_id']);
						$this->set_userdata('logged_in', $parent_session['logged_in']);
						$this->set_userdata('user_id', $parent_session['user_id']);

					}

				} else {
					$this->sess_destroy();
					return;
				}

			}
			
		}

	}

	/** 
	 * Fetch the current session data if it exists
	 *
	 * @access	public
	 * @return	bool
	 */
	function sess_read() {
		$this->CI->load->library('Mongo_db');
		// Fetch the cookie
		$session = $this->CI->input->cookie($this->sess_cookie_name);

		// No cookie?  Goodbye cruel world!...
		if ($session === FALSE)
		{
			log_message('debug', 'A session cookie was not found.');
			return FALSE;
		}

		// Decrypt the cookie data
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$session = $this->CI->encrypt->decode($session);
		}
		else
		{
			// encryption was not used, so we need to check the md5 hash
			$hash	 = substr($session, strlen($session)-32); // get last 32 chars
			$session = substr($session, 0, strlen($session)-32);

			// Does the md5 hash match?  This is to prevent manipulation of session data in userspace
			if ($hash !==  md5($session.$this->encryption_key))
			{
				log_message('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
				$this->sess_destroy();
				return FALSE;
			}
		}

		// Unserialize the session array
		$session = $this->_unserialize($session);

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($session) OR ! isset($session['session_id']) OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) OR ! isset($session['last_activity']))
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Is the session current?
		if (($session['last_activity'] + $this->sess_expiration) < $this->now)
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Does the IP Match?
		if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->CI->input->ip_address())
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Does the User Agent Match?
		if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->CI->input->user_agent(), 0, 50)))
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Is there a corresponding session in the DB?
		if ($this->sess_use_database === TRUE)
		{
			$this->CI->mongo_db->where('session_id', $session['session_id']);

			if ($this->sess_match_ip == TRUE)
			{
				$this->CI->mongo_db->where('ip_address', $session['ip_address']);
			}

			if ($this->sess_match_useragent == TRUE)
			{
				$this->CI->mongo_db->where('user_agent', $session['user_agent']);
			}

			$query = $this->CI->mongo_db->limit(1)->get($this->sess_table_name);

			// No result?  Kill it!
			if (sizeof($query) == 0)
			{
				$this->sess_destroy();
				return FALSE;
			}

			// Is there custom data?  If so, add it to the main session array
			$row = $query[0];
			if (isset($row['user_data']) AND $row['user_data'] != '')
			{
				$custom_data = $this->_unserialize($row['user_data']);

				if (is_array($custom_data))
				{
					foreach ($custom_data as $key => $val)
					{
						$session[$key] = $val;
					}
				}
			}
		}

		// Session is valid!
		$this->userdata = $session;
		unset($session);

		return TRUE;
	}
	
	function sess_write() {
		$this->CI->load->library('Mongo_db');
		// Are we saving custom data to the DB?  If not, all we do is update the cookie
		if ($this->sess_use_database === FALSE)
		{
			$this->_set_cookie();
			return;
		}

		// set the custom userdata, the session data we will set in a second
		$custom_userdata = $this->userdata;
		$cookie_userdata = array();

		// Before continuing, we need to determine if there is any custom data to deal with.
		// Let's determine this by removing the default indexes to see if there's anything left in the array
		// and set the session data while we're at it
		foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
		{
			unset($custom_userdata[$val]);
			$cookie_userdata[$val] = $this->userdata[$val];
		}

		// Did we find any custom data?  If not, we turn the empty array into a string
		// since there's no reason to serialize and store an empty array in the DB
		if (count($custom_userdata) === 0)
		{
			$custom_userdata = '';
		}
		else
		{
			// Serialize the custom data array so we can store it
			$custom_userdata = $this->_serialize($custom_userdata);
		}

		// Run the update query
		$this->CI->mongo_db->where('session_id', $this->userdata['session_id']);
		$this->CI->mongo_db->set(array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata))->update($this->sess_table_name);

		// Write the cookie.  Notice that we manually pass the cookie data array to the
		// _set_cookie() function. Normally that function will store $this->userdata, but
		// in this case that array contains custom data, which we do not want in the cookie.
		$this->_set_cookie($cookie_userdata);
	}
	
	function sess_create() {
		$this->CI->load->library('Mongo_db');
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$sessid .= $this->CI->input->ip_address();

		$this->userdata = array(
							'session_id'	=> md5(uniqid($sessid, TRUE)),
							'ip_address'	=> $this->CI->input->ip_address(),
							'user_agent'	=> substr($this->CI->input->user_agent(), 0, 50),
							'last_activity'	=> $this->now
							);

		// Save the data to the DB if needed
		if ($this->sess_use_database === TRUE)
		{
			$this->CI->mongo_db->insert($this->sess_table_name, $this->userdata);
		}

		// Write the cookie
		$this->_set_cookie();
	}
	
	function sess_update() {
		$this->CI->load->library('Mongo_db');
		// We only update the session every five minutes by default
		if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
		{
			return;
		}

		// Save the old session id so we know which record to
		// update in the database if we need it
		$old_sessid = $this->userdata['session_id'];
		$new_sessid = '';
		while (strlen($new_sessid) < 32)
		{
			$new_sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$new_sessid .= $this->CI->input->ip_address();

		// Turn it into a hash
		$new_sessid = md5(uniqid($new_sessid, TRUE));

		// Update the session data in the session data array
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;

		// _set_cookie() will handle this for us if we aren't using database sessions
		// by pushing all userdata to the cookie.
		$cookie_data = NULL;

		// Update the session ID and last_activity field in the DB if needed
		if ($this->sess_use_database === TRUE)
		{
			// set cookie explicitly to only have our session data
			$cookie_data = array();
			foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
			{
				$cookie_data[$val] = $this->userdata[$val];
			}

			$this->CI->mongo_db->where(array('session_id' => $old_sessid))->set(array('last_activity' => $this->now, 'session_id' => $new_sessid))->update($this->sess_table_name);
		}

		// Write the cookie
		$this->_set_cookie($cookie_data);
	}
	
	function sess_destroy() {
		$this->CI->load->library('Mongo_db');
		// Kill the session DB row
		if ($this->sess_use_database === TRUE AND isset($this->userdata['session_id']))
		{
			$this->CI->mongo_db->where(array('session_id' => $this->userdata['session_id']));
			$this->CI->mongo_db->delete($this->sess_table_name);
		}

		// Kill the cookie
		setcookie(
					$this->sess_cookie_name,
					addslashes(serialize(array())),
					($this->now - 31500000),
					$this->cookie_path,
					$this->cookie_domain,
					0
				);
	}
	
	function _sess_gc() {
		if ($this->sess_use_database != TRUE)
		{
			return;
		}

		srand(time());
		if ((rand() % 100) < $this->gc_probability)
		{
			$expire = $this->now - $this->sess_expiration;

			$this->CI->mongo_db->where_lt('last_activity', $expire)->delete($this->sess_table_name);

			log_message('debug', 'Session garbage collection performed.');
		}
	}

	public function get_encrypted_sessdata() {

		$data['session_id'] = $this->userdata('session_id');
		$data['timestamp'] = time();

		$data = serialize($data);
		$data = $this->CI->encrypt->encode($data);
		return $data;

	}


}