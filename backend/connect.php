<?php

$conn = mysqli_connect('localhost', 'root', '','shanny-events');

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>