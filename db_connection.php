<?php
$servername = "fdb1028.awardspace.net"; // Your database server address
$username = "4521183_bitcoin";        // Your MySQL username
$password = "09058176690Ol";          // Your MySQL password
$dbname = "4521183_bitcoin";          // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
