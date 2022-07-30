<?php
session_start();
require("../includes/config.inc.php");
if (checkLogin(PER_ADMIN)) {
	if ($_GET["cart_id"] != "") {
		$cart_id = $db->escape($_GET["cart_id"]);
		$sql = "SELECT * FROM `".TABLE_CARTS."` WHERE `cart_id` = '$cart_id'";
		$record = $db->query_first($sql);
		if ($record) {
			$shoppingCart = unserialize($record["cart_item"]);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>..::Vampire.Vn Shop::..</title>
		<meta content="index, follow" name="robots" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="icon" href="../favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="../styles/main.css" />
		<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="../js/jquery.popupWindow.js"></script>
		<script type="text/javascript" src="../js/main.js" ></script>
	</head>
	<body style="width:880px;">
			<div id="cart">
				<div class="section_title">CART ID: <?=$cart_id?></div>
				<div class="section_title"><?=$buyResult?></div>
				<div class="section_content">
					<table class="content_table">
						<tbody>
							<form name="shoping_cart" method="POST" action="">
								<tr>
									<td class="formstyle centered">
										<strong>CARD NUMBER</strong>
									</td>
									<td class="formstyle centered">
										<strong>FIRST NAME</strong>
									</td>
									<td class="formstyle centered">
										<strong>COUNTRY</strong>
									</td>
									<td class="formstyle centered">
										<strong>STATE</strong>
									</td>
									<td class="formstyle centered">
										<strong>CITY</strong>
									</td>
									<td class="formstyle centered">
										<strong>ZIP</strong>
									</td>
									<td class="formstyle centered">
										<strong>SSN</strong>
									</td>
									<td class="formstyle centered">
										<strong>DOB</strong>
									</td>
									<td class="formstyle centered">
										<strong>PRICE</strong>
									</td>
								</tr>
<?php
			if (count($shoppingCart) > 0) {
				foreach ($shoppingCart as $key=>$value) {
					$card_firstname = explode(" ", $value['card_name']);
					$card_firstname = $card_firstname[0];
?>
								<tr class="formstyle">
									<td class="centered bold">
										<span><?=$value['card_number']?></span>
									</td>
									<td class="centered">
										<span><?=$card_firstname?></span>
									</td>
									<td class="centered">
										<span><?=$value['card_country']?></span>
									</td>
									<td class="centered">
										<span><?=$value['card_state']?></span>
									</td>
									<td class="centered">
										<span><?=$value['card_city']?></span>
									</td>
									<td class="centered">
										<span><?=$value['card_zip']?></span>
									</td>
									<td class="centered">
										<span><?=$value['card_ssn']?></span>
									</td>
									<td class="centered">
										<span><?=$value['card_dob']?></span>
									</td>
									<td class="centered bold">
										<span>
<?php
					printf("$%.2f", $value['card_price']);
					if ($value["binPrice"] > 0) {
						printf(" + $%.2f", $value["binPrice"]);
					}
					if ($value["countryPrice"] > 0) {
						printf(" + $%.2f", $value["countryPrice"]);
					}
					if ($value["statePrice"] > 0) {
						printf(" + $%.2f", $value["statePrice"]);
					}
					if ($value["cityPrice"] > 0) {
						printf(" + $%.2f", $value["cityPrice"]);
					}
					if ($value["zipPrice"] > 0) {
						printf(" + $%.2f", $value["zipPrice"]);
					}
?>
										</span>
									</td>
								</tr>
<?php
				}
			}
			else {
?>
								<tr>
									<td colspan="9" class="centered">
										<span class="red bold">No record found.</span>
									</td>
								</tr>
<?php
			}
?>
								<tr>
									<td colspan="8">
									</td>
									<td class="centered">
										<span class="red bold">$<?=number_format($record["cart_total"], 2, '.', '')?></span>
									</td>
								</tr>
							</form>
						</tbody>
					</table>
				</div>
			</div>
	</body>
</html>
<?php
		}
	}
}
else {
	header("Location: login.php");
}
exit(0);
?>