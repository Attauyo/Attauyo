<?php
include('../db_connection.php');

// Assuming admin is logged in and this page displays after login

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/custom_styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Admin Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <a href="manage_investments.php" class="btn btn-primary btn-block">Manage Investments</a>
            </div>
            <div class="col-md-4">
                <a href="manage_notifications.php" class="btn btn-warning btn-block">Manage Notifications</a>
            </div>
            <div class="col-md-4">
                <a href="logout.php" class="btn btn-danger btn-block">Logout</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/custom_scripts.js"></script>
</body>
</html>
