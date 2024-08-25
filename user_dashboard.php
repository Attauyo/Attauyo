<?php
session_start();
include '../includes/db_connection.php';
include '../includes/notification_functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

$username = $_SESSION['username'];

$investmentSql = "SELECT * FROM investments WHERE username = ?";
$stmt = $conn->prepare($investmentSql);
$stmt->bind_param("s", $username);
$stmt->execute();
$investmentResult = $stmt->get_result();

$notifications = get_notifications($conn, 'user', $username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <title>User Dashboard</title>
</head>
<body>
    <div class="container mt-5">
        <h2>User Dashboard</h2>

        <h3>Current Balance: $<?php
        $balanceSql = "SELECT balance FROM users WHERE username = ?";
        $stmt = $conn->prepare($balanceSql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        echo number_format($balance, 2);
        $stmt->close();
        ?></h3>

        <h3>Investment History</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($investment = $investmentResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $investment['investment_id']; ?></td>
                    <td>$<?php echo number_format($investment['amount'], 2); ?></td>
                    <td><?php echo $investment['date']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Notifications</h3>
        <ul class="list-group">
            <?php foreach ($notifications as $notification): ?>
            <li class="list-group-item">
                <?php echo ucfirst($notification['type']); ?>: <?php echo $notification['message']; ?> - <?php echo $notification['date']; ?>
            </li>
            <?php endforeach; ?>
        </ul>

        <a href="user_logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
