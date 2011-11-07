<?php require_once('header.php'); ?>
<h1>Register</h1>
<form id="register-form" method="post" action="/action/register/">
	<input id="firstname" name="firstname" type="text" placeholder="Firstname" /><br />
	<input id="lastname" name="lastname" type="text" placeholder="Lastname" /><br />
	<input id="email" name="email" type="text" placeholder="Email" /><br />
	<input id="password" name="password" type="password" placeholder="Password" /><br />
	<input id="confirm" name="confirm" type="password" placeholder="Confirm" /><br />
	<input id="register-submit" type="submit" value="Register" />
</form>
<?php require_once('footer.php'); ?>
