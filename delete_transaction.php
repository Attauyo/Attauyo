<?php
// Include the database connection file
include('../db_connection.php');

// Check if transaction ID is provided for deletion
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Prepare the SQL query for deleting
    $sql = "DELETE FROM transactions WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $transaction_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Transaction deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting transaction: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error preparing query: " . $conn->error . "</div>";
    }
}

// Close database connection
$conn->close();
?>
