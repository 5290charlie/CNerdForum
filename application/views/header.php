<?php
(!strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) or die('Please use a REAL browser.');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>CNerdforum [a place for nerds]</title>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="/static/images/icons/favicon.ico" />
		<link href="/static/less/style.less" rel="stylesheet/less" type="text/css" />
		<link href="/static/css/jqueryUI.css" rel="stylesheet" type="text/css" />
		<script src="/static/js/jquery.js" type="text/javascript"></script>
		<script src="/static/js/jqueryUI.js" type="text/javascript"></script>
		<script src="/static/js/functions.js" type="text/javascript"></script>
		<script src="/static/js/less.js" type="text/javascript"></script>
	</head>
	<body>
		<div onclick="clearMsgs()" id="msg">
			<?php require_once('action_msg.php'); ?>
		</div>
		<div id="sidebar">
			<div id="chat-container">
				<div class="info">[scroll down at bottom to "unlock"]</div>
				<h3>Ghetto Chat</h3>
				<div id="chat-content">
				</div>
				<div id="talk">
					<input placeholder="Talk Nerdy . . ." type="text" id="chat" name="chat" />
				</div>
			</div>
		</div>
		<div id="container">
			<div id="userstatus"></div>
			<div id="header" title="Home" onclick="window.location='/';">
				<img src="/static/images/CNerdForumLogo.png" />
				<!-- 
				CNerdForum 
				<span>[a place for nerds]</span>
				-->
			</div>
			<form id="searchForm" method="post" action="/main/search/">
				<input name="search" id="search" placeholder="Search CNerdForum…" />
			</form>
			<div class="clear"></div>
			<div id="content">
			
