<ul>
	<li><a href="#home">Home</a></li>
<? if ($user) { ?>
	<li><a href="#files">Files</a></li>
<? if ($user) { ?>
	<li><a href="#users">Users</a></li>
<? }} ?>
</ul>
<div id="home">
	<h1>Welcome</h1>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</div>
<? if ($user) { ?>
<div id="files">
</div>
<? if ($user) { ?>
<div id="users">
</div>
<? }} ?>