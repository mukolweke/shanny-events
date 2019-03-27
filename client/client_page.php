<?php
session_start();

require_once '../backend/auth.php';

$logged_user = new Auth();

if (!$logged_user->is_logged_in()) {
    $logged_user->redirect('../index.php');
} else {
    if ($_SESSION['user_type'] == 1) {
        $logged_user->redirect('../admin/admin_page.php');
    }
}

$active_page = 'events';
$form_active = $edit_profile = $edit_password = $delete_account = false;
$event_name = $event_location = $event_date = $event_people = $event_costs = $delete_error = "";
$event_form_error = $form_submitted = $delete_event_error = $event_date_edit = $success_message = "";
$event_id = $notification_count = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['page']) && $_POST['page'] === 'events') {
        $active_page = 'events';
    } elseif (isset($_POST['page']) && $_POST['page'] === 'profile') {
        $active_page = 'profile';
    } elseif (isset($_POST['page']) && $_POST['page'] === 'notification') {
        $active_page = 'notification';
    } elseif (isset($_POST["form_active"])) {
        $form_active = true;
    } elseif (isset($_POST['event_form'])) { //save new details
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
                    $param_status = 3; // latest event
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
                } else {
                    $event_date_edit = $event_date;
                }

                $sql = "UPDATE events SET name = '$event_name', location = '$event_location', date = '$event_date_edit', people_count = '$event_people', total_cost = '$event_costs' WHERE id = '$event_id'";

                if (mysqli_query($conn, $sql)) {
                    header("location: client_page.php");
                } else {
                    $event_form_error = "Something went wrong. Please try again later.";
                }
            }


        }
    } elseif (isset($_POST['event_delete'])) { //soft delete existing details
        $event_id = $_POST['event_id'];
        $deleted_date = date("Y/m/d"); // today's date

        $sql = "UPDATE events SET deleted_at = '$deleted_date' WHERE id='$event_id'";

        if (mysqli_query($conn, $sql)) {
            header("location: client_page.php");
        } else {
            $delete_event_error = "Something went wrong. Please try again later.";
        }

    } elseif (isset($_POST['event_edit'])) { // edit existing details
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
    } elseif (isset($_POST['show_edit_profile'])) { // show edit profile page
        $edit_profile = true;
        $active_page = "profile";

    } elseif (isset($_POST['show_edit_password'])) { // show edit password page
        $edit_password = true;
        $active_page = "profile";

    } elseif (isset($_POST['show_delete_account'])) { // show delete account page
        $delete_account = true;
        $active_page = "profile";

    } elseif (isset($_POST['cancel_delete_account'])) { // cancel delete account page
        $delete_account = false;
        $active_page = "profile";

    } elseif (isset($_POST['edit_profile_details'])) { // edit profile
        $user_id = $_POST['user_id'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $mail = $_POST['email'];
        $phone = $_POST['phone'];

        if ($logged_user->editClientInformation($user_id, $fname, $lname, $mail, $phone)) {
            $edit_profile = false;
            $active_page = "profile";
            $success_message = "Profile Details updates successfully";

        } else {
            $edit_profile = true;
            $active_page = "profile";
            $delete_error = "Something went wrong. Please try again later.";
        }
    } elseif (isset($_POST['edit_profile_password'])) { //edit password
        $user_id = $_POST['user_id'];
        $p_pass = $_POST['password_previous'];
        $n_pass = $_POST['password_new'];
        $n_pass_conf = $_POST['password_new_confirm'];


        if (strlen(trim($n_pass)) < 6 && strlen(trim($n_pass_conf)) < 6 && strlen(trim($p_pass)) < 6) {
            $edit_password = true;
            $active_page = "profile";
            $delete_error = "The passwords provided are less than 6 characters.";

        } elseif (empty($delete_error) && ($n_pass !== $n_pass_conf)) {
            $edit_password = true;
            $active_page = "profile";
            $delete_error = "The new passwords don't match, try again.";

        } else {

            $new_pass = password_hash($n_pass, PASSWORD_DEFAULT);

            if ($logged_user->editUserPassword($user_id, $p_pass, $new_pass)) {
                $active_page = "profile";
                $edit_password = false;
                $success_message = "Profile Password updates successfully";
            } else {
                $active_page = "profile";
                $edit_password = true;
                $delete_error = "Wrong Previous Password. Please try again later.";
            }

        }
    } elseif (isset($_POST['delete_account'])) { // delete account

        if ($logged_user->deleteAccount($_POST['user_id'])) {
            $_SESSION = array();
            session_destroy();

            $logged_user->redirect('../index.php');

        } else {
            $delete_error = "Something went wrong. Please try again later.";
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

                    <button class="btn btn-menu-side" type="submit">My Events</button>
                </form>
                <br>
                <br>
                <form action="client_page.php" method="post">
                    <input type="hidden" name="page" value="profile">

                    <button class="btn btn-menu-side" type="submit">My Profile</button>
                </form>
                <br>
                <br>
                <form action="client_page.php" method="post">
                    <input type="hidden" name="page" value="notification">

                    <button class="btn btn-menu-side" type="submit">
                        Notification <?php echo $notification_count ?></button>
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
                <?php } ?>

                <br>

                <?php

                if ($form_active) {
                    include "event_form.php";
                }

                include 'events.php';

            } elseif ($active_page == 'profile') {
                include 'profile.php';
            } elseif ($active_page == 'notification') {
                include 'client_notification.php';
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>


