<?php
header('Access-Control-Allow-Origin: *');
session_start();

require("../includes/config.inc.php");
require("./calendar_class.php");
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
if (isset($_POST["txtUser"]) && isset($_POST["txtPass"]) && isset($_POST["btnLogin"])) {
    if ($_SESSION['security_code'] == $_POST['security_code'] && !empty($_SESSION['security_code'])) {
        $remember = isset($_POST["remember"]);
        $loginError = confirmUser($_POST["txtUser"], $_POST["txtPass"], PER_ADMIN, $remember);
        unset($_SESSION['security_code']);
    } else {
        $loginError = 4;
    }
    $checkLogin = ($loginError == 0);
} else {
    $checkLogin = checkLogin(PER_ADMIN);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Admin Acces :: CVVBASE.ME  ::</title>
        <meta content="index, follow" name="robots" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="icon" href="../favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="../styles/main.css" />
        <link rel="stylesheet" type="text/css" href="../styles/superfish.css" />
        <script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="../js/jquery.popupWindow.js"></script>
        <script type="text/javascript" src="../js/main.js" ></script>
        <script type="text/javascript" src="../js/calendar.js" ></script>
        <script type="text/javascript" src="../js/superfish.js"></script>
        <script type="text/javascript">

            // initialise plugins
            jQuery(function () {
                jQuery('ul.sf-menu').superfish();
            });

        </script>
        <link rel="stylesheet" type="text/css" href="../styles/main_style.css">

            <style>
                li:hover ul {
                    display:block !important;
                }

                #menu ul li ul li {
                    float: none;
                }


                .menuBgRmv{   
                    background: none !important;
                }

            </style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script>
            $(document).ready(function () {






                $("ul").hover(function () {
                    $(".sf-menu").addClass("menuBgRmv");
                });

                $("ul").mouseleave(function () {
                    $(".sf-menu").removeClass("menuBgRmv");
                });

            });
            </script>


    </head>

    <body id="page">
        <div id="banner"></div>
        <div id="wraper">
            <?php
            if ($checkLogin) {
                $sql = "SELECT * FROM `" . TABLE_USERS . "` WHERE user_id = '" . $_SESSION["user_id"] . "'";
                $user_info = $db->query_first($sql);
                if (!$user_info) {
                    $getinfoError = "<span class=\"red bold centered\">Get user information error, please try again</span>";
                }
                ?>
                <div id="menu">
                    <ul class="sf-menu" style="width: 670px;">
                        <li class="">
                            <a href="javascript:void(0);">News</a>
                            <ul>
                                <li><a href="./?act=add">Publish News</a></li>
                                <li><a href="./">News Manager</a></li>
                            </ul>
                        </li>
                        <li class="">
                            <a href="javascript:void(0);">Ads</a>
                            <ul>
                                <li><a href="./ads.php?act=add">Add New AD</a></li>
                                <li><a href="./ads.php">AD Manager</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Users</a>
                            <ul>
                                <li><a href="./users.php?act=add">Add New User</a></li>
                                <li><a href="./users.php">User Manager</a></li>
                                <li><a href="./users.php?act=mail">Email All Users</a></li>
                                <li><a href="./groups.php">Group Manager</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Cards</a>
                            <ul>
                                <li><a href="./cards.php">Card Manager</a></li>
                                <li><a href="./cards.php?act=import">Card Importer</a></li>
                                <li><a href="./mapper.php">Country Price</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Selling History</a>
                            <ul>
                                <li><a href="./deposits.php">Deposit History</a></li>
                                <li><a href="./orders.php">Order History</a></li>
                                <li><a href="./checks.php">Check History</a></li>
                            </ul>
                        </li>
                        <li><a href="./configs.php">Config</a></li>
                        <li><a href="./support.php">Support</a></li>
                        <li class="end"><a href="./logout.php">Logout</a></li>
                        <div class="clear"></div>
                    </ul>
                    <div class="clear"></div>
                </div>
                <?php
                $sql = "SELECT count(*) FROM `" . TABLE_USERS . "`";
                $total_users = $db->query_first($sql);
                $sql = "SELECT count(*) FROM `" . TABLE_USERS . "` WHERE user_balance > 0";
                $balance_users = $db->query_first($sql);
                $sql = "SELECT count(*) FROM `" . TABLE_CARDS . "`";
                $total_cards = $db->query_first($sql);
                $sql = "SELECT count(*) FROM `" . TABLE_CARDS . "` WHERE card_userid <> '0'";
                $sold_cards = $db->query_first($sql);
                $sql = "SELECT count(*), SUM(order_amount) FROM `" . TABLE_ORDERS . "`";
                $total_deposits = $db->query_first($sql);
                $sql = "SELECT count(*), SUM(cart_total) FROM `" . TABLE_CARTS . "`";
                $total_orders = $db->query_first($sql);
                $sql = "SELECT count(*), SUM(check_fee) FROM `" . TABLE_CHECKS . "`";
                $total_checks = $db->query_first($sql);
                ?>
                <div id="admin_static">
                    <table style="width:600px; margin: 0 auto;" class="formstyle">
                        <tbody class="left bold">
                            <tr class="formstyle">
                                <td class="blue">
                                    Total Users: <?= $total_users["count(*)"] ?>
                                </td>
                                <td class="pink">
                                    Balance Users: <?= $balance_users["count(*)"] ?>
                                </td>
                                <td class="green">
                                    Empty Users: <?= $total_users["count(*)"] - $balance_users["count(*)"] ?>
                                </td>
                            </tr>
                            <tr class="formstyle">
                                <td class="blue">
                                    Total Cards: <?= $total_cards["count(*)"] ?>
                                </td>
                                <td class="pink">
                                    Sold cards: <?= $sold_cards["count(*)"] ?>
                                </td>
                                <td class="green">
                                    Unsold cards: <?= $total_cards["count(*)"] - $sold_cards["count(*)"] ?>
                                </td>
                            </tr>
                            <tr class="formstyle">
                                <td class="blue">
                                    Total Deposits Number: <?= $total_deposits["count(*)"] ?>
                                </td>
                                <td class="pink">
                                    Total Orders Number: <?= $total_orders["count(*)"] ?>
                                </td>
                                <td class="green">
                                    Total Checks Number: <?= $total_checks["count(*)"] ?>
                                </td>
                            </tr>
                            <tr class="formstyle">
                                <td class="blue">
                                    Total Deposits Money: $<?= number_format($total_deposits["SUM(order_amount)"], 2, '.', '') ?>
                                </td>
                                <td class="pink">
                                    Total Orders Money: $<?= number_format($total_orders["SUM(cart_total)"], 2, '.', '') ?>
                                </td>
                                <td class="green">
                                    Total Checks Money: $<?= number_format($total_checks["SUM(check_fee)"], 2, '.', '') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
            <div id="content">