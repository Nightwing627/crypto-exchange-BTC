<?php
require("./header.php");
//$sql = "SELECT ticketid, subject, description, department, urgency, status, isanswer, if(isanswer = 1, 'Answered', 'No Answered') answered, opneddate, date_format(opneddate, '%d-%m-%Y %H:%i:%s') str_opneddate , date_format(closeddate, '%d-%m-%Y %H:%i:%s') str_closeddate, user_id FROM `" . TABLE_TICKETS . "` ORDER BY opneddate DESC";
$sql = "SELECT ticketid, subject, description, department, urgency, status, isanswer, if(isanswer = 1, 'Answered', 'No Answered') answered, opneddate, date_format(opneddate, '%d-%m-%Y %H:%i:%s') str_opneddate , date_format(closeddate, '%d-%m-%Y %H:%i:%s') str_closeddate, u.user_id, u.user_name ".
       " FROM `" . TABLE_TICKETS . "` s inner join `" . TABLE_USERS . "` u on u.user_id = s.user_id ORDER BY opneddate DESC";
$ticket_history = $db->fetch_all_array($sql);
if ($checkLogin) {
    ?>
    <div>
        <h1>Tickets</h1>
        <table class="table" width="100%">
            <tbody>
                <tr>
                    <th>
                        SUBJECT
                    </th>
                    <th>
                        DEPARTMENT
                    </th>
                    <th>
                        USERNAME
                    </th>
                    <th>
                        URGENCY
                    </th>
                    <th>
                        STATUS
                    </th>
                    <th>
                        ANSWERED
                    </th>
                    <th>
                        OPENED DATE
                    </th>
                    <th>
                        CLOSED DATE
                    </th>
                </tr>
                <?php
                if (count($ticket_history) > 0) {
                    foreach ($ticket_history as $key => $value) {
                        ?>
                        <tr>
                            <td class="center">
                                <span><a class="ticket-link" href="viewticket.php?common=<?= $value['ticketid'] ?>"><?= $value['subject'] ?></a></span>
                            </td>
                            <td class="center">
                                <span><?= $value['department'] ?></span>
                            </td>
                            <td class="center">
                                <span><?= $value['user_name'] ?></span>
                            </td>
                            <td class="center">
                                <span><?= $value['urgency'] ?></span>
                            </td>
                            <td class="center">
                                <span><?= $value['status'] ?></span>
                            </td>
                            <td class="center">
                                <span><?= $value['answered'] ?></span>
                            </td>
                            <td class="center">
                                <span><?= $value['str_opneddate'] ?></span>
                            </td>
                            <td class="center">
                                <span><?= $value['str_closeddate'] ?></span>
                            </td>                    
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="red bold center">
                            You don't have any order yet.
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
} else {
    require("./minilogin.php");
}
require("./footer.php");
?>