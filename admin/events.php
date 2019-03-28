<?php
require_once "../backend/auth.php";

$logged_user = new Auth();

if ($logged_user->getEventsByStatus($status_id)) {
    $events = $logged_user->getEventsByStatus($status_id);
} else {
    $message = "currently no available events";
}

// get the events
$event_details = $logged_user->viewEventDetails($event_id);
$id = $event_details['id'];
$name = $event_details['name'];
$location = $event_details['location'];
$date = $event_details['date'];
$people_count = $event_details['people_count'];
$total_cost = $event_details['total_cost'];
$user_id = $event_details['user_id'];
$status_id = $event_details['status'];
$total_bal = intval($event_details['total_bal']);

// get the user details: name
$user_details = $logged_user->getClientInformation($user_id);
$full_name = $user_details['first_name'] . ' ' . $user_details['last_name'];

// get the event status name
$status_details = $logged_user->getStatusDetails($status_id);
$status_name = $status_details['name'];

// get the sub-task details for a given event.
$events_task_data = $logged_user->getEventsSubTask($id);

$sum = 0;
$event_balance = 0;

function setSum($value)
{
    global $sum;

    $sum += $value;

    updateBalance($sum);
}

function updateBalance($val)
{
    global $id, $conn, $total_cost;

    $event_balance = $total_cost - $val;

    $sqlBal = "UPDATE events SET total_bal = '$event_balance' WHERE id='$id'";

    mysqli_query($conn, $sqlBal);
}

?>

<div class="events-admin">
    <h2>Events Section</h2>

    <div class="admin-page-actions">
        <form action="admin_page.php" method="post">
            <input type="hidden" name="admin_page_action" value="latest">

            <button class="btn btn-primary">Latest Request</button>
        </form>
        <form action="admin_page.php" method="post">
            <input type="hidden" name="admin_page_action" value="ongoing">

            <button class="btn btn-success">Ongoing Request</button>
        </form>
        <form action="admin_page.php" method="post">
            <input type="hidden" name="admin_page_action" value="completed">

            <button class="btn btn-edit">Completed Request</button>
        </form>
        <form action="admin_page.php" method="post">
            <input type="hidden" name="admin_page_action" value="rejected">

            <button class="btn btn-delete">Rejected Request</button>
        </form>
    </div>

    <div class="event-requests">
        <?php if ($latest_action) { ?>
            <div class="latest-requests">

                <div class="latest-requests-list">
                    <h3>Latest Request</h3>
                    <p style="color: green;"><?php echo $event_action_success; ?></p>
                    <p style="color: red;"><?php echo $event_action_error; ?></p>
                    <br>
                    <?php include "partials/lists_events.php"; ?>
                    <br>
                    <?php if ($view_event) {
                        include "partials/view_event.php";
                    } ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($ongoing_action) { ?>
            <div class="ongoing-requests">
                <div class="ongoing-requests-list">
                    <h3>Ongoing Request</h3>
                    <p style="color: green;"><?php echo $event_action_success; ?></p>
                    <p style="color: red;"><?php echo $event_action_error; ?></p>
                    <br>
                    <?php include "partials/lists_events.php"; ?>
                    <br>
                    <?php if ($view_event) {
                        include "partials/view_event.php";
                    } ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($completed_action) { ?>
            <div class="completed-requests">
                <div class="completed-requests-list">
                    <h3>Completed Request</h3>
                    <p style="color: green;"><?php echo $event_action_success; ?></p>
                    <p style="color: red;"><?php echo $event_action_error; ?></p>
                    <br>
                    <?php include "partials/lists_events.php"; ?>
                    <br>
                    <?php if ($view_event) {
                        include "partials/view_event.php";
                    } ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($rejected_action) { ?>
            <div class="completed-requests">
                <div class="completed-requests-list">
                    <h3>Rejected Request</h3>
                    <p style="color: green;"><?php echo $event_action_success; ?></p>
                    <p style="color: red;"><?php echo $event_action_error; ?></p>
                    <br>
                    <?php include "partials/lists_events.php"; ?>
                    <br>
                    <?php if ($view_event) {
                        include "partials/view_event.php";
                    } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>