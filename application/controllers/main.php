<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('User', 'user', TRUE);
		$this->load->model('Doc', 'doc', TRUE);
		$this->load->model('Post', 'post', TRUE);
		$this->load->model('Vote', 'vote', TRUE);
		$this->load->model('Comment', 'comment', TRUE);
	}
	
	public function search() {
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
		
		if (isset($_GET['result']) && isset($_GET['msg'])) 
		{
			$data['result'] = $_GET['result'];
			$data['msg'] = $_GET['msg'];
		}
				
		if (isset($_POST['search'])) {
			$search = $_POST['search'];
			$data['search'] = $search;
			$data['postResults'] = $this->post->searchPosts($search);
			$data['commentResults'] = $this->comment->searchComments($search);
			$data['userResults'] = $this->user->searchUsers($search);
			$data['pNum'] = 0;
			$data['cNum'] = 0;
			$data['uNum'] = 0;
			if ($data['postResults'])
				foreach($data['postResults']->result() as $p) 
					$data['pNum']++;
			if ($data['commentResults'])
				foreach($data['commentResults']->result() as $c)
					$data['cNum']++;
			if ($data['userResults'])
				foreach($data['userResults']->result() as $u) 
					$data['uNum']++;
		
		} else {
			$data['search'] = FALSE;	
		}
					
		$this->load->view('main_search', $data);
	}


	public function index()
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
		
		if (isset($_GET['result']) && isset($_GET['msg'])) 
		{
			$data['result'] = $_GET['result'];
			$data['msg'] = $_GET['msg'];
		}
		
		$this->load->view('main_index', $data);
	}

	public function post($id)
	{
		if ($data['post'] = $this->post->get($id))
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
			
			if (isset($_GET['result']) && isset($_GET['msg'])) 
			{
				$data['result'] = $_GET['result'];
				$data['msg'] = $_GET['msg'];
			}
			
			if (!isset($_POST['page']))
				$page = 0;
			elseif(empty($_POST['page']))
				$page = 0;
			else
				$page = $_POST['page'];
				
			$data['user_list'] = $this->user->user_list();
			$data['comments'] = $this->comment->get($id, $page);
			$this->load->view('main_post', $data);
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
			
			echo $page;
			
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
	
	public function register()
	{
		$this->load->view('main_register');
	}
	
	public function login()
	{
		$this->load->view('main_login');
	}
	
	public function download($id)
	{
		if ($doc = $this->doc->get($id))
		{
			$filename = $doc->name;
			$file = $doc->path;
		
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		}
	}
}

?>
