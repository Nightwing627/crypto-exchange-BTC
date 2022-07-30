<?php
$page_name = "login";
require("./header.php");
if ($checkLogin) {
?>
			<script type="text/javascript">setTimeout("window.location = './'", 1000);</script>
			<div id="login_success">
				Login Successful, <b><?=$_SESSION['user_name']?></b>.<br/>
				<a href="./">Click here if your browser does not automatically redirect you.</a>
			</div>
<?php
}
else {
	require("./minilogin.php");
}
require("./footer.php");
?>