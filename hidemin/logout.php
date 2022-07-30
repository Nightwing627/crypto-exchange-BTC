<?php
session_start();
setcookie("cookname", "", time()-60*60*24*365, "/");
setcookie("cookpass", "", time()-60*60*24*365, "/");
setcookie("remember", "", time()-60*60*24*365, "/");
unset($_SESSION['user_name']);
unset($_SESSION['user_pass']);
unset($_SESSION['user_groupid']);
$_SESSION = array();
session_destroy();
session_start();
header("location: ../");
?>