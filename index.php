<?php

session_start();

?>


<!DOCTYPE html>
<html>
<head>
    <title>Shanny's Events System</title>

    <link rel="stylesheet" href="styles/main.css">

</head>
<body>
<div class="topnav">
    <div class="container"><a class="active" href="./index.php">Home</a>
        <?php if (empty($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
            echo '<a href="auth/login.php">Login</a>'
        ?>

        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
            echo '<a href="auth/register.php">Register</a>'
        ?>

        <?php if ((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) && $_SESSION['user_type'] == 1)
            echo '<a href="admin/admin_page.php">Admin Dash</a>'
        ?>

        <?php if ((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) && $_SESSION['user_type'] == 2)
            echo '<a href="client/client_page.php">Client Dash</a>'
        ?>

        <?php if (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)
            echo '<a href="auth/logout.php">Logout</a>'
        ?>
    </div>
</div>


<div class="backdrop">
    <div class="banner-text">
        <h1>Shanny's Events</h1>
        <p>We do events that will make awesome memories</p>
        <a href="auth/login.php" class="btn banner-btn">Hire me</a>
        <img src="/styles/img/banner1.jpg" class="backdrop"/>
    </div>
</div>

<br>

<div class="container">
    <div class="services">
        <h2>Services</h2>
        <br>
        <div class="services-panels">
            <div class="service-pa location">
                <img src="../styles/img/placeholder.svg" class="service-icons" alt=""/>
                <h3>Location Scouting</h3>
                <p>We search for the perfect venue for your event, according to your requirements</p>
            </div>
            <div class="service-pa management">
                <img src="../styles/img/parties.svg" class="service-icons" alt=""/>
                <h3>Day of Event Coordination</h3>
                <p>We do everything possible by ensuring all goes well till the very day</p>
            </div>
            <div class="service-pan relations ">
                <img src="../styles/img/relations.svg" class="service-icons" alt=""/>
                <h3>Service Bid and Contract Management</h3>
                <p>We are able to find cost effective vendors for your event.</p>
            </div>

            <div class="service-pan after-service ">
                <img src="../styles/img/meeting.png" class="service-icons" alt=""/>
                <h3>Follow-Up Activities</h3>
                <p>We also do followups to make sure that the clients, and vendors are both satisfied.</p>
            </div>
        </div>
    </div>
</div>

<script rel="script" src="styles/main.js"></script>
</body>
</html>