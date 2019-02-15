<?php
//define('DB_SERVER', 'localhost');
//define('DB_USERNAME', 'root');
//define('DB_PASSWORD', '');
//define('DB_NAME', 'demo');

$conn = mysqli_connect('Localhost', 'homestead', 'secret', 'shanny-events');

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>