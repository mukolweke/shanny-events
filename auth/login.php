<?php
session_start();

require_once '../backend/auth.php';

$logged_user = new Auth();

if ($logged_user->is_logged_in()) {
    if ($_SESSION['user_type'] == 1) {
        $logged_user->redirect('../admin/admin_page.php');
    } else {
        $logged_user->redirect('../client/client_page.php');
    }
}

$email = $password = "";
$user_type = null;
$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email and password are empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please provide an email to continue.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials on DB;
    if (empty($email_err) && empty($password_err)) {
        $logged_user->login($email, $password);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shanny's Events System</title>

    <link rel="stylesheet" href="../styles/main.css">
</head>
<body>
<div class="topnav">
    <div class="container">
        <a href="../index.php">Home</a>
        <a class="active" href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
</div>


<div class="container">
    <div class="wrapper">
        <h2 class="text-center">Welcome to Shann'y Events</h2>
        <form action="login.php" method="post">
            <div class=" <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <!--                <label>Email</label>-->
                <input type="email" name="email" class="input" placeholder="Email Address"
                       value="<?php echo $email; ?>">
                <span class="help-block" style="text-align: center;"><?php echo $email_err; ?></span>
            </div>
            <div class=" <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <!--                <label>Password</label>-->
                <input type="password" name="password" placeholder="************" class="input">
                <span class="help-block" style="text-align: center;"><?php echo $password_err; ?></span>
            </div>
            <div class="">
                <input type="submit" class="btn btn-primary input" value="Login">
            </div>
            <p class="text-center">Don't have an account? <a href="register.php">REGISTER</a>.</p>
        </form>
    </div>
</div>
</body>
</html>