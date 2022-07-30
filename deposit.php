<?php
$is_user_panel = TRUE;
$page_name = "deposit";
require("./header.php");
?>
<h1>DEPOSIT MONEY</h1>
<table class="table" width="100%" style="clear: left;">
    <tbody>
        <tr>
            <td class="paygate_title">
                <span class="red"><img src="https://www.btcmerch.com/img/buttons/buy_with_bitcoin_blue.png" width="150px" height="50px"></span>
            </td>
            <td class="paygate_content">
                <h2> Bitcoins Address:  14nkhzQuaziUaAg2hkZmtZ5KBcJ8mYZhUs</h2>
                <p class="blue bold">Send Bitcoins to this address then click on confirm and make support ticket with transaction details. We will add funds in 10-20 mins in to your account.</p><br />
                <p class="red">MINIMUM PAYMENT: <span class="bold">$<?= number_format($db_config['paygate_minimum'], 2, '.', '') ?></span></p><br />
                <p>
                <form name="libertyreserve" method="post" action="/support">
                    <strong>$</strong>
                    <input name="lr_amnt" type="text" size="15" value="<?= $db_config["paygate_minimum"] ?>">
                    <input type="submit" name="btnLRPay" id="btnLRPay" value="Confirm">
                </form>


                </p>
            </td>
        </tr>
    </tbody>
</table>
<?php
require("./footer.php");
?>