<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $new_balance = $_POST['balance'];

    // Update balance in the database
    $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE user_id = ?");
    $stmt->bind_param("di", $new_balance, $user_id);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Balance updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating balance: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Fetch user data
$user = null;
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Balance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit User Balance</h2>

        <form method="GET" action="edit_balance.php" class="mb-4">
            <div class="input-group">
                <input type="number" name="user_id" class="form-control" placeholder="Enter User ID" required>
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <?php if ($user): ?>
            <form method="POST" action="edit_balance.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="balance" class="form-label">Current Balance</label>
                    <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="<?php echo htmlspecialchars($user['balance']); ?>" required>
                </div>
                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                <button type="submit" class="btn btn-success">Update Balance</button>
            </form>
        <?php elseif (isset($_GET['user_id'])): ?>
            <div class="alert alert-warning">User not found.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
