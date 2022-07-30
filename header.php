<?php
session_start();

require("./includes/config.inc.php");
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
if (isset($_POST["txtUser"]) && isset($_POST["txtPass"]) && isset($_POST["btnLogin"])) {
    $remember = isset($_POST["remember"]);
    $loginError = confirmUser($_POST["txtUser"], $_POST["txtPass"], PER_USER, $remember);
    $checkLogin = ($loginError == 0);
} else {
    $checkLogin = checkLogin(PER_USER);
}

//$_SESSION["user_id"] = 1;
//$checkLogin = true;

if ($checkLogin) {
    if (in_array($page_name, array("register", "login", "forgot"))) {
        header("Location: index");
        exit();
    }
    $sql = "SELECT * FROM `" . TABLE_USERS . "` WHERE user_id = '" . $_SESSION["user_id"] . "'";
    $user_info = $db->query_first($sql);
    if (!$user_info) {
        $getinfoError = "<span class=\"red bold centered\">Get user information error, please try again</span>";
    }
} elseif (!in_array($page_name, array("register", "login", "forgot"))) {
    header("Location: login");
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>PROCC.STORE </title>
        <meta content="index, follow" name="robots" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="icon" href="./images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="./styles/main_style.css" />
        <script type="text/javascript" src="./js/main_script.js" ></script>
        <script type="text/javascript">

            // initialise plugins
//            jQuery(function () {
//                jQuery('ul.sf-menu').superfish();
//            });

            var i18n = {
                'Are you sure you want to delete?': 'Are you sure you want to delete?'
            };

            $(window).ready(function () {
                var d = new Date();
                var weekday = new Array(7);
                weekday[0] = "Sunday";
                weekday[1] = "Monday";
                weekday[2] = "Tuesday";
                weekday[3] = "Wednesday";
                weekday[4] = "Thursday";
                weekday[5] = "Friday";
                weekday[6] = "Saturday";

                var day = weekday[d.getDay()];
                var news = document.getElementById("main_tag").innerHTML;
                if (document.getElementById("main_tag")) {
                    document.getElementById("main_tag").innerHTML = "<span class='latest-update'>*** " + day + " FRESH UPDATES *** </span>" + news;
                }


                $('#ads marquee').marquee('pointer').mouseover(function () {
                    $(this).trigger('stop');
                }).mouseout(function () {
                    $(this).trigger('start');
                }).mousemove(function (event) {
                    if ($(this).data('drag') == true) {
                        this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
                    }
                }).mousedown(function (event) {
                    $(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
                }).mouseup(function () {
                    $(this).data('drag', false);
                });
            });

        </script>
    </head>

    <body id="page">
        <?php
        if ($user_info) {
            $sql = "SELECT * FROM `" . TABLE_ADS . "` ORDER BY ad_time DESC,ad_id DESC LIMIT 10";
            $recordsAds = $db->fetch_all_array($sql);
            ?>
            <div id="ads">
                <marquee behavior="scroll" direction="left" scrollamount="2">

                    <p id="main_tag">
                        <?php
                        if (count($recordsAds) > 0) {
                            foreach ($recordsAds as $key => $value) {
                                ?>
                                <span class="latest-update"><?= $value['ad_content'] ?></span>
                                <?php
                            }
                        }
                        ?>
                    </p>        
                </marquee>
            </div>
        <?php } ?>
        <div id="banner"></div>
        <div id="wraper">

            <div id="header">
                <div id="hwrap" class="clearfix">
                    <?php if ($user_info) { ?>
                        <div id="user">
                            <p>Hello, <a href="https://v-m.su/client/profile"><?= $user_info["user_name"] ?></a>!</p>
                            <p>Balance: <span>$<?= $user_info["user_balance"] ?></span></p>
                        </div>

                        <div id="cart">
                            <p>
                                <a href="cart"><img src="./images/basket.png" alt="Go to Cart"></a>
                                <a href="cart"><?= count($_SESSION["shopping_card"]) ?> items for $<?= number_format($_SESSION["shopping_total"], 2, '.', '') ?></a>
                            </p>
                        </div>
                    <?php } ?>

                    <div id="logo">
                        <a href="./"><img src="./images/logo.png" alt="VaultMarket"></a>
                    </div>
                </div>
                <div id="dline" class="clearfix">
                </div>
            </div>

            <?php
            if ($user_info) {
                ?>




                <div id="menu">
                    <ul>
                        <li class="first <?= (($page_name == "news") ? ("active") : ("")) ?>">
                            <a href="./">News</a>
                        </li>
                        <li class="<?= (($page_name == "cards") ? ("active") : ("")) ?>">
                            <a href="./cards">Buy Cards</a>
                        </li>
                        <li class="<?= (($page_name == "cart") ? ("active") : ("")) ?>"><a href="./cart">Cart</a></li>
                        <li class="<?= ($page_name == "mycards" ? ("active has-child") : ("")) ?>">
                        <!--<li class="< ?= //($is_user_panel ? ("active has-child") : ("")) ?>">-->
                            <a href="./mycards">My Orders</a>
                            <ul>
                                <li class="<?= (($page_name == "mycards") ? ("active") : ("")) ?>"><a href="./mycards">My Cards</a></li>
                                <li class="<?= (($page_name == "myaccount") ? ("active") : ("")) ?>"><a href="./myaccount">My Account</a></li>

                            </ul>


                        </li>
                        <li class="<?= (($page_name == "deposit") ? ("active") : ("")) ?><?=($is_fund_history ?  ("active has-child") : ("")) ?>">
                            <a href="./deposit">Add Funds</a>
                            <ul>
                                <li class="<?= (($page_name == "deposit") ? ("active") : ("")) ?>"><a href="./deposit">Deposit</a></li>
                                <li class="<?= (($page_name == "mydeposits") ? ("active") : ("")) ?>"><a href="./mydeposits">Deposits History</a></li>
                            </ul>
                        </li>
                        <li class="<?= ($is_user_history ? ("active has-child") : ("")) ?>">
                            <a href="./myorders">User's History</a>
                            <ul>
                                <li class="<?= (($page_name == "myorders") ? ("active") : ("")) ?>"><a href="./myorders">Orders History</a></li>
                                <li class="<?= (($page_name == "mycheck") ? ("active") : ("")) ?>"><a href="./mycheck">Check History</a></li>
                            </ul>
                        </li>
                        <li class="<?= (($page_name == "support") ? ("active") : ("")) ?>"><a href="./support">Support</a></li>
                        <li class="last"><a href="./logout">Logout</a></li>
                    </ul>
                </div>
                <div id="getinfoError" class="centered red bold">
                    <?= $changeInfoResult ?>
                </div>
                <?php
                if ($user_info["user_balance"] <= 0) {
                    ?>
                    <div id="balance_notify" class="centered red bold">
                        You balance is empty, please add funds to buy purchase!
                    </div>
                    <?php
                }
                ?>

                <?php
            } else {
                ?>
                <div id="menu">
                    <ul>
                        <li class="<?= (($page_name == "login") ? ("active") : ("")) ?>"><a href="login">Log IN</a></li>
                        <li class="<?= (($page_name == "register") ? ("active") : ("")) ?>"><a href="register">Register</a></li>
                        <li class="<?= (($page_name == "forgot") ? ("active") : ("")) ?>"><a href="forgot">Forgot Password</a></li>
                    </ul>
                </div>
                <?php
            }
            ?>
            <div id="content">