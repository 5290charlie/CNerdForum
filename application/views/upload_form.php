<?php
switch($type)
{
	case 'link':
		echo '<label for="upload-link">Link: </label>';
		echo '<input type="text" id="upload-link" name="link" />';
		echo '<br />';
		break;
	default:
		break;
}

?>