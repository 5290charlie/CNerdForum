<?php if (isset($user)) { 
	echo $user->email; ?> 
	| <a href="#" id="logout">Logout</a>
<?php } else { ?>
	<a href="#" id="login">Login</a> or <a href="#" id="register">Register</a>
<?php } ?>