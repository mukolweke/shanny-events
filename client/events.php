<?php
session_start();

require_once('../backend/connect.php');

$events = array();
$user_id = $_SESSION['id'];

$sql = "SELECT * FROM events WHERE user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

?>

<div class="events-content">
    <h2>Events Section</h2>

    <table style="width:100%">
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Date</th>
            <th># of People</th>
            <th>Budget</th>
            <th></th>
        </tr>

        <?php while($array = mysqli_fetch_row($result)) ?>
        <tr>
            <td><?echo $array[0];?></td>
            <td><?echo $array[1];?></td>
            <td><?echo $array[2];?></td>
            <td><?echo $array[3];?></td>
            <td><?echo $array[4];?></td>
            <td><?echo $array[5];?></td>
        </tr>
        <?php ;?>

        <?php mysqli_free_result($result); ?>
        <?php mysqli_close($conn); ?>
    </table>
</div>
