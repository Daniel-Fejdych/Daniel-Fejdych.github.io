<?php
// Database connection details
$host = "localhost";
$username = "df2017";
$password = "wnd4VKSANY3";
$database = "df2017";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
