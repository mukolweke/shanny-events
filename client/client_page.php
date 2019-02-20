<?php
session_start();

require_once('../backend/connect.php');

// Check if the user is logged in;
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../auth/login.php");
    exit;
}

$active_page = 'events';
$form_active = false;
$event_name = $event_location = $event_date = $event_people = $event_costs = "";
$event_form_error = $form_submitted = $delete_event_error = $event_date_edit = "";
$event_id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['page'] === 'events') {
        $active_page = 'events';
    } elseif ($_POST['page'] === 'profile') {
        $active_page = 'profile';
    } elseif ($_POST["form_active"]) {
        $form_active = true;
    } elseif ($_POST['event_form']) { //save new details
        if (
            empty(trim($_POST['event_name'])) &&
            empty(trim($_POST['event_location'])) &&
            empty(trim($_POST['event_date'])) &&
            empty(trim($_POST['event_people'])) &&
            empty(trim($_POST['event_costs']))
        ) {
            $form_active = true;

            $event_form_error = "Please fill every field correctly";
        } else {
            $form_active = true;

            $event_name = trim($_POST['event_name']);
            $event_location = trim($_POST['event_location']);
            $event_date = trim($_POST['event_date']);
            $event_people = trim($_POST['event_people']);
            $event_costs = trim($_POST['event_costs']);


            //check if its edit or new event submitted
            if (trim($_POST['form_submitted']) == "form_new") {
                $sql = "INSERT INTO events (name, location, date, people_count, total_cost, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param(
                        $stmt, "sssissi",
                        $param_name,
                        $param_location,
                        $param_date,
                        $param_people,
                        $param_cost,
                        $param_status,
                        $param_user
                    );

                    $param_name = $event_name;
                    $param_location = $event_location;
                    $param_date = $event_date;
                    $param_people = $event_people;
                    $param_cost = $event_costs;
                    $param_status = 'Un-Booked';
                    $param_user = $_SESSION['id'];

                    if (mysqli_stmt_execute($stmt)) {

                        header("location: client_page.php");

                        $form_active = false;
                    } else {
                        $form_error = "Something went wrong. Please try again later.";

                        $form_active = true;
                    }
                }
            } else {
                $event_id = $_POST['event_id'];

                if (empty($event_date)) {
                    $event_date_edit = $_POST['event_date_edit'];
                }else {
                    $event_date_edit = $event_date;
                }
                
                $sql = "UPDATE events SET name = '$event_name', location = '$event_location', date = '$event_date_edit', people_count = '$event_people', total_cost = '$event_costs' WHERE id = '$event_id'";

                echo(mysqli_query($conn, $sql));

                if (mysqli_query($conn, $sql)) {
                    header("location: client_page.php");
                } else {
                    $event_form_error = "Something went wrong. Please try again later.";
                }
            }


        }
    } elseif ($_POST['event_delete']) { //soft delete existing details
        $event_id = $_POST['event_id'];
        $deleted_date = date("Y/m/d"); // today's date

        $sql = "UPDATE events SET deleted_at = '$deleted_date' WHERE id='$event_id'";

        if (mysqli_query($conn, $sql)) {
            header("location: client_page.php");
        } else {
            $delete_event_error = "Something went wrong. Please try again later.";
        }

    } elseif ($_POST['event_edit']) { // edit existing details
        $form_active = true;
        $event_id = $_POST['event_id'];

        $sql = "SELECT name, location, date, people_count, total_cost FROM events WHERE id = '$event_id'";

        if ($stmt = mysqli_prepare($conn, $sql)) {

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $event_name, $event_location, $event_date, $event_people, $event_costs);

                    if (mysqli_stmt_fetch($stmt)) {
                        $form_submitted = "edit";
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>
        Client Page
    </title>
    <link rel="stylesheet" href="../styles/main.css">
</head>
<body>
<div class="topnav">
    <div class="container">
        <a href="../index.php">Home</a>

        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
            echo '<a href="../auth/login.php">Login</a>'
        ?>

        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
            echo '<a href="../auth/register.php">Register</a>'
        ?>

        <?php if ((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) && $_SESSION['user_type'] == 1)
            echo '<a href="../admin/admin_page.php">Admin Dash</a>'
        ?>

        <?php if ((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) && $_SESSION['user_type'] == 2)
            echo '<a href="../client/client_page.php">Client Dash</a>'
        ?>

        <?php if (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)
            echo '<a href="../auth/logout.php">Logout</a>'
        ?>
    </div>
</div>

<div class="container">
    <div class="dash-body">
        <div class="side-menu">
            <div id="mySidenav" class="sidenav">
                <form action="client_page.php" method="post">
                    <input type="hidden" name="page" value="events">

                    <button class="btn btn-menu-side" type="submit">Events</button>
                </form>
                <br>
                <br>
                <form action="client_page.php" method="post">
                    <input type="hidden" name="page" value="profile">

                    <button class="btn btn-menu-side" type="submit">Profile</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <?php
            if ($active_page == 'events') {
                ?>
                <h2>Events Section</h2>

                <?php if (!$form_active) { ?>
                    <form action="client_page.php" method="post">
                        <input type="hidden" name="form_active" value="true">

                        <button class="btn btn-primary" type="submit">Request Event</button>
                    </form>
                <?php }
            ; ?>


                <br>

                <?php

                if ($form_active) {
                    include "event_form.php";
                }

                include 'events.php';

            } else {
                include 'profile.php';
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>


