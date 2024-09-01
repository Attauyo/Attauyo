<?php
$servername = "sql300.ezyro.com"; // Your database server address
$username = "ezyro_37220795";        // Your MySQL username
$password = "09058176690";          // Your MySQL password
$dbname = "ezyro_37220795_endless0147";          // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
