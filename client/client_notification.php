<?php
require_once "../backend/auth.php";

$client = new Auth();

$notifications = $client->getNotifications($_SESSION['id']);

function getFromDetails($userId)
{
    global $client;
    $details = $client->getClientInformation($userId);
    return $details['first_name'];
}

function getEventDetails($eventId)
{
    global $client;
    $details = $client->viewEventDetails($eventId);
    return $details['name'];
}

?>

<div class="profile-page">
    <h1 class="profile-title">Notifications</h1>
<?php if ($notifications) { ?>
    <?php if (sizeof($notifications) > 0) { ?>

        <table style="width:100%">
            <tr>
                <th class="">From</th>
                <th class="">Message</th>
                <th class="">Event</th>
                <th class="">Actions</th>
            </tr>

            <?php foreach ($notifications as $array) { ?>
                <tr>
                    <td class=""><?php echo getFromDetails($array['from_id']) ?></td>
                    <td class=""><?php echo $array['message']; ?></td>
                    <td class=""><?php echo getEventDetails($array['event']) ?></td>
                    <td class=" action-btns">
                        <form action="./client_page.php" method="post">
                            <input type="hidden" name="notification_event" value="view_add_amount">

                            <input type="hidden" name="notification_id" value="<?php echo $array['id']; ?>">
                            <input type="hidden" name="event_id" value="<?php echo $array['event']; ?>">

                            <button class="btn btn-primary">Action</button>
                        </form>
                    </td>
                </tr>
            <?php }; ?>
        </table>
    <?php } else { ?>
        <p>No new notifications</p>
    <?php }} ?>

    <?php if ($add_amount_form) { ?>
        <h3>Add Amount</h3>

        <p><b>Event Name: </b><?php echo $event_name ?></p>
        <p><b>Event Budget:</b> <?php echo $event_cost ?></p>
        <p><b>Event Balance: </b><?php echo $event_balance ?></p>

        <!-- form to add the tasks -->
        <form id="edit_form" action="./client_page.php" method="post">
            <div class="edit-form-group">
                <label for="cost">Amount</label>
                <input type="number" tabindex="3" id="amount" name="amount" required>
            </div>

            <input type="hidden" name="notification_id" value="<?php echo $notification_id; ?>">
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <input type="hidden" name="event_cost" value="<?php echo $event_cost; ?>">

            <input type="hidden" name="notification_event" value="add_amount">

            <div class="edit-form-group">
                <button name="submit" type="submit" id="contact-submit" class="edit_form_btn"
                        data-submit="...Sending">Add Amount
                </button>
            </div>
        </form>

        <h3>Else</h3>

        <form id="edit_form" action="./client_page.php" method="post">
            <input type="hidden" name="notification_event" value="mark_as_read">

            <input type="hidden" name="notification_id" value="<?php echo $array['id']; ?>">

            <button class="btn btn-primary">Mark as Read</button>
        </form>

    <?php } ?>

</div>
