<?php
$page_name = "cards";
require("./header.php");
if ($_GET["stagnant"] != "true") {
    $_GET["stagnant"] = "false";
}
$currentGet = "stagnant=" . $_GET["stagnant"] . "&" . "txtBin=" . $_GET["txtBin"] . "&";
if (isset($_GET["btnSearch"])) {
    $currentGet .= "lstCountry=" . $_GET["lstCountry"] . "&lstState=" . $_GET["lstState"] . "&lstCity=" . $_GET["lstCity"] . "&txtZip=" . $_GET["txtZip"];
    $currentGet .= "lstBank=" . $_GET["lstBank"] . "&lstType=" . $_GET["lstType"] . "&lstLevel=" . $_GET["lstLevel"];
    $currentGet .= ($_GET["boxDob"] != "") ? "&boxDob=" . $_GET["boxDob"] : "";
    $currentGet .= ($_GET["boxSSN"] != "") ? "&boxSSN=" . $_GET["boxSSN"] : "";
    $currentGet .= "&btnSearch=Search&";
}
if ($_GET["stagnant"] == "true") {
    $searchExpire = "(card_year = " . date("Y") . " AND card_month = " . date("n") . ")";
} else {
    $searchExpire = "(card_year > " . date("Y") . " OR (card_year = " . date("Y") . " AND card_month > " . date("n") . "))";
}
$searchBin = substr($db->escape($_GET["txtBin"]), 0, 6);
$searchCountry = $db->escape($_GET["lstCountry"]);
$searchState = $db->escape($_GET["lstState"]);
$searchCity = $db->escape($_GET["lstCity"]);
$searchZip = $db->escape($_GET["txtZip"]);
$searchSSN = ($_GET["boxSSN"] == "on") ? " AND c.card_ssn <> ''" : "";
$searchDob = ($_GET["boxDob"] == "on") ? " AND c.card_dob <> ''" : "";
$searchBank = $db->escape($_GET["lstBank"]);
$searchLevel = $db->escape($_GET["lstLevel"]);
$searchType = $db->escape($_GET["lstType"]);
$searchAdd = " AND ('" . $searchBank . "'='' OR b.card_bank = '" . $searchBank . "') ".
        " AND ('" . $searchLevel . "'='' OR b.card_level = '" . $searchLevel . "') ".
        " AND ('" . $searchType . "'='' OR b.card_type = '" . $searchType . "') ";
$sql = "SELECT count(*) FROM `" . TABLE_CARDS . "` ".
        " c inner join `" . TABLE_BINS. "` b on b.card_bin = c.card_bin " .
        " WHERE " . $searchExpire .
        " AND c.card_status = '" . STATUS_DEFAULT . 
        "' AND c.card_userid = '0' "
        . " AND c.card_bin LIKE '" . $searchBin . "%' "
        . "AND ('" . $searchCountry . "'='' OR c.card_country = '" . $searchCountry . "') "
        . "AND ('" . $searchState . "'='' OR c.card_state = '" . $searchState . "') "
        . "AND ('" . $searchCity . "'='' OR c.card_city = '" . $searchCity . "') "
        . "AND c.card_zip LIKE '" . $searchZip . "%' ". $searchAdd . $searchSSN . $searchDob;

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
$sql = "SELECT * FROM `" . TABLE_CARDS . "` c inner join `" . TABLE_BINS. "` b on b.card_bin = c.card_bin WHERE " . $searchExpire . " AND c.card_status = '" . STATUS_DEFAULT . "' AND c.card_userid = '0' AND c.card_bin LIKE '" . $searchBin . "%' AND ('" . $searchCountry . "'='' OR c.card_country = '" . $searchCountry . "') AND ('" . $searchState . "'='' OR c.card_state = '" . $searchState . "') AND ('" . $searchCity . "'='' OR c.card_city = '" . $searchCity . "') AND c.card_zip LIKE '" . $searchZip . "%' ". $searchAdd . $searchSSN . $searchDob . " ORDER BY card_id DESC LIMIT " . (($page - 1) * $perPage) . "," . $perPage;
$listcards = $db->fetch_all_array($sql);
?>
<h1>SEARCH CARDS</h1>
<table class="table" width="100%" style="clear: left;">
    <tbody>
    <form id="searchForm" name="searchForm" method="GET" action="cards">
        <input type="hidden" name="stagnant" value="<?= $_GET["stagnant"] ?>" />
        <tr>
            <th>
                BIN (+$<?= number_format($db_config["binPrice"], 2) ?>)
            </th>
            <!--card_bank, card_type, card_level-->
            <th>
                CARD BANK (+$<?= number_format($db_config["bankPrice"], 2) ?>)
            </th>
            <th>
                CARD TYPE (+$<?= number_format($db_config["typePrice"], 2) ?>)
            </th>
            <th>
                CARD LEVEL (+$<?= number_format($db_config["levelPrice"], 2) ?>)
            </th>
        </tr>
        <tr>
            <td class="center">
                <input name="txtBin" type="text" class="formstyle" id="txtBin" value="<?= $_GET["txtBin"] ?>" size="12" maxlength="6">
            </td>
            <td class="center">
                <select name="lstBank" class="formstyle" id="lstBank">
                    <option value="">All Bank</option>
                    <?php
                    $sql = "SELECT DISTINCT card_bank as card_bank FROM bins ORDER BY card_bank";
                    $allBank = $db->fetch_all_array($sql);
                    if (count($allBank) > 0) {
                        foreach ($allBank as $bank) {
                            echo "<option value=\"" . $bank['card_bank'] . "\"" . (($_GET["lstBank"] == $bank['card_bank']) ? " selected" : "") . ">" . $bank['card_bank'] . " </option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="center">
                <select name="lstType" class="formstyle" id="lstType">
                    <option value="">All Type</option>
                    <?php
                    $sql = "SELECT DISTINCT card_type as card_type FROM bins ORDER BY card_type";
                    $allType = $db->fetch_all_array($sql);
                    if (count($allType) > 0) {
                        foreach ($allType as $type) {
                            echo "<option value=\"" . $type['card_type'] . "\"" . (($_GET["lstType"] == $type['card_type']) ? " selected" : "") . ">" . $type['card_type'] . " </option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="center">
                <select name="lstLevel" class="formstyle" id="lstLevel">
                    <option value="">All Level</option>
                    <?php
                    $sql = "SELECT DISTINCT card_level as card_level FROM bins ORDER BY card_level";
                    $allLevel = $db->fetch_all_array($sql);
                    if (count($allLevel) > 0) {
                        foreach ($allLevel as $level) {
                            echo "<option value=\"" . $level['card_level'] . "\"" . (($_GET["lstLevel"] == $level['card_level']) ? " selected" : "") . ">" . $level['card_level'] . " </option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                COUNTRY (+$<?= number_format($db_config["countryPrice"], 2) ?>)
            </th>
            <th>
                STATE (+$<?= number_format($db_config["statePrice"], 2) ?>)
            </th>
            <th>
                CITY (+$<?= number_format($db_config["cityPrice"], 2) ?>)
            </th>
            <th>
                ZIP (+$<?= number_format($db_config["zipPrice"], 2) ?>)
            </th>
        </tr>
        <tr>
            <td class="center">
                <select name="lstCountry" class="formstyle" id="lstCountry">
                    <option value="">All Country</option>
                    <?php
                    $sql = "SELECT card_country, count(*) FROM `" . TABLE_CARDS . "` WHERE " . $searchExpire . " AND card_status = '" . STATUS_DEFAULT . "' and card_userid = '0' GROUP BY card_country ORDER BY card_country";
                    $allCountry = $db->fetch_all_array($sql);
                    if (count($allCountry) > 0) {
                        foreach ($allCountry as $country) {
                            echo "<option value=\"" . $country['card_country'] . "\"" . (($_GET["lstCountry"] == $country['card_country']) ? " selected" : "") . ">" . $country['card_country'] . " </option>"; //(" . $country['count(*)'] . " cards)
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="center">
                <select name="lstState" class="formstyle" id="lstState">
                    <option value="">All State</option>
                    <?php
                    $sql = "SELECT DISTINCT card_state FROM `" . TABLE_CARDS . "` WHERE " . $searchExpire . " AND card_status = '" . STATUS_DEFAULT . "' and card_userid = '0' ORDER BY card_state";
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
                    $sql = "SELECT DISTINCT card_city FROM `" . TABLE_CARDS . "` WHERE " . $searchExpire . " AND card_status = '" . STATUS_DEFAULT . "' and card_userid = '0' ORDER BY card_city";
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
                <input name="txtZip" type="text" class="formstyle" id="txtZip" value="<?= $_GET["txtZip"] ?>" size="12">
            </td>
        </tr>
        <tr>

            <td class="center">
                <span><input type="checkbox" name="boxSSN" id="boxSSN" <?= ($_GET["boxSSN"] != "") ? "checked " : "" ?>>Have SSN</span>
            </td>
            <td class="center">
                <span><input type="checkbox" name="boxDob" id="boxDob" <?= ($_GET["boxDob"] != "") ? "checked " : "" ?>>Have DoB</span>
            </td>
            <td class="center" colspan="2">
                <input name="btnSearch" type="submit" class="formstyle" id="btnSearch" value="Search">
            </td>
        </tr>
    </form>
</tbody>
</table>

<h1>AVAILABLE CARDS</h1>
<table class="table" width="100%" style="clear: left;">
    <tbody>
    <form name="addtocart" method="POST" action="./cart">
        <tr>

            <th>
                CARD NUMBER
            </th>

            <th>
                EXPIRY
            </th>
            <th>
                FIRST NAME
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
            <th>
                SSN
            </th>
            <th>
                DOB
            </th>
            <th>
                CARD COMMENTS
            </th>
            <th>
                PRICE
            </th>
            <th>
                <input class="formstyle" type="checkbox" name="selectAllCards" id="selectAllCards" onclick="checkAll(this.id, 'cards[]')" value="">
            </th>
        </tr>
        <?php
        if (count($listcards) > 0) {
            foreach ($listcards as $key => $value) {
                $card_firstname = explode(" ", $value['card_name']);
                $card_firstname = $card_firstname[0];
                ?>
                <tr>

                    <td class="center">
                        <span><?= $value['card_bin'] ?>**********</span>
                    </td>





                    <td class="center">
                        <span><?= $value['card_month'] . "/" . $value['card_year'] ?></span>
                    </td>



                    <td class="center">
                        <span><?= $card_firstname ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_country'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_state'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_city'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= $value['card_zip'] ?></span>
                    </td>
                    <td class="center">
                        <span><?= ($value['card_ssn'] == "") ? "NO" : "YES" ?></span>
                    </td>
                    <td class="center">
                        <span><?= ($value['card_dob'] == "") ? "NO" : "YES" ?></span>
                    </td>
                    <td class="center">
                        <span><?= ($value['card_cvv'] == "") ? "No CVV" : "HIGH VALID RATE" ?><?= ($value['card_comment'] == "") ? "" : (" - " . $value['card_comment']) ?></span>
                    </td>
                    <td class="center">
                        <span>
                            <?php
                            printf("$%.2f", $value['card_price']);
                            if (strlen($_GET["txtBin"]) > 1 && $db_config["binPrice"] > 0) {
                                printf(" + $%.2f", $db_config["binPrice"]);
                            }
                            if ($_GET["lstCountry"] != "" && $db_config["countryPrice"] > 0) {
                                printf(" + $%.2f", $db_config["countryPrice"]);
                            }
                            if ($_GET["lstState"] != "" && $db_config["statePrice"] > 0) {
                                printf(" + $%.2f", $db_config["statePrice"]);
                            }
                            if ($_GET["lstCity"] != "" && $db_config["cityPrice"] > 0) {
                                printf(" + $%.2f", $db_config["cityPrice"]);
                            }
                            if ($_GET["txtZip"] != "" && $db_config["zipPrice"] > 0) {
                                printf(" + $%.2f", $db_config["zipPrice"]);
                            }
                            if ($_GET["levelPrice"] != "" && $db_config["levelPrice"] > 0) {
                                printf(" + $%.2f", $db_config["levelPrice"]);
                            }
                            if ($_GET["bankPrice"] != "" && $db_config["bankPrice"] > 0) {
                                printf(" + $%.2f", $db_config["bankPrice"]);
                            }
                            if ($_GET["typePrice"] != "" && $db_config["typePrice"] > 0) {
                                printf(" + $%.2f", $db_config["typePrice"]);
                            }
                            ?>
                        </span>
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
                        <input name="txtBin" type="hidden" id="txtBin" value="<?= $_GET["txtBin"] ?>" />
                        <input name="txtCountry" type="hidden" id="txtCountry" value="<?= $_GET["lstCountry"] ?>" />
                        <input name="lstState" type="hidden" id="lstState" value="<?= $_GET["lstState"] ?>" />
                        <input name="lstCity" type="hidden" id="lstCity" value="<?= $_GET["lstCity"] ?>" />
                        <input name="txtZip" type="hidden" id="txtZip" value="<?= $_GET["txtZip"] ?>" />
                        <input name="addToCart" type="submit" class="blue bold" id="download_select" value="Add Selected Cards to Shopping Cart" />
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