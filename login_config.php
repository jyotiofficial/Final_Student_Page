<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship_portal";

// Create connection
$link = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
