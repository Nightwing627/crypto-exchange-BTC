<?php
$page_name = "support";
require("./header.php");

$sql = "SELECT ticketid, subject, description, department, urgency, status, isanswer, if(isanswer = 1, 'Answered', 'No Answered') answered, opneddate, date_format(opneddate, '%d-%m-%Y %H:%i:%s') str_opneddate , date_format(closeddate, '%d-%m-%Y %H:%i:%s') str_closeddate, user_id FROM `" . TABLE_TICKETS . "` WHERE user_id = '" . $_SESSION['user_id'] . "' ORDER BY opneddate DESC";
$ticket_history = $db->fetch_all_array($sql);
?>
<style>
    #support {
        width:100%;
        display:inline-block;
    }

    #support .left-menu{
        border-right: 1px solid #eee;
        width:10%;
        float: left;
        display:inline-block;
    }
    #support .right-menu{
        width:80%;
        display:inline-block;
        margin-left: 10px;
    }
    #support .ticket-link{
        font-weight: bold;
        font-size: 12px;
        color: #084482 !important;
    }
</style>
<div id="support">
    <div class="left-menu">
        <h3>Support</h3>
        <p><a href="ticket">New Ticket</a></p>
        <p><a href="support">My Tickets</a></p>
        
    </div>
    <div class="right-menu">
        <h1>My Tickets</h1>
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
                                <span><a class="ticket-link" href="viewticket?common=<?= $value['ticketid'] ?>"><?= $value['subject'] ?></a></span>
                            </td>
                            <td class="center">
                                <span><?= $value['department'] ?></span>
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
                            You don't have any ticket yet.
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
require("./footer.php");
?>