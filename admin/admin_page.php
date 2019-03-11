<?php
session_start();

require_once "../backend/connect.php";

// Check if the user is logged in;
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../auth/login.php");
    exit;
}

$active_page = 'events';
$latest_action = true;
$status_id = 3;
$ongoing_action = $completed_action = $rejected_action = $view_event = $sub_task_form = false;
$add_funds_request = $edit_task = $delete_panel = false;
$event_action_error = $event_action_success = $delete_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // change active pages
    if (isset($_POST['page'])) {
        $page = $_POST['page'];

        if ($page == 'events') {
            $active_page = 'events';
        } elseif ($page == 'clients') {
            $active_page = 'clients';
        } elseif ($page == 'profile') {
            $active_page = 'profile';
        }
    }

    // admin page actions
    if (isset($_POST['admin_page_action'])) {
        $action = $_POST['admin_page_action'];

        if ($action == "latest") {
            $completed_action = $ongoing_action = $rejected_action = false;
            $latest_action = true;
            $status_id = 3;
        } elseif ($action == "ongoing") {
            $latest_action = $completed_action = $rejected_action = false;
            $ongoing_action = true;
            $status_id = 2;
        } elseif ($action == "completed") {
            $latest_action = $ongoing_action = $rejected_action = false;
            $completed_action = true;
            $status_id = 1;
        } elseif ($action == "rejected") {
            $latest_action = $ongoing_action = $completed_action = false;
            $rejected_action = true;
            $status_id = 4;
        }
    }

    // view event
    if (isset($_POST['view_event'])) {
        $active_page = 'events';
        $view_event = true;
        $event_id = $_POST['event_id'];
        $status_id = $_POST['status_id'];

        if ($status_id == 3) {
            $completed_action = $ongoing_action = $rejected_action = false;
            $latest_action = true;
        } elseif ($status_id == 2) {
            $latest_action = $completed_action = $rejected_action = false;
            $ongoing_action = true;
        } elseif ($status_id == 1) {
            $latest_action = $ongoing_action = $rejected_action = false;
            $completed_action = true;
        } elseif ($status_id == 4) {
            $latest_action = $ongoing_action = $completed_action = false;
            $rejected_action = true;
        }
    }

    // view_event_action
    if (isset($_POST['view_event_action'])) {
        $action = $_POST['view_event_action'];
        $event_id = $_POST['event_id'];
        $active_page = 'events';

        if ($action == 'accept') {
            $sql = "UPDATE events SET status = 2 WHERE id = '$event_id'";

            if (mysqli_query($conn, $sql)) {
                $active_page = 'events';
                $completed_action = $ongoing_action = $rejected_action = false;
                $latest_action = true;
                $status_id = 1;
                $event_action_success = "Event status successfully upgraded";
            } else {
                $active_page = 'events';
                $completed_action = $ongoing_action = $rejected_action = false;
                $latest_action = true;
                $status_id = 1;
                $event_action_error = "Something went wrong. Please try again later.";
            }
        } elseif ($action == 'reject') {
            $sql = "UPDATE events SET status = 4 WHERE id = '$event_id'";

            if (mysqli_query($conn, $sql)) {
                $active_page = 'events';
                $completed_action = $ongoing_action = $rejected_action = false;
                $latest_action = true;
                $status_id = 1;
                $event_action_success = "Event status successfully rejected";
            } else {
                $active_page = 'events';
                $completed_action = $ongoing_action = $rejected_action = false;
                $latest_action = true;
                $status_id = 1;
                $event_action_error = "Something went wrong. Please try again later.";
            }
        } elseif ($action == 'done') {
            $sql = "UPDATE events SET status = 1 WHERE id = '$event_id'";

            if (mysqli_query($conn, $sql)) {
                $active_page = 'events';
                $latest_action = $completed_action = $rejected_action = false;
                $ongoing_action = true;
                $status_id = 2;
                $event_action_success = "Event successfully marked done";
            } else {
                $active_page = 'events';
                $latest_action = $completed_action = $rejected_action = false;
                $ongoing_action = true;
                $status_id = 2;
                $event_action_error = "Something went wrong. Please try again later.";
            }
        } elseif ($action = 'view_sub_task') {
            $active_page = 'events';
            $latest_action = $completed_action = $rejected_action = false;
            $ongoing_action = true;
            $status_id = 2;
            $view_event = true;
            $event_id = $_POST['event_id'];
            $sub_task_form = true;
        }
    }

    // sub-task actions
    if (isset($_POST['event_sub_task_actions'])) {
        $event_task_action = $_POST['event_sub_task_actions'];

        if ($event_task_action == 'add_sub_task') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $cost = $_POST['cost'];
            $event = $_POST['event_id'];
            $task_sum = $_POST['task_sum'] + $cost;

            $sqlEvent = "SELECT * FROM events WHERE id = '$event'";
            $event_data = mysqli_query($conn, $sqlEvent);
            $event_details = mysqli_fetch_row($event_data);

            // check if budget is passed
            if ($task_sum < $event_details[5]) {
                $sql = "INSERT INTO events_task (name, description, cost, event_id) VALUES ('$name', '$description', '$cost', '$event')";

                if (mysqli_query($conn, $sql)) {
                    $active_page = 'events';
                    $latest_action = $completed_action = $rejected_action = false;
                    $ongoing_action = true;
                    $status_id = 2;
                    $view_event = true;
                    $event_id = $event;
                    $sub_task_form = false;
                    $event_action_success = "Event Task successfully added";
                } else {
                    $active_page = 'events';
                    $ongoing_action = true;
                    $status_id = 2;
                    $latest_action = $completed_action = $rejected_action = false;
                    $view_event = true;
                    $event_id = $event;
                    $sub_task_form = false;
                    $event_action_error = "Something went wrong. Please try again later.";
                }
            } else {
                $active_page = 'events';
                $latest_action = $completed_action = $rejected_action = false;
                $ongoing_action = true;
                $status_id = 2;
                $view_event = true;
                $event_id = $event;
                $add_funds_request = true;
                $sub_task_form = false;
                $event_action_error = "The Budget limit has reached. Please add or edit current budget to spend";
            }

        } else if ($event_task_action == 'request_add_funds') {
            $to = $_POST['client_id'];
            $from = $_SESSION['id'];
            $message = "Additional Funds requested for this event";
            $event = $_POST['event_id'];


            $sqlAddFunds = "INSERT INTO notifications (from_id, to_id, message, event, status) values ('$from', '$to', '$message', '$event', 1)";

            if (mysqli_query($conn, $sqlAddFunds)) {
                $active_page = 'events';
                $latest_action = $completed_action = $rejected_action = false;
                $ongoing_action = true;
                $status_id = 2;
                $view_event = true;
                $event_id = $event;
                $add_funds_request = false;
                $sub_task_form = false;
                $event_action_success = "Event Notification sent successfully to client";
            } else {
                $active_page = 'events';
                $latest_action = $completed_action = $rejected_action = false;
                $ongoing_action = true;
                $status_id = 2;
                $view_event = true;
                $event_id = $event;
                $add_funds_request = true;
                $sub_task_form = false;
                $event_action_error = "Notification not sent...";
            }
        } else if ($event_task_action == 'show_edit_task') {
            $active_page = 'events';
            $latest_action = $completed_action = $rejected_action = false;
            $ongoing_action = true;
            $status_id = 2;
            $view_event = true;
            $event_id = $_POST['event_id'];
            $task_id = $_POST['task_id'];
            $edit_task = true;
            $sub_task_form = false;
        }
    }

    // admin clients module action
    if (isset($_POST['delete_client'])) {
        $action = $_POST['delete_client'];
        $user_id = $_POST['user_id'];

        if ($action == 'show_panel') {
            $active_page = 'clients';
            $delete_panel = true;

            $userSql = "SELECT * FROM users WHERE id='$user_id'";

            $user_data = mysqli_query($conn, $userSql);
            $user_details = mysqli_fetch_row($user_data);
        } elseif ($action == 'cancel_delete') {
            $active_page = 'clients';
            $delete_panel = false;
            $delete_message = "Client Delete action canceled";
        } elseif ($action == 'delete_client') {
            $active_page = 'clients';
            $delete_panel = false;
            $deleted_date = date("Y/m/d"); // today's date

            $sqlDel = "UPDATE users SET deleted_at = '$deleted_date' WHERE id='$user_id'";

            if (mysqli_query($conn, $sqlDel)) {
                $delete_message = "Client Delete action success";
            } else {
                $delete_message = "Something went wrong. Please try again later.";
            }
        }

    }

    // profile actions
    if (isset($_POST['show_edit_profile'])) { // show edit profile page
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

        $sql = "UPDATE users SET first_name = '$fname', last_name = '$lname', email = '$mail', phone = '$phone' WHERE id='$user_id'";

        if (mysqli_query($conn, $sql)) {
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

        $sql = "SELECT password FROM users WHERE id = '$user_id'";

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

            if ($stmt = mysqli_prepare($conn, $sql)) {
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $hashed_pass);

                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($p_pass, $hashed_pass)) {
                                $sql = "UPDATE users SET password = '$new_pass' WHERE id='$user_id'";

                                if (mysqli_query($conn, $sql)) {
                                    $active_page = "profile";
                                    $edit_password = false;
                                    $success_message = "Profile Password updates successfully";

                                } else {
                                    $active_page = "profile";
                                    $edit_password = true;
                                    $delete_error = "Something went wrong. Please try again later.";
                                }
                            } else {
                                $edit_password = true;
                                $active_page = "profile";
                                $delete_error = "Password doesn't match. Try Again.";
                            }
                        }
                    }
                } else {
                    $edit_password = true;
                    $active_page = "profile";
                    $delete_error = "cjui.";
                }
            }
        }
    } elseif (isset($_POST['delete_account'])) { // delete account
        $user_id = $_POST['user_id'];
        $deleted_date = date("Y/m/d"); // today's date

        $sql = "UPDATE users SET deleted_at = '$deleted_date' WHERE id='$user_id'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION = array();
            session_destroy();

            header("location: ../index.php");
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
        Admin Page
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

        <?php if (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)
            echo $_SESSION["email"];
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
                <form action="admin_page.php" method="post">
                    <input type="hidden" name="page" value="events">

                    <button class="btn btn-menu-side" type="submit">Events</button>
                </form>
                <br>
                <br>
                <form action="admin_page.php" method="post">
                    <input type="hidden" name="page" value="clients">

                    <button class="btn btn-menu-side" type="submit">Clients</button>
                </form>
                <br>
                <br>
                <form action="admin_page.php" method="post">
                    <input type="hidden" name="page" value="profile">

                    <button class="btn btn-menu-side" type="submit">My Profile</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <?php if ($active_page == 'events') {
                include "events.php";
            } elseif ($active_page == 'clients') {
                include "clients.php";
            } elseif ($active_page == 'profile') {
                include "profile.php";
            } ?>
        </div>
    </div>
</div>
</body>
</html>
