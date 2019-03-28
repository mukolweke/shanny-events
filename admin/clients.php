<?php
$eventCount = 0;
function getUserEventsCount($user_id)
{
    global $eventCount;
    global $logged_user;

    $eventCount = $logged_user->countUserEvents($user_id);

    if ($eventCount == 0) {
        echo "None";
    } else {
        echo $eventCount . " event(s)";
    }
}

$user_type = 2;

$clients_data = $logged_user->getAllUsersByType($user_type);

?>

<div>
    <h1>Clients Section</h1>

    <div>
        <h3>Clients List</h3>

        <div class="btn">
            <form action="../backend/export.php" method="post">
                <input type="hidden" name="export_action" value="export_clients">

                <button type="submit" id="btnExport" name='export_clients'
                        value="Export to Excel" class="btn btn-success">Export to
                    excel
                </button>
            </form>
        </div>

        <br>

        <p style="color: green"><?php echo $delete_message; ?></p>

        <div>
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>No. of Events</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($clients_data as $array) { ?>
                    <tr>
                        <td class=""><?php echo $array['first_name'] . ' ' . $array['last_name']; ?></td>
                        <td class=""><?php echo $array['email']; ?></td>
                        <td class=""><?php echo $array['phone']; ?></td>
                        <td class=""><?php getUserEventsCount($array['id']); ?></td>
                        <td class=" action-btns">
                            <form action="../admin/admin_page.php" method="post">
                                <input type="hidden" name="delete_client" value="show_panel">

                                <input type="hidden" name="user_id" value="<?php echo $array['id']; ?>">

                                <button class="btn btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php }; ?>

            </table>

        </div>

        <br>

        <?php if ($delete_panel) { ?>
            <div>
                <form id="edit_form" action="admin_page.php" method="post">
                    <h3>Delete Details</h3>

                    <h4 style="color: red;">Are you sure you want to delete clients account details</h4>

                    <p>Name: <?php echo $user_details['first_name'] . ' ' . $user_details['last_name'] ?></p>
                    <p>Email: <?php echo $user_details['email']; ?></p>
                    <p>Phone: <?php echo $user_details['phone']; ?></p>

                    <div class="edit-form-group">

                        <form action="admin_page.php" method="post" style="float: left;width: 50%;">
                            <input type="hidden" name="delete_client" value="cancel_delete">

                            <button name="submit" type="submit" id="contact-submit" class="cancel_form_btn"
                                    data-submit="...Sending">NO
                            </button>
                        </form>

                        <form action="admin_page.php" method="post" style="float: left;width: 50%;">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                            <input type="hidden" name="delete_client" value="delete_client">

                            <button name="submit" type="submit" id="contact-submit" class="delete_form_btn"
                                    data-submit="...Sending">YES
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
