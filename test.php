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

//include 'connectdb.php'; // Uncomment later
/*
mysqli_select_db($conn,"df2017");
$sql="SELECT country_name, country_capital FROM records";
$result = mysqli_query($conn,$sql);

echo "Results:"

error_reporting(E_ERROR | E_PARSE);

	\
while($row = mysqli_fetch_array($result)) {
	echo $row['country_name'] . "<br>";
	echo $row['country_capital'] . "<br>";
}
*/
mysqli_close($conn);
?>