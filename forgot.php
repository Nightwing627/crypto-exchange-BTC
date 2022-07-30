<?php
$page_name = "forgot";
require("./header.php");
function genPassword($length = 8) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}
	if ($_POST["btnForgot"] != "") {
		switch (emailFaild($_POST["user_mail"])) {
			case 0:
				$emailError = "";
				$forgot_user_mail = $db->escape($_POST["user_mail"]);
				break;
			case 1:
				$emailError = "Invalid e-mail address.";
				break;
			case 2:
		}
		switch (usernameFaild($_POST["user_name"])) {
			case 0:
				$usernameError = "";
				$forgot_user_name = $db->escape($_POST["user_name"]);
				break;
			case 1:
				$usernameError = "Username is too short.";
				break;
			case 2:
				$usernameError = "Username is too long.";
				break;
		}
		if ($emailError == "" && $usernameError == "") {
			$new_password = genPassword();
			$user_update["user_salt"] = rand(100,999);
			$user_update["user_pass"] = md5(md5($new_password).$user_update["user_salt"]);
			if($db->query_update(TABLE_USERS, $user_update, "user_name='".$forgot_user_name."' AND user_mail='".$forgot_user_mail."'")) {
				if($db->affected_rows == 1){
					$to = $forgot_user_mail;
					$subject = "New password for [".$forgot_user_name."] at [".$db_config["site_url"]."]";
					$message = "Hello $forgot_user_name,\r\n Your new password is:$new_password";
					$headers = "From: ".$db_config["support_email"]."\r\n" .
						"Reply-To: ".$db_config["support_email"]."\r\n" .
						"X-Mailer: PHP/".phpversion();
					if (@mail($to, $subject, $message, $headers)) {
						$forgotResult = "<span class=\"green bold centered\">Your new password has been sent to your email address.</span>";
					}
					else {
						$forgotResult = "<span class=\"red bold centered\">Can't send email address, please contact administator for support.</span>";
					}
				}
				else{
					$forgotResult = "<span class=\"red bold centered\">Wrong information.</span>";
				}
			}
			else {
				$forgotResult = "<span class=\"red bold centered\">Update User error.</span>";
			}
		}
		else {
			$forgotResult = "<span class=\"red bold centered\">Please correct all information.</span>";
		}
	}
?>
					<h1>USER FORGOT PASSWORD</h1>
					<div class="section_title"><?=$forgotResult?></div>
					
					
					
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
				<div class="form-label"><label for="email">Email:</label></div>
				<div class="form-field">
					<input name="user_mail" type="text" class="formstyle" id="user_mail" value="<?=$_POST["user_mail"]?>">
					<br>        
					<ul class="form-errors"><?=$emailError?></ul>
				</div>
			</div>		

			
			<div class="form-row">
				<div class="form-label">
					&nbsp;
				</div>
				<div class="form-buttons" style="margin-top: 1.5em;">
					<input name="btnForgot" type="submit" class="formstyle" id="btnForgot" value="Reset Password">
					<input name="btnCancel" type="button" class="formstyle" id="btnCancel" value="Cancel" onclick="window.location='./'">
				</div>		
			</div>
					</form>
<?php
require("./footer.php");
?>