<?php
$is_user_panel = TRUE;
$page_name = "myaccount";
require("./header.php");
	if ($getinfoError == "" && $_POST["btnChangePwd"] != "") {
		if ($_POST["user_pass"] == "") {
			$changeInfoResult = "<span class=\"red bold centered\">Please enter your current password</span>";
		}
		else if (md5(md5($_POST["user_pass"]).$user_info["user_salt"]) == $user_info["user_pass"]) {
			switch (passwordFaild($_POST["user_pass_new"], $_POST["user_pass_new_re"])) {
				case 0:
					$user_update["user_salt"] = rand(100,999);
					$user_update["user_pass"] = md5(md5($_POST["user_pass_new"]).$user_update["user_salt"]);
					if($db->query_update(TABLE_USERS, $user_update, "user_id='".$_SESSION["user_id"]."'")) {
						$changeInfoResult = "<span class=\"green bold centered\">Change password successfully.</span>";
						$user_info["user_salt"] = $user_update["user_salt"];
						$user_info["user_pass"] = $user_update["user_pass"];
					}
					else {
						$changeInfoResult = "<span class=\"red bold centered\">Update user information error, please try again.</span>";
					}
					break;
				case 1:
					$changeInfoResult = "<span class=\"red bold centered\">New Password is too short.</span>";
					break;
				case 2:
					$changeInfoResult = "<span class=\"red bold centered\">New Password is too long.</span>";
					break;
				case 3:
					$changeInfoResult = "<span class=\"red bold centered\">New Password doesn't match.</span>";
					break;
			}
		}
		else {
			$changeInfoResult = "<span class=\"red bold centered\">Wrong password, please try again</span>";
		}
	}
	if ($getinfoError == "" && $_POST["btnChangeEmail"] != "") {
		if ($_POST["user_pass"] == "") {
			$changeInfoResult = "<span class=\"red bold centered\">Please enter your current password</span>";
		}
		else if (md5(md5($_POST["user_pass"]).$user_info["user_salt"]) == $user_info["user_pass"]) {
			switch (emailFaild($_POST["user_mail"])) {
				case 0:
					$user_update["user_mail"] = $_POST["user_mail"];
					if($db->query_update(TABLE_USERS, $user_update, "user_id='".$_SESSION["user_id"]."'")) {
						$changeInfoResult = "<span class=\"green bold centered\">Change email address successfully.</span>";
						$user_info["user_mail"] = $user_update["user_mail"];
					}
					else {
						$changeInfoResult = "<span class=\"red bold centered\">Update user information error, please try again.</span>";
					}
					break;
				case 1:
					$changeInfoResult = "<span class=\"red bold centered\">Invalid e-mail address.</span>";
					break;
			}
		}
		else {
			$changeInfoResult = "<span class=\"red bold centered\">Wrong password, please try again</span>";
		}
	}
	if ($getinfoError == "" && $_POST["btnChangeYahoo"] != "") {
		if ($_POST["user_pass"] == "") {
			$changeInfoResult = "<span class=\"red bold centered\">Please enter your current password</span>";
		}
		else if (md5(md5($_POST["user_pass"]).$user_info["user_salt"]) == $user_info["user_pass"]) {
			$user_update["user_yahoo"] = $_POST["user_yahoo"];
			if($db->query_update(TABLE_USERS, $user_update, "user_id='".$_SESSION["user_id"]."'")) {
				$changeInfoResult = "<span class=\"green bold centered\">Change Yahoo id successfully.</span>";
				$user_info["user_yahoo"] = $user_update["user_yahoo"];
			}
			else {
				$changeInfoResult = "<span class=\"red bold centered\">Update user information error, please try again.</span>";
			}
		}
		else {
			$changeInfoResult = "<span class=\"red bold centered\">Wrong password, please try again</span>";
		}
	}
	if ($getinfoError == "" && $_POST["btnChangeICQ"] != "") {
		if ($_POST["user_pass"] == "") {
			$changeInfoResult = "<span class=\"red bold centered\">Please enter your current password</span>";
		}
		else if (md5(md5($_POST["user_pass"]).$user_info["user_salt"]) == $user_info["user_pass"]) {
			$user_update["user_icq"] = $_POST["user_icq"];
			if($db->query_update(TABLE_USERS, $user_update, "user_id='".$_SESSION["user_id"]."'")) {
				$changeInfoResult = "<span class=\"green bold centered\">Change ICQ id successfully.</span>";
				$user_info["user_icq"] = $user_update["user_icq"];
			}
			else {
				$changeInfoResult = "<span class=\"red bold centered\">Update user information error, please try again.</span>";
			}
		}
		else {
			$changeInfoResult = "<span class=\"red bold centered\">Wrong password, please try again</span>";
		}
	}
?>
			<h1>ACCOUNT INFORMATION</h1>
			<div class="section_title"><?=$getinfoError?></div>
			<div class="section_title"><?=$changeInfoResult?></div>
			<form action="" method="POST" class="form narrow-cols">
				
				
			<div class="form-row">
				<div class="form-label">
					<label for="password">Current Password:</label>
				</div>
				<div class="form-field">
					<input name="user_pass" type="password" value="">
				</div>
			</div>
			<div class="form-row">
				<div class="form-label">
					<label for="password">New Password:</label>
				</div>
				<div class="form-field">
					<input name="user_pass_new" type="password" value="">
				</div>
			</div>
			<div class="form-row">
				<div class="form-label">
					<label for="password">Verify New Password:</label>
				</div>
				<div class="form-field">
					<input name="user_pass_new_re" type="password" value="">
				</div>
			</div>
			<br>
			<div class="form-buttons">
				<input type="submit" name="btnChangePwd" value="Change Password" />
			</div>
			<hr>




			<div class="form-row">
				<div class="form-label">
					<label for="password">Current Email Address:</label>
				</div>
				<div style="width: 100%;">
					<input type="text" value="<?=$user_info["user_mail"]?>" disabled>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label">
					<label for="password">New Email Address:</label>
				</div>
				<div class="form-field">
					<input name="user_mail" type="text" value="">
				</div>
			</div>
			<br>
			<div class="form-buttons">
				<input type="submit" name="btnChangeEmail" value="Change Email Address" />
			</div>
			<hr>



			<div class="form-row">
				<div class="form-label">
					<label for="password">Current Yahoo ID:</label>
				</div>
				<div style="width: 100%;">
					<input type="text" value="<?=$user_info["user_yahoo"]?>" disabled>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label">
					<label for="password">New Yahoo ID:</label>
				</div>
				<div class="form-field">
					<input name="user_yahoo" type="text" value="">
				</div>
			</div>
			<br>
			<div class="form-buttons">
				<input type="submit" name="btnChangeYahoo" value="Change Yahoo ID" />
			</div>
			<hr>




			<div class="form-row">
				<div class="form-label">
					<label for="icq">Current ICQ ID:</label>
				</div>
				<div style="width: 100%;">
					<input type="text" value="<?=$user_info["user_icq"]?>" disabled>
				</div>
			</div>
			<div class="form-row">
				<div class="form-label">
					<label for="icq">New ICQ ID:</label>
				</div>
				<div class="form-field">
					<input name="user_icq" type="text" value="">
				</div>
			</div>
			<br>
			<div class="form-buttons">
				<input type="submit" name="btnChangeICQ" value="Change ICQ ID" />
			</div>
			<hr>			
		</form>
<?php
require("./footer.php");
?>