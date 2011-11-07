<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doc extends CI_Model {
	
	var $id = 0;
	var $name = '';
	var $path = '';
	var $type = '';
	var $size = 0;
	var $date = '';
	var $subject = '';

	function __construct()
	{
		parent::__construct();
	}

	function upload($name, $path, $type, $size)
	{
		$this->name = $name;
		$this->path = $path;
		$this->type = $type;
		$this->size = $size;
		$this->date = time();
		return $this->db->insert('docs', $this);
	}
	
	function delete($id)
	{
		return $this->db->delete('docs', array('id' => $id));
	}
	
	function all()
	{
		return $this->db->get('docs');
	}
	
	function get($id)
	{
		$query = $this->db->get_where('docs', array('id' => $id), 1, 0);
		foreach($query->result() as $r) 
		{
			return $r;
		}
		
		return FALSE;
	}
}
