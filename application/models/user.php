<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {
	
	var $id = 0;
	var $firstname = '';
	var $lastname = '';
	var $email = '';
	var $username = '';
	var $password = '';
	var $ip = '';
	var $auth = FALSE;
	var $admin = FALSE;
	var $active = TRUE;
	var $session_id = '';
	var $photoloc = '/static/images/users/default.png';
	var $laston = '';

	function __construct()
	{
		parent::__construct();
	}
	
	function getAll() 
    {
    	return $this->db->get('users');
    }
	
	function lowerUserNames() 
	{
		$query = $this->db->get('users');
		foreach($query->result() as $r)
		{
			$old = $r->username;
			$new = strtolower($old);
			$data = array(
				'username' => $new
			);
			$this->db->where('id', $r->id);
			if ($this->db->update('users', $data))
				echo $old . ' changed to ' . $new . '<br />';
			else
				echo 'failed changing <br />' . $old; 
		}
	}
	
	function searchUsers($search)
    {
    	$searchArray = explode(" ", $search);
    	$this->db->select('*');
    	$this->db->like('username', $searchArray[0]);
    	$this->db->or_like('firstname', $searchArray[0]);
    	$this->db->or_like('lastname', $searchArray[0]);
    	$this->db->or_like('email', $searchArray[0]);
    	for ($i=1; $i<count($searchArray); $i++) {
	    	$this->db->or_like('username', $searchArray[$i]);
	    	$this->db->or_like('firstname', $searchArray[$i]);
	    	$this->db->or_like('lastname', $searchArray[$i]);
	    	$this->db->or_like('email', $searchArray[$i]);

	    }
    	if ($this->db->count_all_results('users') > 0)
    	{
    		$this->db->select('*');
			$this->db->from('users');
    		$this->db->like('username', $searchArray[0]);
	    	$this->db->or_like('firstname', $searchArray[0]);
	    	$this->db->or_like('lastname', $searchArray[0]);
	    	$this->db->or_like('email', $searchArray[0]);
	    	for ($i=1; $i<count($searchArray); $i++) {
		    	$this->db->or_like('username', $searchArray[$i]);
		    	$this->db->or_like('firstname', $searchArray[$i]);
		    	$this->db->or_like('lastname', $searchArray[$i]);
		    	$this->db->or_like('email', $searchArray[$i]);
	
		    }
    		return $this->db->get();
    	} else
    		return FALSE;
    }
	
	function get($id)
    {
    	$this->db->select('*');
    	$this->db->from('users');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		return $r;
    	}
    	return FALSE;
    }
    
    function setPhotoloc($id, $photoloc)
    {
    	$data = array(
    		'photoloc' => $photoloc
    	);
    	return $this->db->update('users', $data, array('id' => $id));
    }
	
	function user_list()
	{
		$query = $this->db->get('users');
		$row = $query->result();
    	foreach($row as $r) {
    		$data[$r->id] = $r->username;
    	}
    	return $data;
	}
	
	function user_array()
	{
		$query = $this->db->get('users');
		$row = $query->result();
    	foreach($row as $r) {
    		$data[$r->id] = $r;
    	}
    	return $data;
	}
	
	function photo_list()
	{
		$query = $this->db->get('users');
		$row = $query->result();
    	foreach($row as $r) {
    		$data[$r->id] = $r->photoloc;
    	}
    	return $data;
	}
	
	function create($firstname, $lastname, $email, $username, $password)
	{
		$query = $this->db->get_where('users', array('username' => $username));
		$numRows = $this->db->count_all_results();
		if ($numRows > 0) 
		{
			foreach($query->result() as $row)
			{
				if (!$row->active)
					return -2;
				else
					return -3;
			}
		}
		$query = $this->db->get_where('users', array('email' => $email));
		$numRows = $this->db->count_all_results();
		if ($numRows > 0) 
		{
			foreach($query->result() as $row)
			{
				if (!$row->active)
					return -4;
				else
					return -5;
			}
		}
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->username = $username;
		$this->password = $password;
		if ($this->db->insert('users', $this))
			return 0;
		else
			return -1;
	}
	
	function delete($id)
	{
		return $this->db->delete('users', array('id' => $id));
	}

	function instructors()
	{
		$data = array(
			'active' => TRUE,
			'admin' => TRUE
		);
		return $this->db->get_where('users', $data);
	}

	function participants()
	{
		$data = array(
			'active' => TRUE,
			'admin' => FALSE
		);
		return $this->db->get_where('users', $data);
	}

	function pending()
	{
		$data = array(
			'active' => FALSE
		);
		return $this->db->get_where('users', $data);
	}

	function activate($id)
	{
		$data = array(
			'active' => TRUE
		);
		return $this->db->update('users', $data, array('id' => $id));
	}
	
	function deactivate($id)
	{
		$data = array(
			'active' => FALSE
		);
		return $this->db->update('users', $data, array('id' => $id));
	}
	
	function promote($id)
	{
		$data = array(
			'admin' => TRUE
		);
		return $this->db->update('users', $data, array('id' => $id));
	}
	
	function demote($id)
	{
		$data = array(
			'admin' => FALSE
		);
		return $this->db->update('users', $data, array('id' => $id));
	}

	function authenticate($username, $password, $session)
	{
		$username = strtolower($username);
		$query = $this->db->get_where('users', array('username' => $username), 1, 0);
		foreach($query->result() as $row)
		{
			if ($row->active)
			{
				if ($password == $row->password) 
				{
					$this->id = $row->id;
					$this->firstname = $row->firstname;
					$this->lastname = $row->lastname;
					$this->email = $row->email;
					$this->username = $row->username;
					$this->password = $row->password;
					$this->ip = $session['ip'];
					$this->auth = TRUE;
					$this->admin = $row->admin;
					$this->active = $row->active;
					$this->session_id = $session['id'];
					$this->photoloc = $row->photoloc;
					$this->laston = time();
					
					if($this->db->update('users', $this, array('id' => $this->id)))
						return 0;
					else
						return -3;
				} else return -1;
			} else return -2;
		}
		
		$query = $this->db->get_where('users', array('email' => $username), 1, 0);
		foreach($query->result() as $row)
		{
			if ($row->active)
			{
				if ($password == $row->password) 
				{
					$this->id = $row->id;
					$this->firstname = $row->firstname;
					$this->lastname = $row->lastname;
					$this->email = $row->email;
					$this->username = $row->username;
					$this->password = $row->password;
					$this->ip = $session['ip'];
					$this->auth = TRUE;
					$this->admin = $row->admin;
					$this->active = $row->active;
					$this->session_id = $session['id'];
					$this->photoloc = $row->photoloc;
					$this->laston = time();
					
					if($this->db->update('users', $this, array('id' => $this->id)))
						return 0;
					else
						return -3;
				} else return -1;
			} else return -2;
		}
		return -1;
	}
	
	function is_auth($session)
	{
		$data = array(
			'session_id' => $session['id'],
			'ip' => $session['ip']
		);
		$query = $this->db->get_where('users', $data, 1, 0);
		foreach($query->result() as $row)
		{
			if ($row->auth && $row->active)
			{
				$this->id = $row->id;
				$this->firstname = $row->firstname;
				$this->lastname = $row->lastname;
				$this->email = $row->email;
				$this->username = $row->username;
				$this->password = $row->password;
				$this->ip = $session['ip'];
				$this->auth = $row->auth;
				$this->admin = $row->admin;
				$this->active = $row->active;
				$this->session_id = $session['id'];
				$this->photoloc = $row->photoloc;
				$this->laston = time();
				
				if ($this->db->update('users', $this, array('id' => $this->id)))
					return $this;
				else
					return FALSE;
			}
		}
		return FALSE;
	}
}
