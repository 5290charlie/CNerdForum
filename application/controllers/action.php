<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function genRand($length) {
	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$str = '';
	
	for ($i=0; $i<$length; $i++) {
		$str .= $chars[mt_rand(0, strlen($chars)-1)];
	}
	
	return $str;
}

class Action extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('User', 'user', TRUE);
		$this->load->model('Post', 'post', TRUE);
		$this->load->model('Doc', 'doc', TRUE);
		$this->load->model('Comment', 'comment', TRUE);
		$this->load->model('Vote', 'vote', TRUE);
	}

	public function register()
	{
		$result = 'error';
		if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm']))
		{
			$fname = $_POST['firstname'];
			$lname = $_POST['lastname'];
			$email = $_POST['email'];
			$username = $_POST['username'];
			$pass = $_POST['password'];
			$conf = $_POST['confirm'];
			
			if ($fname == '')
				$msg = 'Please provide your firstname';
			else if ($lname == '')
				$msg = 'Please provide your lastname';
			else if ($email == '')
				$msg = 'Please provide your email';
			else if ($username == '')
				$msg = 'Please provide your username';
			else if ($pass == $conf)
			{
				if (strlen($pass) > 5)
				{
					$create = $this->user->create($fname, $lname, $email, $username, base64_encode($pass));
					
					switch($create) 
					{
						case 0:
							$result = 'success';
							break;
						case -1:
							$msg = 'Error creating user account';
							break;
						case -2:
							$msg = "User '$username' is pending activation";
							break;
						case -3:
							$msg = "Username '$username' is already taken";
							break;
						case -4:
							$msg = "Email '$email' is pending activation";
							break;
						case -5:
							$msg = "Email '$email' is already registered";
							break;
						default:
							$msg = 'Error creating user account';
							break;
					}
				}
				else
					$msg = 'Password must be at least 6 characters';
			}
			else
				$msg = 'Passwords do not match';
		}
		else
			$msg = 'Missing data';
			
		if($result == 'success')
			$msg = 'Account created';
		
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function login()
	{
		$result = 'error';
		
		if(isset($_POST['username']) && isset($_POST['password']))
		{
			$username = $_POST['username'];
			$pass = $_POST['password'];
			$session['id'] = $this->session->userdata('session_id');
			$session['ip'] = $this->session->userdata('ip_address');
			$auth = $this->user->authenticate($username, base64_encode($pass), $session);
			
			switch($auth)
			{
				case 0:
					$result = 'success';
					break;
				case -1:
					$msg = "Invalid credentials";
					break;
				case -2:
					$msg = "Username '$username' is pending activation";
					break;
				default:
					$msg = "Error logging in";
					break;
			}
		}
		else
			$msg = 'Missing data';
			
		if($result == 'success')
			$msg = 'Logged in';
		
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		
		$data['result'] = 'success';
		$data['msg'] = 'Logged out';
		
		$this->load->view('action_msg', $data);
	}

	public function activate($id)
	{
		$result = 'error';
		
		if ($this->user->activate($id))
			$result = 'success';
		else
			$msg = 'Error activating account';
		
		if($result == 'success')
			$msg = 'Account activated';
		
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function deactivate($id)
	{
		$result = 'error';

		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');
		
		$user = $this->user->is_auth($session);
		
		if ($user->id == $id)
		{
			$msg = 'Cannot deactivate yourself';
		} 
		else
		{		
			if ($this->user->deactivate($id))
				$result = 'success';
			else
				$msg = 'Error deactivating account';
			
			if($result == 'success')
				$msg = 'Account deactivated';
		}
				
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function promote($id)
	{
		$result = 'error';
		
		if ($this->user->promote($id))
			$result = 'success';
		else
			$msg = 'Error promoting account';
		
		if($result == 'success')
			$msg = 'Account promoted';
		
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function demote($id)
	{
		$result = 'error';
		
		$session['id'] = $this->session->userdata('session_id');
		$session['ip'] = $this->session->userdata('ip_address');
		
		$user = $this->user->is_auth($session);
		
		if ($user->id == $id)
		{
			$msg = 'Cannot demote yourself';
		} 
		else
		{
			if ($this->user->demote($id))
				$result = 'success';
			else
				$msg = 'Error demoting account';
			
			if($result == 'success')
				$msg = 'Account demoted';
		}
				
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function delete($id)
	{
		$result = 'error';
		
		if ($this->user->delete($id))
			$result = 'success';
		else
			$msg = 'Error deleting account';
		
		if($result == 'success')
			$msg = 'Account deleted';
		
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function upload()
	{
		$result = 'error';
		$msg = 'Error creating post';
		
		if (isset($_POST['title']) && isset($_POST['details']))
		{
			$title = $_POST['title'];
			$details = $_POST['details'];
			
			$session['id'] = $this->session->userdata('session_id');
			$session['ip'] = $this->session->userdata('ip_address');
			
			$user = $this->user->is_auth($session);			
			
			if (empty($title))
				$msg = 'Please provide a title';
			else if (empty($details))
				$msg = 'Please provide some details';
			else if ($this->post->insert($title, $details, $user->id)) {
				$result = 'success';
			} else
				$msg = 'Error creating post';
		} 
		else 
			$msg = 'Please provide all data';

		if ($result == 'success')
			$msg = 'Post created';
			
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function comment($pid)
	{
		$result = 'error';
		$msg = 'Error leaving comment';
		
		if (isset($_POST['comment']))
		{
			$comment = $_POST['comment'];
			
			$session['id'] = $this->session->userdata('session_id');
			$session['ip'] = $this->session->userdata('ip_address');
			
			$user = $this->user->is_auth($session);			
			
			if (empty($comment))
				$msg = 'Please provide a comment';
			else if ($this->comment->insert($pid, $comment, $user->id) && $this->post->addComment($pid))
				$result = 'success';
			else
				$msg = 'Error leaving comment';
		} 
		else 
			$msg = 'Please provide all data';

		if ($result == 'success')
			$msg = 'Comment left';
			
		$data['result'] = $result;
		$data['msg'] = $msg;
		
		$this->load->view('action_msg', $data);
	}
	
	public function deletePost($id)
	{
		$result = 'error';
		$msg = 'Error deleting post';
		
		if (isset($id))
		{
			$this->vote->deletePostVotes($id);
			if ($this->post->delete($id) && $this->comment->deletePost($id))
				$result = 'success';
			else
				$msg = 'Error deleting post';
		}
		else
			$msg = 'Invalid post ID';
			
		if ($result == 'success')
			$msg = 'Post deleted';
			
		$data['result'] = $result;
		$data['msg'] = $msg;
		$this->load->view('action_msg', $data);
	}
	
	public function deleteComment($id)
	{
		$result = 'error';
		$msg = 'Error deleting comment';
		if (isset($id))
		{
			$com = $this->comment->getOne($id);
			$this->vote->deleteCommentVotes($id);
			if ($this->post->delComment($com->post) && $this->comment->delete($id))
					$result = 'success';
		}
		else
			$msg = 'Invalid comment ID';
			
		if ($result == 'success')
			$msg = 'Comment deleted';
			
		$data['result'] = $result;
		$data['msg'] = $msg;
		$this->load->view('action_msg', $data);
	}
}

?>
