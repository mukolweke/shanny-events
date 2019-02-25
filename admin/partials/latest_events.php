<?php

$status_id = 3; // latest status

// latest events
$sql = "SELECT * FROM events WHERE status = '$status_id' AND deleted_at IS NULL OR deleted_at = ''";

$latest_data = mysqli_query($conn, $sql);

?>

<div class="latest-requests-list-form">

    <table style="width:100%">
        <tr>
            <th class="">Name</th>
            <th class="">Location</th>
            <th class="">Date</th>
            <th class="">Actions</th>
        </tr>

        <?php while ($array = mysqli_fetch_row($latest_data)) { ?>
            <tr>
                <td class=""><?php echo $array[1]; ?></td>
                <td class=""><?php echo $array[2]; ?></td>
                <td class=""><?php echo $array[3]; ?></td>
                <td class=" action-btns">
                    <form action="" method="post">
                        <input type="hidden" name="event_edit" value="edit">

                        <input type="hidden" name="event_id" value="<?php echo $array[0]; ?>">

                        <button class="btn btn-primary">View</button>
                    </form>

                    <form action="" method="post">
                        <input type="hidden" name="event_delete" value="delete">

                        <input type="hidden" name="event_id" value="<?php echo $array[0]; ?>">

                        <button class="btn btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php }; ?>
    </table>
</div>