<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Model {

	var $id = 0;
	var $title = '';
	var $type = '';
	var $details = '';
	var $link = '';
	var $date = '';
	var $updated = '';
	var $user = '';
	var $comments = 0;
	var $mana = 0;

    function __construct()
    {
        parent::__construct();
    }
    
    function getAll() 
    {
    	return $this->db->get('posts');
    }
    
    function testJoin()
    {
    	$this->db->select('*');
    	$this->db->from('posts');
    	$this->db->join('users', 'posts.user = users.id', 'right inner');
    	return $this->db->get();
    }
    
    function insert($title, $details, $user)
    {
    	$this->title = $title;
    	$this->details = $details;
    	$this->date = time();
    	$this->updated = time();
    	$this->user = $user;
    	return $this->db->insert('posts', $this);
    }
    
    function countAllPosts()
    {
    	return $this->db->count_all_results('posts');
    }
    
    function searchPosts($search) {
    	$searchArray = explode(" ", $search);
    	$this->db->select('*');
    	$this->db->like('title', $searchArray[0]);
    	$this->db->or_like('details', $searchArray[0]);
    	for ($i=1; $i<count($searchArray); $i++) {
	    	$this->db->or_like('title', $searchArray[$i]);
	    	$this->db->or_like('details', $searchArray[$i]);
	    }
    	if ($this->db->count_all_results('posts') > 0)
    	{
    		$this->db->select('*');
			$this->db->from('posts');
    		$this->db->like('title', $searchArray[0]);
	    	$this->db->or_like('details', $searchArray[0]);
	    	for ($i=1; $i<count($searchArray); $i++) {
		    	$this->db->or_like('title', $searchArray[$i]);
		    	$this->db->or_like('details', $searchArray[$i]);
		    }
    		return $this->db->get();
    	} else
    		return FALSE;
	}
    
    function getUserPosts($id)
    {
    	$data = array(
    		'user' => $id
    	);
    	$this->db->order_by('mana', 'desc');
    	return $this->db->get_where('posts', $data);
    }
    
    function updateMana($id)
    {
    	$this->db->select('*');
    	$this->db->from('votes');
    	$this->db->where('post', $id);
    	$query = $this->db->get();
    	$mana = 0;
    	foreach($query->result() as $row) {
    		$mana += $row->value;
    	}
    	
    	$data = array(
   			'mana' => $mana
   		);
   		
   		$this->db->where('id', $id);
   		return $this->db->update('posts', $data);
    }
    
    function userOf($id)
    {
    	$this->db->select('*');
    	$this->db->from('posts');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		return $r->user;
    	}
    	return FALSE;
    }
    	
    function addComment($id)
    {
    	$this->db->select('*');
    	$this->db->from('posts');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		$comments = $r->comments;
    	}
    	
    	$comments++;
    	
   		$data = array(
   			'comments' => $comments,
   			'updated' => time()
   		);
   		$this->db->where('id', $id);
   		return $this->db->update('posts', $data);
    }
    
    function delComment($id)
    {
    	$this->db->select('*');
    	$this->db->from('posts');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		$comments = $r->comments;
    	}
    	
    	$comments--;
    	
   		$data = array(
   			'comments' => $comments
   		);
   		$this->db->where('id', $id);
   		return $this->db->update('posts', $data);
    }
    	
   	function upVote($id)
   	{
   		$this->db->select('*');
    	$this->db->from('posts');
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
   		return $this->db->update('posts', $data);
   	}
   	
   	function downVote($id)
   	{
   		$this->db->select('*');
    	$this->db->from('posts');
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
   		return $this->db->update('posts', $data);
   	}
    
    function delete($id)
    {
    	$this->db->where('id', $id);
    	return $this->db->delete('posts');
    }
    
    function get($id)
    {
    	$this->db->select('*');
    	$this->db->from('posts');
    	$this->db->where('id', $id);
    	$query = $this->db->get();
    	$row = $query->result();
    	foreach($row as $r) {
    		return $r;
    	}
    	return FALSE;
    }
    
    function numPostBy($user)
    {
    	$this->db->select('*');
    	$this->db->from('posts');
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
    	$this->db->from('posts');
    	$this->db->where('user', $user);
    	$query = $this->db->get();
    	$row = $query->result();
    	$mana = 0;
    	foreach($row as $r) {
    		$mana += $r->mana;
    	}
    	return $mana;
    }
    
    function setTitle($id, $title)
    {
    	$data = array(
    		'title' => $title
    	);
    	$this->db->where('id', $id);
    	return $this->db->update('posts', $data);
    
    }
    
    function setDetails($id, $details)
    {
    	$data = array(
    		'details' => $details
    	);
    	$this->db->where('id', $id);
    	return $this->db->update('posts', $data);
    }
    
    function setUser($id, $user)
    {
    	$data = array(
    		'user' => $user
    	);
    	$this->db->where('id', $id);
    	return $this->db->update('posts', $data);
    }
    
    function setAll($id, $title, $details, $user)
    {
    	$data = array(
    		'title' => $title,
    		'details' => $details,
    		'user' => $user
    	);
    	$this->db->where('id', $id);
    	return $this->db->update('posts', $data);
    }
    
    function all($sort, $way, $page)
    {
    	$this->db->order_by($sort, $way); 
    	$query = $this->db->get('posts', POSTS_PER_PAGE, ($page * POSTS_PER_PAGE));
    	return($query);
    }
}
?>
