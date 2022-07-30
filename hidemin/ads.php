<?php
require("./header.php");
if ($checkLogin) {
	if ($_GET["act"] == "add") {
?>
				<div id="ads">
					<div class="section_title">PUBLISH ADS</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
<?php
			if (isset($_POST["ad_add_save"])) {
				$ad_add["ad_title"] = $_POST["ad_title"];
				$ad_add["ad_content"] = $_POST["ad_content"];
				$ad_add["ad_time"] = time();
				if($db->query_insert(TABLE_ADS, $ad_add)) {
?>
									<script type="text/javascript">setTimeout("window.location = './ads.php'", 1000);</script>
									<tr>
										<td colspan="2" class="centered">
											<span class="green bold">Add AD successfully.</span>
										</td>
									</tr>
<?php
				}
				else {
?>
									<tr>
										<td colspan="2" class="centered">
											<span class="red bold">Add AD error.</span>
										</td>
									</tr>
<?php
				}
			}
?>
<?php
?>
								<form method="POST" action="">
									<tr>
										<td class="ad_editor">
											Title:
										</td>
										<td>
											<input class="ad_title_editor" name="ad_title" type="text" value="<?=$_POST["ad_title"]?>" />
										</td>
									</tr>
									<tr>
										<td class="ad_editor">
											Content:
										</td>
										<td>
											<textarea class="ad_content_editor" name="ad_content" type="text"><?=$_POST['ad_content']?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="2" class="centered">
											<input type="submit" name="ad_add_save" value="Save" /><input onclick="window.location='./ads.php'"type="button" name="ad_add_cancel" value="Cancel" />
										</td>
									</tr>
								</form>
							</tbody>
						</table>
					</div>
				</div>
<?php
	}
	else if ($_GET["act"] == "edit" && $_GET["ad_id"] != "") {
		$ad_id = $db->escape($_GET["ad_id"]);
?>
				<div id="ads">
					<div class="section_title">ADS EDITOR</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
<?php
		if (isset($_POST["ad_edit_save"])) {
			$ad_update["ad_title"] = $_POST["ad_title"];
			$ad_update["ad_content"] = $_POST["ad_content"];
			$ad_update["ad_time"] = strtotime($_POST["ad_time"]." ".date("H:i:s"));
			if($db->query_update(TABLE_ADS, $ad_update, "ad_id='".$ad_id."'")) {
?>
									<tr>
										<td colspan="2" class="centered">
											<span class="green bold">Update AD successfully.</span>
										</td>
									</tr>
<?php
			}
			else {
?>
									<tr>
										<td colspan="2" class="centered">
											<span class="red bold">Update AD error.</span>
										</td>
									</tr>
<?php
			}
		}
?>
<?php
		$sql = "SELECT * FROM `".TABLE_ADS."` WHERE ad_id = '".$ad_id."'";
		$records = $db->fetch_all_array($sql);
		if (count($records)>0) {
			$value = $records[0];
?>
								<form method="POST" action="">
									<tr>
										<td class="ad_editor">
											Title:
										</td>
										<td>
											<input class="ad_title_editor" name="ad_title" type="text" value="<?=$value['ad_title']?>" />
										</td>
									</tr>
									<tr>
										<td class="ad_editor">
											Content:
										</td>
										<td>
											<textarea class="ad_content_editor" name="ad_content" type="text"><?=$value['ad_content']?></textarea>
										</td>
									</tr>
									<tr>
										<td class="ad_editor">
											Time:
										</td>
										<td class="ad_info black">
											<?php
												$myCalendar = new tc_calendar("ad_date", true);
												$myCalendar->setIcon("../images/iconCalendar.gif");
												$myCalendar->setDate(date("d", $value['ad_time']), date("m", $value['ad_time']), date("Y", $value['ad_time']));
												$myCalendar->setPath("./");
												$myCalendar->setYearInterval(2011, 2050);
												$myCalendar->dateAllow('2011-01-01', '2050-01-01');
												$myCalendar->writeScript();
											?>
											<input name="ad_time" type="hidden" value="" />
										</td>
										<div class="clear"></div>
									</tr>
									<tr>
										<td colspan="2" class="centered">
											<input type="submit" onclick="javascript:ad_time.value = ad_date.value;" name="ad_edit_save" value="Save" /><input onclick="window.location='./ads.php'"type="button" name="ad_edit_cancel" value="Cancel" />
										</td>
									</tr>
								</form>
<?php
		}
		else {
?>
								<tr>
									<td class="ad_title">
										<span class="red">AD ID Invalid.</span>
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
	else if ($_GET["act"] == "delete" && $_GET["ad_id"] != "") {
		$ad_id = $db->escape($_GET["ad_id"]);
		$sql = "DELETE FROM `".TABLE_ADS."` WHERE ad_id = '".$ad_id."'";
		if ($db->query($sql)) {
?>
				<script type="text/javascript">setTimeout("window.location = './ads.php'", 1000);</script>
				<div id="ads">
					<div class="section_title">ADS DELETE</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<tr>
									<td class="ad_title">
										<span class="red">Delete AD ID <?=$ad_id?> successfully.</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
<?php
		}
		else {
?>
				<div id="ads">
					<div class="section_title">ADS DELETE</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<tr>
									<td class="ad_title">
										<span class="red">AD ID Invalid.</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
<?php
		}
	}
	else if ($_GET["act"] == "deleteall") {
		$sql = "TRUNCATE TABLE `ads`";
		$db->query($sql);
?>
				<script type="text/javascript">setTimeout("window.location = './ads.php'", 1000);</script>
				<div id="ads">
					<div class="section_title">ADS DELETE ALL</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
								<tr>
									<td class="ad_title">
										<span class="red">Delete all of AD successfully.</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
<?php
	}
	else {
		$sql = "SELECT count(*) FROM `".TABLE_ADS."`";
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
		$sql = "SELECT * FROM `".TABLE_ADS."` ORDER BY ad_time DESC,ad_id DESC LIMIT ".(($page-1)*$perPage).",".$perPage;
		$records = $db->fetch_all_array($sql);
?>
				<div id="ads">
					<div class="section_title">ADS MANAGER</div>
					<div class="section_title"><a href="?act=add">Add ADS</a> | <a href="?act=deleteall">Delete all ADS</a></div>
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
<?php
		if (count($records) > 0)
		{
			foreach ($records as $key=>$value) {
?>
						<table class="formstyle content_table">
							<tbody>
								<tr>
									<td class="ad_menu">
										<span><a href="?act=edit&ad_id=<?=$value['ad_id']?>">Edit</a> | <a href="?act=delete&ad_id=<?=$value['ad_id']?>">Delete</a></span>
									</td>
									<div class="clear"></div>
								</tr>
								<tr>
									<td class="ad_title">
										<span class="red"><?=$value['ad_title']?></span>
									</td>
								</tr>
								<tr>
									<td class="ad_content">
										<span class="black"><?=$value['ad_content']?></span>
									</td>
								</tr>
								<tr>
									<td class="ad_info black">
										Published in <?=date("H:i:s d/M/Y", $value['ad_time'])?>
									</td>
									<div class="clear"></div>
								</tr>
							</tbody>
						</table>
<?php
			}
		}
		else {
?>
						<table class="content_table">
							<tbody>
								<tr>
									<td class="red bold ad_title">
										No record found.
									</td>
								</tr>
							</tbody>
						</table>
<?php
		}
?>
					</div>
				</div>
<?php
	}
}
else {
	require("./minilogin.php");
}
require("./footer.php");
?>