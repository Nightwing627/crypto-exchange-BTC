<?php
require("./header.php");
if ($checkLogin) {
	if (isset($_GET["btnSearch"])) {
		$currentGet = "";
		$currentGet .= "lstCartuserid=".$_GET["lstCartuserid"]."&txtCartamount=".$_GET["txtCartamount"]."&lstCartdate=".$_GET["lstCartdate"]."&lstCartmonth=".$_GET["lstCartmonth"]."&lstCartyear=".$_GET["lstCartyear"];
		$currentGet .= "&btnSearch=Search&";
	}
	$searchCartuserid = $db->escape($_GET["lstCartuserid"]);
	$searchCartamount = $db->escape($_GET["txtCartamount"]);
	$searchCartdate = $db->escape($_GET["lstCartdate"]);
	$searchCartmonth = $db->escape($_GET["lstCartmonth"]);
	$searchCartyear = $db->escape($_GET["lstCartyear"]);
	$sql = "SELECT count(*) FROM `".TABLE_CARTS."` JOIN `".TABLE_USERS."` ON ".TABLE_CARTS.".cart_userid = ".TABLE_USERS.".user_id WHERE ('".$searchCartuserid."'='' OR ".TABLE_CARTS.".cart_userid = '".$searchCartuserid."') AND ('".$searchCartamount."'='' OR ".TABLE_CARTS.".cart_total = '".$searchCartamount."') AND ('".$searchCartdate."'='' OR FROM_UNIXTIME(".TABLE_CARTS.".cart_time, '%d') = '".$searchCartdate."') AND ('".$searchCartmonth."'='' OR FROM_UNIXTIME(".TABLE_CARTS.".cart_time, '%m') = '".$searchCartmonth."') AND ('".$searchCartyear."'='' OR FROM_UNIXTIME(".TABLE_CARTS.".cart_time, '%Y') = '".$searchCartyear."')";
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
	} else {
		$page = 1;
	}
	$sql = "SELECT * FROM `".TABLE_CARTS."` JOIN `".TABLE_USERS."` ON ".TABLE_CARTS.".cart_userid = ".TABLE_USERS.".user_id WHERE ('".$searchCartuserid."'='' OR ".TABLE_CARTS.".cart_userid = '".$searchCartuserid."') AND ('".$searchCartamount."'='' OR ".TABLE_CARTS.".cart_total = '".$searchCartamount."') AND ('".$searchCartdate."'='' OR FROM_UNIXTIME(".TABLE_CARTS.".cart_time, '%d') = '".$searchCartdate."') AND ('".$searchCartmonth."'='' OR FROM_UNIXTIME(".TABLE_CARTS.".cart_time, '%m') = '".$searchCartmonth."') AND ('".$searchCartyear."'='' OR FROM_UNIXTIME(".TABLE_CARTS.".cart_time, '%Y') = '".$searchCartyear."') ORDER BY cart_id DESC LIMIT ".(($page-1)*$perPage).",".$perPage;
	$cart_historys = $db->fetch_all_array($sql);
?>
				<div id="search_cards">
					<div class="section_title">SEARCH ORDER HISTORY</div>
					<div class="section_content">
						<table class="content_table centered">
							<tbody>
								<form name="search" method="GET" action="">
									<tr>
										<td class="formstyle">
											<span class="bold">USERNAME</span>
										</td>
										<td class="formstyle">
											<span class="bold">AMOUNT</span>
										</td>
										<td class="formstyle">
											<span class="bold">DATE</span>
										</td>
									</tr>
									<tr>
										<td>
											<select name="lstCartuserid" class="formstyle bold" id="lstCartuserid" style="color:<?=$user_groups[$_GET["lstCartuserid"]]["group_color"]?>;" onchange="javascript:($('#lstCartuserid').css('color', ($('#lstCartuserid option:selected').css('color'))));">
												<option value="" style="color:#0D0D0D;">All users</option>
<?php
		$sql = "SELECT * FROM `".TABLE_USERS."`";
		$allUsers = $db->fetch_all_array($sql);
		if (count($allUsers) > 0) {
			foreach ($allUsers as $user) {
				echo "<option value=\"".$user['user_id']."\"".(($_GET["lstCartuserid"] == $user['user_id'])?" selected":"")." style=\"color:".$user_groups[$user['user_groupid']]["group_color"].";\" >".$user['user_name']."</option>";
			}
		}
?>
											</select>
										</td>
										<td>
											<input name="txtCartamount" type="text" class="formstyle" id="txtCartamount" value="<?=$_GET["txtCartamount"]?>" size="20">
										</td>
										<td>
											<select name="lstCartdate" class="formstyle bold" id="lstCartdate" >
												<option value="">Date</option>
												<option value="01" <?php if ($_GET["lstCartdate"] == "01") echo "selected ";?>>01</option>
												<option value="02" <?php if ($_GET["lstCartdate"] == "02") echo "selected ";?>>02</option>
												<option value="03" <?php if ($_GET["lstCartdate"] == "03") echo "selected ";?>>03</option>
												<option value="04" <?php if ($_GET["lstCartdate"] == "04") echo "selected ";?>>04</option>
												<option value="05" <?php if ($_GET["lstCartdate"] == "05") echo "selected ";?>>05</option>
												<option value="06" <?php if ($_GET["lstCartdate"] == "06") echo "selected ";?>>06</option>
												<option value="07" <?php if ($_GET["lstCartdate"] == "07") echo "selected ";?>>07</option>
												<option value="08" <?php if ($_GET["lstCartdate"] == "08") echo "selected ";?>>08</option>
												<option value="09" <?php if ($_GET["lstCartdate"] == "09") echo "selected ";?>>09</option>
												<option value="10" <?php if ($_GET["lstCartdate"] == "10") echo "selected ";?>>10</option>
												<option value="11" <?php if ($_GET["lstCartdate"] == "11") echo "selected ";?>>11</option>
												<option value="12" <?php if ($_GET["lstCartdate"] == "12") echo "selected ";?>>12</option>
												<option value="13" <?php if ($_GET["lstCartdate"] == "13") echo "selected ";?>>13</option>
												<option value="14" <?php if ($_GET["lstCartdate"] == "14") echo "selected ";?>>14</option>
												<option value="15" <?php if ($_GET["lstCartdate"] == "15") echo "selected ";?>>15</option>
												<option value="16" <?php if ($_GET["lstCartdate"] == "16") echo "selected ";?>>16</option>
												<option value="17" <?php if ($_GET["lstCartdate"] == "17") echo "selected ";?>>17</option>
												<option value="18" <?php if ($_GET["lstCartdate"] == "18") echo "selected ";?>>18</option>
												<option value="19" <?php if ($_GET["lstCartdate"] == "19") echo "selected ";?>>19</option>
												<option value="20" <?php if ($_GET["lstCartdate"] == "20") echo "selected ";?>>20</option>
												<option value="21" <?php if ($_GET["lstCartdate"] == "21") echo "selected ";?>>21</option>
												<option value="22" <?php if ($_GET["lstCartdate"] == "22") echo "selected ";?>>22</option>
												<option value="23" <?php if ($_GET["lstCartdate"] == "23") echo "selected ";?>>23</option>
												<option value="24" <?php if ($_GET["lstCartdate"] == "24") echo "selected ";?>>24</option>
												<option value="25" <?php if ($_GET["lstCartdate"] == "25") echo "selected ";?>>25</option>
												<option value="26" <?php if ($_GET["lstCartdate"] == "26") echo "selected ";?>>26</option>
												<option value="27" <?php if ($_GET["lstCartdate"] == "27") echo "selected ";?>>27</option>
												<option value="28" <?php if ($_GET["lstCartdate"] == "28") echo "selected ";?>>28</option>
												<option value="29" <?php if ($_GET["lstCartdate"] == "29") echo "selected ";?>>29</option>
												<option value="30" <?php if ($_GET["lstCartdate"] == "30") echo "selected ";?>>30</option>
												<option value="31" <?php if ($_GET["lstCartdate"] == "31") echo "selected ";?>>31</option>
											</select>
											<select name="lstCartmonth" class="formstyle bold" id="lstCartmonth" >
												<option value="">Month</option>
												<option value="01" <?php if ($_GET["lstCartmonth"] == "01") echo "selected ";?>>01</option>
												<option value="02" <?php if ($_GET["lstCartmonth"] == "02") echo "selected ";?>>02</option>
												<option value="03" <?php if ($_GET["lstCartmonth"] == "03") echo "selected ";?>>03</option>
												<option value="04" <?php if ($_GET["lstCartmonth"] == "04") echo "selected ";?>>04</option>
												<option value="05" <?php if ($_GET["lstCartmonth"] == "05") echo "selected ";?>>05</option>
												<option value="06" <?php if ($_GET["lstCartmonth"] == "06") echo "selected ";?>>06</option>
												<option value="07" <?php if ($_GET["lstCartmonth"] == "07") echo "selected ";?>>07</option>
												<option value="08" <?php if ($_GET["lstCartmonth"] == "08") echo "selected ";?>>08</option>
												<option value="09" <?php if ($_GET["lstCartmonth"] == "09") echo "selected ";?>>09</option>
												<option value="10" <?php if ($_GET["lstCartmonth"] == "10") echo "selected ";?>>10</option>
												<option value="11" <?php if ($_GET["lstCartmonth"] == "11") echo "selected ";?>>11</option>
												<option value="12" <?php if ($_GET["lstCartmonth"] == "12") echo "selected ";?>>12</option>
											</select>
											<select name="lstCartyear" class="formstyle bold" id="lstCartyear" >
												<option value="">Year</option>
												<option value="2011" <?php if ($_GET["lstCartyear"] == "2011") echo "selected ";?>>2011</option>
												<option value="2012" <?php if ($_GET["lstCartyear"] == "2012") echo "selected ";?>>2012</option>
												<option value="2013" <?php if ($_GET["lstCartyear"] == "2013") echo "selected ";?>>2013</option>
												<option value="2014" <?php if ($_GET["lstCartyear"] == "2014") echo "selected ";?>>2014</option>
												<option value="2015" <?php if ($_GET["lstCartyear"] == "2015") echo "selected ";?>>2015</option>
												<option value="2016" <?php if ($_GET["lstCartyear"] == "2016") echo "selected ";?>>2016</option>
												<option value="2017" <?php if ($_GET["lstCartyear"] == "2017") echo "selected ";?>>2017</option>
												<option value="2018" <?php if ($_GET["lstCartyear"] == "2018") echo "selected ";?>>2018</option>
												<option value="2019" <?php if ($_GET["lstCartyear"] == "2019") echo "selected ";?>>2019</option>
												<option value="2020" <?php if ($_GET["lstCartyear"] == "2020") echo "selected ";?>>2020</option>
												<option value="2021" <?php if ($_GET["lstCartyear"] == "2021") echo "selected ";?>>2021</option>
												<option value="2022" <?php if ($_GET["lstCartyear"] == "2022") echo "selected ";?>>2022</option>
												<option value="2023" <?php if ($_GET["lstCartyear"] == "2023") echo "selected ";?>>2023</option>
												<option value="2024" <?php if ($_GET["lstCartyear"] == "2024") echo "selected ";?>>2024</option>
												<option value="2025" <?php if ($_GET["lstCartyear"] == "2025") echo "selected ";?>>2025</option>
												<option value="2026" <?php if ($_GET["lstCartyear"] == "2026") echo "selected ";?>>2026</option>
												<option value="2027" <?php if ($_GET["lstCartyear"] == "2027") echo "selected ";?>>2027</option>
												<option value="2028" <?php if ($_GET["lstCartyear"] == "2028") echo "selected ";?>>2028</option>
												<option value="2029" <?php if ($_GET["lstCartyear"] == "2029") echo "selected ";?>>2029</option>
												<option value="2030" <?php if ($_GET["lstCartyear"] == "2030") echo "selected ";?>>2030</option>
												<option value="2031" <?php if ($_GET["lstCartyear"] == "2031") echo "selected ";?>>2031</option>
												<option value="2032" <?php if ($_GET["lstCartyear"] == "2032") echo "selected ";?>>2032</option>
												<option value="2033" <?php if ($_GET["lstCartyear"] == "2033") echo "selected ";?>>2033</option>
												<option value="2034" <?php if ($_GET["lstCartyear"] == "2034") echo "selected ";?>>2034</option>
												<option value="2035" <?php if ($_GET["lstCartyear"] == "2035") echo "selected ";?>>2035</option>
												<option value="2036" <?php if ($_GET["lstCartyear"] == "2036") echo "selected ";?>>2036</option>
												<option value="2037" <?php if ($_GET["lstCartyear"] == "2037") echo "selected ";?>>2037</option>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="4">
											<input name="btnSearch" type="submit" class="formstyle" id="btnSearch" value="Search">
										</td>
									</tr>
								</form>
							</tbody>
						</table>
					</div>
				</div>
				<div id="check_history">
					<div class="section_title">ORDERS HISTORY</div>
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
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<tr>
									<td class="formstyle centered">
										<strong>ORDER ID</strong>
									</td>
									<td class="formstyle centered">
										<strong>USER</strong>
									</td>
									<td class="formstyle centered">
										<strong>DATE</strong>
									</td>
									<td class="formstyle centered">
										<strong>AMOUNT</strong>
									</td>
									<td class="formstyle centered">
										<strong>BEFORE BALANCE</strong>
									</td>
									<td class="formstyle centered">
										<strong>AFTER BALANCE</strong>
									</td>
									<td class="formstyle centered">
										<strong>ACTION</strong>
									</td>
								</tr>
<?php
	if (count($cart_historys) > 0) {
		foreach ($cart_historys as $key=>$value) {
?>

								<tr class="formstyle">
									<td class="centered">
										<span><?=$value['cart_id']?></span>
									</td>
									<td class="bold centered">
										<span><?=$value['user_name']?></span>
									</td>
									<td class="centered">
										<span><?=date("H:i:s d/M/Y", $value['cart_time'])?></span>
									</td>
									<td class="bold centered">
										<span>$<?=$value['cart_total']?></span>
									</td>
									<td class="centered">
										<span>$<?=$value['cart_before']?></span>
									</td>
									<td class="centered">
										<span>$<?=$value['cart_before'] - $value['cart_total']?></span>
									</td>
									<td class="bold centered">
										<span><a href="./showcart.php?cart_id=<?=$value['cart_id']?>" class="viewcard">View Shopping Cart</a></span>
									</td>
								</tr>
<?php
		}
	}
	else {
?>
								<tr>
									<td colspan="8" class="red bold centered">
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
else {
	require("./minilogin.php");
}
require("./footer.php");
?>