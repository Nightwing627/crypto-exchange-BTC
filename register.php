<?php
$page_name = "register";
require("./header.php");
	$showForm = true;
	if ($_POST["btnRegister"] != "") {
			$user_add["user_groupid"] = intval(DEFAULT_GROUP_ID);
			switch (emailFaild($_POST["user_mail"])) {
				case 0:
					$emailError = "";
					$user_add["user_mail"] = $_POST["user_mail"];
					break;
				case 1:
					$emailError = "Invalid e-mail address.";
					break;
				case 2:
			}
			if ($emailError == "") {
				$sql = "SELECT count(*) FROM `".TABLE_USERS."` WHERE user_mail = '".$db->escape($_POST["user_mail"])."'";
				$user_mailCount = $db->query_first($sql);
				if ($user_mailCount) {
					if (intval($user_mailCount["count(*)"]) != intval(0)) {
						$emailError = "This email has been used.";
					}
				} else {
					$emailError = "Check email error, please try again";
				}
			}
			$user_add["user_yahoo"] = $_POST["user_yahoo"];
			switch (passwordFaild($_POST["user_pass"], $_POST["user_pass_re"])) {
				case 0:
					$passwordError = "";
					$user_add["user_salt"] = rand(100,999);
					$user_add["user_pass"] = md5(md5($_POST["user_pass"]).$user_add["user_salt"]);
					break;
				case 1:
					$passwordError = "Password is too short.";
					break;
				case 2:
					$passwordError = "Password is too long.";
					break;
				case 3:
					$passwordError = "Password doesn't match.";
					break;
			}
			switch (usernameFaild($_POST["user_name"])) {
				case 0:
					$usernameError = "";
					$user_add["user_name"] = $_POST["user_name"];
					break;
				case 1:
					$usernameError = "Username is too short.";
					break;
				case 2:
					$usernameError = "Username is too long.";
					break;
			}
			if ($_POST["user_reference"] != "") {
				$sql = "SELECT user_id FROM `".TABLE_USERS."` WHERE user_name = '".$db->escape($_POST["user_reference"])."'";
				$user_reference = $db->query_first($sql);
				if ($user_reference) {
					$user_add["user_referenceid"] = $user_reference["user_id"];
					$referenceError = "";
				} else {
					$referenceError = "This username doesn't exist.";
				}
			} else {
				$user_add["user_referenceid"] = "0";
				$referenceError = "";
			}
			if ($usernameError == "") {
				$sql = "SELECT count(*) FROM `".TABLE_USERS."` WHERE user_name = '".$db->escape($_POST["user_name"])."'";
				$user_nameCount = $db->query_first($sql);
				if ($user_nameCount) {
					if (intval($user_nameCount["count(*)"]) != intval(0)) {
						$usernameError = "This username has been used.";
					}
				} else {
					$usernameError = "Check username error, please try again";
				}
			}
			$user_add["user_balance"] = doubleval(DEFAULT_BALANCE);
			$user_add["user_regdate"] = time();
			if ($emailError == "" && $passwordError == "" && $usernameError == "" && $referenceError == "") {
				if($db->query_insert(TABLE_USERS, $user_add)) {
					$registerResult = "<span class=\"green bold centered\">Welcome [".$user_add["user_name"]."], click <a href=\"./login.php\">here</a> to login.</span>";
					$showForm = false;
				}
				else {
					$registerResult = "<span class=\"red bold centered\">Register new user error.</span>";
				}
			}
			else {
				$registerResult = "<span class=\"red bold centered\">Please correct all information.</span>";
			}
	}
?>
	<h1>Registration</h1>
	<div class="section_title"><?=$registerResult?></div>
<?php
	if ($showForm) {
?>
		<form class="form wide-cols" name="login" method="post" action="" autocomplete="off">		
			<div class="form-row">
				<div class="form-label"><label for="login">Username:</label></div>
				<div class="form-field">
					<input name="user_name" type="text" class="formstyle" id="user_name" value="<?=$_POST["user_name"]?>">
					<br>        
					<ul class="form-errors"><?=$usernameError?></ul>
				</div>
			</div>
			
			
			
			<div class="form-row">
				<div class="form-label"><label for="password">Password:</label></div>
				<div class="form-field">
					<input name="user_pass" type="password" class="formstyle" id="user_pass">
					<br>        
					<ul class="form-errors"><?=$passwordError?></ul>
				</div>
			</div>

			<div class="form-row">
				<div class="form-label"><label for="password2">Verify Password:</label></div>
				<div class="form-field">
					<input name="user_pass_re" type="password" class="formstyle" id="user_pass_re">
					<br>        
					<ul class="form-errors"></ul>
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-label"><label for="email">Email:</label></div>
				<div class="form-field">
					<input name="user_mail" type="text" class="formstyle" id="user_mail" value="<?=$_POST["user_mail"]?>">
					<br>        
					<ul class="form-errors"><?=$emailError?></ul>
				</div>
			</div>		
			
			<div class="form-row">
				<div class="form-label"><label for="yahoo">Yahoo:</label></div>
				<div class="form-field">
					<input name="user_yahoo" type="text" class="formstyle" id="user_yahoo" value="<?=$_POST["user_yahoo"]?>">
					<br>        
					<ul class="form-errors"></ul>
				</div>
			</div>				
			
			<div class="form-row">
				<div class="form-label"><label for="reference">Reference by::</label></div>
				<div class="form-field">
					<input name="user_reference" type="text" class="formstyle" id="user_reference" value="<?=$_POST["user_reference"]?>">
					<br>        
					<ul class="form-errors"><?=$referenceError?></ul>
				</div>
			</div>

			
			<div class="form-row">
				<div class="form-label">
					&nbsp;
				</div>
				<div class="form-buttons" style="margin-top: 1.5em;">
					<input type="submit" class="formstyle" name="btnRegister" value="Register">
					<input name="btnCancel" type="button" class="formstyle" id="btnCancel" value="Cancel" onclick="window.location='./'">
				</div>		
			</div>
		</form>
<?php
	}
require("./footer.php");
?>