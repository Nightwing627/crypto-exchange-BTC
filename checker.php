<?php

set_time_limit(0);
session_start();
require("./includes/config.inc.php");
require("./checkers/checker.php");
if (checkLogin(PER_USER)) {
    if ($_GET["card_id"] != "") {
        $card_id = $db->escape($_GET["card_id"]);
        $sql = "SELECT *, AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') AS card_number FROM `" . TABLE_CARDS . "` WHERE card_id = '" . $card_id . "' AND card_check = '" . strval(CHECK_DEFAULT) . "'";
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
                        if ($check == 1) {
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
                        } else {
                            $check_add["check_result"] = strval(CHECK_UNKNOWN);
                            $respond = "<span class=\"blue bold\">UNKNOWN</span>";
                        }
                    } else {
                        $respond = "<span class=\"red bold\">VALID</span>";
                    }
                } else {
                    $check_add["check_result"] = strval(CHECK_INVALID);
                    $check_update["card_check"] = strval(CHECK_INVALID);
                    $respond = "<span class=\"red bold\">VALID</span>";
                }
            } else {
                $respond = "<span class=\"red bold\">This card doesn't belong to you</span>";
            }
            if (count($check_update) > 0) {
                if (!$db->query_update(TABLE_CARDS, $check_update, "card_id='" . $card_id . "'")) {
                    $respond = "<span class=\"red bold\">Update card check error, please try again</span>";
                }
            }
            if (count($check_add) > 0) {
                if (!$db->query_insert(TABLE_CHECKS, $check_add)) {
                    $respond = "<span class=\"red bold\">Insert check information error, please try again</span>";
                }
            }
            if (count($credit_update) > 0) {
                if ($db->query_update(TABLE_USERS, $credit_update, "user_id='" . $_SESSION["user_id"] . "'")) {
                    $user_info["user_balance"] = $credit_update["user_balance"];
                } else {
                    $respond = "<span class=\"red bold\">Update credit error, please try again</span>";
                }
            }
        } else {
            $respond = "<span class=\"red bold\">This card doesn't exist</span>";
        }
        echo $respond;
    }
} else {
    header("Location: login");
}
exit(0);
?>