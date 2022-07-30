<?php
session_start();
require("../includes/config.inc.php");
if (checkLogin(PER_USER)) {
	if ($_POST["lr_amnt"] != "" && doubleval($_POST["lr_amnt"]) >= doubleval($db_config["paygate_minimum"])) {
?>
<body onload="document.getElementById('libertyreserve').submit();">
	<form id="libertyreserve" method="post" action="https://sci.libertyreserve.com/">
		<input type="hidden" name="lr_amnt" value="<?=$_POST["lr_amnt"]?>">
		<input type="hidden" name="lr_acc" value="<?=$db_config["lr_account"]?>">
		<input type="hidden" name="lr_currency" value="LRUSD">
		<input type="hidden" name="lr_comments" value="Adding $<?=$_POST["lr_amnt"]?> balance to user account: <?=$_SESSION["user_name"]?>">
		<input type="hidden" name="lr_success_url" value="<?=$db_config["site_url"]?>/mydeposits.php">
		<input type="hidden" name="lr_success_url_method" value="POST">
		<input type="hidden" name="lr_fail_url" value="<?=$db_config["site_url"]?>/deposit.php">
		<input type="hidden" name="lr_fail_url_method" value="POST">
		<input type="hidden" name="lr_store" value="<?=$db_config["lr_store_name"]?>">
		<input type="hidden" name="lr_status_url" value="<?=$db_config["site_url"]?>/paygates/lrstatus.php">
		<input type="hidden" name="lr_status_url_method" value="POST">
		<input type="hidden" name="user_id" value="<?=$_SESSION["user_id"]?>">
	</form>
	<a href="javascript:void(0);" onclick="document.getElementById('libertyreserve').submit();">Click here if the page doesn't redirect properly</a>
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