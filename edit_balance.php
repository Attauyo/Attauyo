<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $new_balance = $_POST['balance'];

    // Update balance in the database
    $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->bind_param("di", $new_balance, $id);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Balance updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating balance: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Fetch user data
$user = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
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
                <input type="number" name="id" class="form-control" placeholder="Enter User ID" required>
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <?php if ($user): ?>
            <form method="POST" action="edit_balance.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="balance" class="form-label">Current Balance</label>
                    <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="<?php echo htmlspecialchars($user['balance'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn btn-success">Update Balance</button>
            </form>
        <?php elseif (isset($_GET['id'])): ?>
            <div class="alert alert-warning">User not found.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
