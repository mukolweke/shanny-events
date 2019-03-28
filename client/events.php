<?php

require_once('../backend/auth.php');

$logged_user = new Auth();
$message = "";
$events = "";

if ($logged_user->getAllEvents($_SESSION['id'])) {
    $events = $logged_user->getAllEvents($_SESSION['id']);
} else {
    $message = "currently no available events";
}


?>

<div class="events-content">
    <h2>My Request Events</h2>

    <p><?php echo $delete_event_error ?></p>

    <table style="width:100%">
        <tr>
            <th class="">Name</th>
            <th class="">Location</th>
            <th class="">Date</th>
            <th class=""># of People</th>
            <th class="">Budget</th>
            <th class="">Status</th>
            <th class="">Actions</th>
        </tr>

        <?php if($events) { foreach ($events as $array) { ?>
            <tr>
                <td class=""><?php echo $array['name']; ?></td>
                <td class=""><?php echo $array['location']; ?></td>
                <td class=""><?php echo $array['date']; ?></td>
                <td class=""><?php echo $array['people_count']; ?></td>
                <td class=""><?php echo $array['total_cost']; ?></td>
                <td class=""><?php echo $logged_user->statusName($array['status']); ?></td>
                <td class=" action-btns">
                    <form action="client_page.php" method="post">
                        <input type="hidden" name="event_delete" value="delete">

                        <input type="hidden" name="event_id" value="<?php echo $array['id']; ?>">

                        <button class="btn btn-delete">Delete</button>
                    </form>

                    <form action="client_page.php" method="post">
                        <input type="hidden" name="event_edit" value="edit">

                        <input type="hidden" name="event_id" value="<?php echo $array['id']; ?>">

                        <button class="btn btn-edit">Edit</button>
                    </form>
                </td>
            </tr>
        <?php }}; ?>
    </table>
</div>
