<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote extends CI_Model {

	var $id = 0;
	var $user = 0;
	var $post = -1;
	var $comment = -1;
	var $value = 0;

    function __construct()
    {
        parent::__construct();
    }
    
    function deletePostVotes($id)
    {
    	$data = array(
    		'post' => $id
    	);
    	$query = $this->db->get_where('comments', $data);
    	
    	foreach($query->result() as $r)
    	{
    		$data2 = array(
    			'comment' => $r->id
    		);
    		if (!$this->db->delete('votes', $data2))
    			return FALSE;
    	}
    	
    	return $this->db->delete('votes', $data);
    }
    
    function deleteCommentVotes($id)
    {
    	$data = array(
    		'comment' => $id
    	);
    	return $this->db->delete('votes', $data);
    }
    
    function getPostVotes($id)
    {
    	$this->db->select('*');
    	$this->db->from('votes');
    	$data = array(
    		'post' => $id,
    		'value' => 1
    	);
    	$this->db->where($data);
    	$ups = $this->db->count_all_results();
    	$this->db->select('*');
    	$this->db->from('votes');
    	$data = array(
    		'post' => $id,
    		'value' => -1
    	);
    	$this->db->where($data);
    	$downs = $this->db->count_all_results();

		$ret = array(
			'up' => $ups,
			'down' => $downs
		);
		return $ret;
    }
    
    function getCommentVotes($id)
    {
    	$this->db->select('*');
    	$this->db->from('votes');
    	$data = array(
    		'comment' => $id,
    		'value' => 1
    	);
    	$this->db->where($data);
    	$ups = $this->db->count_all_results();
    	$this->db->select('*');
    	$this->db->from('votes');
    	$data = array(
    		'comment' => $id,
    		'value' => -1
    	);
    	$this->db->where($data);
    	$downs = $this->db->count_all_results();

		$ret = array(
			'up' => $ups,
			'down' => $downs
		);
		return $ret;
    }
    
    function upVotePost($user, $post)
    {
    	$this->db->select("*");
    	$this->db->from('votes');
    	$data = array(
    		'user' => $user,
    		'post' => $post,
    		'comment' => -1
    	);
    	$this->db->where($data);
    	$numRows = $this->db->count_all_results();
    	if ($numRows == 1) {
	    	$query = $this->db->get_where('votes', $data, 1, 0);
	    	$value = 0;
	    	foreach($query->result() as $row) {
	    		$value = $row->value;
	    		$id = $row->id;
	    		
	    		if ($value == 1)
		    		$newValue = 0;
		    	else
		    		$newValue = 1;
		    		
	    		$data = array(
	    			'value' => $newValue
	    		);
	    	}
	    	$this->db->where('id', $id);
	    	return $this->db->update('votes', $data);
	    } else {
	    	$this->user = $user;
	    	$this->post = $post;
	    	$this->value = 1;
	    	return $this->db->insert('votes', $this);
	    }
    }
    
    function downVotePost($user, $post)
    {
    	$this->db->select("*");
    	$this->db->from('votes');
    	$data = array(
    		'user' => $user,
    		'post' => $post,
    		'comment' => -1
    	);
    	$this->db->where($data);
    	$numRows = $this->db->count_all_results();
    	if ($numRows == 1) {
	    	$query = $this->db->get_where('votes', $data, 1, 0);
	    	$value = 0;
	    	foreach($query->result() as $row) {
	    		$value = $row->value;
	    		$id = $row->id;
	    		
	    		if ($value == -1)
		    		$newValue = 0;
		    	else
		    		$newValue = -1;
		    		
	    		$data = array(
	    			'value' => $newValue
	    		);
	    	}
	    	$this->db->where('id', $id);
	    	return $this->db->update('votes', $data);
	    } else {
	    	$this->user = $user;
	    	$this->post = $post;
	    	$this->value = -1;
	    	return $this->db->insert('votes', $this);
	    }
    }
    
    function upVoteComment($user, $comment)
    {
    	$this->db->select("*");
    	$this->db->from('votes');
    	$data = array(
    		'user' => $user,
    		'post' => -1,
    		'comment' => $comment
    	);
    	$this->db->where($data);
    	$numRows = $this->db->count_all_results();
    	if ($numRows == 1) {
	    	$query = $this->db->get_where('votes', $data, 1, 0);
	    	$value = 0;
	    	foreach($query->result() as $row) {
	    		$value = $row->value;
	    		$id = $row->id;
	    		
	    		if ($value == 1)
		    		$newValue = 0;
		    	else
		    		$newValue = 1;
		    		
	    		$data = array(
	    			'value' => $newValue
	    		);
	    	}
	    	$this->db->where('id', $id);
	    	return $this->db->update('votes', $data);
	    } else {
	    	$this->user = $user;
	    	$this->comment = $comment;
	    	$this->value = 1;
	    	return $this->db->insert('votes', $this);
	    }
    }
    
    function downVoteComment($user, $comment)
    {
    	$this->db->select("*");
    	$this->db->from('votes');
    	$data = array(
    		'user' => $user,
    		'post' => -1,
    		'comment' => $comment
    	);
    	$this->db->where($data);
    	$numRows = $this->db->count_all_results();
    	if ($numRows == 1) {
	    	$query = $this->db->get_where('votes', $data, 1, 0);
	    	$value = 0;
	    	foreach($query->result() as $row) {
	    		$value = $row->value;
	    		$id = $row->id;
	    		
	    		if ($value == -1)
		    		$newValue = 0;
		    	else
		    		$newValue = -1;
		    		
	    		$data = array(
	    			'value' => $newValue
	    		);
	    	}
	    	$this->db->where('id', $id);
	    	return $this->db->update('votes', $data);
	    } else {
	    	$this->user = $user;
	    	$this->comment = $comment;
	    	$this->value = -1;
	    	return $this->db->insert('votes', $this);
	    }
    }
    
    function getPostVoteList() {
    	$this->db->where('post !=', -1);
    	$query = $this->db->get('votes');
    	
    	$data = array();
    	
    	foreach($query->result() as $row) {
    		$data[$row->post][$row->user] = $row->value;	
    	}
    	
    	return $data;
    }
    
    function getVoteList($user) {
    	$data = array(
    		'user' => $user
    	);
    	return $this->db->get_where('votes', $data);
    }
    
    function insertPost($user, $post)
    {
    	$this->user = $user;
    	$this->post = $post;
    	return $this->db->insert('votes', $this);
    }
    
    function insertComment($user, $post, $comment)
    {
    	$this->user = $user;
    	$this->post = $post;
    	$this->comment = $comment;
    	return $this->db->insert('votes', $this);
    }
    
    function postVote($user, $post, $value)
    {
    	$this->user = $user;
    	$this->post = $post;
    	$this->value = $value;
    	return $this->db->insert('votes', $this);
    }
    
    function allPostVotes($user)
    {
    	$this->db->select("*");
    	$where = array(
    		'user' => $user,
    		'comment' => -1
    	);
    	$this->db->where($where);
    	$query = $this->db->get('votes');
    	$data = array();
    	
    	foreach($query->result() as $row) {
    		$data[$row->post] = $row->value;
    	}
    	
    	return $data;
    }
    
    function commentVote($user, $post, $comment, $value)
    {
    	$this->user = $user;
    	$this->post = $post;
    	$this->comment = $comment;
    	$this->value = $value;
    	$query = $this->db->insert('votes', $this);
    	$data = array();
    	
    	foreach($query->result() as $row) {
    		$data[$row->comment] = $row->value;
    	}
    	
    	return $data;
    }
    
    function allCommentVotes($user, $post)
    {
    	$this->db->select("*");
    	$where = array(
    		'user' => $user,
    		'post' => $post
    	);
    	$this->db->where($where);
    	return $this->db->get('votes');
    }
}
?>
