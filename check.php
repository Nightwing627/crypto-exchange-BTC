<?php

set_time_limit(40);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require("./includes/config.inc.php");
require("./checkers/checker.php");

$_SESSION["debug"] = 1;
/* ==========================================================\
  |															|
  |	Sample php code to using API with www.UG-Market.com		|
  |	Require: Your host must supports php, curl				|
  |	Any questions, contact: windovv@runbox.com				|
  |															|
  \========================================================== */

//echo time();
if (checkLogin(PER_USER)) {
    if ($_GET["card_id"] != "") {
        $sql = "SELECT * FROM `" . TABLE_USERS . "` WHERE user_id = '" . $_SESSION["user_id"] . "'";
        $user_info = $db->query_first($sql);
        $card_id = $db->escape($_GET["card_id"]);
        $sql = "SELECT *, AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') AS card_number FROM `" . TABLE_CARDS . "` WHERE card_id = '" . $card_id . "' AND (card_check = '" . strval(CHECK_DEFAULT) . "' OR card_check IS NULL)";
        $records = $db->fetch_all_array($sql);
        if (count($records) > 0) {
            $value = $records[0];
            if ($value["card_userid"] == $_SESSION["user_id"]) {
                if (intval($value["card_buyTime"]) + intval($db_config["check_timeout"]) >= time()) {
                    $user_balance = $user_info["user_balance"];
                    if (doubleval($user_balance) >= doubleval($db_config["check_fee"])) {
                        $check_add["check_userid"] = $_SESSION["user_id"];
                        $check_add["check_cardid"] = $card_id;
                        $check_add["check_time"] = time();
                        $check = check($value["card_number"], $value["card_month"], $value["card_year"], $value["card_cvv"]);
                        if ($check == -1) {
                            if ($_SESSION["debug"] != 1) {
                                $credit_update["user_balance"] = doubleval($user_balance) + doubleval($value["card_price"]);
                                $check_add["check_fee"] = $db_config["check_fee"];
                                $check_add["check_result"] = strval(CHECK_REFUND);
                                $check_update["card_check"] = strval(CHECK_REFUND);
                            }
                            $respond = "<span class=\"pink bold\">INVALID - REFUNDED</span>";
                        } else if ($check == 1) {
                            $credit_update["user_balance"] = doubleval($user_balance) - doubleval($db_config["check_fee"]);
                            $check_add["check_fee"] = $db_config["check_fee"];
                            $check_add["check_result"] = strval(CHECK_VALID);
                            $check_update["card_check"] = strval(CHECK_VALID);
                            $respond = "<span class=\"green bold\">VALID</span>";
                        } else if ($check == 2) {
                            $credit_update["user_balance"] = doubleval($user_balance) + doubleval($value["card_price"]);
                            $check_add["check_fee"] = $db_config["check_fee"];
                            $check_add["check_result"] = strval(CHECK_REFUND);
                            $check_update["card_check"] = strval(CHECK_REFUND);
                            $respond = "<span class=\"pink bold\">INVALID - REFUNDED</span>";
                        } else if ($check == 3) {
                            $respond = "<span class=\"blue bold\">API ERROR</span>";
                        } else if ($check == -10) {
                            $respond = "<span class=\"blue bold\">CURL ERROR</span>";
                        } else if (($check == '0') || ($check == -2)) {
                            $respond = "<span class=\"blue bold\">TIMEOUT</span>";
                        } else {
                            $check_add["check_result"] = strval(CHECK_UNKNOWN);
                            $respond = "<span class=\"blue bold\">UNKNOWN(" . $check . ")</span>";
                        }
                    } else {
                        $respond = "<span class=\"red bold\">You must have $" . number_format($db_config["check_fee"], 2, '.', '') . " to check card</span>";
                    }
                } else {
                    $check_add["check_result"] = strval(CHECK_INVALID);
                    $check_update["card_check"] = strval(CHECK_INVALID);
                    $respond = "<span class=\"red bold\">TIMEOUT</span>";
                }
            } else {
                $respond = "<span class=\"red bold\">This card doesn't belong to you</span>";
            }
//            if (count($check_update) > 0) {
//                if (!$db->query_update(TABLE_CARDS, $check_update, "card_id='" . $card_id . "'")) {
//                    $respond = "<span class=\"red bold\">Update card check error, please try again</span>";
//                }
//            }
//            if (count($check_add) > 0) {
//                if (!$db->query_insert(TABLE_CHECKS, $check_add)) {
//                    $respond = "<span class=\"red bold\">Insert check information error, please try again</span>";
//                }
//            }
//            if (count($credit_update) > 0) {
//                if ($db->query_update(TABLE_USERS, $credit_update, "user_id='" . $_SESSION["user_id"] . "'")) {
//                    $user_info["user_balance"] = $credit_update["user_balance"];
//                } else {
//                    $respond = "<span class=\"red bold\">Update credit error, please try again</span>";
//                }
//            }
        } else {
            $respond = "<span class=\"red bold\">This card doesn't exist</span>";
        }
        if ($_SESSION["debug"] == 1) {
            if (isset($check) && ($check > 0))
                echo "<br/>Checking Code: " . $check . "<br>";
            echo "Respond: " . $respond;
            if ($check == -200) {       // Curl Error
                echo "<br><br><font color='red'> ==> Review your host";
            } elseif ($check == -1) {      // Invalid format/type
                echo "<br><br><font color='red'> ==> Invalid/Exp Card or Not accepted type";
            } elseif (($check == 1) || ($check == 2)) {  // Checking OK
                echo "<br><br><font color='red'> ==> It seems like everything is OK, Set \$_SESSION[\"debug\"] = 0 in mycards.php to exit Testing Mode";
            } elseif ($check == 3) {      // API Error
                echo "<br><br><font color='red'> ==> Review your Username, Password, Gate config";
            }
        }
    }
    if ($_SESSION["debug"] != 1) {
        header('location:mycards.php');
    }
} else {
    header("Location: login.php");
}
exit(0);
?>