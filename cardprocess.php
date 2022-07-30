<?php
session_start();
require("./includes/config.inc.php");
if (checkLogin(PER_USER)) {
	if (isset($_POST["download_expired"]) || isset($_POST["download_all"]) || (isset($_POST["download_select"]) && is_array($_POST["cards"]))) {
		if (isset($_POST["download_expired"])) {
			$file_name = $_SESSION["user_name"]."_EXPIRED_".date("Y_m_d").".txt";
			$sql = "SELECT *, AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') AS card_number FROM `".TABLE_CARDS."` WHERE card_year < ".date("Y")." OR (card_year = ".date("Y")." AND card_month < ".date("n").")";
		} else if (isset($_POST["download_all"])) {
			$file_name = $_SESSION["user_name"]."_DOWNLOAD_".date("Y_m_d").".txt";
			$sql = "SELECT *, AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') AS card_number FROM `".TABLE_CARDS."` WHERE card_status = '".STATUS_DEFAULT."' AND card_userid = '".$_SESSION["user_id"]."'";
		} else {
			$file_name = $_SESSION["user_name"]."_DOWNLOAD_".date("Y_m_d").".txt";
			$allCards = $_POST["cards"];
			$lastCards = $db->escape($allCards[count($allCards)-1]);
			unset($allCards[count($allCards)-1]);
			$sql = "SELECT *, AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') AS card_number FROM `".TABLE_CARDS."` WHERE card_status = '".STATUS_DEFAULT."' AND card_userid = '".$_SESSION["user_id"]."' AND card_id IN (";
			if (count($allCards) > 0) {
				foreach ($allCards as $key=>$value) {
					$sql .= "'".$db->escape($value)."', ";
				}
			}
			$sql .= $lastCards.")";
		}
		$downloadCards = $db->fetch_all_array($sql);
		$content = "CARDNUMBER|MM/YYYY|CVV|NAME|ADDRESS|CITY|STATE|ZIPCODE|COUNTRY|PHONE|SSN|DOB|COMMENT|CHECK RESULT\r\n";
		if (count($downloadCards) > 0) {
			foreach ($downloadCards as $key=>$value) {
				switch ($value['card_check']) {
					case strval(CHECK_VALID):
						$value['card_checkText'] = "VALID";
						break;
					case strval(CHECK_INVALID):
						$value['card_checkText'] = "TIMEOUT";
						break;
					case strval(CHECK_REFUND):
						$value['card_checkText'] = "INVALID - REFUNDED";
						break;
					case strval(CHECK_UNKNOWN):
						$value['card_checkText'] = "UNKNOWN";
						break;
					default :
						$value['card_checkText'] = "UNCHECK";
						break;
				}
				$content .= $value['card_number']."|".$value['card_month']."/".$value['card_year']."|".$value['card_cvv']."|".$value['card_name']."|".$value['card_address']."|".$value['card_city']."|".$value['card_state']."|".$value['card_zip']."|".$value['card_country']."|".$value['card_phone']."|".$value['card_ssn']."|".$value['card_dob']."|".$value['card_comment']."|".$value['card_checkText']."|"."\r\n";
			}
		}
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/force-download");
		header("Content-Disposition: attachment; filename=".$file_name);
		header("Content-Description: File Transfer");
		echo $content;
	}
	else if (isset($_POST["delete_invalid"]) || (isset($_POST["delete_select"]) && is_array($_POST["cards"]))) {
		if (isset($_POST["delete_invalid"])) {
			$sql = "UPDATE `".TABLE_CARDS."` SET card_status = '".strval(STATUS_DELETED)."' WHERE card_userid = '".$_SESSION["user_id"]."' AND card_check = '".strval(CHECK_INVALID)."'";
		} else {
			$allCards = $_POST["cards"];
			$lastCards = $db->escape($allCards[count($allCards)-1]);
			unset($allCards[count($allCards)-1]);
			$sql = "UPDATE `".TABLE_CARDS."` SET card_status = '".strval(STATUS_DELETED)."' WHERE card_userid = '".$_SESSION["user_id"]."' AND card_id IN (";
			if (count($allCards) > 0) {
				foreach ($allCards as $key=>$value) {
					$sql .= "'".$db->escape($value)."', ";
				}
			}
			$sql .= "'".$lastCards."')";
		}
		$db->query($sql);
		//$db->affected_rows;
		if ($_SERVER["HTTP_REFERER"] != "") {
			header("Location: ".$_SERVER["HTTP_REFERER"]);
		}
		else {
			header("Location: mycards");
		}
	}
	else {
		if ($_SERVER["HTTP_REFERER"] != "") {
			header("Location: ".$_SERVER["HTTP_REFERER"]);
		}
		else {
			header("Location: mycards");
		}
	}
}
else {
	header("Location: login");
}
exit(0);
?>