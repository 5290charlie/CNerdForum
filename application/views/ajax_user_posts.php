<h4>[<? echo $numPosts; ?>] Posts</h4>
<?php 
if ($posts && ($numPosts > 0)) {
	echo '<ol>';
	foreach ($posts->result() as $row) {
		echo '<li>[<img src="/static/images/icons/mana.png" width="10px" />' . $row->mana . '] ';
		echo '<a href="/main/post/' . $row->id . '/">' . $row->title . '</a></li>';
	}
	echo '</ol>';
} else {
	echo 'No Posts';
}