<?php
session_start(); // session start

// Check if the user is already logged in;
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../index.php");
    exit;
}

require_once "../backend/connect.php";

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
        $sql = "SELECT id, email, password, user_type FROM users WHERE email = '$email' AND deleted_at IS NULL OR deleted_at = ''";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {

                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $user_type);

                    if (mysqli_stmt_fetch($stmt)) {
                        // Password is correct, so start a new session
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["user_type"] = $user_type;

                            //redirect user according to user type
                            if ($user_type == 1) {
                                // admin/ event planner
                                header("location: /admin/admin_page.php");
                            } elseif ($user_type == 2) {
                                // customer
                                header("location: /client/client_page.php");
                            }

                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $email_err = "No account found with that email address.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
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
                <input type="email" name="email" class="input" placeholder="Email Address" value="<?php echo $email; ?>">
                <span class="help-block text-center"><?php echo $email_err; ?></span>
            </div>
            <div class=" <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
<!--                <label>Password</label>-->
                <input type="password" name="password" placeholder="************" class="input">
                <span class="help-block text-center"><?php echo $password_err; ?></span>
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