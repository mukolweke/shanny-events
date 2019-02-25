<?php
session_start();

require_once "../backend/connect.php";

// Check if the user is logged in;
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$active_page = 'events';
$latest_action = $ongoing_action = $completed_action = false;

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
            $latest_action = true;
        } elseif ($action == "ongoing") {
            $ongoing_action = true;
        } elseif ($action == "completed") {
            $completed_action = true;
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
            echo $_SESSION["name"];
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
            <?php if ($active_page == 'events') { ?>
                <?php include "events.php"; ?>
            <?php } elseif ($active_page == 'clients') { ?>
                <h2>Clients Section</h2>
            <?php } elseif ($active_page == 'profile') { ?>
                <h2>Profile Page</h2>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
