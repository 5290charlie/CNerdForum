<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('User', 'user', TRUE);
		$this->load->model('Doc', 'doc', TRUE);
		$this->load->model('Post', 'post', TRUE);
		$this->load->model('Comment', 'comment', TRUE);
		$this->load->model('Vote', 'vote', TRUE);
		$this->load->model('Trophie', 'trophie', TRUE);
	}
	
	public function searchComplete() 
	{
		$data = array();
		$posts = $this->post->getAll();
		foreach($posts->result() as $p)
		{
			array_push($data, $p->title);
		}
		$users = $this->user->getAll();
		foreach($users->result() as $u)
		{
			$userString = $u->firstname . ' ' . $u->lastname . ' - ' . $u->username . ' -- ' . $u->email;
			array_push($data, $userString);
		}

		echo json_encode($data);
	}
	
	public function chat()
	{
		$file = '/home/charlie/alpha.cnerdforum.com/public/static/chatFile';
		$fh = fopen($file, 'r') or exit('Unable to open chat file!');	
		$data['chat_list'] = array();
		
		while(!feof($fh))
		{
			array_push($data['chat_list'], fgets($fh));
		}
		fclose($fh);
		
		if (isset($_POST['chat']) && ($_POST['chat'] != ''))
		{
			$session['id'] = $this->session->userdata('session_id');
			$session['ip'] = $this->session->userdata('ip_address');
			
			if (!($user = $this->user->is_auth($session)))
				$username = 'Nobody';
			else
				$username = $user->username;
				
			$date = date('m-d-Y', time());
			$time = date('h:i:s A', time());
			
			$chatData = "";
			
			foreach($data['chat_list'] as $c)
			{
				$chatData .= $c;
			}
			
			$chatData .= "<span>$date @ $time, $username said:</span><br />" . strip_tags($_POST['chat']) . "\n";
			
			$fh = fopen($file, 'w') or exit('Unable to open chat file!');
	
			fwrite($fh, $chatData);
			fclose($fh);
		} 
		
		$this->load->view('ajax_chat', $data);

	}

	public function userstatus() 
	{
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;

		$this->load->view('ajax_userstatus', $data);
	}
	
	public function content()
	{
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;
		
		if ($data['user']) 
		{
			$data['pending'] = $this->user->pending();
			$data['instructors'] = $this->user->instructors();
			$data['participants'] = $this->user->participants();
		}
		
		$this->load->view('content', $data);
	}
	
	public function upvote_post()
	{
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;
		
		if ($data['user']) 
		{
			if (isset($_POST['id'])) {
				$post = $_POST['id'];
				if ($this->vote->upVotePost($data['user']->id, $post) && $this->post->updateMana($post)) {
					echo 'success';
				} else 
					echo 'failure';
			} 
			else
				echo 'failure';
		} else 
			echo 'failure';
	}
	
	public function downvote_post()
	{
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;
		
		if ($data['user']) 
		{
			if (isset($_POST['id'])) {
				$post = $_POST['id'];
				if ($this->vote->downVotePost($data['user']->id, $post) && $this->post->updateMana($post)) {
					echo 'success';
				} else 
					echo 'failure';
			} 
			else
				echo 'failure';
		} else 
			echo 'failure';
	}
	
	public function upvote_comment()
	{
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;
		
		if ($data['user']) 
		{
			if (isset($_POST['id'])) {
				if ($this->vote->upVoteComment($data['user']->id, $_POST['id']) && $this->comment->updateMana($_POST['id'])) {
					echo 'success';
				} else 
					echo 'failure';
			} 
			else
				echo 'failure';
		} else 
			echo 'failure';
	}
	
	public function downvote_comment()
	{
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;
		
		if ($data['user']) 
		{
			if (isset($_POST['id'])) {
				if ($this->vote->downVoteComment($data['user']->id, $_POST['id']) && $this->comment->updateMana($_POST['id'])) {
					echo 'success';
				} else 
					echo 'failure';
			} 
			else
				echo 'failure';
		} else 
			echo 'failure';
	}
	
	public function user($id) {
		if ($data['user'] = $this->user->get($id))
		{
			$data['numPosts'] = $this->post->numPostBy($id);
			$data['numComments'] = $this->comment->numCommentBy($id);
			$data['postMana'] = $this->post->usersMana($id);
			$data['commentMana'] = $this->comment->usersMana($id);
			$data['userRank'] = $this->trophie->getRank($data['postMana']+$data['commentMana']);
			
			$this->load->view('ajax_user', $data);
		}
	}
	
	public function user_info($id) {
		if ($data['user'] = $this->user->get($id))
		{
			$data['numPosts'] = $this->post->numPostBy($id);
			$data['numComments'] = $this->comment->numCommentBy($id);
			$data['postMana'] = $this->post->usersMana($id);
			$data['commentMana'] = $this->comment->usersMana($id);
			$data['userRank'] = $this->trophie->getRank($data['postMana']+$data['commentMana']);
			
			$this->load->view('ajax_user_info', $data);
		}
	}
	
	public function user_trophies($mana) {
		if ($data['trophies'] = $this->trophie->getTrophies($mana))
		{
			$this->load->view('ajax_user_trophies', $data);
		}		
	}
	
	public function user_posts($id) {
		if ($data['posts'] = $this->post->getUserPosts($id))
		{
			$data['numPosts'] = $this->post->numPostBy($id);
			$data['postMana'] = $this->post->usersMana($id);
			$this->load->view('ajax_user_posts', $data);
		}
	}
	
	public function user_comments($id) {
		if ($data['comments'] = $this->comment->getUserComments($id))
		{
			$data['numComments'] = $this->comment->numCommentBy($id);
			$data['commentMana'] = $this->comment->usersMana($id);
			$this->load->view('ajax_user_comments', $data);
		}
	}
	
	public function posts()
	{
		if (!isset($_POST['sort']))
			$sort = 'updated';
		elseif (empty($_POST['sort']))
			$sort = 'updated';
		else
			$sort = $_POST['sort'];
			
		if (!isset($_POST['way']))
			$way = 'desc';
		elseif(empty($_POST['way']))
			$way = 'desc';
		else
			$way = $_POST['way'];
					
		if (!isset($_POST['page']))
			$page = 0;
		elseif(empty($_POST['page']))
			$page = 0;
		else
			$page = $_POST['page'];
			
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;
			
		$data['posts'] = $this->post->all($sort, $way, $page);
		$data['sort_type'] = $sort;
		$data['sort_way'] = $way;
		$data['page'] = $page;
		$data['user_list'] = $this->user->user_list();
		$data['total_posts'] = $this->post->countAllPosts();
		
		$newData = array();
		
		foreach($data['posts']->result() as $r) {
			$id = $r->id;
			$newData[$id] = $this->vote->getPostVotes($id);
		}
		
		$data['post_votes'] = $newData;
		
		if ($data['user'])
			$data['vote_list'] = $this->vote->getVoteList($data['user']->id);
		
		foreach($this->user->pending()->result() as $usr) {
			$postMana = $this->post->usersMana($usr->id);
			$commentMana = $this->comment->usersMana($usr->id);
			$mana = $postMana + $commentMana;
			$data['mana_list'][$usr->id] = $mana;
			$data['rank_list'][$usr->id] = $this->trophie->getRank($mana);
		}
		
		foreach($this->user->instructors()->result() as $usr) {
			$postMana = $this->post->usersMana($usr->id);
			$commentMana = $this->comment->usersMana($usr->id);
			$mana = $postMana + $commentMana;
			$data['mana_list'][$usr->id] = $mana;
			$data['rank_list'][$usr->id] = $this->trophie->getRank($mana);
		}
		
		foreach($this->user->participants()->result() as $usr) {
			$postMana = $this->post->usersMana($usr->id);
			$commentMana = $this->comment->usersMana($usr->id);
			$mana = $postMana + $commentMana;
			$data['mana_list'][$usr->id] = $mana;
			$data['rank_list'][$usr->id] = $this->trophie->getRank($mana);
		}
		
		$this->load->view('ajax_posts', $data);
	}
	
	public function post($id)
	{
		if ($data['post'] = $this->post->get($id))
		{
			if (!isset($_POST['page']))
				$page = 0;
			elseif(empty($_POST['page']))
				$page = 0;
			else
				$page = $_POST['page'];

			$session['id'] = $this->session->userdata('session_id');
			$session['ip'] = $this->session->userdata('ip_address');
	
			if (!($data['user'] = $this->user->is_auth($session)))
				$data['user'] = FALSE;
			
			$data['user_list'] = $this->user->user_list();
			$data['photo_list'] = $this->user->photo_list();
			$data['comments'] = $this->comment->get($id, $page);
			
			$newData = array();
			
			foreach($data['comments']->result() as $r) {
				$cid = $r->id;
				$newData[$cid] = $this->vote->getCommentVotes($cid);
			}
			
			$data['comment_votes'] = $newData;
			
			$data['page'] = $page;
			$data['total_comments'] = $this->comment->countAllComments($id);
						
			if ($data['user'])
				$data['vote_list'] = $this->vote->getVoteList($data['user']->id);
			
			$this->load->view('ajax_post', $data);
		}
	}
	
	public function post_form()
	{
		if (isset($_POST['type']))
		{
			switch($_POST['type'])
			{
				case 'link':
					echo '<label for="upload-link">Link: </label>';
					echo '<input type="text" id="upload-link" name="link" />';
					echo '<br />';
					break;
				default:
					break;
			}
		}
	}
	
	public function gotoComment($post, $comment)
	{
		if ($data['post'] = $this->post->get($post))
		{
			$commentPos = $this->comment->getCommentPos($post, $comment);
			$data['total_comments'] = $this->comment->countAllComments($post);
			
			$ratio = $commentPos / COMMENTS_PER_PAGE;
			$numPages =  floor($data['total_comments'] / COMMENTS_PER_PAGE);
			$page = $ratio % $numPages;
						
			$session['id'] = $this->session->userdata('session_id');
			$session['ip'] = $this->session->userdata('ip_address');
	
			if (!($data['user'] = $this->user->is_auth($session)))
				$data['user'] = FALSE;
			
			$data['user_list'] = $this->user->user_list();
			$data['photo_list'] = $this->user->photo_list();
			$data['comments'] = $this->comment->get($post, $page);
			$data['page'] = $page;
						
			if ($data['user'])
				$data['vote_list'] = $this->vote->getVoteList($data['user']->id);
			
			$this->load->view('ajax_post', $data);
		}
	}
	
	public function users()
	{
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');

		if (!($data['user'] = $this->user->is_auth($session)))
			$data['user'] = FALSE;
		
		if ($data['user']) 
		{
			$data['pending'] = $this->user->pending();
			$data['instructors'] = $this->user->instructors();
			$data['participants'] = $this->user->participants();
		}
		
		foreach($data['pending']->result() as $usr) {
			$postMana = $this->post->usersMana($usr->id);
			$commentMana = $this->comment->usersMana($usr->id);
			$mana = $postMana + $commentMana;
			$data['mana_list'][$usr->id] = $mana;
			$data['rank_list'][$usr->id] = $this->trophie->getRank($mana);
		}
		
		foreach($data['instructors']->result() as $usr) {
			$postMana = $this->post->usersMana($usr->id);
			$commentMana = $this->comment->usersMana($usr->id);
			$mana = $postMana + $commentMana;
			$data['mana_list'][$usr->id] = $mana;
			$data['rank_list'][$usr->id] = $this->trophie->getRank($mana);
		}
		
		foreach($data['participants']->result() as $usr) {
			$postMana = $this->post->usersMana($usr->id);
			$commentMana = $this->comment->usersMana($usr->id);
			$mana = $postMana + $commentMana;
			$data['mana_list'][$usr->id] = $mana;
			$data['rank_list'][$usr->id] = $this->trophie->getRank($mana);
		}
		
		$this->load->view('ajax_users', $data);
	}
	
	public function updateAvatars()
	{
		$users = $this->user->user_array();
		foreach($users as $usr) 
		{
			$commentMana = $this->comment->usersMana($usr->id);
			$postMana = $this->post->usersMana($usr->id);
			$mana = $commentMana + $postMana;
			$rank = $this->trophie->getRank($mana);
			if ($rank == 'Not Ranked')
				$photo = '/static/images/users/default.png';
			else
				$photo = '/static/images/icons/trophies/' . $rank . '.png';
			if ($this->user->setPhotoloc($usr->id, $photo))
				echo 'changed to ' . $photo;
			else
				echo 'fail';
		}
	}
}

?>
