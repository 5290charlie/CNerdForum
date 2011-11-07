<?php require_once('header.php'); ?>
<ul>
	<li id="posts-tab"><a href="#posts">Forum</a></li>
	<li id="users-tab" <? if (!$user) { ?>style="display:none;"<? } ?>><a href="#users">Users</a></li>
</ul>
<div id="posts">
</div>
<div <? if (!$user) { ?>style="display:none;"<? } ?>id="users">
</div>
<?php require_once('footer.php'); ?>