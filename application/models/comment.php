<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends CI_Model {

	var $id = 0;
	var $post = 0;
	var $body = '';
	var $date = '';
	var $user = '';
	var $replies = 0;
	var $mana = 0;

    function __construct()
    {
        parent::__construct();
    }
    
    function getAll() 
    {
    	return $this->db->get('comments');
    }
    
    function insert($post, $body, $user)
    {
    	$this->post = $post;
    	$this->body = $body;
    	$this->date = time();
    	$this->user = $user;
    	return $this->db->insert('comments', $this);
    }
    
    function searchComments($search)
    {
    	$searchArray = explode(" ", $search);
    	$this->db->select('*');
    	$this->db->like('body', $searchArray[0]);
    	for ($i=1; $i<count($searchArray); $i++) {
	    	$this->db->or_like('body', $searchArray[$i]);
	    }
    	if ($this->db->count_all_results('comments') > 0)
    	{
    		$this->db->select('*');
			$this->db->from('comments');
    		$this->db->like('body', $searchArray[0]);
	    	for ($i=1; $i<count($searchArray); $i++) {
		    	$this->db->or_like('body', $searchArray[$i]);
		    }
    		return $this->db->get();
    	} else
    		return FALSE;
    }
    
    function getUserComments($id)
    {
    	$data = array(
    		'user' => $id
    	);
    	$this->db->order_by('mana', 'desc');
    	return $this->db->get_where('comments', $data);
    }
    
    function countAllComments($id)
    {
    	$this->db->where('post', $id);
    	return $this->db->count_all_results('comments');
    }
    
    function getCommentPos($pid, $cid)
    {
		$data = array(
			'post' => $pid
		);
		$this->db->order_by('id', 'desc');
    	$query = $this->db->get_where('comments', $data);
    	$pos = 0;
    	foreach($query->result() as $r)
    	{
    		if ($r->id == $cid)
    			return $pos;
    		else
    			$pos++;
    	}
    	
    	return -1;
    }
    
    function updateMana($id)
    {
    	$this->db->select('*');
    	$this->db->from('votes');
    	$this->db->where('comment', $id);
    	$query = $this->db->get();
    	$mana = 0;
    	foreach($query->result() as $row) {
    		$mana += $row->value;
    	}
    	
    	$data = array(
   			'mana' => $mana
   		);
   		
   		$this->db->where('id', $id);
   		return $this->db->update('comments', $data);
    }
    
    function userOf($id)
    {
    	$this->db->select('*');
    	$this->db->from('comments');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		return $r->user;
    	}
    	return FALSE;
    }
    	
   	function upVote($id)
   	{
   		$this->db->select('*');
    	$this->db->from('comments');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		$mana = $r->mana;
    	}
    	
    	$mana++;
    	
   		$data = array(
   			'mana' => $mana
   		);
   		$this->db->where('id', $id);
   		return $this->db->update('comments', $data);
   	}
   	
   	function downVote($id)
   	{
   		$this->db->select('*');
    	$this->db->from('comments');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		$mana = $r->mana;
    	}
    	
    	$mana--;
    	
   		$data = array(
   			'mana' => $mana
   		);
   		$this->db->where('id', $id);
   		return $this->db->update('comments', $data);
   	}
    
    function delete($id)
    {
    	$this->db->where('id', $id);
    	return $this->db->delete('comments');
    }
    
    function deletePost($id)
    {
    	$this->db->where('post', $id);
    	return $this->db->delete('comments');
    }
    
    function get($pid, $page)
    {
    	$this->db->order_by("date", "desc"); 
    	$this->db->where('post', $pid);
    	return $this->db->get('comments', COMMENTS_PER_PAGE, ($page * COMMENTS_PER_PAGE));
    }
    
    function getOne($cid)
    {
    	$this->db->select('*');
    	$this->db->from('comments');
    	$this->db->where('id', $cid);
    	$query = $this->db->get();
    	foreach($query->result() as $row) {
    		return $row;
    	}
    	return FALSE;
    }
    
    function numCommentBy($user)
    {
    	$this->db->select('*');
    	$this->db->from('comments');
    	$this->db->where('user', $user);
    	$query = $this->db->get();
    	$row = $query->result();
    	$i = 0;
    	foreach($row as $r) {
    		$i++;
    	}
    	return $i;
    }
    
    function usersMana($user)
    {
    	$this->db->select('*');
    	$this->db->from('comments');
    	$this->db->where('user', $user);
    	$query = $this->db->get();
    	$row = $query->result();
    	$mana = 0;
    	foreach($row as $r) {
    		$mana += $r->mana;
    	}
    	return $mana;
    }
}
?>
