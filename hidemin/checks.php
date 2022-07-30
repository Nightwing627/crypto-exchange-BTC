<?php
require("./header.php");
if ($checkLogin) {
	if (isset($_GET["btnSearch"])) {
		$currentGet = "";
		$currentGet .= "lstCheckuserid=".$_GET["lstCheckuserid"]."&txtCardnumber=".$_GET["txtCardnumber"]."&lstCheckdate=".$_GET["lstCheckdate"]."&lstCheckmonth=".$_GET["lstCheckmonth"]."&lstCheckyear=".$_GET["lstCheckyear"];
		$currentGet .= "&btnSearch=Search&";
	}
	$searchCheckuserid = $db->escape($_GET["lstCheckuserid"]);
	$searchCardnumber = $db->escape($_GET["txtCardnumber"]);
	$searchCheckdate = $db->escape($_GET["lstCheckdate"]);
	$searchCheckmonth = $db->escape($_GET["lstCheckmonth"]);
	$searchCheckyear = $db->escape($_GET["lstCheckyear"]);
	$sql = "SELECT count(*) FROM `".TABLE_CHECKS."` JOIN `".TABLE_CARDS."` JOIN `".TABLE_USERS."` ON ".TABLE_CHECKS.".check_cardid = ".TABLE_CARDS.".card_id AND ".TABLE_CHECKS.".check_userid = ".TABLE_USERS.".user_id WHERE ('".$searchCheckuserid."'='' OR ".TABLE_CHECKS.".check_userid = '".$searchCheckuserid."') AND ('".$searchCardnumber."'='' OR AES_DECRYPT(".TABLE_CARDS.".card_number, '".strval(DB_ENCRYPT_PASS)."') LIKE '".$searchCardnumber."%') AND ('".$searchCheckdate."'='' OR FROM_UNIXTIME(".TABLE_CHECKS.".check_time, '%d') = '".$searchCheckdate."') AND ('".$searchCheckmonth."'='' OR FROM_UNIXTIME(".TABLE_CHECKS.".check_time, '%m') = '".$searchCheckmonth."') AND ('".$searchCheckyear."'='' OR FROM_UNIXTIME(".TABLE_CHECKS.".check_time, '%Y') = '".$searchCheckyear."')";
	$totalRecords = $db->query_first($sql);
	$totalRecords = $totalRecords["count(*)"];
	$perPage = 10;
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
	$sql = "SELECT `".TABLE_CHECKS."`.*, `".TABLE_CARDS."`.card_id, AES_DECRYPT(".TABLE_CARDS.".card_number, '".strval(DB_ENCRYPT_PASS)."') AS card_number,`".TABLE_USERS."`.user_name FROM `".TABLE_CHECKS."` JOIN `".TABLE_CARDS."` JOIN `".TABLE_USERS."` ON ".TABLE_CHECKS.".check_cardid = ".TABLE_CARDS.".card_id AND ".TABLE_CHECKS.".check_userid = ".TABLE_USERS.".user_id WHERE ('".$searchCheckuserid."'='' OR ".TABLE_CHECKS.".check_userid = '".$searchCheckuserid."') AND ('".$searchCardnumber."'='' OR AES_DECRYPT(".TABLE_CARDS.".card_number, '".strval(DB_ENCRYPT_PASS)."') LIKE '".$searchCardnumber."%') AND ('".$searchCheckdate."'='' OR FROM_UNIXTIME(".TABLE_CHECKS.".check_time, '%d') = '".$searchCheckdate."') AND ('".$searchCheckmonth."'='' OR FROM_UNIXTIME(".TABLE_CHECKS.".check_time, '%m') = '".$searchCheckmonth."') AND ('".$searchCheckyear."'='' OR FROM_UNIXTIME(".TABLE_CHECKS.".check_time, '%Y') = '".$searchCheckyear."') ORDER BY check_id DESC LIMIT ".(($page-1)*$perPage).",".$perPage;
	$listcards = $db->fetch_all_array($sql);
?>
				<div id="search_cards">
					<div class="section_title">SEARCH CHECK HISTORY</div>
					<div class="section_content">
						<table class="content_table centered">
							<tbody>
								<form name="search" method="GET" action="">
									<tr>
										<td class="formstyle">
											<span class="bold">USERNAME</span>
										</td>
										<td class="formstyle">
											<span class="bold">CARD NUMBER</span>
										</td>
										<td class="formstyle">
											<span class="bold">DATE</span>
										</td>
									</tr>
									<tr>
										<td>
											<select name="lstCheckuserid" class="formstyle bold" id="lstCheckuserid" style="color:<?=$user_groups[$_GET["lstCheckuserid"]]["group_color"]?>;" onchange="javascript:($('#lstCheckuserid').css('color', ($('#lstCheckuserid option:selected').css('color'))));">
												<option value="" style="color:#0D0D0D;">All users</option>
<?php
		$sql = "SELECT * FROM `".TABLE_USERS."`";
		$allUsers = $db->fetch_all_array($sql);
		if (count($allUsers) > 0) {
			foreach ($allUsers as $user) {
				echo "<option value=\"".$user['user_id']."\"".(($_GET["lstCheckuserid"] == $user['user_id'])?" selected":"")." style=\"color:".$user_groups[$user['user_groupid']]["group_color"].";\" >".$user['user_name']."</option>";
			}
		}
?>
											</select>
										</td>
										<td>
											<input name="txtCardnumber" type="text" class="formstyle" id="txtCardnumber" value="<?=$_GET["txtCardnumber"]?>" size="20">
										</td>
										<td>
											<select name="lstCheckdate" class="formstyle bold" id="lstCheckdate" >
												<option value="">Date</option>
												<option value="01" <?php if ($_GET["lstCheckdate"] == "01") echo "selected ";?>>01</option>
												<option value="02" <?php if ($_GET["lstCheckdate"] == "02") echo "selected ";?>>02</option>
												<option value="03" <?php if ($_GET["lstCheckdate"] == "03") echo "selected ";?>>03</option>
												<option value="04" <?php if ($_GET["lstCheckdate"] == "04") echo "selected ";?>>04</option>
												<option value="05" <?php if ($_GET["lstCheckdate"] == "05") echo "selected ";?>>05</option>
												<option value="06" <?php if ($_GET["lstCheckdate"] == "06") echo "selected ";?>>06</option>
												<option value="07" <?php if ($_GET["lstCheckdate"] == "07") echo "selected ";?>>07</option>
												<option value="08" <?php if ($_GET["lstCheckdate"] == "08") echo "selected ";?>>08</option>
												<option value="09" <?php if ($_GET["lstCheckdate"] == "09") echo "selected ";?>>09</option>
												<option value="10" <?php if ($_GET["lstCheckdate"] == "10") echo "selected ";?>>10</option>
												<option value="11" <?php if ($_GET["lstCheckdate"] == "11") echo "selected ";?>>11</option>
												<option value="12" <?php if ($_GET["lstCheckdate"] == "12") echo "selected ";?>>12</option>
												<option value="13" <?php if ($_GET["lstCheckdate"] == "13") echo "selected ";?>>13</option>
												<option value="14" <?php if ($_GET["lstCheckdate"] == "14") echo "selected ";?>>14</option>
												<option value="15" <?php if ($_GET["lstCheckdate"] == "15") echo "selected ";?>>15</option>
												<option value="16" <?php if ($_GET["lstCheckdate"] == "16") echo "selected ";?>>16</option>
												<option value="17" <?php if ($_GET["lstCheckdate"] == "17") echo "selected ";?>>17</option>
												<option value="18" <?php if ($_GET["lstCheckdate"] == "18") echo "selected ";?>>18</option>
												<option value="19" <?php if ($_GET["lstCheckdate"] == "19") echo "selected ";?>>19</option>
												<option value="20" <?php if ($_GET["lstCheckdate"] == "20") echo "selected ";?>>20</option>
												<option value="21" <?php if ($_GET["lstCheckdate"] == "21") echo "selected ";?>>21</option>
												<option value="22" <?php if ($_GET["lstCheckdate"] == "22") echo "selected ";?>>22</option>
												<option value="23" <?php if ($_GET["lstCheckdate"] == "23") echo "selected ";?>>23</option>
												<option value="24" <?php if ($_GET["lstCheckdate"] == "24") echo "selected ";?>>24</option>
												<option value="25" <?php if ($_GET["lstCheckdate"] == "25") echo "selected ";?>>25</option>
												<option value="26" <?php if ($_GET["lstCheckdate"] == "26") echo "selected ";?>>26</option>
												<option value="27" <?php if ($_GET["lstCheckdate"] == "27") echo "selected ";?>>27</option>
												<option value="28" <?php if ($_GET["lstCheckdate"] == "28") echo "selected ";?>>28</option>
												<option value="29" <?php if ($_GET["lstCheckdate"] == "29") echo "selected ";?>>29</option>
												<option value="30" <?php if ($_GET["lstCheckdate"] == "30") echo "selected ";?>>30</option>
												<option value="31" <?php if ($_GET["lstCheckdate"] == "31") echo "selected ";?>>31</option>
											</select>
											<select name="lstCheckmonth" class="formstyle bold" id="lstCheckmonth" >
												<option value="">Month</option>
												<option value="01" <?php if ($_GET["lstCheckmonth"] == "01") echo "selected ";?>>01</option>
												<option value="02" <?php if ($_GET["lstCheckmonth"] == "02") echo "selected ";?>>02</option>
												<option value="03" <?php if ($_GET["lstCheckmonth"] == "03") echo "selected ";?>>03</option>
												<option value="04" <?php if ($_GET["lstCheckmonth"] == "04") echo "selected ";?>>04</option>
												<option value="05" <?php if ($_GET["lstCheckmonth"] == "05") echo "selected ";?>>05</option>
												<option value="06" <?php if ($_GET["lstCheckmonth"] == "06") echo "selected ";?>>06</option>
												<option value="07" <?php if ($_GET["lstCheckmonth"] == "07") echo "selected ";?>>07</option>
												<option value="08" <?php if ($_GET["lstCheckmonth"] == "08") echo "selected ";?>>08</option>
												<option value="09" <?php if ($_GET["lstCheckmonth"] == "09") echo "selected ";?>>09</option>
												<option value="10" <?php if ($_GET["lstCheckmonth"] == "10") echo "selected ";?>>10</option>
												<option value="11" <?php if ($_GET["lstCheckmonth"] == "11") echo "selected ";?>>11</option>
												<option value="12" <?php if ($_GET["lstCheckmonth"] == "12") echo "selected ";?>>12</option>
											</select>
											<select name="lstCheckyear" class="formstyle bold" id="lstCheckyear" >
												<option value="">Year</option>
												<option value="2011" <?php if ($_GET["lstCheckyear"] == "2011") echo "selected ";?>>2011</option>
												<option value="2012" <?php if ($_GET["lstCheckyear"] == "2012") echo "selected ";?>>2012</option>
												<option value="2013" <?php if ($_GET["lstCheckyear"] == "2013") echo "selected ";?>>2013</option>
												<option value="2014" <?php if ($_GET["lstCheckyear"] == "2014") echo "selected ";?>>2014</option>
												<option value="2015" <?php if ($_GET["lstCheckyear"] == "2015") echo "selected ";?>>2015</option>
												<option value="2016" <?php if ($_GET["lstCheckyear"] == "2016") echo "selected ";?>>2016</option>
												<option value="2017" <?php if ($_GET["lstCheckyear"] == "2017") echo "selected ";?>>2017</option>
												<option value="2018" <?php if ($_GET["lstCheckyear"] == "2018") echo "selected ";?>>2018</option>
												<option value="2019" <?php if ($_GET["lstCheckyear"] == "2019") echo "selected ";?>>2019</option>
												<option value="2020" <?php if ($_GET["lstCheckyear"] == "2020") echo "selected ";?>>2020</option>
												<option value="2021" <?php if ($_GET["lstCheckyear"] == "2021") echo "selected ";?>>2021</option>
												<option value="2022" <?php if ($_GET["lstCheckyear"] == "2022") echo "selected ";?>>2022</option>
												<option value="2023" <?php if ($_GET["lstCheckyear"] == "2023") echo "selected ";?>>2023</option>
												<option value="2024" <?php if ($_GET["lstCheckyear"] == "2024") echo "selected ";?>>2024</option>
												<option value="2025" <?php if ($_GET["lstCheckyear"] == "2025") echo "selected ";?>>2025</option>
												<option value="2026" <?php if ($_GET["lstCheckyear"] == "2026") echo "selected ";?>>2026</option>
												<option value="2027" <?php if ($_GET["lstCheckyear"] == "2027") echo "selected ";?>>2027</option>
												<option value="2028" <?php if ($_GET["lstCheckyear"] == "2028") echo "selected ";?>>2028</option>
												<option value="2029" <?php if ($_GET["lstCheckyear"] == "2029") echo "selected ";?>>2029</option>
												<option value="2030" <?php if ($_GET["lstCheckyear"] == "2030") echo "selected ";?>>2030</option>
												<option value="2031" <?php if ($_GET["lstCheckyear"] == "2031") echo "selected ";?>>2031</option>
												<option value="2032" <?php if ($_GET["lstCheckyear"] == "2032") echo "selected ";?>>2032</option>
												<option value="2033" <?php if ($_GET["lstCheckyear"] == "2033") echo "selected ";?>>2033</option>
												<option value="2034" <?php if ($_GET["lstCheckyear"] == "2034") echo "selected ";?>>2034</option>
												<option value="2035" <?php if ($_GET["lstCheckyear"] == "2035") echo "selected ";?>>2035</option>
												<option value="2036" <?php if ($_GET["lstCheckyear"] == "2036") echo "selected ";?>>2036</option>
												<option value="2037" <?php if ($_GET["lstCheckyear"] == "2037") echo "selected ";?>>2037</option>
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
					<div class="section_title">CHECK HISTORY</div>
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
										<strong>ID</strong>
									</td>
									<td class="formstyle centered">
										<strong>USER</strong>
									</td>
									<td class="formstyle centered">
										<strong>DATE</strong>
									</td>
									<td class="formstyle centered">
										<strong>Card Number</strong>
									</td>
									<td class="formstyle centered">
										<strong>Check Result</strong>
									</td>
								</tr>
<?php
	if (count($listcards) > 0) {
		foreach ($listcards as $key=>$value) {
?>
								<tr class="formstyle">
									<td class="centered">
										<span><?=$value['check_id']?></span>
									</td>
									<td class="centered bold">
										<span><?=$value['user_name']?></span>
									</td>
									<td class="centered">
										<span><?=date("H:i:s d/M/Y", $value['check_time'])?></span>
									</td>
									<td class="centered">
										<span><?=$value['card_number']?></span>
									</td>
									<td class="centered bold">
<?php
			switch ($value['check_result']) {
				case strval(CHECK_VALID):
					echo "<span class=\"green bold\">VALID</span>";
					break;
				case strval(CHECK_INVALID):
					echo "<span class=\"red bold\">TIMEOUT</span>";
					break;
				case strval(CHECK_REFUND):
					echo "<span class=\"pink bold\">INVALID - REFUNED</span>";
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
	}
	else {
?>
								<tr>
									<td colspan="5" class="red bold centered">
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