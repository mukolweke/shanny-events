<div class="latest-requests-list-form">
    <?php if (sizeof($events) > 0) { ?>

        <form action="../../backend/export.php" method="post">
            <input type="hidden" name="export_action" value="print_events">

            <input type="hidden" name="status_id" value="<?php echo $status_id; ?>">

            <button class="btn" style="color: #fff;background-color: #f0ad4e;border-color: #eea236;">PRINT EVENTS</button>
        </form>
        <br>
        <hr>

        <table style="width:100%">
            <tr>
                <th class="">Name</th>
                <th class="">Location</th>
                <th class="">Date</th>
                <th class="">Actions</th>
            </tr>

            <?php foreach ($events as $array) { ?>
                <tr>
                    <td class=""><?php echo $array['name']; ?></td>
                    <td class=""><?php echo $array['location']; ?></td>
                    <td class=""><?php echo $array['date']; ?></td>
                    <td class=" action-btns">
                        <form action="../admin/admin_page.php" method="post">
                            <input type="hidden" name="view_event" value="view">

                            <input type="hidden" name="event_id" value="<?php echo $array['id']; ?>">
                            <input type="hidden" name="status_id" value="<?php echo $array['status']; ?>">

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