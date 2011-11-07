<a href="/">&lt;&lt; All posts</a>
<div class="details">
	<?
	if ($user) {
		if (($user->id == $post->user) || $user->admin) {
			echo '<span class="options"><img onclick="deletePost(' . $post->id . ')" title="Delete Post" src="/static/images/icons/delete.png" /></span>';
		}
	}
	?>
	<div class="title"><? echo $post->title; ?></div>
	<p><? echo $post->details; ?></p>
<div class="clear"></div>
	<div class="info">
		Submitted by: <span onclick="showUserInfo(<? echo $post->user; ?>,'<? echo $user_list[$post->user]; ?>')"><? echo $user_list[$post->user]; ?></span>, 
		On: <? echo date(DATE_FORMAT, $post->date); ?> | Updated: <? echo date(DATE_FORMAT, $post->updated); ?> | 
		<? echo $post->comments; ?> Comments | 
		[<img src="/static/images/icons/mana.png" /><? echo $post->mana; ?>]
	</div>
</div>
<? if ($user) { ?>
<div id="comment-wrapper">
	<textarea id="comment" name="comment" placeholder="Leave a comment..."></textarea>
	<span onclick="leaveComment(<? echo $post->id; ?>)" id="comment-button">Comment</span>
	<div class="clear"></div>
</div>

<? } 
$begin = ($page * COMMENTS_PER_PAGE)+1;
$end = (((COMMENTS_PER_PAGE * ($page+1)) > $total_comments) ? $total_comments : (COMMENTS_PER_PAGE * ($page+1)));
include('comment_paging.php');
?>
<h4>Comments</h4>
<br />
<? if ($comments && ($post->comments > 0)) { $i=0;
	foreach ($comments->result() as $row) { $i++; ?>
		<div id="comment-<? echo $row->id; ?>" class="comment <? echo (($i%2)==0) ? 'alt' : ''; ?>">
			<div class="info">
				<? echo date(DATE_FORMAT, $row->date); ?> | 
				[<img src="/static/images/icons/mana.png" /><? echo $row->mana; ?>]
				<?
				$commentVotes = $comment_votes[$row->id];
				?>
				<span title="<? echo $commentVotes['up']; ?> like(s)/<? echo $commentVotes['down']; ?> dislike(s)" class="manainfo"> [<span class="up"><? echo $commentVotes['up']; ?></span>|<span class="down"><? echo $commentVotes['down']; ?></span>]</span>
			</div>
			<span class="userdata">
				<img src="<? echo $photo_list[$row->user]; ?>" /><br />
				<span class="link" onclick="showUserInfo(<? echo $row->user; ?>,'<? echo $user_list[$row->user]; ?>')"><? echo $user_list[$row->user]; ?></span>
			</span>
			<?
			$upClass = '';
			$downClass = '';
			if ($user) {
				foreach($vote_list->result() as $r) {
					if (($r->post == -1) && ($r->comment == $row->id)) {
						if ($r->value > 0)
							$upClass = 'class="selected"';
						else if ($r->value < 0)
							$downClass = 'class="selected"';
					}
				}
			}
			?>
			<div class="vote">
				<img src="/static/images/icons/mana.png" onclick="upVoteComment('<? echo $row->id; ?>','<? echo $page; ?>')" <? echo $upClass; ?>/><br />
				<? echo $row->mana; ?><br />
				<img src="/static/images/icons/bana.png" onclick="downVoteComment('<? echo $row->id; ?>','<? echo $page; ?>')" <? echo $downClass; ?>/>
			</div>
			<p>
				<? if ($user) { if (($user->id == $row->user) || $user->admin) { ?>
				<span class="options"><img onclick="deleteComment(<? echo $row->id; ?>)" title="Delete Comment" src="/static/images/icons/delete.png" /></span>
				<? } } echo $row->body; ?>
			</p>
			<div class="clear"></div>
			
			
		</div>
<?	} 
} else {
	echo '<div class="center">No Comments</div>';
}

include('comment_paging.php');
?>