<?php
set_time_limit(0);
require("./header.php");
require("../checkers/checker.php");
if ($checkLogin) {
    if ($_GET["act"] == "import") {
        ?>
        <div id="cards">
            <div class="section_title">IMPORT CARDS</div>
            <div class="section_content">
                <table class="content_table">
                    <tbody>
                        <?php
                        if (isset($_POST["card_import_save"]) || isset($_POST["card_import_preview"])) {
                            foreach ($_POST as &$temp) {
                                if ($id == "card_spliter" && $temp != " ") {
                                    $temp = trim($temp);
                                }
                            }
                            if ($_POST["card_content"] == "") {
                                $errorMsg = "Please input card content";
                            }
//				else if ($_POST["card_price"] == "" || $_POST["card_price"] <= 0) {
//					$errorMsg = "Please input  a valid card price";
//				}
                            else if ($_POST["card_spliter"] == "") {
                                $errorMsg = "Please input card spliter";
                            } else if ($_POST["card_number"] == "") {
                                $errorMsg = "Please input card number position";
                            } else if ($_POST["card_month"] == "") {
                                $errorMsg = "Please input card exp month position";
                            } else if ($_POST["card_year"] == "") {
                                $errorMsg = "Please input card exp year position";
                            } else if ($_POST["city_select_mode"] == "1" AND $_POST["card_zip"] == "") {
                                $errorMsg = "Please input zip code position";
                            } else if ($_POST["city_select_mode"] == "2" AND ( $_POST["card_city"] == "" || $_POST["card_state"] == "")) {
                                $errorMsg = "Please input city and state position";
                            } else {
                                if (isset($_POST["card_import_preview"])) {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="centered">
                                            <table style="width:786px;margin: 0 auto;">
                                                <tbody>
                                                    <tr>
                                                        <td class="formstyle centered">
                                                            <span class="bold">CARD NUMBER</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span class="bold">EXPIRE</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>CVV</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>NAME</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>ADDRESS</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>CITY</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>STATE</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>ZIP</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>COUNTRY</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>PHONE</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>SSN</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span>DOB</span>
                                                        </td>
                                                        <td class="formstyle centered">
                                                            <span class="red bold">COMMENT</span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                $_POST["card_content"] = str_replace("\r", "", $_POST["card_content"]);
                                                $_POST["card_content"] = str_replace(array(" " . $_POST["card_spliter"], $_POST["card_spliter"] . " "), $_POST["card_spliter"], $_POST["card_content"]);
                                                while (substr_count($_POST["card_content"], "\n\n")) {
                                                    $_POST["card_content"] = str_replace("\n\n", "\n", $_POST["card_content"]);
                                                }
                                                $card_content = explode("\n", $_POST["card_content"]);
                                                $card_import["card_price"] = $_POST["card_price"];
                                                $card_import["card_comment"] = $_POST["card_comment"];
                                                foreach ($card_content as $id => $line) {
                                                    if (strlen($line) > 10) {
                                                        $lineField = explode($_POST["card_spliter"], $line);
                                                        $card_import["card_fullinfo"] = $line;
                                                        $card_import["card_number"] = $lineField[$_POST["card_number"] - 1];
                                                        $card_import["card_bin"] = substr($card_import["card_number"], 0, 6);
                                                        if ($_POST["card_month"] == $_POST["card_year"]) {
                                                            if (strlen($lineField[$_POST["card_month"] - 1]) == 3) {
                                                                $card_import["card_month"] = substr($lineField[$_POST["card_month"] - 1], 0, 1);
                                                                $card_import["card_year"] = substr($lineField[$_POST["card_month"] - 1], -2);
                                                            } else {
                                                                $card_import["card_month"] = substr($lineField[$_POST["card_month"] - 1], 0, 2);
                                                                $card_import["card_year"] = substr($lineField[$_POST["card_month"] - 1], -2);
                                                            }
                                                        } else {
                                                            $card_import["card_month"] = $lineField[$_POST["card_month"] - 1];
                                                            $card_import["card_year"] = $lineField[$_POST["card_year"] - 1];
                                                        }
                                                        if (strlen($card_import["card_year"]) == 1) {
                                                            $card_import["card_year"] = "200" . $card_import["card_year"];
                                                        } else if (strlen($card_import["card_year"]) == 2) {
                                                            $card_import["card_year"] = "20" . $card_import["card_year"];
                                                        } else if (strlen($card_import["card_year"]) == 3) {
                                                            $card_import["card_year"] = "2" . $card_import["card_year"];
                                                        }
                                                        $card_import["card_month"] = intval($card_import["card_month"]);
                                                        $card_import["card_year"] = intval($card_import["card_year"]);
                                                        if ($_POST["card_cvv"] == "") {
                                                            $card_import["card_cvv"] = "";
                                                        } else {
                                                            $card_import["card_cvv"] = $lineField[$_POST["card_cvv"] - 1];
                                                        }
                                                        if ($_POST["card_fname"] == $_POST["card_lname"] && $_POST["card_fname"] != "") {
                                                            $card_import["card_name"] = $lineField[$_POST["card_fname"] - 1];
                                                        } else {
                                                            if ($_POST["card_fname"] != "" && $_POST["card_fname"] == $_POST["card_lname"]) {
                                                                $card_import["card_name"] = trim($lineField[$_POST["card_fname"] - 1]);
                                                            } else {
                                                                if ($_POST["card_fname"] == "") {
                                                                    //$card_import["card_fname"] = "";
                                                                    $card_fname = "";
                                                                } else {
                                                                    $card_fname = explode(" ", $lineField[$_POST["card_fname"] - 1]);
                                                                    $card_fname = trim($card_fname[0]);
                                                                }
                                                                if ($_POST["card_lname"] == "") {
                                                                    //$card_import["card_lname"] = "";
                                                                    $card_lname = "";
                                                                } else {
                                                                    $card_lname = explode($card_fname, $lineField[$_POST["card_lname"] - 1]);
                                                                    $card_lname = $card_lname[count($card_lname) - 1];
                                                                }
                                                                $card_import["card_name"] = $card_fname . " " . $card_lname;
                                                            }
                                                        }
                                                        if ($_POST["card_address"] == "") {
                                                            $card_import["card_address"] = "";
                                                        } else {
                                                            $card_import["card_address"] = $lineField[$_POST["card_address"] - 1];
                                                        }
                                                        if ($_POST["city_select_mode"] == "0") {
                                                            if ($_POST["card_city"] == "") {
                                                                $card_import["card_city"] = "";
                                                            } else {
                                                                $card_import["card_city"] = $lineField[$_POST["card_city"] - 1];
                                                            }
                                                            if ($_POST["card_state"] == "") {
                                                                $card_import["card_state"] = "";
                                                            } else {
                                                                $card_import["card_state"] = $lineField[$_POST["card_state"] - 1];
                                                            }
                                                            if ($_POST["card_zip"] == "") {
                                                                $card_import["card_zip"] = "";
                                                            } else {
                                                                $card_import["card_zip"] = $lineField[$_POST["card_zip"] - 1];
                                                            }
                                                        } else if ($_POST["city_select_mode"] == "1") {
                                                            $card_import["card_zip"] = $lineField[$_POST["card_zip"] - 1];
                                                            $card_zip = $db->escape($card_import["card_zip"]);
                                                            $sql = "SELECT CITY, REGION FROM `" . TABLE_ZIPCODES . "` WHERE ZIPCODE = '" . $card_zip . "'";
                                                            $card_zip = $db->query_first($sql);
                                                            if ($card_zip) {
                                                                if (trim($card_zip["CITY"]) != "" AND trim($card_zip["CITY"]) != "-" AND trim($card_zip["REGION"]) != "" AND trim($card_zip["REGION"]) != "-") {
                                                                    $card_import["card_city"] = trim($card_zip["CITY"]);
                                                                    $card_import["card_state"] = trim($card_zip["REGION"]);
                                                                } else {
                                                                    $import_get_zipcode_error1[] = $line . " => Zipcode not found in database.";
                                                                    $get_zipcode_error = <<<HTML
									<tr>
										<td colspan="8" class="centered">
											<span class="blue bold">{$line} => Zipcode not found in database.</span>
										</td>
									</tr>
HTML;
                                                                }
                                                            } else {
                                                                $import_get_zipcode_error2[] = $line . " => Get City, State error.";
                                                                $get_zipcode_error = <<<HTML
									<tr>
										<td colspan="8" class="centered">
											<span class="red bold">{$line} => Get City, State error.</span>
										</td>
									</tr>
HTML;
                                                            }
                                                        } else if ($_POST["city_select_mode"] == "2") {
                                                            $card_import["card_city"] = $lineField[$_POST["card_city"] - 1];
                                                            $card_import["card_state"] = $lineField[$_POST["card_state"] - 1];
                                                            $card_city = $db->escape($card_import["card_city"]);
                                                            $card_state = $db->escape($card_import["card_state"]);
                                                            $sql = "SELECT * FROM `" . TABLE_ZIPCODES . "` WHERE (CITY = '" . $card_city . "' AND REGION = '" . $card_state . "') OR (CITY = '" . $card_city . "')";
                                                            $card_zip = $db->query_first($sql);
                                                            if ($card_zip) {
                                                                if (trim($card_zip["ZIPCODE"]) != "" AND trim($card_zip["ZIPCODE"]) != "-") {
                                                                    $card_import["card_zip"] = trim($card_zip["ZIPCODE"]);
                                                                    $card_import["card_state"] = trim($card_zip["REGION"]);
                                                                } else {
                                                                    $import_get_zipcode_error1[] = $line . " => City, State not found in database.";
                                                                    $get_zipcode_error = <<<HTML
									<tr>
										<td colspan="8" class="centered">
											<span class="blue bold">{$line} => City, State not found in database.</span>
										</td>
									</tr>
HTML;
                                                                }
                                                            } else {
                                                                $import_get_zipcode_error2[] = $line . " => Get zipcode error.";
                                                                $get_zipcode_error = <<<HTML
									<tr>
										<td colspan="8" class="centered">
											<span class="red bold">{$line} => Get zipcode error.</span>
										</td>
									</tr>
HTML;
                                                            }
                                                        }
                                                        if ($_POST["card_country"] == "") {
                                                            $card_import["card_country"] = "";
                                                        } else if ($_POST["card_country"] == "AUTO BY BIN") {
                                                            $cardBin = $db->escape(substr($card_import["card_number"], 0, 6));
                                                            $sql = "SELECT card_country FROM `" . TABLE_BINS . "` WHERE card_bin = '" . $cardBin . "'";
                                                            $card_country = $db->query_first($sql);
                                                            if ($card_country) {
                                                                if (trim($card_country["card_country"]) != "" AND trim($card_country["card_country"]) != "-") {
                                                                    $card_import["card_country"] = trim($card_country["card_country"]);
                                                                } else {
                                                                    $import_get_country_error1[] = $line . " => BIN not found in database.";
                                                                    $get_country_error = <<<HTML
									<tr>
										<td colspan="8" class="centered">
											<span class="blue bold">{$line} => BIN not found in database.</span>
										</td>
									</tr>
HTML;
                                                                }
                                                            } else {
                                                                $import_get_country_error2[] = $line . " => Get country error.";
                                                                $get_country_error = <<<HTML
									<tr>
										<td colspan="8" class="centered">
											<span class="red bold">{$line} => Get country error.</span>
										</td>
									</tr>
HTML;
                                                            }
                                                        } else {
                                                            $card_import["card_country"] = $lineField[$_POST["card_country"] - 1];
                                                        }
                                                        if ($_POST["card_phone"] == "") {
                                                            $card_import["card_phone"] = "";
                                                        } else {
                                                            $card_import["card_phone"] = $lineField[$_POST["card_phone"] - 1];
                                                        }
                                                        if ($_POST["card_ssn"] == "") {
                                                            $card_import["card_ssn"] = "";
                                                        } else {
                                                            $card_import["card_ssn"] = $lineField[$_POST["card_ssn"] - 1];
                                                        }
                                                        if ($_POST["card_dob"] == "") {
                                                            $card_import["card_dob"] = "";
                                                        } else {
                                                            $card_import["card_dob"] = $lineField[$_POST["card_dob"] - 1];
                                                        }
                                                        if (isset($_POST["card_import_save"])) {
                                                            $sql = "SELECT count(*) FROM `" . TABLE_CARDS . "` WHERE card_number = AES_ENCRYPT('" . $db->escape($card_import["card_number"]) . "', '" . strval(DB_ENCRYPT_PASS) . "')";
                                                            $card_duplicate = $db->query_first($sql);
                                                            if ($get_zipcode_error != "") {
                                                                echo $get_zipcode_error;
                                                            } else if ($get_country_error != "") {
                                                                echo $get_country_error;
                                                            } else if ($card_duplicate) {
                                                                if (intval($card_duplicate["count(*)"]) == 0) {
                                                                    if ($_POST["checklive"] == "on") {
                                                                        $check = check($card_import["card_number"], $card_import["card_month"], $card_import["card_year"], $card_import["card_cvv"]);
                                                                    } else {
                                                                        $check = 1;
                                                                    }
                                                                    if ($check == 1) {
                                                                        $card_import["card_fullinfo"] = "AES_ENCRYPT('" . $card_import["card_fullinfo"] . "', '" . strval(DB_ENCRYPT_PASS) . "')";
                                                                        $card_import["card_number"] = "AES_ENCRYPT('" . $card_import["card_number"] . "', '" . strval(DB_ENCRYPT_PASS) . "')";

                                                                        //Added by DHSprout Start
                                                                        $sql_query = "SELECT price FROM `" . TABLE_COUNTRIES . "` WHERE country_name = '" . $card_import["card_country"] . "'";
                                                                        $country_price = $db->query_first($sql_query);
                                                                        $card_import["card_price"] = $country_price['price'];
                                                                        //Added by DHSprout End
                                                                        if ($db->query_insert(TABLE_CARDS, $card_import)) {
                                                                            ?>
                                                                            <tr>
                                                                                <td colspan="8" class="centered">
                                                                                    <span class="green bold"><?= $line ?> => Add Card successfully.</span>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                            //$import_result[] = "<span class=\"green bold\">".$line." => Add Card successfully.</span>";
                                                                        } else {
                                                                            ?>
                                                                            <tr>
                                                                                <td colspan="8" class="centered">
                                                                                    <span class="blue bold"><?= $line ?> => Add Card error.</span>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                            //$import_result[] = "<span class=\"blue bold\">".$line." => Add Card error.</span>";
                                                                        }
                                                                    } else if ($check == 2) {
                                                                        ?>
                                                                        <tr>
                                                                            <td colspan="8" class="centered">
                                                                                <span class="red bold"><?= $line ?> => Card die.</span>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        //$import_result[] = "<span class=\"red bold\">".$line." => Card die.</span>";
                                                                    } else {
                                                                        ?>
                                                                        <tr>
                                                                            <td colspan="8" class="centered">
                                                                                <span class="blue bold"><?= $line ?> => Other Error.</span>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        //$import_result[] = "<span class=\"blue bold\">".$line." => Other Error.</span>";
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="8" class="centered">
                                                                            <span class="pink bold"><?= $line ?> => Duplicated in database.</span>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    //$import_result[] = "<span class=\"pink bold\">".$line." => Duplicated in database.</span>";
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="8" class="centered">
                                                                        <span class="blue bold"><?= $line ?> => Check duplicate error.</span>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                //$import_result[] = "<span class=\"blue bold\">".$line." => Check duplicate error.</span>";
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr class="formstyle">
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_number'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_month'] ?>/<?= $card_import['card_year'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_cvv'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_name'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_address'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_city'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_state'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_zip'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_country'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_phone'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_ssn'] ?></span>
                                                                </td>
                                                                <td class="centered">
                                                                    <span><?= $card_import['card_dob'] ?></span>
                                                                </td>
                                                                <td class="red centered">
                                                                    <span><?= $card_import['card_comment'] ?></span>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    flush();
                                                }
                                                if (isset($_POST["card_import_preview"])) {
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <!--tr>
                                        <td colspan="8" class="centered">
                                <?php
                                /*
                                  if (count($import_result) > 0) {
                                  foreach ($import_result as $temp) {
                                  echo $temp."<br/>";
                                  }
                                  }
                                 */
                                ?>
                                        </td>
                                </tr-->
                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="8" class="centered">
                                <span class="red bold"><?= $errorMsg ?></span>
                            </td>
                        </tr>
                        <?php
                        ?>
                    <form method="POST" action="">
                        <tr>
                            <td class="centered bold" colspan="8">
                                Card Content:
                                <textarea class="card_content_editor" name="card_content" type="text"><?= $_POST['card_content'] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="centered bold red" colspan="8">
                                Select City, State, Zipcode Mode: <select name="city_select_mode" onchange="change_city_select_mode();">
                                    <option value="0" <?php if ($_POST["city_select_mode"] == "0") echo "selected "; ?>>Manual - Recomend for International or US, UK none City, State, Zipcode</option>
                                    <option value="1" <?php if ($_POST["city_select_mode"] == "1") echo "selected "; ?>>Auto find City & State by Zipcode - Recomend for US, UK have Zipcode</option>
                                    <option value="2" <?php if ($_POST["city_select_mode"] == "2") echo "selected "; ?>>Auto find Zipcode by City & State - Recomend for US, UK have City & State</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="centered bold red" colspan="8">
                                Select country Mode: <select name="country_select_mode" onchange="change_country_select_mode();">
                                    <option value="0" <?php if ($_POST["country_select_mode"] == "0") echo "selected "; ?>>Auto find Country by Card Number - Recomend</option>
                                    <option value="1" <?php if ($_POST["country_select_mode"] == "1") echo "selected "; ?>>Manual position</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="centered bold formstyle">
                                Card Price
                            </td>
                            <td class="centered bold formstyle">
                                Spliter
                            </td>
                            <td class="centered bold formstyle">
                                Card number
                            </td>
                            <td class="centered bold formstyle">
                                Exp month
                            </td>
                            <td class="centered bold formstyle">
                                Exp year
                            </td>
                            <td class="centered formstyle">
                                Card CVV2
                            </td>
                            <td class="centered formstyle">
                                First Name
                            </td>
                            <td class="centered formstyle">
                                Last Name
                            </td>
                        </tr>
                        <tr>
                            <td class="centered bold">
                                <input name="card_price" type="text" size="4" value="<?= $_POST["card_price"] ?>" readonly="readonly"/>
                            </td>
                            <td class="centered bold">
                                <input name="card_spliter" type="text" size="4" value="<?= $_POST["card_spliter"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_number" type="text" size="4" value="<?= $_POST["card_number"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_month" type="text" size="4" value="<?= $_POST["card_month"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_year" type="text" size="4" value="<?= $_POST["card_year"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_cvv" type="text" size="4" value="<?= $_POST["card_cvv"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_fname" type="text" size="4" value="<?= $_POST["card_fname"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_lname" type="text" size="4" value="<?= $_POST["card_lname"] ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="centered formstyle">
                                Address
                            </td>
                            <td id="card_city" class="centered formstyle">
                                City
                            </td>
                            <td id="card_state" class="centered formstyle">
                                State
                            </td>
                            <td id="card_zip" class="centered formstyle">
                                Zipcode
                            </td>
                            <td class="centered formstyle">
                                Country
                            </td>
                            <td class="centered formstyle">
                                Phone
                            </td>
                            <td class="centered formstyle">
                                SSN
                            </td>
                            <td class="centered formstyle">
                                DOB
                            </td>
                        </tr>
                        <tr>
                            <td class="centered bold">
                                <input name="card_address" type="text" size="4" value="<?= $_POST["card_address"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_city" type="text" size="11" value="<?= $_POST["card_city"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_state" type="text" size="11" value="<?= $_POST["card_state"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_zip" type="text" size="12" value="<?= $_POST["card_zip"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_country" type="text" size="11" value="<?= $_POST["card_country"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_phone" type="text" size="4" value="<?= $_POST["card_phone"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_ssn" type="text" size="4" value="<?= $_POST["card_ssn"] ?>" />
                            </td>
                            <td class="centered bold">
                                <input name="card_dob" type="text" size="4" value="<?= $_POST["card_dob"] ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="card_editor">
                                Card Comment:
                            </td>
                            <td colspan="6">
                                <textarea class="card_comment" name="card_comment" type="text" wrap="on";><?= $value['card_comment'] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" class="red bold centered">
                                CHECK CARD LIVE BEFORE IMPORT: <input type="checkbox" name="checklive" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" class="centered">
                                <input type="submit" name="card_import_preview" value="Preview" /><input type="submit" name="card_import_save" value="Import" /><input onclick="window.location = './cards.php'"type="button" name="card_import_cancel" value="Cancel" />
                            </td>
                        </tr>
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    else if ($_GET["act"] == "edit" && $_GET["card_id"] != "") {
        $card_id = $db->escape($_GET["card_id"]);
        ?>
        <div id="cards">
            <div class="section_title">CARD EDITOR</div>
            <div class="section_content">
                <table class="content_table">
                    <tbody>
                        <?php
                        if (isset($_POST["card_edit_save"])) {
                            $card_update["card_fullinfo"] = "AES_ENCRYPT('" . $_POST["card_fullinfo"] . "', '" . strval(DB_ENCRYPT_PASS) . "')";
                            $card_update["card_number"] = "AES_ENCRYPT('" . $_POST["card_number"] . "', '" . strval(DB_ENCRYPT_PASS) . "')";
                            $card_update["card_month"] = $_POST["card_month"];
                            $card_update["card_year"] = $_POST["card_year"];
                            $card_update["card_cvv"] = $_POST["card_cvv"];
                            $card_update["card_name"] = $_POST["card_name"];
                            $card_update["card_address"] = $_POST["card_address"];
                            $card_update["card_city"] = $_POST["card_city"];
                            $card_update["card_state"] = $_POST["card_state"];
                            $card_update["card_zip"] = $_POST["card_zip"];
                            $card_update["card_country"] = $_POST["card_country"];
                            $card_update["card_ssn"] = $_POST["card_ssn"];
                            $card_update["card_dob"] = $_POST["card_dob"];
                            $card_update["card_phone"] = $_POST["card_phone"];
                            $card_update["card_price"] = $_POST["card_price"];
                            $card_update["card_userid"] = $_POST["card_userid"];
                            $card_update["card_check"] = $_POST["card_check"];
                            $card_update["card_comment"] = $_POST["card_comment"];
                            if ($db->query_update(TABLE_CARDS, $card_update, "card_id='" . $card_id . "'")) {
                                ?>
                                <tr>
                                    <td colspan="4" class="centered">
                                        <span class="green bold">Update Card successfully.</span>
                                    </td>
                                </tr>
                                <?php
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4" class="centered">
                                        <span class="red bold">Update Card error.</span>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        $sql = "SELECT user_id, user_name from `" . TABLE_USERS . "` ORDER BY user_name";
                        $allUsers = $db->fetch_all_array($sql);
                        $sql = "SELECT *, AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') AS card_number, AES_DECRYPT(card_fullinfo, '" . strval(DB_ENCRYPT_PASS) . "') AS card_fullinfo FROM `" . TABLE_CARDS . "` WHERE card_id = '" . $card_id . "'";
                        $records = $db->fetch_all_array($sql);
                        if (count($records) > 0) {
                            $value = $records[0];
                            ?>
                        <form method="POST" action="">
                            <tr>
                                <td colspan="4">
                                    <textarea class="card_full_info" name="card_fullinfo" type="text" wrap="on";><?= $value['card_fullinfo'] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card number:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_number" type="text" value="<?= $value['card_number'] ?>" />
                                </td>
                                <td class="card_editor">
                                    Card CVV2:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_cvv" type="text" value="<?= $value['card_cvv'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card month:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_month" type="text" value="<?= $value['card_month'] ?>" />
                                </td>
                                <td class="card_editor">
                                    Card year:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_year" type="text" value="<?= $value['card_year'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card First Name:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_name" type="text" value="<?= $value['card_name'] ?>" />
                                </td>
                                <td class="card_editor">
                                    Card Address:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_address" type="text" value="<?= $value['card_address'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card City:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_city" type="text" value="<?= $value['card_city'] ?>" />
                                </td>
                                <td class="card_editor">
                                    Card State:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_state" type="text" value="<?= $value['card_state'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card Zipcode:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_zip" type="text" value="<?= $value['card_zip'] ?>" />
                                </td>
                                <td class="card_editor">
                                    Card Country:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_country" type="text" value="<?= $value['card_country'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card SSN:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_ssn" type="text" value="<?= $value['card_ssn'] ?>" />
                                </td>
                                <td class="card_editor">
                                    Card DOB:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_dob" type="text" value="<?= $value['card_dob'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card Phone:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_phone" type="text" value="<?= $value['card_phone'] ?>" />
                                </td>
                                <td class="card_editor">
                                    Card Price:
                                </td>
                                <td>
                                    <input class="card_value_editor" name="card_price" type="text" value="<?= $value['card_price'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card Used by:
                                </td>
                                <td>
                                    <select class="card_value_editor" name="card_userid">
                                        <option value="0">--Unsold--</option>
                                        <?php
                                        if (count($allUsers) > 0) {
                                            foreach ($allUsers as $k => $v) {
                                                ?>
                                                <option value="<?= $v["user_id"] ?>" <?= ($v["user_id"] == $value["card_userid"]) ? "selected " : "" ?>><?= $v["user_name"] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="card_editor">
                                    Card Check Status:
                                </td>
                                <td>
                                    <select class="card_value_editor" name="card_check">
                                        <option value="<?= strval(CHECK_DEFAULT) ?>" <?= (strval(CHECK_DEFAULT) == $value["card_check"]) ? "selected " : "" ?>>UNCHECK</option>
                                        <option value="<?= strval(CHECK_INVALID) ?>" <?= (strval(CHECK_INVALID) == $value["card_check"]) ? "selected " : "" ?>>INVALID</option>
                                        <option value="<?= strval(CHECK_VALID) ?>" <?= (strval(CHECK_VALID) == $value["card_check"]) ? "selected " : "" ?>>VALID</option>
                                        <option value="<?= strval(CHECK_REFUND) ?>" <?= (strval(CHECK_REFUND) == $value["card_check"]) ? "selected " : "" ?>>REFUNDED</option>
                                        <option value="<?= strval(CHECK_UNKNOWN) ?>" <?= (strval(CHECK_UNKNOWN) == $value["card_check"]) ? "selected " : "" ?>>UNKNOWN</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="card_editor">
                                    Card Comment:
                                </td>
                                <td colspan="3">
                                    <textarea class="card_comment" name="card_comment" type="text" wrap="on";><?= $value['card_comment'] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="centered">
                                    <input type="submit" name="card_edit_save" value="Save" /><input onclick="window.location = './cards.php'"type="button" name="card_edit_cancel" value="Cancel" />
                                </td>
                            </tr>
                        </form>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td class="red bold centered">
                                <span class="red">Card ID Invalid.</span>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    } else if ($_GET["act"] == "delete" && $_GET["card_id"] != "") {
        $card_id = $db->escape($_GET["card_id"]);
        $sql = "DELETE FROM `" . TABLE_CARDS . "` WHERE card_id = '" . $card_id . "'";
        if ($db->query($sql)) {
            ?>
            <script type="text/javascript">setTimeout("window.location = './cards.php'", 1000);</script>
            <div id="cards">
                <div class="section_title">CARD DELETE</div>
                <div class="section_content">
                    <table class="content_table">
                        <tbody>
                            <tr>
                                <td class="green bold centered">
                                    Delete Card ID <?= $card_id ?> successfully.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div id="cards">
                <div class="section_title">CARD DELETE</div>
                <div class="section_content">
                    <table class="content_table">
                        <tbody>
                            <tr>
                                <td class="red bold centered">
                                    Card ID Invalid.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
    } else {
        if ($_POST["delete_expired"] != "") {
            $sql = "DELETE FROM `" . TABLE_CARDS . "` WHERE card_year < " . date("Y") . " OR (card_year = " . date("Y") . " AND card_month < " . date("n") . ")";
            if ($db->query($sql)) {
                $deleteResult = <<<END
										<td colspan="13" class="green bold centered">
											Delete all expired cards successfully.
										</td>
END;
            } else {
                $deleteResult = <<<END
										<td colspan="13" class="red bold centered">
											Delete expired cards error.
										</td>
END;
            }
        } else if ($_POST["delete_invalid"] != "") {
            $sql = "DELETE FROM `" . TABLE_CARDS . "` WHERE card_check = '" . strval(CHECK_INVALID) . "' OR card_check = '" . strval(CHECK_REFUND) . "'";
            if ($db->query($sql)) {
                $deleteResult = <<<END
										<td colspan="13" class="green bold centered">
											Delete all invalid and refund cards successfully.
										</td>
END;
            } else {
                $deleteResult = <<<END
										<td colspan="13" class="red bold centered">
											Delete invalid and refund cards error.
										</td>
END;
            }
        } else if ($_POST["delete_select"] != "" && $_POST["cards"] != "" && is_array($_POST["cards"])) {
            $allCards = $_POST["cards"];
            $lastCards = $db->escape($allCards[count($allCards) - 1]);
            unset($allCards[count($allCards) - 1]);
            $sql = "DELETE FROM `" . TABLE_CARDS . "` WHERE card_id IN (";
            if (count($allCards) > 0) {
                foreach ($allCards as $key => $value) {
                    $sql .= "'" . $db->escape($value) . "', ";
                }
            }
            $sql .= "'" . $lastCards . "')";
            if ($db->query($sql)) {
                $deleteResult = <<<END
										<td colspan="13" class="green bold centered">
											Delete selected cards successfully.
										</td>
END;
            } else {
                $deleteResult = <<<END
										<td colspan="13" class="red bold centered">
											Delete selected cards error.
										</td>
END;
            }
        }

        if (isset($_GET["btnSearch"])) {
            $currentGet = "";
            $currentGet .= "txtBin=" . $_GET["txtBin"] . "&lstCountry=" . $_GET["lstCountry"] . "&lstState=" . $_GET["lstState"] . "&lstCity=" . $_GET["lstCity"] . "&txtZip=" . $_GET["txtZip"] . "&lstAvailable=" . $_GET["lstAvailable"] . "&lstExpire=" . $_GET["lstExpire"] . "&lstStatus=" . $_GET["lstStatus"] . "&lstCheck=" . $_GET["lstCheck"];
            $currentGet .= ($_GET["boxDob"] != "") ? "&boxDob=" . $_GET["boxDob"] : "";
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
        switch ($_GET["lstAvailable"]) {
            case "unsold":
                $searchAvailable = "card_userid = '0'";
                break;
            case "sold":
                $searchAvailable = "card_userid <> '0'";
                break;
            default:
                $searchAvailable = "1";
                break;
        }
        switch ($_GET["lstExpire"]) {
            case strval(EXPIRE_FUTURE):
                $searchExpire = "(card_year > " . date("Y") . " OR (card_year = " . date("Y") . " AND card_month > " . date("n") . "))";
                break;
            case strval(EXPIRE_STAGNANT):
                $searchExpire = "(card_year = " . date("Y") . " AND card_month = " . date("n") . ")";
                break;
            case strval(EXPIRE_EXPIRED):
                $searchExpire = "(card_year < " . date("Y") . " OR (card_year = " . date("Y") . " AND card_month < " . date("n") . "))";
                break;
            default:
                $searchExpire = "1";
                break;
        }
        switch ($_GET["lstStatus"]) {
            case strval(STATUS_DEFAULT):
                $searchStatus = "card_status = '" . strval(STATUS_DEFAULT) . "'";
                break;
            case strval(STATUS_DELETED):
                $searchStatus = "card_status = '" . strval(STATUS_DELETED) . "'";
                break;
            case strval(STATUS_STAGNANT):
                $searchStatus = "card_status = '" . strval(STATUS_STAGNANT) . "'";
                break;
            case strval(STATUS_EXPIRED):
                $searchStatus = "card_status = '" . strval(STATUS_EXPIRED) . "'";
                break;
            default:
                $searchStatus = "1";
                break;
        }
        switch ($_GET["lstCheck"]) {
            case strval(CHECK_DEFAULT):
                $searchCheck = "card_check = '" . strval(CHECK_DEFAULT) . "'";
                break;
            case strval(CHECK_VALID):
                $searchCheck = "card_check = '" . strval(CHECK_VALID) . "'";
                break;
            case strval(CHECK_INVALID):
                $searchCheck = "card_check = '" . strval(CHECK_INVALID) . "'";
                break;
            case strval(CHECK_REFUND):
                $searchCheck = "card_check = '" . strval(CHECK_REFUND) . "'";
                break;
            case strval(CHECK_UNKNOWN):
                $searchCheck = "card_check = '" . strval(CHECK_UNKNOWN) . "'";
                break;
            default:
                $searchCheck = "1";
                break;
        }
        $sql = "SELECT count(*) FROM `" . TABLE_CARDS . "` WHERE " . $searchExpire . " AND " . $searchStatus . " AND " . $searchCheck . " AND " . $searchAvailable . " AND AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') LIKE '" . $searchBin . "%' AND ('" . $searchCountry . "'='' OR card_country = '" . $searchCountry . "') AND ('" . $searchState . "'='' OR card_state = '" . $searchState . "') AND ('" . $searchCity . "'='' OR card_city = '" . $searchCity . "') AND card_zip LIKE '" . $searchZip . "%'" . $searchSSN . $searchDob;
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
        $sql = "SELECT " . TABLE_CARDS . ".*, AES_DECRYPT(" . TABLE_CARDS . ".card_number, '" . strval(DB_ENCRYPT_PASS) . "') AS card_number, " . TABLE_USERS . ".user_name, " . TABLE_GROUPS . ".group_color FROM `" . TABLE_CARDS . "` LEFT JOIN `" . TABLE_USERS . "` ON " . TABLE_CARDS . ".card_userid = " . TABLE_USERS . ".user_id LEFT JOIN `" . TABLE_GROUPS . "` ON " . TABLE_USERS . ".user_groupid = " . TABLE_GROUPS . ".group_id WHERE " . $searchExpire . " AND " . $searchStatus . " AND " . $searchCheck . " AND " . $searchAvailable . " AND AES_DECRYPT(card_number, '" . strval(DB_ENCRYPT_PASS) . "') LIKE '" . $searchBin . "%' AND ('" . $searchCountry . "'='' OR card_country = '" . $searchCountry . "') AND ('" . $searchState . "'='' OR card_state = '" . $searchState . "') AND ('" . $searchCity . "'='' OR card_city = '" . $searchCity . "') AND card_zip LIKE '" . $searchZip . "%'" . $searchSSN . $searchDob . " ORDER BY card_id LIMIT " . (($page - 1) * $perPage) . "," . $perPage;
        $listcards = $db->fetch_all_array($sql);
        ?>
        <div id="search_cards">
            <div class="section_title">SEARCH CARDS</div>
            <div class="section_content">
                <table class="content_table centered">
                    <tbody>
                    <form name="search" method="GET" action="cards.php">
                        <tr>
                            <td colspan="2" class="formstyle">
                                <span class="bold">CARD NUMBER</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">COUNTRY</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">STATE</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">CITY</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">ZIP</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input name="txtBin" type="text" class="formstyle" id="txtBin" value="<?= $_GET["txtBin"] ?>" size="25" maxlength="16">
                            </td>
                            <td>
                                <select name="lstCountry" class="formstyle" id="lstCountry">
                                    <option value="">All Country</option>
                                    <?php
                                    $sql = "SELECT DISTINCT card_country FROM `" . TABLE_CARDS . "`";
                                    $allCountry = $db->fetch_all_array($sql);
                                    if (count($allCountry) > 0) {
                                        foreach ($allCountry as $country) {
                                            echo "<option value=\"" . $country['card_country'] . "\"" . (($_GET["lstCountry"] == $country['card_country']) ? " selected" : "") . ">" . $country['card_country'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="lstState" class="formstyle" id="lstState">
                                    <option value="">All State</option>
                                    <?php
                                    $sql = "SELECT DISTINCT card_state FROM `" . TABLE_CARDS . "`";
                                    $allCountry = $db->fetch_all_array($sql);
                                    if (count($allCountry) > 0) {
                                        foreach ($allCountry as $country) {
                                            echo "<option value=\"" . $country['card_state'] . "\"" . (($_GET["lstState"] == $country['card_state']) ? " selected" : "") . ">" . $country['card_state'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="lstCity" class="formstyle" id="lstCity">
                                    <option value="">All City</option>
                                    <?php
                                    $sql = "SELECT DISTINCT card_city FROM `" . TABLE_CARDS . "`";
                                    $allCountry = $db->fetch_all_array($sql);
                                    if (count($allCountry) > 0) {
                                        foreach ($allCountry as $country) {
                                            echo "<option value=\"" . $country['card_city'] . "\"" . (($_GET["lstCity"] == $country['card_city']) ? " selected" : "") . ">" . $country['card_city'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input name="txtZip" type="text" class="formstyle" id="txtZip" value="<?= $_GET["txtZip"] ?>" size="12">
                            </td>
                        </tr>
                        <tr>
                            <td class="formstyle">
                                <span class="bold">Have SSN</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">Have DoB</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">AVAILABLE</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">EXPIRE TYPE</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">STATUS</span>
                            </td>
                            <td class="formstyle">
                                <span class="bold">CHECK</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><input type="checkbox" name="boxSSN" id="boxSSN" <?= ($_GET["boxSSN"] != "") ? "checked " : "" ?>></span>
                            </td>
                            <td>
                                <span><input type="checkbox" name="boxDob" id="boxDob" <?= ($_GET["boxDob"] != "") ? "checked " : "" ?>></span>
                            </td>
                            <td>
                                <select name="lstAvailable" class="formstyle" id="lstAvailable">
                                    <option value="" <?= (($_GET["lstAvailable"] == "") ? " selected" : "") ?>>ALL</option>
                                    <option value="unsold" <?= (($_GET["lstAvailable"] == "unsold") ? " selected" : "") ?>>UNSOLD</option>
                                    <option value="sold" <?= (($_GET["lstAvailable"] == "sold") ? " selected" : "") ?>>SOLD</option>
                                </select>
                            </td>
                            <td>
                                <select name="lstExpire" class="formstyle" id="lstExpire">

                                    <option value="" <?= (($_GET["lstExpire"] == "") ? " selected" : "") ?>>ALL</option>
                                    <option value="<?= strval(EXPIRE_FUTURE) ?>" <?= (($_GET["lstExpire"] == strval(EXPIRE_FUTURE)) ? " selected" : "") ?>>FUTURE</option>
                                    <option value="<?= strval(EXPIRE_STAGNANT) ?>" <?= (($_GET["lstExpire"] == strval(EXPIRE_STAGNANT)) ? " selected" : "") ?>>THIS MONTH</option>
                                    <option value="<?= strval(EXPIRE_EXPIRED) ?>" <?= (($_GET["lstExpire"] == strval(EXPIRE_EXPIRED)) ? " selected" : "") ?>>EXPIRED</option>
                                </select>
                            </td>
                            <td>
                                <select name="lstStatus" class="formstyle" id="lstStatus">

                                    <option value="" <?= (($_GET["lstStatus"] == "") ? " selected" : "") ?>>ALL</option>
                                    <option value="<?= strval(STATUS_DEFAULT) ?>" <?= (($_GET["lstStatus"] == strval(STATUS_DEFAULT)) ? " selected" : "") ?>>USER DEFAULT</option>
                                    <option value="<?= strval(STATUS_DELETED) ?>" <?= (($_GET["lstStatus"] == strval(STATUS_DELETED)) ? " selected" : "") ?>>USER DELETED</option>
                                </select>
                            </td>
                            <td>
                                <select name="lstCheck" class="formstyle" id="lstCheck">

                                    <option value="" <?= (($_GET["lstCheck"] == "") ? " selected" : "") ?>>ALL</option>
                                    <option value="<?= strval(CHECK_DEFAULT) ?>" <?= (($_GET["lstCheck"] == strval(CHECK_DEFAULT)) ? " selected" : "") ?>>UNCHECK</option>
                                    <option value="<?= strval(CHECK_VALID) ?>" <?= (($_GET["lstCheck"] == strval(CHECK_VALID)) ? " selected" : "") ?>>VALID</option>
                                    <option value="<?= strval(CHECK_INVALID) ?>" <?= (($_GET["lstCheck"] == strval(CHECK_INVALID)) ? " selected" : "") ?>>TIMEOUT</option>
                                    <option value="<?= strval(CHECK_REFUND) ?>" <?= (($_GET["lstCheck"] == strval(CHECK_REFUND)) ? " selected" : "") ?>>REFUNDED</option>
                                    <option value="<?= strval(CHECK_UNKNOWN) ?>" <?= (($_GET["lstCheck"] == strval(CHECK_UNKNOWN)) ? " selected" : "") ?>>UNKNOWN</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <input name="btnSearch" type="submit" class="formstyle" id="btnSearch" value="Search">
                            </td>
                        </tr>
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="cards">
            <div class="section_title">AVAILABLE CARDS</div>
            <div class="section_title" style="text-align: right; padding: 10px;"><a href="?act=import">Import Cards</a></div>
            <div class="section_content">
                <table class="content_table">
                    <tbody>
                    <form name="cards" method="POST" action="">
                        <tr>
                            <?= $deleteResult ?>
                        </tr>
                        <tr>
                            <td class="formstyle centered">
                                <span class="bold">CARD NUMBER</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">EXPIRE</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">CVV</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">CITY</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">STATE</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">ZIP</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">COUNTRY</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">SSN/DOB</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">SOLD TO</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="red bold">COMMENT</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">CHECK</span>
                            </td>
                            <td class="formstyle centered">
                                <span class="bold">ACTION</span>
                            </td>
                            <td class="formstyle centered">
                                <input class="formstyle" type="checkbox" name="selectAllCards" id="selectAllCards" onclick="checkAll(this.id, 'cards[]')" value="">
                            </td>
                        </tr>
                        <?php
                        if (count($listcards) > 0) {
                            foreach ($listcards as $key => $value) {
                                ?>
                                <tr class="formstyle">
                                    <td class="centered bold">
                                        <span><?= $value['card_bin'] ?>...</span>
                                    </td>
                                    <td class="centered">
                                        <span><?= $value['card_month'] ?>/<?= $value['card_year'] ?></span>
                                    </td>
                                    <td class="centered">
                                        <span><?= $value['card_cvv'] ?></span>
                                    </td>
                                    <td class="centered">
                                        <span><?= $value['card_city'] ?></span>
                                    </td>
                                    <td class="centered">
                                        <span><?= $value['card_state'] ?></span>
                                    </td>
                                    <td class="centered">
                                        <span><?= $value['card_zip'] ?></span>
                                    </td>
                                    <td class="centered">
                                        <span><?= $value['card_country'] ?></span>
                                    </td>
                                    <td class="centered">
                                        <span><?= ($value['card_ssn'] == "") ? "NO" : "YES" ?></span>/<span><?= ($value['card_dob'] == "") ? "NO" : "YES" ?></span>
                                    </td>
                                    <td class="centered">
                                        <?php
                                        switch ($value['card_userid']) {
                                            case 0:
                                                echo "<span class=\"bold\"> - </span>";
                                                break;
                                            default :
                                                echo "<span class=\"bold\" style=\"color:" . $value['group_color'] . ";\" >" . $value['user_name'] . "</span>";
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="red centered">
                                        <span><?= ($value['card_cvv'] == "") ? "No CVV2" : "Have CVV2" ?><?= ($value['card_comment'] == "") ? "" : (" - " . $value['card_comment']) ?></span>
                                    </td>
                                    <td class="centered bold">
                                        <span>
                                            <?php
                                            switch ($value['card_check']) {
                                                case strval(CHECK_VALID):
                                                    echo "<span class=\"green bold\">VALID</span>";
                                                    break;
                                                case strval(CHECK_INVALID):
                                                    echo "<span class=\"red bold\">TIMEOUT</span>";
                                                    break;
                                                case strval(CHECK_REFUND):
                                                    echo "<span class=\"pink bold\">INVALID - REFUNDED</span>";
                                                    break;
                                                case strval(CHECK_UNKNOWN):
                                                    echo "<span class=\"blue bold\">UNKNOWN</span>";
                                                    break;
                                                default :
                                                    echo "<span class=\"black bold\">UNCHECK</span>";
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td class="centered">
                                        <span><a href="?act=edit&card_id=<?= $value['card_id'] ?>">Edit</a> | <a href="?act=delete&card_id=<?= $value['card_id'] ?>" onClick="return confirm('Are you sure you want to delete this cards?')">Delete</a></span>
                                    </td>
                                    <td class="centered">
                                        <input class="formstyle" type="checkbox" name="cards[]" value="<?= $value['card_id'] ?>">
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="13" class="centered">
                                <p>
                                    <label>
                                        <input name="download_expired" type="button" class="black bold" id="download_expired" onclick="$('#download_expired_form').submit();" value="Download Expired Cards">
                                    </label>
                                    <span> | </span>
                                    <label>
                                        <input name="delete_expired" type="submit" class="blue bold" id="delete_expired" onClick="return confirm('Are you sure you want to delete the EXPIRED cards?')" value="Delete Expired Cards">
                                        <span> | </span><label>
                                            <input name="delete_invalid" type="submit" class="pink bold" id="delete_invalid" onClick="return confirm('Are you sure you want to delete the INVALID cards?')" value="Delete Invalid/Refunded Cards">
                                        </label>
                                    </label> | </span>
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
            </div>
        </div>
        <form id="download_expired_form" method="POST" action="../cardprocess.php">
            <input name="download_expired" type="hidden">
        </form>
        <?php
    }
} else {
    require("./minilogin.php");
}
require("./footer.php");
?>