<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "Puteri123";
$dbname = "sidehustle";

// Establish database connection
if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user']);
}
?>