<?php
require("./header.php");
if ($checkLogin) {
	if ($_GET["act"] == "edit" && $_GET["config_id"] != "") {
		$config_id = $db->escape($_GET["config_id"]);
?>
				<div id="config_manager">
					<div class="section_title">CONFIG EDITOR</div>
					<div class="section_content">
						<table class="content_table">
							<tbody>
<?php
		if (isset($_POST["config_edit_save"])) {
			$config_update["config_value"] = $_POST["config_value"];
			if ($errorMsg == "") {
				if($db->query_update(TABLE_CONFIGS, $config_update, "config_id='".$config_id."'")) {
					$errorMsg = "";
				}
				else {
					$errorMsg = "Update Config error.";
				}
			}
			if ($errorMsg == "") {
?>
									<script type="text/javascript">setTimeout("window.location = './configs.php'", 1000);</script>
									<tr>
										<td colspan="7" class="centered">
											<span class="green bold">Update Config successfully.</span>
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
		$sql = "SELECT * FROM `".TABLE_CONFIGS."` WHERE config_id = '".$config_id."'";
		$records = $db->fetch_all_array($sql);
		if (count($records)>0) {
			$value = $records[0];
?>
								<form method="POST" action="">
								<tr>
									<td class="formstyle centered">
										<strong>CONFIG ID</strong>
									</td>
									<td class="formstyle centered">
										<strong>CONFIG NAME</strong>
									</td>
									<td class="formstyle centered">
										<strong>CONFIG VALUE</strong>
									</td>
								</tr>
									<tr>
										<td class="centered">
											<span><?=$value['config_id']?></span>
										</td>
										<td class="bold centered">
											<span><?=$value['config_name']?></span>
										</td>
										<td class="centered">
											<span><input class="formstyle" name="config_value" type="text" value="<?=$value['config_value']?>" /></span>
										</td>
									</tr>
									<tr>
										<td colspan="7" class="centered">
											<input type="submit" name="config_edit_save" value="Save" /><input onclick="window.location='./configs.php'"type="button" name="config_edit_cancel" value="Cancel" />
										</td>
									</tr>
								</form>
<?php
		}
		else {
?>
								<tr>
									<td class="configs_title">
										<span class="red">Config ID Invalid.</span>
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
		$sql = "SELECT count(*) FROM `".TABLE_CONFIGS."`";
		$totalRecords = $db->query_first($sql);
		$totalRecords = $totalRecords["count(*)"];
		$perPage = 50;
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
		$sql = "SELECT * FROM `".TABLE_CONFIGS."` ORDER BY config_id ASC LIMIT ".(($page-1)*$perPage).",".$perPage;
		$order_historys = $db->fetch_all_array($sql);
?>
				<div id="config_manager">
					<div class="section_title">CONFIGS MANAGER</div>
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
										<strong>CONFIG ID</strong>
									</td>
									<td class="formstyle centered">
										<strong>CONFIG NAME</strong>
									</td>
									<td class="formstyle centered">
										<strong>CONFIG VALUE</strong>
									</td>
									<td class="formstyle centered">
										<strong>CONFIG DESCRIPTION</strong>
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
										<span><?=$value['config_id']?></span>
									</td>
									<td class="bold centered">
										<span><?=$value["config_name"]?></span>
									</td>
									<td class="centered">
										<span><?=$value['config_value']?></span>
									</td>
									<td class="centered">
										<span><?=$value['config_description']?></span>
									</td>
									<td class="centered">
										<span><a href="?act=edit&config_id=<?=$value['config_id']?>">Edit</a></span>
									</td>
								</tr>
<?php
			}
		}
		else {
?>
								<tr>
									<td colspan="7" class="red bold centered">
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