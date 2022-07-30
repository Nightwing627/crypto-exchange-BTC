<?php

$page_name = "support";
require("./header.php");

if (isset($_POST['validate']) && $_POST['validate'] == "ticketForm") {
    $ticket_add["subject"] = $_POST['subject'];
    $ticket_add["department"] = $_POST['department'];
    $ticket_add["description"] = $_POST['description'];
    $ticket_add["user_id"] = $_SESSION['user_id'];
    $ticket_add["urgency"] = 'Low';
    $ticket_add["status"] = 'Opened';
    $ticket_add["isanswer"] = '0';
    $ticket_add["opneddate"] = 'now()';
    $isInsrted = $db->query_insert(TABLE_TICKETS, $ticket_add);
}
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
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
</style>
<div id="support">
    <div class="left-menu">
        <h3>Support</h3>
        <p><a href="support">My Tickets</a></p>
        <p><a href="ticket">New Tickets</a></p>
    </div>
    <div class="right-menu">
        <h1>New Tickets</h1>
        <?php

        if ($isInsrted) {
            echo '<p class="alert alert-success">Your ticket has been opened successfully.</p>';
        }
        ?>
        <p></p>
        <form method="post" class="form narrow-cols" action="ticket">
            <input type="hidden" name="validate" value="ticketForm">
            <div class="form-row">
                <div class="form-label">
                    <label for="email">Subject* :</label>
                </div>
                <div class="form-field">
                    <input id="subject" class="focus-if-empty" name="subject" type="text" style="width: 500px;" required="required"><br>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label for="subject">Department* :</label>
                </div>
                <div class="form-field">
                    <select id="department" class="focus-if-empty" name="department"  style="width: 500px;" required="required">
                        <option value="ANY">ANY</option>
                        <option value="Sales Department">Sales Department</option>
                        <option value="Technical Support Department">Technical Support Department</option>
                    </select><br>
                </div>
            </div>
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
                <input type="submit" name="btnSend" value="Submit">
                <input type="reset" name="btnReset" value="Reset">
            </div>
        </form>
    </div>
</div>
<?php

require("./footer.php");
?>