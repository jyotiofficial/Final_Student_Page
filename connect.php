<?php
/*
This file contains database configuration assuming you are running MySQL using user "root" and password ""
*/
session_start();
session_regenerate_id(true);
//
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'internship_portal');

// Try connecting to the Database
$db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($db_connection === false) {
    die('Error: Cannot connect');
}
?>