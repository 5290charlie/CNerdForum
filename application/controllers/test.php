<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

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
	
	public function userLower() 
	{
		$this->user->lowerUserNames();
	}
	
	public function testJoin()
	{
		$test = $this->post->testJoin();
		
		foreach($test->result() as $r)
		{
			echo 'Post: ' . $r->id;
			echo 'User: ' . $r->username;
		}
	}
	
	public function gotoComment($post, $comment)
	{
		echo $post . $comment;
	}
	
	public function game()
	{
		$this->load->view('test_game');
	}
	
	public function trophie($mana)
	{
		$data = $this->trophie->getRank($mana);
		$n = $data[0];
		for ($i=1; $i<=$n; $i++) {
			$row = $data[$i];
			echo $row->mana . '-' . $row->rank . '-' . $row->icon . '<br />';
		}
	}
	
	public function allTrophies()
	{
		$mana = 3000;
		$data = $this->trophie->getRank($mana);
		$n = $data[0];
		for ($i=1; $i<=$n; $i++) {
			$row = $data[$i];
			echo '<strong>Rank: </strong>' . $row->rank . ' <img src="/static/images/icons/' . $row->icon . '" />';
			echo 'Requires: [<img src="/static/images/icons/mana.png" width="10px" />' . $row->mana . ']<br />';
		}
	}
	
	public function browser()
	{
		$agent = $_SERVER['HTTP_USER_AGENT'] . "\n\n";
		
		if (strpos($agent, 'MSIE'))
			echo 'fuck';
		else
			echo 'yay';		
	}
	
	public function allPosts()
	{
		echo $this->post->countAllPosts();
	}
	
	public function changeAvatars()
	{
		$users = $this->user->user_array();
		foreach($users as $usr) 
		{
			$commentMana = $this->comment->usersMana($usr->id);
			$postMana = $this->post->usersMana($usr->id);
			$mana = $commentMana + $postMana;
			$rank = $this->trophie->getRank($mana);
			$photo = '/static/images/icons/trophies/' . $rank . '.png';
			if ($this->user->setPhotoloc($usr->id, $photo))
				echo 'changed to ' . $photo;
			else
				echo 'fail';
		}
	}
	
	public function chat()
	{
		$file = '/home/charlie/alpha.cnerdforum.com/public/static/chat';
		if (isset($_POST['chat']))
		{
			$data = "";
			$fh = fopen($file, 'r') or exit('Unable to open chat file!');
			
			$i = 0;
			while(!feof($fh))
			{
				$line = fgets($fh);
				$data .= "$line";
				$i++;
			}
			fclose($fh);
			
			$data .= "<p id='$i'>" . $_POST['chat'] . "</p>\n";
			
			$fh = fopen($file, 'w') or exit('Unable to open chat file!');
	
			fwrite($fh, $data);
			fclose($fh);
			
			$fh = fopen($file, 'r') or exit('Unable to open chat file!');
	
			while(!feof($fh))
			{
				echo fgets($fh) . '<br />';
			}
			fclose($fh);
		} 
		else
		{
			
			$fh = fopen($file, 'r') or exit('Unable to open chat file!');
	
			while(!feof($fh))
			{
				$line = fgets($fh);
				echo $line . "<br />";
			}
			fclose($fh);
		}
	}
}
?>
