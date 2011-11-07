<?php require_once('header.php'); ?>
<h1>Login</h1>
<form id="login-form" method="post" action="/action/login/">
	<input id="email" name="email" type="text" placeholder="Email" /><br />
	<input id="password" name="password" type="password" placeholder="Password" /><br />
	<input id="login-submit" type="submit" value="Login" />
</form>
<?php require_once('footer.php'); ?>
