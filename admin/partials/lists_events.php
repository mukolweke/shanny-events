<?php
require_once '../../backend/auth.php';

$logged_user = new Auth();

echo "here";

//$latest_data = $logged_user->getEventsByStatus($status_id);

?>

<div class="latest-requests-list-form">
    <?php if (mysqli_num_rows($latest_data) > 0) { ?>
        <table style="width:100%">
            <tr>
                <th class="">Name</th>
                <th class="">Location</th>
                <th class="">Date</th>
                <th class="">Actions</th>
            </tr>

            <?php while ($array = mysqli_fetch_array($latest_data)) { ?>
                <tr>
                    <td class=""><?php echo $array['name']; ?></td>
                    <td class=""><?php echo $array['location']; ?></td>
                    <td class=""><?php echo $array['date']; ?></td>
                    <td class=" action-btns">
                        <form action="../admin/admin_page.php" method="post">
                            <input type="hidden" name="view_event" value="view">

                            <input type="hidden" name="event_id" value="<?php echo $array[0]; ?>">
                            <input type="hidden" name="status_id" value="<?php echo $array[10]; ?>">

                            <button class="btn btn-primary">View</button>
                        </form>
                    </td>
                </tr>
            <?php }; ?>
        </table>
    <?php } else { ?>
        <p>No Events Available yet</p>

    <?php } ?>
</div>