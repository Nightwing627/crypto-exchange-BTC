<?php
$page_name = "support";
require("./header.php");
	$sql = "SELECT user_mail FROM `".TABLE_USERS."` WHERE user_id='".$_SESSION['user_id']."'";
	$user_mail = $db->query_first($sql);
	if ($user_mail) {
		$user_mail = $user_mail["user_mail"];
	}
	if ($_POST["btnSend"] != "") {
		$to = $db_config["support_email"];
		$subject = "Support [".$_SESSION["user_name"]."]: ".$_POST["message"];
		$message = "From user".$_SESSION["user_name"].",\r\n".$_POST["message"];
		$headers = "From: ".$_POST["email"]."\r\n" .
			"Reply-To: ".$_POST["email"]."\r\n" .
			"X-Mailer: PHP/".phpversion();
		if (@mail($to, $subject, $message, $headers)) {
			$sendResult = "<span class=\"green bold centered\">Success! Please check your email within next 12 hours. Also Check Your Spam Mails.</span>";
		}
		else {
			$sendResult = "<span class=\"red bold centered\">Can't send email address, please contact administator for support.</span>";
		}
	}
?>
					<h1>ONLINE SUPPORT</h1>
						<table class="table" width="100%" style="clear: left;">
							<tbody>
<?php
	if ($db_config["support_yahoo1"] != "") {
?>
								<tr>
									<td class="support_title">
										<span class="black bold">Yahoo support 1:</span>
									</td>
									<td class="support_content">
										<a href="ymsgr:sendIM?<?=$db_config["support_yahoo1"]?>"><?=$db_config["support_yahoo1"]?> <img src="http://opi.yahoo.com/online?u=<?=$db_config["support_yahoo1"]?>&t=8" border="0" width="110px" height="80px;" VALIGN="MIDDLE"  /></a>
									</td>
								</tr>
<?php
	}
	if ($db_config["support_yahoo2"] != "") {
?>
								<tr>
									<td class="support_title">
										<span class="black bold">Yahoo support 2:</span>
									</td>
									<td class="support_content">
										<a href="ymsgr:sendIM?<?=$db_config["support_yahoo2"]?>"><?=$db_config["support_yahoo2"]?> <img src="http://opi.yahoo.com/online?u=<?=$db_config["support_yahoo2"]?>&t=8" border="0" width="110px" height="80px;" VALIGN="MIDDLE"  /></a>
									</td>
								</tr>
<?php
	}
	if ($db_config["support_icq"] != "") {
?>
								<tr>
									<td class="support_title">
										<span class="black bold">ICQ support:</span>
									</td>
									<td class="support_content">
										<a href="http://www.icq.com/people/cmd.php?uin=<?=$db_config["support_icq"]?>&action=message"><?=$db_config["support_icq"]?></a>
									</td>
								</tr>
<?php
	}
	if ($db_config["support_skype"] != "") {
?>
								<tr>
									<td class="support_title">
										<span class="black bold">Skype support:</span>
									</td>
									<td class="support_content">
										<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
										<a href="skype:<?=$db_config["support_skype"]?>?call"><img src="http://download.skype.com/share/skypebuttons/buttons/call_blue_white_124x52.png" style="border: none;" width="124" height="52" alt="Skype Me™!" /></a>
									</td>
								</tr>
<?php
	}
?>
							</tbody>
						</table>

					<h1>Support Ticket</h1>
					<div class="section_title"><?=$sendResult?></div>
					
					
					<form method="post" class="form narrow-cols">
    
    <div class="form-row">
        <div class="form-label">
            <label for="email">Your Email:</label>
        </div>
        <div class="form-field">
            <input id="subject" class="focus-if-empty" name="email" value="<?=$user_mail?>" type="text" style="width: 500px;"><br>
                    </div>
    </div>
	<div class="form-row">
        <div class="form-label">
            <label for="subject">Subject:</label>
        </div>
        <div class="form-field">
            <input id="subject" class="focus-if-empty" name="subject" type="text" style="width: 500px;"><br>
                    </div>
    </div>

    <div class="form-row">
        <div class="form-label">
            <label for="text">Message:</label>
        </div>
        <div class="form-field">
            <textarea id="text" class="focus-if-empty" name="message" cols="50" rows="10" style="width: 500px;"></textarea><br>
                    </div>
    </div>
    <br>
    <div class="form-buttons">
        <input type="submit" name="btnSend" value="Send">
		<input type="button" name="btnCancel" value="Cancel" onclick="window.location='./'"/>
    </div>
</form>
<?php
require("./footer.php");
?>