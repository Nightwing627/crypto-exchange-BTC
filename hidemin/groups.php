<?php
require("./header.php");
if ($checkLogin) {
	if ($_GET["act"] == "edit" && $_GET["group_id"] != "") {
		$group_id = $db->escape($_GET["group_id"]);
?>
				<div id="group_manager">
					<div class="section_title">GROUP EDITOR</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
<?php
		if (isset($_POST["group_edit_save"])) {
			$group_update["group_name"] = $_POST["group_name"];
			$group_update["group_color"] = $_POST["group_color"];
			if ($errorMsg == "") {
				if($db->query_update(TABLE_GROUPS, $group_update, "group_id='".$group_id."'")) {
					$errorMsg = "";
				}
				else {
					$errorMsg = "Update Group error.";
				}
			}
			if ($errorMsg == "") {
?>
									<script type="text/javascript">setTimeout("window.location = './groups.php'", 1000);</script>
									<tr>
										<td colspan="7" class="centered">
											<span class="green bold">Update Group successfully.</span>
										</td>
									</tr>
<?php
			}
			else {
?>
									<tr>
										<td colspan="7" class="centered">
											<span class="red bold"><?=$errorMsg?></span>
										</td>
									</tr>
<?php
			}
		}
?>
<?php
		$sql = "SELECT * FROM `".TABLE_GROUPS."` WHERE group_id = '".$group_id."'";
		$records = $db->fetch_all_array($sql);
		if (count($records)>0) {
			$value = $records[0];
?>
								<form method="POST" action="">
								<tr>
									<td class="formstyle centered">
										<strong>GROUP ID</strong>
									</td>
									<td class="formstyle centered">
										<strong>GROUP NAME</strong>
									</td>
									<td class="formstyle centered">
										<strong>GROUP COLOR</strong>
									</td>
								</tr>
									<tr>
										<td class="centered">
											<span><?=$value['group_id']?></span>
										</td>
										<td class="bold centered">
											<span><input class="formstyle bold" id="group_name" name="group_name" type="text" style="color:<?=$value['group_color']?>;" value="<?=$value['group_name']?>" /></span>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="group_color" type="text" value="<?=$value['group_color']?>" onchange="javascript:($('#group_name').css('color', (this.value)));" /></span>
										</td>
									</tr>
									<tr>
										<td colspan="7" class="centered">
											<input type="submit" name="group_edit_save" value="Save" /><input onclick="window.location='./groups.php'"type="button" name="group_edit_cancel" value="Cancel" />
										</td>
									</tr>
								</form>
<?php
		}
		else {
?>
								<tr>
									<td class="groups_title">
										<span class="red">Group ID Invalid.</span>
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
		$sql = "SELECT count(*) FROM `".TABLE_GROUPS."`";
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
		$sql = "SELECT * FROM `".TABLE_GROUPS."` ORDER BY group_id ASC LIMIT ".(($page-1)*$perPage).",".$perPage;
		$order_historys = $db->fetch_all_array($sql);
?>
				<div id="group_manager">
					<div class="section_title">GROUPS MANAGER</div>
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
										<strong>GROUP ID</strong>
									</td>
									<td class="formstyle centered">
										<strong>GROUP NAME</strong>
									</td>
									<td class="formstyle centered">
										<strong>GROUP COLOR</strong>
									</td>
									<td class="formstyle centered">
										<strong>ACTION</strong>
									</td>
								</tr>
<?php
		if (count($order_historys) > 0) {
			foreach ($order_historys as $key=>$value) {
?>
								<tr class="formstyle">
									<td class="centered">
										<span><?=$value['group_id']?></span>
									</td>
									<td class="bold centered">
										<span style="color:<?=$value["group_color"]?>;"><?=$value["group_name"]?></span>
									</td>
									<td class="centered">
										<span><?=$value['group_color']?></span>
									</td>
									<td class="centered">
										<span><a href="?act=edit&group_id=<?=$value['group_id']?>">Edit</a></span>
									</td>
								</tr>
<?php
			}
		}
		else {
?>
								<tr>
									<td colspan="7" class="red centered">
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
?>
<?php
}
else {
	require("./minilogin.php");
}
require("./footer.php");
?>