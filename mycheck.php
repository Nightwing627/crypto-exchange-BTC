<?php
$is_user_history = TRUE;
$page_name = "mycheck";
require("./header.php");

	if ($_POST["clear_history"] != "") {
		$check_update["check_hide"] = "1";
		if ($db->query_update(TABLE_CHECKS, $check_update, "check_userid='".$_SESSION["user_id"]."'")) {
			$clearHistoryResult = "<span class=\"green red centered\">Clear check history successfully</span>";
		}
		else {
			$clearHistoryResult = "<span class=\"bold red centered\">Clear check history error</span>";
		}
	}
	$sql = "SELECT `".TABLE_CHECKS."`.*, `".TABLE_CARDS."`.card_id, AES_DECRYPT(`".TABLE_CARDS."`.card_number, '".strval(DB_ENCRYPT_PASS)."') AS card_number FROM `".TABLE_CHECKS."` JOIN `".TABLE_CARDS."` ON ".TABLE_CHECKS.".check_userid = ".$_SESSION["user_id"]." AND ".TABLE_CHECKS.".check_cardid = ".TABLE_CARDS.".card_id AND ".TABLE_CHECKS.".check_hide = '0' ORDER BY check_id DESC LIMIT 0, 50";
	$listcards = $db->fetch_all_array($sql);
?>
					<h1>CHECK HISTORY (last 50 tasks)</h1>
					<div class="section_title"><?=$clearHistoryResult?></div>
						<table class="table" width="100%" style="clear: left;">
							<tbody>
								<tr>
									<th>
										DATE
									</th>
									<th>
										CARD NUMBER
									</th>
									<th>
										CHECK RESULT
									</th>
								</tr>
<?php
	if (count($listcards) > 0) {
		foreach ($listcards as $key=>$value) {
?>
								<tr>
									<td class="center">
										<span><?=date("H:i:s d/M/Y", $value['check_time'])?></span>
									</td>
									<td class="center">
										<span><?=$value['card_number']?></span>
									</td>
									<td class="center">
<?php
			switch ($value['check_result']) {
				case strval(CHECK_VALID):
					echo "<span class=\"green bold\">VALID</span>";
					break;
				case strval(CHECK_INVALID):
					echo "<span class=\"red bold\">TIMEOUT</span>";
					break;
				case strval(CHECK_REFUND):
					echo "<span class=\"pink bold\">INVALID - REFUNDED</span>";
					break;
				case strval(CHECK_UNKNOWN):
					echo "<span class=\"blue bold\">UNKNOWN</span>";
					break;
				default :
					echo "<span class=\"black bold\">UNCHECK</span>";
					break;
			}
?>
									</td>
								</tr>
<?php
		}
?>
								<tr>
									<td colspan="3" class="center">
										<label>
											<form action="" method="POST">
												<input name="clear_history" type="submit" class="red bold" id="clear_history" value="Clear Check History" >
											</form>
										</label>
									</td>
								</tr>
<?php
	}
	else {
?>
								<tr>
									<td colspan="3" class="red bold center">
										No record found.
									</td>
								</tr>
<?php
	}
?>
							</tbody>
						</table>
<?php
require("./footer.php");
?>