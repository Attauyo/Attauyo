<?php
// Include the database connection file
include('db_connection.php');

// Initialize variables
$notification_id = '';
$user_id = '';
$message = '';
$date_sent = '';

// Check if the form was submitted for editing
if (isset($_POST['update_notification'])) {
    $notification_id = $_POST['notification_id'];
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];
    $date_sent = $_POST['date_sent'];

    // Prepare the SQL query for updating
    $sql = "UPDATE notifications 
            SET user_id = ?, message = ?, date_sent = ? 
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issi", $user_id, $message, $date_sent, $notification_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Notification updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating notification: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error preparing query: " . $conn->error . "</div>";
    }
}

// Check if notification ID is provided for editing
if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];

    // Fetch the notification details for editing
    $sql = "SELECT * FROM notifications WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $notification_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $notification = $result->fetch_assoc();
            $user_id = $notification['user_id'];
            $message = $notification['message'];
            $date_sent = $notification['date_sent'];
        } else {
            echo "<div class='alert alert-warning'>Notification not found.</div>";
            exit();
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error preparing query: " . $conn->error . "</div>";
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notification</title>
    <link rel="stylesheet" href="bootstrap.min.css">
     <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> 
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Edit Notification</h3>
        </div>
        <div class="card-body">
            <form method="post" action="edit_notification.php">
                <input type="hidden" name="notification_id" value="<?php echo $notification_id; ?>">

                <div class="mb-3">
                    <label for="user_id" class="form-label">User ID</label>
                    <input type="number" class="form-control" name="user_id" value="<?php echo $user_id; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" name="message" rows="3" required><?php echo $message; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="date_sent" class="form-label">Date Sent</label>
                    <input type="date" class="form-control" name="date_sent" value="<?php echo $date_sent; ?>" required>
                </div>

                <button type="submit" name="update_notification" class="btn btn-primary">Update Notification</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
