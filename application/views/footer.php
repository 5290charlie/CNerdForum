			</div>
			<div id="footer">
				A <a href="http://charliemcclung.com">Charlie McClung</a> Production
			</div>
		</div>
		<div title="Login" id="login-wrapper">
			<form id="login-form" method="post" action="/action/login/">
				<label for="login-username">Username: </label><input id="login-username" name="username" type="text" /><br />
				<label for="login-password">Password: </label><input id="login-password" name="password" type="password" /><br />
			</form>	
		</div>
		<div title="Register" id="register-wrapper">
			<form id="register-form" method="post" action="/action/register/">
				<label for="register-firstname">Firstname: </label><input id="register-firstname" name="firstname" type="text" /><br />
				<label for="register-lastname">Lastname: </label><input id="register-lastname" name="lastname" type="text" /><br />
				<label for="register-email">Email: </label><input id="register-email" name="email" type="text" /><br />
				<label for="register-username">Username: </label><input id="register-username" name="username" type="text" /><br />
				<label for="register-password">Password: </label><input id="register-password" name="password" type="password" /><br />
				<label for="register-confirm">Confirm: </label><input id="register-confirm" name="confirm" type="password" /><br />
			</form>
		</div>
		<div title="New Post" id="upload-wrapper">
			<form id="upload-form" enctype="multipart/form-data" method="post" action="/action/upload/">
				<label for="upload-title">Title: </label>
				<input id="upload-title" name="title" type="text" /><br />
				<!-- <label for="upload-type">Type: </label>
				<select id="upload-type" name="type" onchange="updateUpload()">
					<option value="text">Text</option>
					<option value="link">Link</option>
				</select>
				<br /> -->
				<span id="upload-ajax"></span>
				<label for="upload-details">Details: </label><br />
				<textarea style="width:100%;" id="upload-details" name="details"></textarea>
			</form>
		</div>
		<div id="user-account">
		</div>
	</body>
</html>
<!--
				<p>Please select a file to upload (.pdf, .doc)</p>
				<input type="file" id="upload-file" name="upload-file" onchange="document.getElementById('upload-form').submit();" />
				<input type="submit" style="display:none;" value="Login" /> 
				
-->