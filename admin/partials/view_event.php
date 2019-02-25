<?php

$event_id = 1; // latest status

// latest events
$sqlEvent = "SELECT * FROM events WHERE id = '$event_id'";
$event_data = mysqli_query($conn, $sqlEvent);
$event_details = mysqli_fetch_row($event_data);

$name = $event_details[1];
$location = $event_details[2];
$date = $event_details[3];
$people_count = $event_details[4];
$total_cost = $event_details[5];
$user_id = $event_details[6];
$status_id = $event_details[10];

$sqlUser = "SELECT * FROM users WHERE id = '$user_id'";
$user_data = mysqli_query($conn, $sqlUser);
$user_details = mysqli_fetch_row($user_data);

$full_name = $user_details[1] . ' ' . $user_details[2];

$sqlStatus = "SELECT * FROM events_status WHERE id = '$status_id'";
$status_data = mysqli_query($conn, $sqlStatus);
$status_details = mysqli_fetch_row($status_data);

$status_name = $status_details[1];

?>

<div class="admin-view-event">
    <h3>Event Description</h3>

    <div>
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>Name</td>
                <td><?php echo $name ?></td>
            </tr>

            <tr>
                <td>Location</td>
                <td><?php echo $location ?></td>
            </tr>

            <tr>
                <td>Date</td>
                <td><?php echo $date ?></td>
            </tr>

            <tr>
                <td># of People</td>
                <td><?php echo $people_count ?></td>
            </tr>

            <tr>
                <td>Budget</td>
                <td><?php echo $total_cost ?></td>
            </tr>

            <tr>
                <td>Client</td>
                <td><?php echo $full_name ?></td>
            </tr>

            <tr>
                <td>Status</td>
                <td><?php echo $status_name ?></td>
            </tr>

        </table>
    </div>
</div>
