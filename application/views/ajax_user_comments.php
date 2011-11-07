<h4>[<? echo $numComments; ?>] Comments</h4>
<div class="top-comments">
	<?php 
	if ($comments && ($numComments > 0)) {
		echo '<ol>';
		foreach ($comments->result() as $row) {
			echo '<li>[<img src="/static/images/icons/mana.png" width="10px" />' . $row->mana . '] ';
			echo '<a href="/main/post/' . $row->post . '/">' . (strlen(strip_tags($row->body)) > 0 ? strip_tags($row->body) : '…') . ' </a></li>';
		}
		echo '</ol>';
	} else {
		echo 'No Comments';
	}
	?>
</div>