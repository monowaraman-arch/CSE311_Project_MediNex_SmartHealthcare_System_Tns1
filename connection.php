<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medinex_database";
$port = 3306;

$database = new mysqli($servername, $username, $password, $dbname, $port);
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}
?>
