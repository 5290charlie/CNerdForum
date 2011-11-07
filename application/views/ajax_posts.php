<?
$begin = ($page * POSTS_PER_PAGE)+1;
$end = (((POSTS_PER_PAGE * ($page+1)) > $total_posts) ? $total_posts : (POSTS_PER_PAGE * ($page+1)));

$popular = '';
$AtoZ = '';
$recent = '';
switch($sort_type) {
	case 'mana':
		$popular = 'class="selected"';
		break;
	case 'title':
		$AtoZ = 'class="selected"';
		break;
	default:
		$recent = 'class="selected"';
		break;
}

switch($sort_way) {
	case 'asc':
		$current_way = 'Ascending';
		$next_way = 'desc';
		break;
	default:
		$current_way = 'Descending';
		$next_way = 'asc';
		break;
}

$sort_parms = "'$sort_type','$sort_way'";
include('post_paging.php'); ?>
<div id="new-post">
<?php 
if ($user) { ?>
	<span class="ui-button ui-widget ui-state-default ui-corner-all" role="button" onclick="showUpload()" id="upload-button">New Post</span>
<? } else { ?>
<span>Login to Post/Comment</span>
<? } ?>
</div>
<input type="hidden" id="sort-type" value="<? echo $sort_type; ?>" />
<input type="hidden" id="sort-way" value="<? echo $sort_way; ?>" />
<span id="sorting">
	<span <? echo $recent; ?> onclick="sortPosts(<? echo $page; ?>,'updated','<? echo $sort_way; ?>')">Recent</span> | 
	<span <? echo $popular; ?> onclick="sortPosts(<? echo $page; ?>,'mana','<? echo $sort_way; ?>')">Popular</span> | 
	<span <? echo $AtoZ; ?> onclick="sortPosts(<? echo $page; ?>,'title','<? echo $sort_way; ?>')">A-Z</span> 
	(<span onclick="sortPosts(<? echo $page; ?>,'<? echo $sort_type; ?>','<? echo $next_way; ?>')"><? echo $current_way; ?></span>)
</span>
<h1>All Posts</h1>
<?php if ($posts) { $i=0;
	foreach($posts->result() as $p) { $i++; ?>
		<div class="post <? echo (($i%2)==0) ? 'alt' : ''; ?>">
		<?
		$postVotes = $post_votes[$p->id];
		?>
		<div title="<? echo $postVotes['up']; ?> like(s)/<? echo $postVotes['down']; ?> dislike(s)" class="manainfo">[<span class="up"><? echo $postVotes['up']; ?></span>|<span class="down"><? echo $postVotes['down']; ?></span>]</div>
		<div class="clear"></div>
		<?
		/*
		$upClass = '';
		$downClass = '';
		if (isset($post_votes[$p->id])) {
			if ($post_votes[$p->id] == 'up') {
				$upClass = 'class="voted"';
				$downClass = 'onclick="downVotePost(' . $p->id . ')"';
			} elseif ($post_votes[$p->id] == 'down') {
				$upClass = 'onclick="upVotePost(' . $p->id . ')"';
				$downClass = 'class="voted"';
			}
		} else {
			$upClass = 'onclick="upVotePost(' . $p->id . ')"';
			$downClass = 'onclick="downVotePost(' . $p->id . ')"';
		}
		*/
		$sort_parms = "'$sort_type','$sort_way'";
		$full_parms = $page . ',' . $sort_parms;
		$upClass = 'onclick="upVotePost(' . $p->id . ',' . $full_parms . ')"';
		$downClass = 'onclick="downVotePost(' . $p->id . ',' . $full_parms . ')"';
		echo '<div class="vote">';
		if ($user) {
			foreach($vote_list->result() as $row) {
				if (($row->comment == -1) && ($row->post == $p->id)) {
					if ($row->value > 0)
						$upClass .= ' class="selected"';
					else if ($row->value < 0)
						$downClass .= ' class="selected"';
				}
			}
		}
		echo '<img src="/static/images/icons/mana.png" ' . $upClass . ' /><br />' . $p->mana . '<br />';
		echo '<img src="/static/images/icons/bana.png" ' . $downClass . ' /></div>';
		echo '<a class="title" href="/main/post/' . $p->id . '/">' . $p->title . '</a>';
		echo '<span class="date"><strong>Started on:</strong> ' . date(DATE_FORMAT, $p->date) . '<br />';
		echo '<strong>Updated on:</strong> ' . date(DATE_FORMAT, $p->updated) . '<br />';
?>
		<hr>
		<strong>Started by:</strong> <span class="link" onclick="showUserInfo(<? echo $p->user; ?>,'<? echo $user_list[$p->user]; ?>')">
<?		echo $user_list[$p->user] . '</span><span class="ranking"> [' . $rank_list[$p->user] . ']</span><br />';
		echo '<a href="/main/post/' . $p->id . '/">' . $p->comments . ' Comments</a><br />';
		if ($user) {
			if (($user->id == $p->user) || $user->admin) {
			echo '<span class="options"><img onclick="deletePost(' . $p->id . ')" title="Delete Post" src="/static/images/icons/delete.png" /></span>';
			}
		}
		echo '</span><div class="clear"></div></div>';
	}
} 

include('post_paging.php');
?>