<?php
session_start();
include 'db_connection.php';

// Assuming user is logged in and their ID is stored in session
$user_id = $_SESSION['user_id'];

// Fetch current balance
$sql = "SELECT balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

// Fetch minimum withdrawal amount from the settings table
$min_withdrawal_sql = "SELECT value FROM settings WHERE key_name = 'min_withdrawal'";
$result = $conn->query($min_withdrawal_sql);
$min_withdrawal = $result->fetch_assoc()['value'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];
    $bank_name = $_POST['bank_name'];

    // Check if the withdrawal amount meets the minimum requirement
    if ($amount < $min_withdrawal) {
        echo "The minimum withdrawal amount is $" . number_format($min_withdrawal, 2);
    } elseif ($balance < $amount) {
        echo "Insufficient balance.";
    } else {
        // Deduct the amount from balance
        $new_balance = $balance - $amount;
        $update_sql = "UPDATE users SET balance = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("di", $new_balance, $user_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Record the transaction
        $transaction_sql = "INSERT INTO transactions (user_id, type, amount, status, date, account_name, account_number, bank_name) VALUES (?, 'withdrawal', ?, 'pending', NOW(), ?, ?, ?)";
        $transaction_stmt = $conn->prepare($transaction_sql);
        $transaction_stmt->bind_param("idsss", $user_id, $amount, $account_name, $account_number, $bank_name);
        $transaction_stmt->execute();
        $transaction_stmt->close();

        echo "Withdrawal request of $" . number_format($amount, 2) . " submitted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Withdraw Funds</h2>
        <p>Your current balance: $<?php echo number_format($balance, 2); ?></p>
        <p>Please note: The minimum withdrawal amount is $<?php echo number_format($min_withdrawal, 2); ?></p>
        <form method="POST" action="withdraw.php">
            <div class="form-group">
                <label for="amount">Withdraw Amount:</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <h4>Account Details</h4>
            <div class="form-group">
                <label for="account_name">Account Name:</label>
                <input type="text" class="form-control" id="account_name" name="account_name" required>
            </div>
            <div class="form-group">
                <label for="account_number">Account Number:</label>
                <input type="text" class="form-control" id="account_number" name="account_number" required>
            </div>
            <div class="form-group">
                <label for="bank_name">Bank Name:</label>
                <input type="text" class="form-control" id="bank_name" name="bank_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Withdrawal</button>
        </form>
    </div>
</body>
</html>
