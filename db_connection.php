<?php
$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "jobaaj-crud-1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Close the connection
// $conn->close();
?>
