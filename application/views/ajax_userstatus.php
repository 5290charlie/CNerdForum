Welcome, 
<?php if ($user) { 
	echo $user->firstname . ' ' . $user->lastname . ' '; ?>
	| <span onclick="showUserInfo(<? echo $user->id; ?>,'<? echo $user->username; ?>')">Account</span> 
	| <span onclick="logout()" id="logout">logout</span>
<?php } else { ?>
	<span onclick="login()" id="login">login</span> or <span onclick="register()" id="register">register</span>
<?php } 
if (isset($username) && isset($password) && isset($auth)) {
	if ($auth)
		echo "auth'd as $username, pass=$password";
	else
		echo "unauth as $username, pass=$password";
}

?>
