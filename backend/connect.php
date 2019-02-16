<?php

$conn = mysqli_connect('Localhost', 'homestead', 'secret', 'shanny-events');

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>