<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include database connection
include('db_connection.php');

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT username, current_balance FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Fetch user's transactions
$transactions_query = "SELECT type, amount, date, status FROM transactions WHERE user_id = $user_id ORDER BY date DESC";
$transactions_result = $conn->query($transactions_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <div class="card">
            <div class="card-header">
                Account Overview
            </div>
            <div class="card-body">
                <h5 class="card-title">Current Balance: $<?php echo number_format($user['current_balance'], 2); ?></h5>
            </div>
        </div>

        <div class="mt-4">
            <h3>Transaction History</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction = $transactions_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo ucfirst($transaction['type']); ?></td>
                        <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                        <td><?php echo $transaction['date']; ?></td>
                        <td><?php echo ucfirst($transaction['status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <h3>Notifications</h3>
            <ul class="list-group">
                <?php
                $notifications_query = "SELECT message, date_sent FROM notifications WHERE user_id = $user_id ORDER BY date_sent DESC";
                $notifications_result = $conn->query($notifications_query);
                while ($notification = $notifications_result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($notification['message']); ?>
                    <span class="badge badge-primary float-right"><?php echo $notification['date_sent']; ?></span>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
