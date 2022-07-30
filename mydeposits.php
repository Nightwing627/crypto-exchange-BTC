<?php
$is_fund_history = TRUE;
$page_name = "mydeposits";
require("./header.php");
	$sql = "SELECT * FROM `".TABLE_ORDERS."` WHERE order_userid='".$_SESSION['user_id']."' ORDER BY order_id DESC LIMIT 0, 10";
	$order_historys = $db->fetch_all_array($sql);
?>

					<h1>PURCHASED HISTORY (last 10 orders)</h1>
						<table class="table" width="100%" style="clear: left;">
							<tbody>
								<tr>
									<th>
										ORDER ID
									</th>
									<th>
										DATE
									</th>
									<th>
										METHOD
									</th>
									<th>
										AMOUNT
									</th>
									<th>
										BEFORE BALANCE
									</th>
									<th>
										AFTER BALANCE
									</th>
									<th>
										ORDER PROOF
									</th>
								</tr>
								
<?php
	if (count($order_historys) > 0) {
		foreach ($order_historys as $key=>$value) {
?>
								<tr>
									<td class="center">
										<span><?=$value['order_id']?></span>
									</td>
									<td class="center">
										<span><?=date("H:i:s d/M/Y", $value['order_time'])?></span>
									</td>
									<td class="center">
										<span><?=$value['order_paygate']?></span>
									</td>
									<td class="center">
										<span>$<?=$value['order_amount']?></span>
									</td>
									<td class="center">
										<span>$<?=$value['order_before']?></span>
									</td>
									<td class="center">
										<span>$<?=$value['order_before'] + $value['order_amount']?></span>
									</td>
									<td class="center">
										<span><?=$value['order_proof']?></span>
									</td>
								</tr>
<?php
		}
	}
	else {
?>
								<tr>
									<td colspan="7" class="red bold center">
										You don't have any order yet.
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