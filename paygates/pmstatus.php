<?php
session_start();
require("../includes/config.inc.php");
function paygate_log($errorMsg)
{
	$filename = "PM_payment_paygate_log.txt";
	if ($handle = fopen($filename, 'a+')) {
		if (fwrite($handle, $errorMsg) === FALSE) {
			echo "Cannot write to file ($filename)";
		}
	}
	else {
		echo "Cannot open file ($filename)";
	}
	fclose($handle);
}

$string=
      $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
      $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
      $_POST['PAYMENT_BATCH_NUM'].':'.
      $_POST['PAYER_ACCOUNT'].':'.strtoupper(md5($db_config["pm_alternate_passphrase"])).':'.
      $_POST['TIMESTAMPGMT'];

$hash=strtoupper(md5($string));
foreach ($_POST as $k => $v)
{
	$msgBody .= $k.":".$v."\r\n";
}
$msgBody .= "$string\r\n";
$msgBody .= "$hash\r\n";
if($hash==$_POST['V2_HASH']){
		if (strtoupper($_POST["PAYEE_ACCOUNT"]) == strtoupper($db_config["pm_account"])) {
			$user_id = $db->escape($_POST["user_id"]);
			$sql = "SELECT count(*) from `".TABLE_ORDERS."` WHERE order_proof = '".$db->escape($_POST["PAYMENT_BATCH_NUM"])."'";
			$record = $db->query_first($sql);
			if ($record) {
				$record = $record["count(*)"];
				if (intval($record) == 0) {
					$sql = "SELECT user_balance, user_referenceid from `".TABLE_USERS."` WHERE user_id = '".$user_id."'";
					$user_balance = $db->query_first($sql);
					$user_referenceid = $user_balance["user_referenceid"];
					$user_balance = $user_balance["user_balance"];
					if (intval($user_referenceid) != 0) {
						$sql = "SELECT user_balance from `".TABLE_USERS."` WHERE user_id = '".$user_referenceid."'";
						$reference_balance = $db->query_first($sql);
						$reference_balance = $reference_balance["user_balance"];
						$reference_update["user_balance"] = doubleval($reference_balance)+(doubleval($_POST["PAYMENT_AMOUNT"])*$db_config["commission"]);
					}
					$credit_update["user_balance"] = doubleval($user_balance)+doubleval($_POST["PAYMENT_AMOUNT"]);
					$orders_add["order_userid"] = $user_id;
					$orders_add["order_paygate"] = "Perfect Money";
					$orders_add["order_amount"] = doubleval($_POST["PAYMENT_AMOUNT"]);
					$orders_add["order_before"] = doubleval($user_balance);
					$orders_add["order_proof"] = $_POST["PAYMENT_BATCH_NUM"];
					$orders_add["order_time"] = time();
					$msgBody .= $user_referenceid."|".$reference_balance."|".$reference_update["user_balance"]."\r\n";
					if($db->query_insert(TABLE_ORDERS, $orders_add)) {
						if ($db->query_update(TABLE_USERS, $credit_update, "user_id='".$user_id."'")) {
							if (intval($user_referenceid) == 0 || $db->query_update(TABLE_USERS, $reference_update, "user_id='".$user_referenceid."'")) {
								$msgBody .= "Payment was verified and is successful.\r\n";
							} else {
								$msgBody .= "Update Reference Credit: SQL Error.\r\n";
							}
						} else {
							$msgBody .= "Update Credit: SQL Error.\r\n";
						}
					}
					else {
						$msgBody .= "Insert Deposit Record: SQL Error.\r\n";
					}
				}
				else {
					$msgBody .= "Duplicate recored.\r\n";
				}
			}
			else {
				$msgBody .= "Check duplicate: SQL Error.\r\n";
			}
		}
		else
		{
			$msgBody .= "Cheating recored.\r\n";
		}
}
else
{
	$msgBody .= "Invalid response. Sent hash didn't match the computed hash.\r\n";
}
paygate_log($msgBody."\r\n");
echo $msgBody;

?>