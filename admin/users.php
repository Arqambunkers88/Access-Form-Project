<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Fetch all users including their disability_profile
$stmt = $pdo->prepare("SELECT user_id, name, email, role, disability_profile, is_disabled FROM user WHERE user_id != :id ORDER BY role, name");
$stmt->execute([':id' => $_SESSION['user_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Access Form</title>
    <!-- Version updated to 22 for cache clear -->
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page">
    <div class="app-container">
        
        <!-- Top Header matches Figure 20 exactly -->
        <header class="top-header" role="banner">
            <h1 tabindex="0">Manage Users</h1>
            
            <!-- Global Accessibility Controls for Admin -->
            <div class="header-a11y" aria-label="Accessibility Controls">
                <button type="button" id="decrease-font" aria-label="Decrease Font Size">A-</button>
                <button type="button" id="increase-font" aria-label="Increase Font Size">A+</button>
                <button type="button" id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
                <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
                <button type="button" id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            
            <!-- Sidebar -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php" class="active" aria-current="page">Users</a>
                <a href="surveys.php">Surveys</a>
                <a href="reports.php">Reports</a>
                <a href="settings.php">Settings</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <h2 tabindex="0">System Users</h2>
                
                <div class="table-responsive" tabindex="0" aria-label="Scrollable users table">
                    <table class="data-table" aria-label="System Users Table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Disability Profile</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($users) > 0): ?>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td tabindex="0"><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td tabindex="0"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td tabindex="0"><strong><?php echo htmlspecialchars($user['role']); ?></strong></td>
                                    
                                    <!-- FORMATTING THE DISABILITY TEXT BEAUTIFULLY -->
                                    <td tabindex="0" style="color: #555;">
                                        <?php 
                                            $prof = isset($user['disability_profile']) ? $user['disability_profile'] : 'none';
                                            if ($prof === 'visual') echo 'Visual Impairment';
                                            elseif ($prof === 'colorblind') echo 'Color Blindness';
                                            elseif ($prof === 'physical') echo 'Physical Impairment';
                                            else echo 'None';
                                        ?>
                                    </td>

                                    <!-- Status (Active/Disabled) -->
                                    <td tabindex="0" style="color: <?php echo $user['is_disabled'] ? 'red' : 'green'; ?>; font-weight: bold;">
                                        <?php echo $user['is_disabled'] ? 'Disabled' : 'Active'; ?>
                                    </td>
                                    
                                    <!-- Actions (Enable/Disable/Delete) -->
                                    <td>
                                        <form action="user_action.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="current_status" value="<?php echo $user['is_disabled']; ?>">
                                            <button type="submit" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: <?php echo $user['is_disabled'] ? '#28a745' : '#ffc107'; ?>; color: black;" aria-label="<?php echo $user['is_disabled'] ? 'Enable User' : 'Disable User'; ?>">
                                                <?php echo $user['is_disabled'] ? 'Enable' : 'Disable'; ?>
                                            </button>
                                        </form>

                                        <form action="user_action.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to permanently delete this user?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #dc3545; margin-left: 5px;" aria-label="Delete User">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" tabindex="0">No other users found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- ADDED: Original JS file to make buttons work -->
    <script src="../assets/js/accessibility.js?v=60"></script>
</body>
</html>