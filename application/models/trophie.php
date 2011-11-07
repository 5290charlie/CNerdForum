<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trophie extends CI_Model {
	
	var $id = 0;
	var $rank = '';
	var $mana = 0;
	var $icon = '';

	function __construct()
	{
		parent::__construct();
	}
	
	function get($id)
    {
    	$this->db->select('*');
    	$this->db->from('trophies');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		return $r;
    	}
    	return FALSE;
    }
    
    function getTrophies($mana)
    {
    	$query = $this->db->get('trophies');
    	$i=0;
    	foreach($query->result() as $row) {
    		if ($mana >= $row->mana) {
    			$i++;
    			$data[$i] = $row;
    		} else {
    			break;
    		}
       	}
       	$data[0] = $i;
    	return $data;
    }	
    
    function getRank($mana)
    {
    	$query = $this->db->get('trophies');
    	$rank = '';
    	foreach($query->result() as $row) {
    		if ($mana >= $row->mana) {
    			$rank = $row->rank;
    		} else {
    			break;
    		}
       	}
       	if ($rank == '')
       		$rank = 'Not Ranked';
    	return $rank;
    }
}