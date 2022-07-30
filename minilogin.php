
	<div id="form">
		<h1>LOG IN</h1>
		<form id="form_1f5e62" action="./login.php" name="login" method="post" class="login-form narrow-cols">

			<div class="form-row">
				<div class="form-label">
					<label id="login-login-label" for="login-login-input">Login:</label>
				</div>
				<div class="form-field">
					<input id="txtUser" type="text" name="txtUser" value="">
				</div>
			</div>

			<div class="form-row">
				<div class="form-label">
					<label id="login-password-label" for="login-password-input">Password:</label>
				</div>
				<div class="form-field">
					<input id="txtPass" type="password" name="txtPass" value="">
				</div>
			</div>


			<div class="form-row">
				<div class="form-label">
					&nbsp;
				</div>
				<div class="form-field">

					<label id="login-remember-label"><input id="remember" type="checkbox" name="remember" value="1">&nbsp;Keep signed in</label>
				</div>
			</div>

			<div id="page-error">
			<?php
				switch ($loginError) {
					case 1:
						echo "This username doesn't exist.";
						break;
					case 2:
						echo "Wrong password.";
						break;
					case 3:
						echo "You don't have permission to log in to this page.";
						break;
					case 4:
						echo "Sorry, you have provided an invalid security code.";
						break;
				}
			?>
			</div>

			<div class="form-row">
				<div class="form-label">
					&nbsp;
				</div>
				<div class="form-buttons" style="margin-top: 1.5em;">
				<input type="submit" class="formstyle" name="btnLogin" value="Login">
				</div>		
			</div>
		</form>
	</div>