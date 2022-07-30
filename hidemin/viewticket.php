<?php
$page_name = "support";
require("./header.php");
$u_ticket_id = $_REQUEST['common'];

if (isset($_REQUEST['rem']) && $_REQUEST['rem'] == "y") {
    $close["status"] = 'Closed';
    $close["isanswer"] = '1';
    $close["closeddate"] = 'now()';

    $db->query_update(TABLE_TICKETS, $close, "ticketid = '" . $u_ticket_id . "'");
} else if (isset($_POST['validate']) && $_POST['validate'] == "viewticketForm") {
    $answer_add["description"] = $_POST['description'];
    $answer_add["ticketid"] = $_POST['ticketid'];
    $answer_add["user_id"] = $_SESSION['user_id'];
    $answer_add["posttime"] = 'now()';
    $isInsrted = $db->query_insert(TABLE_ANSWERS, $answer_add);
    $u_ticket_id = $_POST['ticketid'];

    $close["isanswer"] = '1';
    $db->query_update(TABLE_TICKETS, $close, "ticketid = '" . $u_ticket_id . "'");
}
$sql = "SELECT ticketid, subject, description, department, urgency, status, isanswer, if(isanswer = 1, 'Answered', 'No Answered') answered, opneddate, date_format(opneddate, '%d-%m-%Y %H:%i:%s') str_opneddate , date_format(closeddate, '%d-%m-%Y %H:%i:%s') str_closeddate, u.user_id, u.user_name ".
       " FROM `" . TABLE_TICKETS . "` s inner join `" . TABLE_USERS . "` u on u.user_id = s.user_id WHERE ticketid = '" . $u_ticket_id . "' ORDER BY opneddate DESC";
$ticket_history = $db->fetch_all_array($sql);
$ms_sql = "select  description, date_format(posttime, '%d-%m-%Y %H:%i:%s') str_posttime, user_name  from `" . TABLE_ANSWERS . "` s inner join `" . TABLE_USERS . "` u on u.user_id = s.user_id "
        . " where s.ticketid = " . $u_ticket_id . " order by posttime desc";
$ticket_answer = $db->fetch_all_array($ms_sql);
?>
<div id="support">
    <div>
        <h1>View Ticket</h1>
        <table class="table view-ticket" width="100%">
            <tbody>
                <?php
                if (count($ticket_history) > 0) {
                    foreach ($ticket_history as $key => $value) {
                        ?>
                        <tr>
                            <td width="20%">
                                Subject
                            </td>
                            <td class="view-tic-td">
                                <span><?= $value['subject'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Department
                            </td>
                            <td class="view-tic-td">
                                <span><?= $value['department'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                User Name
                            </td>
                            <td class="view-tic-td">
                                <span><?= $value['user_name'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Status
                            </td>
                            <td class="view-tic-td">
                                <span><?= $value['status'] ?></span>
                                <?php $status = $value['status']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Urgency
                            </td>
                            <td class="view-tic-td">
                                <span><?= $value['urgency'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="view-tic-td" colspan="2">
                                <h3>Message:</h3><br/>
                                <span><?= $value['description'] ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <h1>Comments</h1>
        <table class="table" width = "100%">
            <tbody>
                <tr>
                    <th width="20%">
                        ADDED
                    </th>
                    <th width="20%">
                        USERNAME
                    </th>
                    <th>
                        MASSAGE
                    </th>
                </tr>
                <?php
                if (count($ticket_answer) > 0) {
                    foreach ($ticket_answer as $key => $value) {
                        ?>
                        <tr>
                            <td class="center">
                                <span><?= $value['str_posttime'] ?></span>
                            </td>
                            <td class="center">
                                <span><?= $value['user_name'] ?></span>
                            </td>
                            <td class="view-tic-td">
                                <span><?= $value['description'] ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="red bold center">
                            You don't have any answer yet.
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
        if ($status != "Closed") {
            ?>
            <form method="post" class="form narrow-cols" action="viewticket.php?common=<?= $_REQUEST['common'] ?>">
                <input type="hidden" name="validate" value="viewticketForm">
                <input type="hidden" name="ticketid" value="<?= $_REQUEST['common'] ?>">
                <div class="form-row">
                    <div class="form-label">
                        <label for="text">Message:</label>
                    </div>
                    <div class="form-field">
                        <textarea id="description" class="focus-if-empty" name="description" cols="50" rows="10" style="width: 500px;" required="required"></textarea><br>
                    </div>
                </div>
                <br>
                <div class="form-buttons">
                    <input type="submit" name="btnSend" value="Send">
                    <a class="button_link" href="viewticket.php?rem=y&common=<?= $_REQUEST['common'] ?>">Close Ticket</a>
                </div>
            </form>
        <?php } ?>
    </div>
</div>
<?php
require("./footer.php");
?>