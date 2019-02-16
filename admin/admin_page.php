<?php
session_start();

// Check if the user is logged in;
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
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
    <a href="../index.html">Home</a>

    <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
        echo '<a href="../auth/login.php">Login</a>'
    ?>

    <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
        echo '<a href="../auth/register.php">Register</a>'
    ?>

    <?php if (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true)
        echo '<a href="../auth/logout.php">Logout</a>'
    ?>

</div>

<div class="container">
    <div class="wrapper">
        <h1>Welcome Admin</h1>
    </div>
</div>
</body>
</html>
