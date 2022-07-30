<?php
$is_user_history = TRUE;
$page_name = "myorders";
require("./header.php");
	$sql = "SELECT count(*) FROM `".TABLE_CARTS."` WHERE cart_userid = '".$_SESSION["user_id"]."' ORDER BY cart_id";
	$totalRecords = $db->query_first($sql);
	$totalRecords = $totalRecords["count(*)"];
	$perPage = 30;
	$totalPage = ceil($totalRecords/$perPage);
	if (isset($_GET["page"])) {
		$page = $db->escape($_GET["page"]);
		if ($page < 1) {
			$page = 1;
		}
		else if ($page > $totalPage) {
			$page = 1;
		}
	} else {
		$page = 1;
	}
	$sql = "SELECT * FROM `".TABLE_CARTS."` WHERE cart_userid = '".$_SESSION["user_id"]."' ORDER BY cart_id DESC LIMIT ".(($page-1)*$perPage).",".$perPage;
	$cart_historys = $db->fetch_all_array($sql);
?>
					<h1>ORDERS HISTORY</h1>
					<div class="section_page_bar">
<?php
	if ($totalRecords > 0) {
		echo "Page:";
		if ($page>1) {
			echo "<a href=\"?page=".($page-1)."\">&lt;</a>";
			echo "<a href=\"?page=1\">1</a>";
		}
		if ($page>3) {
			echo "...";
		}
		if (($page-1) > 1) {
			echo "<a href=\"?page=".($page-1)."\">".($page-1)."</a>";
		}
		echo "<input type=\"TEXT\" class=\"page_go\" value=\"".$page."\" onchange=\"window.location.href='?page='+this.value\"/>";
		if (($page+1) < $totalPage) {
			echo "<a href=\"?page=".($page+1)."\">".($page+1)."</a>";
		}
		if ($page < $totalPage-2) {
			echo "...";
		}
		if ($page<$totalPage) {
			echo "<a href=\"?page=".$totalPage."\">".$totalPage."</a>";
			echo "<a href=\"?page=".($page+1)."\">&gt;</a>";
		}
	}
?>
					</div>
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
										AMOUNT
									</th>
									<th>
										BEFORE BALANCE
									</th>
									<th>
										AFTER BALANCE
									</th>
									<th>
										ACTION
									</th>
								</tr>
<?php
	if (count($cart_historys) > 0) {
		foreach ($cart_historys as $key=>$value) {
?>

								<tr>
									<td class="center">
										<span><?=$value['cart_id']?></span>
									</td>
									<td class="center">
										<span><?=date("H:i:s d/M/Y", $value['cart_time'])?></span>
									</td>
									<td class="center">
										<span>$<?=$value['cart_total']?></span>
									</td>
									<td class="center">
										<span>$<?=$value['cart_before']?></span>
									</td>
									<td class="center">
										<span>$<?=$value['cart_before'] - $value['cart_total']?></span>
									</td>
									<td class="center">
										<span><a href="./showcart?cart_id=<?=$value['cart_id']?>" class="viewcard">View Shopping Cart</a></span>
									</td>
								</tr>
<?php
		}
	}
	else {
?>
								<tr>
									<td colspan="8" class="red bold center">
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