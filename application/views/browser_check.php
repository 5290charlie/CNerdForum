<?php
$agent = $_SERVER['HTTP_USER_AGENT'];

if (strpos('MSIE', $agent))
	die('Please use a real browser.');
?>
