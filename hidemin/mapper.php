<?php
set_time_limit(0);
require("./header.php");
//require("../checkers/checker.php");
if ($checkLogin) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST["savePrice"] == "savePrice" && $_POST['contry_price_save'] == "Save Price") {
        if ($_POST['card_price'] == '') {
            $isPriveNullVal = true;
        } else {
            $country_add["price"] = ($_POST['card_price'] == '' ? '0' : $_POST['card_price']);
            $country_add["updated_date"] = 'now()';
            $isUpdated = $db->query_update(TABLE_COUNTRIES, $country_add, "country_id = '" . $_POST['country'] . "'");
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST["addCountries"] == "addCountries" && $_POST['add_contry'] == "Add Country") {
        if ($_POST['country_name'] != '') {
            $country_add["country_name"] = $_POST['country_name'];
            $country_add["created_date"] = 'now()';
            $isInsrted = $db->query_insert(TABLE_COUNTRIES, $country_add);
        } else {
            $isNullVal = true;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST["saveCount"] == "saveCount" && $_POST['contry_count_save'] == "Save Count") {
        if ($_POST['card_count'] == '') {
            $isCountNullVal = true;
        } else {
            $country_add["counts"] = ($_POST['card_count'] == '' ? '0' : $_POST['card_count']);
            $country_add["updated_date"] = 'now()';
            $isCountUpdated = $db->query_update(TABLE_COUNTRIES, $country_add, "country_id = '" . $_POST['country_count'] . "'");
        }
    }
    $sql = "SELECT country_id, concat(country_name, ' - $',price) name FROM `" . TABLE_COUNTRIES . "` c order by country_name asc";
    $country_history = $db->fetch_all_array($sql);
    $sql_counts = "SELECT country_id, concat(country_name, ' - $',counts) name FROM `" . TABLE_COUNTRIES . "` c order by country_name asc";
    $counts_history = $db->fetch_all_array($sql_counts);
    ?>
    <div id="cards">
        <div class = "section_title">CARDS PRICE</div>
        <div class = "section_content">
            <form method="POST" action="">
                <table class="content_table">
                    <tbody>
                        <?php
                        if ($isUpdated) {
                            ?>
                            <tr>
                                <td colspan = "2">
                                    <h3 class="alert alert-success">Country prices updated.</h3>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        if ($isPriveNullVal) {
                            ?>
                            <tr>
                                <td colspan = "2">
                                    <h3 class="alert alert-danger">Please enter price.</h3>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td class="centered bold red">
                                Country Name
                            </td>
                            <td class="centered bold red">
                                <input type="hidden" name="savePrice" value="savePrice">
                                <select name="country" id="country">
                                    <?php
                                    if (count($country_history) > 0) {
                                        foreach ($country_history as $key => $value) {
                                            ?>
                                            <option value="<?= $value['country_id'] ?>"><?= $value['name'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="centered bold red">
                                Price
                            </td>
                            <td class="centered bold red">
                                <input name="card_price" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="centered">
                                <input type="submit" name="contry_price_save" value="Save Price" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <div class = "section_title">Add Country</div>
        <div class = "section_content">
            <form method="POST" action="">
                <table class = "content_table">
                    <tbody>
                        <?php
                        if ($isInsrted) {
                            ?>
                            <tr>
                                <td colspan = "2">
                                    <h3 class="alert alert-success">Country added successfully.</h3>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        if ($isNullVal) {
                            ?>
                            <tr>
                                <td colspan = "2">
                                    <h3 class="alert alert-danger">Please enter country name.</h3>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td class="centered bold red">
                                <input type="hidden" name="addCountries" value="addCountries">
                                Country Name
                            </td>
                            <td class="centered bold red">
                                <input name="country_name" type="text" />
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="centered">
                                <input type="submit" name="add_contry" value="Add Country" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>

        <div class = "section_title">CARDS COUNT</div>
        <div class = "section_content">
            <form method="POST" action="">
                <table class="content_table">
                    <tbody>
                        <?php
                        if ($isCountUpdated) {
                            ?>
                            <tr>
                                <td colspan = "2">
                                    <h3 class="alert alert-success">Country count updated.</h3>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        if ($isCountNullVal) {
                            ?>
                            <tr>
                                <td colspan = "2">
                                    <h3 class="alert alert-danger">Please enter count.</h3>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td class="centered bold red">
                                Country Name
                            </td>
                            <td class="centered bold red">
                                <input type="hidden" name="saveCount" value="saveCount">
                                <select name="country_count" id="country">
                                    <?php
                                    if (count($counts_history) > 0) {
                                        foreach ($counts_history as $key => $value) {
                                            ?>
                                            <option value="<?= $value['country_id'] ?>"><?= $value['name'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="centered bold red">
                                Count
                            </td>
                            <td class="centered bold red">
                                <input name="card_count" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="centered">
                                <input type="submit" name="contry_count_save" value="Save Count" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <?php
} else {
    require("./minilogin.php");
}
require("./footer.php");
?>