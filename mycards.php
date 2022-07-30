<?php
$is_user_panel = TRUE;
$page_name = "mycards";
require("./header.php");
if (isset($_GET["btnSearch"])) {
    $currentGet = "";
    $currentGet .= ($_GET["boxDob"] != "") ? "boxDob=" . $_GET["boxDob"] . "&" : "";
    $currentGet .= "txtBin=" . $_GET["txtBin"] . "&lstCountry=" . $_GET["lstCountry"] . "&lstState=" . $_GET["lstState"] . "&lstCity=" . $_GET["lstCity"] . "&txtZip=" . $_GET["txtZip"];
    $currentGet .= ($_GET["boxSSN"] != "") ? "&boxSSN=" . $_GET["boxSSN"] : "";
    $currentGet .= "&btnSearch=Search&";
}
$searchBin = $db->escape($_GET["txtBin"]);
$searchCountry = $db->escape($_GET["lstCountry"]);
$searchState = $db->escape($_GET["lstState"]);
$searchCity = $db->escape($_GET["lstCity"]);
$searchZip = $db->escape($_GET["txtZip"]);
$searchSSN = ($_GET["boxSSN"] == "on") ? " AND card_ssn <> ''" : "";
$searchDob = ($_GET["boxDob"] == "on") ? " AND card_dob <> ''" : "";
$sql = "SELECT count(*) FROM `" . TABLE_CARDS . "` WHERE card_status = '" . STATUS_DEFAULT . "' AND card_userid = " . $_SESSION["user_id"] . " AND AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') LIKE '" . $searchBin . "%' AND ('" . $searchCountry . "'='' OR card_country = '" . $searchCountry . "') AND ('" . $searchState . "'='' OR card_state = '" . $searchState . "') AND ('" . $searchCity . "'='' OR card_city = '" . $searchCity . "') AND card_zip LIKE '" . $searchZip . "%'" . $searchSSN . $searchDob;
$totalRecords = $db->query_first($sql);
$totalRecords = $totalRecords["count(*)"];
$perPage = 20;
$totalPage = ceil($totalRecords / $perPage);
if (isset($_GET["page"])) {
    $page = $db->escape($_GET["page"]);
    if ($page < 1) {
        $page = 1;
    } else if ($page > $totalPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
$sql = "SELECT *, AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') AS card_number FROM `" . TABLE_CARDS . "` WHERE card_status = '" . STATUS_DEFAULT . "' AND card_userid = " . $_SESSION["user_id"] . " AND AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') LIKE '" . $searchBin . "%' AND ('" . $searchCountry . "'='' OR card_country = '" . $searchCountry . "') AND ('" . $searchState . "'='' OR card_state = '" . $searchState . "') AND ('" . $searchCity . "'='' OR card_city = '" . $searchCity . "') AND card_zip LIKE '" . $searchZip . "%'" . $searchSSN . $searchDob . " ORDER BY card_id LIMIT " . (($page - 1) * $perPage) . "," . $perPage;
$listcards = $db->fetch_all_array($sql);
?>
<h1>SEARCH CARDS</h1>
<table class="table" width="100%" style="clear: left;">
    <tbody>
    <form name="search" method="GET" action="mycards">
        <tr>
            <th>
                CARD NUMBER
            </th>
            <th>
                COUNTRY
            </th>
            <th>
                STATE
            </th>
            <th>
                CITY
            </th>
            <th>
                ZIP
            </th>
            <th rowspan="2">
                <input name="btnSearch" type="submit" class="formstyle" id="btnSearch" value="Search">
            </th>
        </tr>
        <tr>
            <td class="center">
                <input name="txtBin" type="text" class="formstyle" id="txtBin" value="<?= $_GET["txtBin"] ?>" size="12" maxlength="16">
            </td>
            <td class="center">
                <select name="lstCountry" class="formstyle" id="lstCountry">
                    <option value="">All Country</option>
                    <?php
                    $sql = "SELECT DISTINCT card_country FROM `" . TABLE_CARDS . "` WHERE card_status = '" . STATUS_DEFAULT . "' AND card_userid = '" . $_SESSION["user_id"] . "'";
                    $allCountry = $db->fetch_all_array($sql);
                    if (count($allCountry) > 0) {
                        foreach ($allCountry as $country) {
                            echo "<option value=\"" . $country['card_country'] . "\"" . (($_GET["lstCountry"] == $country['card_country']) ? " selected" : "") . ">" . $country['card_country'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="center">
                <select name="lstState" class="formstyle" id="lstState">
                    <option value="">All State</option>
                    <?php
                    $sql = "SELECT DISTINCT card_state FROM `" . TABLE_CARDS . "` WHERE card_status = '" . STATUS_DEFAULT . "' AND card_userid = '" . $_SESSION["user_id"] . "'";
                    $allCountry = $db->fetch_all_array($sql);
                    if (count($allCountry) > 0) {
                        foreach ($allCountry as $country) {
                            echo "<option value=\"" . $country['card_state'] . "\"" . (($_GET["lstState"] == $country['card_state']) ? " selected" : "") . ">" . $country['card_state'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="center">
                <select name="lstCity" class="formstyle" id="lstCity">
                    <option value="">All City</option>
                    <?php
                    $sql = "SELECT DISTINCT card_city FROM `" . TABLE_CARDS . "` WHERE card_status = '" . STATUS_DEFAULT . "' AND card_userid = '" . $_SESSION["user_id"] . "'";
                    $allCountry = $db->fetch_all_array($sql);
                    if (count($allCountry) > 0) {
                        foreach ($allCountry as $country) {
                            echo "<option value=\"" . $country['card_city'] . "\"" . (($_GET["lstCity"] == $country['card_city']) ? " selected" : "") . ">" . $country['card_city'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="center">
                <input name="txtZip" type="text" class="formstyle" id="txtZip" value="<?= $_GET["txtZip"] ?>" size="20">
            </td>
        </tr>
    </form>
</tbody>
</table>

<h1>YOUR CARDS</h1>
<p>Download cards to view full card information (Phone, SSN, DOB, ...)</p>
<p>Click on 'Check' to check card, check fee is $<?= number_format($db_config["check_fee"], 2, '.', '') ?></p>
<div class="section_page_bar">
</div>
<table class="table" width="100%" style="clear: left;">
    <tbody>
    <form name="mycards" method="POST" action="cardprocess">
        <tr>
            <th>
                CARD NUMBER
            </th>
            <th>
                EXPIRE
            </th>
            <th>
                CVV
            </th>
            <th>
                NAME
            </th>
            <th>
                ADDRESS
            </th>
            <th>
                CITY
            </th>
            <th>
                STATE
            </th>
            <th>
                ZIP
            </th>
            <th>
                COUNTRY
            </th>
            <th>
                COMMENT
            </th>
            <th>
                CHECK
            </th>
            <th>
                <input class="formstyle" type="checkbox" name="selectAllCards" id="selectAllCards" onclick="checkAll(this.id, 'cards[]')" value="">
            </th>
        </tr>
        <?php
        if (count($listcards) > 0) {
            foreach ($listcards as $key => $value) {
                switch ($value['card_check']) {
                    case strval(CHECK_VALID):
                        $value['card_checkText'] = "<span class=\"green bold\">VALID</span>";
                        break;
                    case strval(CHECK_INVALID):
                        $value['card_checkText'] = "<span class=\"red bold\">TIMEOUT</span>";
                        break;
                    case strval(CHECK_REFUND):
                        $value['card_checkText'] = "<span class=\"pink bold\">INVALID - REFUNDED</span>";
                        break;
                    case strval(CHECK_UNKNOWN):
                        $value['card_checkText'] = "<span class=\"blue bold\">UNKNOWN</span>";
                        break;
                    default :
//                        $value['card_checkText'] = "<span class=\"black bold\"><a href=\"#\" onclick=\"checkCard('" . $value['card_id'] . "')\">Check ($" . number_format($db_config["check_fee"], 2, '.', '') . ")</a></span>";
                        if ($_SESSION["debug"] == 1) {
                            $value['card_checkText'] = "<span class=\"black bold\"><a href=check.php?card_id=" . $value['card_id'] . " target = 'new'>Check ($" . number_format($db_config["check_fee"], 2, '.', '') . ")</a></span>";
                        } else {
                            $value['card_checkText'] = "<span class=\"black bold\"><a href=check.php?card_id=" . $value['card_id'] . ">Check ($" . number_format($db_config["check_fee"], 2, '.', '') . ")</a></span>";
                        }
                        break;
                }
                ?>
                <tr>
                    <td class="center">
                        <span><?= $value['card_number'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_month'] ?>/<?= $value['card_year'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_cvv'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_name'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_address'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_city'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_state'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_zip'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_country'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_comment'] ?></span>
                    </td>
                    <td class="center">
                        <span id="check_<?= $value['card_id'] ?>"><?= $value['card_checkText'] ?></span>
                    </td>
                    <td class="center">
                        <input class="formstyle" type="checkbox" name="cards[]" value="<?= $value['card_id'] ?>">
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        <tr>
            <td colspan="12" class="center">
                <p>
                    <label>
                        <input name="download_all" type="submit" class="black bold" id="download_all" value="Download All Cards" >
                    </label>
                    <span> | </span>
                    <label>
                        <input name="download_select" type="submit" class="blue bold" id="download_select" value="Download Selected Cards" >
                    </label>
                    <span> | </span>
                    <label>
                        <input name="download_expired" type="submit" class="red bold" id="download_expired" value="Download Expired Cards" >
                    </label>
                    <span> | </span>
                    <label>
                        <input name="delete_invalid" type="submit" class="pink bold" id="delete_invalid" onClick="return confirm('Are you sure you want to delete the INVALID cards?')" value="Delete Invalid/Refunded Cards">
                    </label>
                    <span> | </span>
                    <label>
                        <input name="delete_select" type="submit" class="red bold" id="delete_select" onClick="return confirm('Are you sure you want to delete the SELECTED cards?')" value="Delete Selected Cards">
                    </label>
                </p>
            </td>
        </tr>
    </form>
</tbody>
</table>
<?php
require("./pagination.php");
?>
<?php
require("./footer.php");
?>