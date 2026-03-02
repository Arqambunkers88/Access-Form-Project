<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Fetch all users except the currently logged-in admin (to prevent self-deletion)
$stmt = $pdo->prepare("SELECT user_id, name, email, role, is_disabled FROM User WHERE user_id != :id ORDER BY role, name");
$stmt->execute([':id' => $_SESSION['user_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1>Manage Users</h1>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php" class="active">Users</a>
                <a href="surveys.php">Surveys</a>
                <a href="reports.php">Reports</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <h2 tabindex="0">System Users</h2>
                <div class="table-responsive" tabindex="0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($users) > 0): ?>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($user['role']); ?></strong></td>
                                    <td style="color: <?php echo $user['is_disabled'] ? 'red' : 'green'; ?>; font-weight: bold;">
                                        <?php echo $user['is_disabled'] ? 'Disabled' : 'Active'; ?>
                                    </td>
                                    <td>
                                        <!-- Disable / Enable Toggle -->
                                        <form action="user_action.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="current_status" value="<?php echo $user['is_disabled']; ?>">
                                            <button type="submit" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: <?php echo $user['is_disabled'] ? '#28a745' : '#ffc107'; ?>; color: black;">
                                                <?php echo $user['is_disabled'] ? 'Enable' : 'Disable'; ?>
                                            </button>
                                        </form>

                                        <!-- Delete User -->
                                        <form action="user_action.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to permanently delete this user? All their surveys and responses will be destroyed.');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #dc3545; margin-left: 5px;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5">No other users found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>