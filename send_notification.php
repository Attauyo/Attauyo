<?php
session_start();
include('../db_connection.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    // Insert the notification into the database
    $query = "INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$message')";
    if (mysqli_query($conn, $query)) {
        $success = "Notification sent successfully!";
    } else {
        $error = "Error sending notification: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Send Notification</h2>
        <?php if (isset($success)) { ?>
            <div class="alert alert-success text-center">
                <?php echo $success; ?>
            </div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <form method="post">
                    <div class="form-group">
                        <label for="user_id">User ID</label>
                        <input type="text" name="user_id" class="form-control" id="user_id" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" class="form-control" id="message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
