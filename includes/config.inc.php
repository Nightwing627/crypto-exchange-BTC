<?php
error_reporting(E_ALL  ^ E_NOTICE);
//CC Encrypt password
define('DB_ENCRYPT_PASS', "vampirevhc");
date_default_timezone_set('America/Los_Angeles');
/* Begin Configurations */
define("SHOW_SQL_ERROR", true);
define('DB_SERVER', "localhost");
define('DB_DATABASE', "proccuser");
define('DB_USER', "root");
define('DB_PASS', "");

$dbc = mysqli_connect (DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit;
}

// mysql_select_db (DB_DATABASE) OR die ('Could not select the database: ' . mysql_error() );
//table names
define('TABLE_ADS', "ads");
define('TABLE_BINS', "bins");
define('TABLE_CARDS', "cards");
define('TABLE_CARTS', "carts");
define('TABLE_CHECKS', "checks");
define('TABLE_CHECKERS', "checkers");
define('TABLE_CONFIGS', "configs");
define('TABLE_NEWS', "news");
define('TABLE_ORDERS', "orders");
define('TABLE_USERS', "users");
define('TABLE_GROUPS', "groups");
define('TABLE_ZIPCODES', "zipcodes");
//Added By DHSprout
define('TABLE_TICKETS', "tickets");
define('TABLE_ANSWERS', "answers");
define('TABLE_COUNTRIES', "countries");

//permission
define('PER_BAN', 4);
define('PER_USER', 3);
define('PER_MOD', 2);
define('PER_ADMIN', 1);

//check result
define('CHECK_DEFAULT', 0);
define('CHECK_VALID', 1);
define('CHECK_INVALID', 2);
define('CHECK_REFUND', 3);
define('CHECK_UNKNOWN', 4);

//card status
define('STATUS_DEFAULT', 0);
define('STATUS_DELETED', 1);

//card expire
define('EXPIRE_FUTURE', 0);
define('EXPIRE_STAGNANT', 1);
define('EXPIRE_EXPIRED', 2);

//config
define('DEFAULT_GROUP_ID', 3);
define('DEFAULT_BALANCE', 0);

/* End Configurations */
/* Begin Global Functions */
function confirmUser($user_name, $user_pass, $user_groupid, $remember = true) {
	global $db;
	$db->escape($user_name);
	$sql = "SELECT user_pass, user_salt, user_groupid, user_id FROM `".TABLE_USERS."` WHERE user_name = '$user_name'";
	$record = $db->query_first($sql);
	if($record) {
		if(md5(md5($user_pass).$record['user_salt']) == $record['user_pass']) {
			if ($user_groupid >= $record['user_groupid'])
			{
				$_SESSION['user_id'] = $record['user_id'];
				$_SESSION['user_name'] = $user_name;
				$_SESSION['user_pass'] = $user_pass;
				$_SESSION['user_groupid'] = $record['user_groupid'];
				if($remember){
					setcookie("cookname", $_SESSION['user_name'], time()+60*60*24*365, "/");
					setcookie("cookpass", $_SESSION['user_pass'], time()+60*60*24*365, "/");
					setcookie("remember", true, time()+60*60*24*365, "/");
				}
				else {
					setcookie("cookname", $_SESSION['user_name'], time()+60*60, "/");
					setcookie("cookpass", $_SESSION['user_pass'], time()+60*60, "/");
					setcookie("remember", false, time()+60*60*24, "/");
				}
				return 0; //Success
			}
			else
			{
				return 3; //Not have permission
			}
		}
		else {
			return 2; //Wrong password
		}
	}
	else
	{
		return 1; //Username not found
	}
}
function checkLogin($user_groupid) {
	if(isset($_SESSION['user_name']) && !isset($_SESSION['user_pass']) && isset($_SESSION['user_groupid'])) {
		if ($user_groupid >= $_SESSION['user_groupid']) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])) {
			$_SESSION['user_name'] = $_COOKIE['cookname'];
			$_SESSION['user_pass'] = $_COOKIE['cookpass'];
			if(confirmUser($_SESSION['user_name'], $_SESSION['user_pass'], $user_groupid, $_COOKIE['remember']) == 0) {
				return true;
			}
		}
		return false;
	}
}
function usernameFaild($username) {
	if (strlen($username) < 4) {
		return 1;
	}
	else if (strlen($username) > 32) {
		return 2;
	}
	else {
		return 0;
	}
}
function passwordFaild($password, $repassword) {
	if (strlen($password) < 6) {
		return 1;
	}
	else if (strlen($password) > 32) {
		return 2;
	}
	else if ($password != $repassword) {
		return 3;
	}
	else {
		return 0;
	}
}
function emailFaild($email) {
	if (!preg_match("/^([a-zA-Z0-9.])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/", $email))
	{
		return 1;
	}
	else {
		return 0;
	}
}
/* End Global Functions */

/* Begin Load Class MySQL */
require("mysql.class.php");
/* End Load Class MySQL */

/* Begin Connect MySQL */
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
/* End Connect MySQL */

/* Begin load db configuration */
$sql = "SELECT * FROM `".TABLE_CONFIGS."` ORDER BY config_name";
$db_config_temp = $db->fetch_all_array($sql);
foreach ($db_config_temp as $key => $value) {
	$db_config[$value["config_name"]] = $value["config_value"];
}
unset($db_config_temp);
$sql = "SELECT * FROM `".TABLE_GROUPS."` ORDER BY group_id";
$user_groups_temp = $db->fetch_all_array($sql);
foreach ($user_groups_temp as $key => $value) {
	$user_groups[$value["group_id"]] = array("group_name"=>$value["group_name"], "group_color"=>$value["group_color"]);
}
/* End load db configuration */

?>