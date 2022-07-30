<?php
session_start();
require("../includes/config.inc.php");
if (checkLogin(PER_USER)) {
	if ($_POST["pm_amnt"] != "" && doubleval($_POST["pm_amnt"]) >= doubleval($db_config["paygate_minimum"])) {
?>
<body onload="document.getElementById('perfectmoney').submit();">
	<form id="perfectmoney" method="post" action="https://perfectmoney.com/api/step1.asp">
		<input type="hidden" name="PAYEE_ACCOUNT" value="<?=$db_config["pm_account"]?>">
		<input type="hidden" name="PAYEE_NAME" value="<?=$db_config["pm_payee_name"]?>">
		<input type="hidden" name="PAYMENT_AMOUNT" value="<?=$_POST["pm_amnt"]?>">
		<input type="hidden" name="PAYMENT_UNITS" value="USD">
		<input type="hidden" name="STATUS_URL" value="<?=$db_config["site_url"]?>/paygates/pmstatus.php">
		<input type="hidden" name="PAYMENT_URL" value="<?=$db_config["site_url"]?>/mydeposits.php">
		<input type="hidden" name="PAYMENT_URL_METHOD" value="LINK">
		<input type="hidden" name="NOPAYMENT_URL" value="<?=$db_config["site_url"]?>/deposit.php">
		<input type="hidden" name="NOPAYMENT_URL_METHOD" value="LINK">
		<input type="hidden" name="SUGGESTED_MEMO" value="Adding $<?=$_POST["pm_amnt"]?> balance to user account: <?=$_SESSION["user_name"]?>">
		<input type="hidden" name="BAGGAGE_FIELDS" value="user_id">
		<input type="hidden" name="user_id" value="<?=$_SESSION["user_id"]?>">
	</form>
	<a href="javascript:void(0);" onclick="document.getElementById('perfectmoney').submit();">Click here if the page doesn't redirect properly</a>
</body>
<?php
	}
	else {
		header("Location: ../deposit.php");
	}
}
else {
	header("Location: ../login.php");
}
exit(0);
?>