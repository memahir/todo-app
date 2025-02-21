<?php
$host = "localhost";
$user = "root"; // Change if using a live server
$pass = "";
$dbname = "todo-user";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
