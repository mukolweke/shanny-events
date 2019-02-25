<?php
session_start();

require_once('../backend/connect.php');

$user_id = $_SESSION['id'];

$sql = "SELECT * FROM events WHERE user_id = '$user_id' AND deleted_at IS NULL OR deleted_at = ''";

$data = mysqli_query($conn, $sql);

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

        <?php while ($array = mysqli_fetch_row($data)) { ?>
            <tr>
                <td class=""><?php echo $array[1]; ?></td>
                <td class=""><?php echo $array[2]; ?></td>
                <td class=""><?php echo $array[3]; ?></td>
                <td class=""><?php echo $array[4]; ?></td>
                <td class=""><?php echo $array[5]; ?></td>
                <td class=""><?php echo $array[6]; ?></td>
                <td class=" action-btns">
                    <form action="client_page.php" method="post">
                        <input type="hidden" name="event_delete" value="delete">

                        <input type="hidden" name="event_id" value="<?php echo $array[0]; ?>">

                        <button class="btn btn-delete">Delete</button>
                    </form>

                    <form action="client_page.php" method="post">
                        <input type="hidden" name="event_edit" value="edit">

                        <input type="hidden" name="event_id" value="<?php echo $array[0]; ?>">

                        <button class="btn btn-edit">Edit</button>
                    </form>
                </td>
            </tr>
        <?php }; ?>
    </table>
</div>

<?php mysqli_free_result($data); ?>
<?php mysqli_close($conn); ?>

