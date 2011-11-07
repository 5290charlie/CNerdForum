<?php require_once('header.php'); ?>
<ul>
	<li id="search-tab"><a href="#searchResults">Search Results</a></li>
	<li id="users-tab" <? if (!$user) { ?>style="display:none;"<? } ?>><a href="#users">Users</a></li>
</ul>
<div id="searchResults">
<a href="/">&lt;&lt; All posts</a>
<? $searchArray = explode(' ', $search);
$searchSplit = '"' . $searchArray[0] . '"';
for ($i=1; $i<count($searchArray); $i++) {
	if (!preg_match('[-{1,2}]', $searchArray[$i]))
		$searchSplit .= ',"' . $searchArray[$i] . '"';
}
?>
<h2><? echo $pNum + $cNum + $uNum; ?> Results For: <? echo $searchSplit; ?></h2>
<h3>Posts <span>[<? echo $pNum; ?>]</span></h3>
<ul>
	<?php
	if ($postResults) {
		foreach($postResults->result() as $pr) {
			echo '<li><a href="/main/post/' . $pr->id . '">' . $pr->title . '</a></li>';
		}
	} else { echo 'No Results'; }
	?>
</ul>
<h3>Comments <span>[<? echo $cNum; ?>]</span></h3>
<ul>
	<?php
	if ($commentResults) {
		foreach($commentResults->result() as $cr) {
			$body = strip_tags($cr->body, ALLOWED_TAGS);
			if (strlen($body) == 0)
				$body = '[goto post]';
			echo '<li><a href="/main/post/' . $cr->post . '">' . $body . '</a></li>';
		}
	} else { echo 'No Results'; }
	?>
</ul>
<h3>Users <span>[<? echo $uNum; ?>]</span></h3>
<ul>
	<?php
	if ($userResults) {
		foreach($userResults->result() as $ur) { ?>
			<li><span onclick="showUserInfo(<? echo $ur->id; ?>,'<? echo $ur->username; ?>')"><? echo $ur->username; ?></span></li>
		<? }
	} else { echo 'No Results'; }
	?>
</ul>
</div>
<div <? if (!$user) { ?>style="display:none;"<? } ?>id="users">
</div>
<?php require_once('footer.php'); ?>