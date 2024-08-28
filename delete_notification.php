<?php
// Include the database connection file
include('db_connection.php');

// Check if notification ID is provided for deletion
if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];

    // Prepare the SQL query for deleting
    $sql = "DELETE FROM notifications WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $notification_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Notification deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting notification: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error preparing query: " . $conn->error . "</div>";
    }
}

// Close database connection
$conn->close();
?>
