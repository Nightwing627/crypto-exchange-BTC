<?php
require("./header.php");
if ($checkLogin) {
	if ($_GET["act"] == "mail") {
		if ($_POST["btnSend"] != "") {
			$sql = "SELECT user_mail from `".TABLE_USERS."` ORDER BY user_id";
			$allEmail = $db->fetch_all_array($sql);
			$lastEmail = $allEmail[count($allEmail)-1]["user_mail"];
			unset($allEmail[count($allEmail)-1]["user_mail"]);
			$to = $db_config["support_email"];
			if (count($allEmail) > 0) {
				$bcc = "";
				foreach ($allEmail as $oneEmail) {
					$bcc .= $oneEmail["user_mail"].", ";
				}
				$bcc .= $lastEmail;
			}
			$subject = $_POST["message"];
			$message = $_POST["message"];
			$headers = "From: ".$db_config["support_email"]."\r\n" .
				"Reply-To: ".$db_config["support_email"]."\r\n" .
				"BCC: ".$bcc."\r\n" .
				"X-Mailer: PHP/".phpversion();
			if (@mail($to, $subject, $message, $headers)) {
				$sendResult = "<span class=\"green bold centered\">Your new password has been sent to your email address.</span>";
			}
			else {
				$sendResult = "<span class=\"red bold centered\">Can't send email address, please contact administator for support.</span>";
			}
		}
?>
				<div id="user_manager">
					<div class="section_title">SEND BROADCAST MAIL</div>
					<div class="section_title"><?=$sendResult?></div>
					<div class="section_content">
						<table class="formstyle content_table">
							<form action="" method="POST">
								<tbody>
									<tr>
										<td class="support_title">
											<span class="black bold">Subject</span>
										</td>
										<td class="support_content">
											<span class="black bold"><input type="text" name="subject" /></span>
										</td>
									</tr>
									<tr>
										<td class="support_title">
											<span class="black bold">Message</span>
										</td>
										<td class="support_content">
											<span class="black bold"><textarea name="message"></textarea></span>
										</td>
									</tr>
									<tr>
										<td colspan="2" class="centered">
											<input type="submit" name="btnSend" value="Send Email" />
											<input type="button" name="btnCancel" value="Cancel" onclick="window.location='./users.php'"/>
										</td>
									</tr>
								</tbody>
							</form>
						</table>
					</div>
				</div>
<?php
	}
	else if ($_GET["act"] == "add") {
?>
				<div id="user_manager">
					<div class="section_title">ADD NEW USER</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<form method="POST" action="">
<?php
		if (!isset($_POST["user_groupid"])) {
			$_POST["user_groupid"] = intval(DEFAULT_GROUP_ID);
		}
		if (!isset($_POST["user_balance"])) {
			$_POST["user_balance"] = doubleval(DEFAULT_BALANCE);
		}
		if (isset($_POST["user_add_save"])) {
			$user_add["user_groupid"] = $_POST["user_groupid"];
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
			$user_add["user_yahoo"] = $_POST["user_yahoo"];
			switch (passwordFaild($_POST["user_pass"], $_POST["user_pass"])) {
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
			$user_add["user_balance"] = $_POST["user_balance"];
			if ($user_add["user_balance"] < 0) {
				$balanceError = "Balance invalid.";
			}
			$user_add["user_regdate"] = time();
			if ($emailError == "" && $passwordError == "" && $usernameError == "" && $balanceError == "") {
				if($db->query_insert(TABLE_USERS, $user_add)) {
					$errorMsg = "";
				}
				else {
					$errorMsg = "Add new User error.";
				}
			}
			if ($errorMsg == "" && $emailError == "" && $passwordError == "" && $usernameError == "" && $balanceError == "") {
?>
									<script type="text/javascript">setTimeout("window.location = './users.php'", 1000);</script>
									<tr>
										<td colspan="7" class="centered">
											<span class="green bold">Add new User successfully.</span>
										</td>
									</tr>
<?php
			}
			else {
?>
									<tr>
										<td colspan="7" class="centered">
											<span class="red bold"><?=$errorMsg?></span>
										</td>
									</tr>
<?php
			}
		}
?>
									<tr>
										<td class="formstyle centered">
											<strong>USER TYPE</strong>
										</td>
										<td class="formstyle centered">
											<strong>EMAIL</strong>
										</td>
										<td class="formstyle centered">
											<strong>YAHOO</strong>
										</td>
										<td class="formstyle centered">
											<strong>USERNAME</strong>
										</td>
										<td class="formstyle centered">
											<strong>PASSWORD</strong>
										</td>
										<td class="formstyle centered">
											<strong>BALANCE</strong>
										</td>
									</tr>
									<tr>
										<td class="bold centered">
											<select class="formstyle bold" name="user_groupid" id="user_groupid" style="color:<?=$user_groups[$_POST["user_groupid"]]["group_color"]?>;" onchange="javascript:($('#user_groupid').css('color', ($('#user_groupid option:selected').css('color'))));">
<?php
			foreach ($user_groups as $type_id=>$type_value) {
?>
												<option name="user_mail" type="text" value="<?=$type_id?>" style="color:<?=$type_value['group_color']?>;"<?=($_POST["user_groupid"] == $type_id)?"selected ":" "?>><?=$type_value['group_name']?></option>
<?php
			}
?>
											</select>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="user_mail" type="text" value="<?=$_POST['user_mail']?>" /></span>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="user_yahoo" type="text" value="<?=$_POST['user_yahoo']?>" /></span>
										</td>
										<td class="bold centered">
											<span><input class="formstyle" name="user_name" type="text" value="<?=$_POST['user_name']?>" /></span>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="user_pass" type="text" autocomplete="off" value="<?=$value['user_pass']?>"/></span>
										</td>
										<td class="bold centered">
											<span>$<input class="formstyle" name="user_balance" size="6" type="text" value="<?=$_POST['user_balance']?>" /></span>
										</td>
									</tr>
									<tr>
										<td class="red bold centered">
										</td>
										<td class="red bold centered">
											<?=$emailError?>
										</td>
										<td class="red bold centered">
											<?=$usernameError?>
										</td>
										<td class="red bold centered" colspan="2">
											<?=$passwordError?>
										</td>
										<td class="red bold centered">
											<?=$balanceError?>
										</td>
									</tr>
									<tr>
										<td colspan="7" class="centered">
											<input type="submit" name="user_add_save" value="Save" /><input onclick="window.location='./users.php'"type="button" name="user_add_cancel" value="Cancel" />
										</td>
									</tr>
								</form>
							</tbody>
						</table>
					</div>
				</div>
<?php
	}
	else if ($_GET["act"] == "edit" && $_GET["user_id"] != "") {
		$user_id = $db->escape($_GET["user_id"]);
?>
				<div id="user_manager">
					<div class="section_title">USER EDITOR</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
<?php
		if (isset($_POST["user_edit_save"])) {
			$user_update["user_groupid"] = $_POST["user_groupid"];
			$user_update["user_mail"] = $_POST["user_mail"];
			$user_update["user_yahoo"] = $_POST["user_yahoo"];
			$user_update["user_name"] = $_POST["user_name"];
			if ($_POST["user_pass"] != "") {
				switch (passwordFaild($_POST["user_pass"], $_POST["user_pass"])) {
					case 0:
						$errorMsg = "";
						$user_update["user_salt"] = rand(100,999);
						$user_update["user_pass"] = md5(md5($_POST["user_pass"]).$user_update["user_salt"]);
						break;
					case 1:
						$errorMsg = "Password is too short.";
						break;
					case 2:
						$errorMsg = "Password is too long.";
						break;
					case 3:
						$errorMsg = "Password doesn't match.";
						break;
				}
			}
			$user_update["user_balance"] = $_POST["user_balance"];
			if ($errorMsg == "") {
				if($db->query_update(TABLE_USERS, $user_update, "user_id='".$user_id."'")) {
					$errorMsg = "";
				}
				else {
					$errorMsg = "Update User error.";
				}
			}
			if ($errorMsg == "") {
?>
									<script type="text/javascript">setTimeout("window.location = './users.php'", 1000);</script>
									<tr>
										<td colspan="7" class="centered">
											<span class="green bold">Update User successfully.</span>
										</td>
									</tr>
<?php
			}
			else {
?>
									<tr>
										<td colspan="7" class="centered">
											<span class="red bold"><?=$errorMsg?></span>
										</td>
									</tr>
<?php
			}
		}
?>
<?php
		$sql = "SELECT * FROM `".TABLE_USERS."` WHERE user_id = '".$user_id."'";
		$records = $db->fetch_all_array($sql);
		if (count($records)>0) {
			$value = $records[0];
?>
								<form method="POST" action="">
									<tr>
										<td class="formstyle centered">
											<strong>USER ID</strong>
										</td>
										<td class="formstyle centered">
											<strong>USER TYPE</strong>
										</td>
										<td class="formstyle centered">
											<strong>EMAIL</strong>
										</td>
										<td class="formstyle centered">
											<strong>YAHOO</strong>
										</td>
										<td class="formstyle centered">
											<strong>USERNAME</strong>
										</td>
										<td class="formstyle centered">
											<strong>PASSWORD</strong>
										</td>
										<td class="formstyle centered">
											<strong>BALANCE</strong>
										</td>
										<td class="formstyle centered">
											<strong>REGISTION DATE</strong>
										</td>
									</tr>
									<tr>
										<td class="centered">
											<span><?=$value['user_id']?></span>
										</td>
										<td class="bold centered">
											<select class="formstyle bold" name="user_groupid" id="user_groupid" style="color:<?=$user_groups[$value["user_groupid"]]["group_color"]?>;" onchange="javascript:($('#user_groupid').css('color', ($('#user_groupid option:selected').css('color'))));">
<?php
			foreach ($user_groups as $type_id=>$type_value) {
?>
												<option type="text" value="<?=$type_id?>" style="color:<?=$type_value['group_color']?>;"<?=($value["user_groupid"] == $type_id)?"selected ":" "?>><?=$type_value['group_name']?></option>
<?php
			}
?>
											</select>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="user_mail" type="text" value="<?=$value['user_mail']?>" /></span>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="user_yahoo" type="text" value="<?=$value['user_yahoo']?>" /></span>
										</td>
										<td class="bold centered">
											<span><input class="formstyle" name="user_name" type="text" value="<?=$value['user_name']?>" /></span>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="user_pass" type="password" autocomplete="off" /></span>
										</td>
										<td class="bold centered">
											<span>$<input class="formstyle" name="user_balance" size="6" type="text" value="<?=$value['user_balance']?>" /></span>
										</td>
										<td class="centered">
											<span><?=date("d/M/Y", $value['user_regdate'])?></span>
										</td>
									</tr>
									<tr>
										<td colspan="7" class="centered">
											<input type="submit" name="user_edit_save" value="Save" /><input onclick="window.location='./users.php'"type="button" name="user_edit_cancel" value="Cancel" />
										</td>
									</tr>
								</form>
<?php
		}
		else {
?>
								<tr>
									<td class="users_title">
										<span class="red">User ID Invalid.</span>
									</td>
								</tr>
<?php
		}
?>
							</tbody>
						</table>
					</div>
				</div>
<?php
	}
	else if ($_GET["act"] == "delete" && $_GET["user_id"] != "") {
		$user_id = $db->escape($_GET["user_id"]);
		$sql = "DELETE FROM `".TABLE_USERS."` WHERE user_id = '".$user_id."'";
		if ($db->query($sql)) {
?>
				<script type="text/javascript">setTimeout("window.location = './users.php'", 1000);</script>
				<div id="user_manager">
					<div class="section_title">USER DELETE</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<tr>
									<td class="users_title">
										<span class="red">Delete USER ID <?=$user_id?> successfully.</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
<?php
		}
		else {
?>
				<div id="user_manager">
					<div class="section_title">USER DELETE</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<tr>
									<td class="users_title">
										<span class="red">User ID Invalid.</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
<?php
		}
	}
	else {
		if (isset($_GET["btnSearch"])) {
			$currentGet = "";
			$currentGet .= "txtUserid=".$_GET["txtUserid"]."&txtUsername=".$_GET["txtUsername"]."&txtUsermail=".$_GET["txtUsermail"]."&txtUseryahoo=".$_GET["txtUseryahoo"]."&lstUserbalance=".$_GET["lstUserbalance"]."&lstUsertype=".$_GET["lstUsertype"];
			$currentGet .= "&btnSearch=Search&";
		}
		$searchUserid = $db->escape($_GET["txtUserid"]);
		$searchUsername = $db->escape($_GET["txtUsername"]);
		$searchUsermail = $db->escape($_GET["txtUsermail"]);
		$searchUseryahoo = $db->escape($_GET["txtUseryahoo"]);
		switch ($_GET["lstUserbalance"]) {
			case '0':
				$searchUserbalance = 'user_balance <= 0';
				break;
			case '1':
				$searchUserbalance = 'user_balance > 0';
				break;
			default:
				$searchUserbalance = '1';
				break;
		}
		$searchUsertype = $db->escape($_GET["lstUsertype"]);
		$sql = "SELECT count(*) FROM `".TABLE_USERS."` WHERE ('".$searchUserid."'='' OR user_id = '".$searchUserid."') AND ('".$searchUsername."'='' OR user_name LIKE '".$searchUsername."%' OR user_name LIKE '%".$searchUsername."%') AND ('".$searchUsermail."'='' OR user_mail LIKE '".$searchUsermail."%' OR user_mail LIKE '%".$searchUsermail."%') AND ('".$searchUseryahoo."'='' OR user_yahoo LIKE '".$searchUseryahoo."%' OR user_yahoo LIKE '%".$searchUseryahoo."%') AND ".$searchUserbalance." AND ('".$searchUsertype."'='' OR user_groupid = '".$searchUsertype."')";
		$totalRecords = $db->query_first($sql);
		$totalRecords = $totalRecords["count(*)"];
		$perPage = 30;
		$totalPage = ceil($totalRecords/$perPage);
		if (isset($_GET["page"])) {
			$page = $db->escape($_GET["page"]);
			if ($page < 1)
			{
				$page = 1;
			}
			else if ($page > $totalPage)
			{
				$page = 1;
			}
		}
		else
		{
			$page = 1;
		}
		$sql = "SELECT * FROM `".TABLE_USERS."` WHERE ('".$searchUserid."'='' OR user_id = '".$searchUserid."') AND ('".$searchUsername."'='' OR user_name LIKE '".$searchUsername."%' OR user_name LIKE '%".$searchUsername."%') AND ('".$searchUsermail."'='' OR user_mail LIKE '".$searchUsermail."%' OR user_mail LIKE '%".$searchUsermail."%') AND ('".$searchUseryahoo."'='' OR user_yahoo LIKE '".$searchUseryahoo."%' OR user_yahoo LIKE '%".$searchUseryahoo."%') AND ".$searchUserbalance." AND ('".$searchUsertype."'='' OR user_groupid = '".$searchUsertype."') ORDER BY user_id DESC LIMIT ".(($page-1)*$perPage).",".$perPage;
		$order_historys = $db->fetch_all_array($sql);
?>
				<div id="search_cards">
					<div class="section_title">SEARCH USERS</div>
					<div class="section_content">
						<table class="content_table centered">
							<tbody>
								<form name="search" method="GET" action="">
									<tr>
										<td class="formstyle">
											<span class="bold">USER ID</span>
										</td>
										<td class="formstyle">
											<span class="bold">USERNAME</span>
										</td>
										<td class="formstyle">
											<span class="bold">EMAIL</span>
										</td>
										<td class="formstyle">
											<span class="bold">YAHOO</span>
										</td>
										<td class="formstyle">
											<span class="bold">BALANCE</span>
										</td>
										<td class="formstyle">
											<span class="bold">USER TYPE</span>
										</td>
									</tr>
									<tr>
										<td>
											<input name="txtUserid" type="text" class="formstyle" id="txtUserid" value="<?=$_GET["txtUserid"]?>" size="12">
										</td>
										<td>
											<input name="txtUsername" type="text" class="formstyle" id="txtUsername" value="<?=$_GET["txtUsername"]?>" size="20">
										</td>
										<td>
											<input name="txtUsermail" type="text" class="formstyle" id="txtUsermail" value="<?=$_GET["txtUsermail"]?>" size="40">
										</td>
										<td>
											<input name="txtUseryahoo" type="text" class="formstyle" id="txtUseryahoo" value="<?=$_GET["txtUseryahoo"]?>" size="20">
										</td>
										<td>
											<select name="lstUserbalance" class="formstyle" id="lstUserbalance">
												<option value="" <?=(($_GET["lstUserbalance"] == "")?" selected":"")?>>ALL</option>
												<option value="1" <?=(($_GET["lstUserbalance"] == "1")?" selected":"")?>>Balanced</option>
												<option value="0" <?=(($_GET["lstUserbalance"] == "0")?" selected":"")?>>Empty</option>
											</select>
										</td>
										<td>
											<select name="lstUsertype" class="formstyle bold" id="lstUsertype" style="color:<?=$user_groups[$_GET["lstUsertype"]]["group_color"]?>;" onchange="javascript:($('#lstUsertype').css('color', ($('#lstUsertype option:selected').css('color'))));">
												<option value="">All Type</option>
<?php
		if (count($user_groups) > 0) {
			foreach ($user_groups as $user_group_id => $user_group) {
				echo "<option value=\"".$user_group_id."\"".(($_GET["lstUsertype"] == $user_group_id)?" selected":"")." style=\"color:".$user_group['group_color'].";\" >".$user_group['group_name']."</option>";
			}
		}
?>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="6">
											<input name="btnSearch" type="submit" class="formstyle" id="btnSearch" value="Search">
										</td>
									</tr>
								</form>
							</tbody>
						</table>
					</div>
				</div>
				<div id="user_manager">
					<div class="section_title">USERS MANAGER</div>
					<div class="section_title"><a href="?act=add">Add Users</a> | <a href="?act=mail">Send Broadcast Mail</a></div>
					<div class="section_page_bar">
<?php
		if ($totalRecords > 0) {
			echo "Page:";
			if ($page>1) {
				echo "<a href=\"?".$currentGet."page=".($page-1)."\">&lt;</a>";
				echo "<a href=\"?".$currentGet."page=1\">1</a>";
			}
			if ($page>3) {
				echo "...";
			}
			if (($page-1) > 1) {
				echo "<a href=\"?".$currentGet."page=".($page-1)."\">".($page-1)."</a>";
			}
			echo "<input type=\"TEXT\" class=\"page_go\" value=\"".$page."\" onchange=\"window.location.href='?".$currentGet."page='+this.value\"/>";
			if (($page+1) < $totalPage) {
				echo "<a href=\"?".$currentGet."page=".($page+1)."\">".($page+1)."</a>";
			}
			if ($page < $totalPage-2) {
				echo "...";
			}
			if ($page<$totalPage) {
				echo "<a href=\"?".$currentGet."page=".$totalPage."\">".$totalPage."</a>";
				echo "<a href=\"?".$currentGet."page=".($page+1)."\">&gt;</a>";
			}
		}
?>
					</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<tr>
									<td class="formstyle centered">
										<strong>USER ID</strong>
									</td>
									<td class="formstyle centered">
										<strong>USER TYPE</strong>
									</td>
									<td class="formstyle centered">
										<strong>EMAIL</strong>
									</td>
									<td class="formstyle centered">
										<strong>YAHOO</strong>
									</td>
									<td class="formstyle centered">
										<strong>USERNAME</strong>
									</td>
									<td class="formstyle centered">
										<strong>BALANCE</strong>
									</td>
									<td class="formstyle centered">
										<strong>REGISTION DATE</strong>
									</td>
									<td class="formstyle centered">
										<strong>ACTION</strong>
									</td>
								</tr>
<?php
		if (count($order_historys) > 0) {
			foreach ($order_historys as $key=>$value) {
?>
								<tr class="formstyle">
									<td class="centered">
										<span><?=$value['user_id']?></span>
									</td>
									<td class="bold centered">
										<span><font color='<?=$user_groups[$value['user_groupid']]["group_color"]?>'><?=$user_groups[$value['user_groupid']]["group_name"]?></span>
									</td>
									<td class="centered">
										<span><?=$value['user_mail']?></span>
									</td>
									<td class="centered">
										<span><?=$value['user_yahoo']?></span>
									</td>
									<td class="bold centered">
										<span><?=$value['user_name']?></span>
									</td>
									<td class="bold centered">
										<span>$<?=$value['user_balance']?></span>
									</td>
									<td class="centered">
										<span><?=date("d/M/Y", $value['user_regdate'])?></span>
									</td>
									<td class="centered">
										<span><a href="./deposits.php?lstOrderuserid=<?=$value['user_id']?>&txtOrderamount=&txtOrderproof=&lstOrderdate=&lstOrdermonth=&lstOrderyear=&btnSearch=Search">Deposits</a> | <a href="./orders.php?lstCartuserid=<?=$value['user_id']?>&txtCartamount=&lstCartdate=&lstCartmonth=&lstCartyear=&btnSearch=Search">Orders</a> | <a href="./checkers.php?lstCheckuserid=<?=$value['user_id']?>&txtCardnumber=&lstCheckdate=&lstCheckmonth=&lstCheckyear=&btnSearch=Search">Checks</a> | <a href="?act=edit&user_id=<?=$value['user_id']?>">Edit</a> | <a href="?act=delete&user_id=<?=$value['user_id']?>">Delete</a></span>
									</td>
								</tr>
<?php
			}
		}
		else {
?>
								<tr>
									<td colspan="7" class="red bold centered">
										No record found.
									</td>
								</tr>
<?php
		}
?>
							</tbody>
						</table>
					</div>
				</div>
<?php
	}
?>
<?php
}
else {
	require("./minilogin.php");
}
require("./footer.php");
?>