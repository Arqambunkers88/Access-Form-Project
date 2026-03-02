<?php
// Require authentication and database
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Fetch quick stats for the dashboard
$totalUsers = $pdo->query("SELECT COUNT(*) FROM User")->fetchColumn();
$totalSurveys = $pdo->query("SELECT COUNT(*) FROM Survey")->fetchColumn();
$totalResponses = $pdo->query("SELECT COUNT(*) FROM Response")->fetchColumn();

// Fetch recent surveys to display in the table
$recentSurveys = $pdo->query("
    SELECT s.title, u.name as creator_name, s.status 
    FROM Survey s
    JOIN User u ON s.creator_id = u.user_id
    ORDER BY s.created_date DESC LIMIT 5
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>

    <div class="app-container">
        <!-- Top Header matches Figure 20 exactly -->
        <header class="top-header" role="banner">
            <h1>Admin Dashboard</h1>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php" class="active" aria-current="page">Dashboard</a>
                <a href="users.php">Users</a>
                <a href="surveys.php">Surveys</a>
                <a href="reports.php">Reports</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content" role="main">
                
                <div class="stats-container">
                    <div class="stat-card" tabindex="0">
                        <h3>Total Users</h3>
                        <p><?php echo $totalUsers; ?></p>
                    </div>
                    <div class="stat-card" tabindex="0">
                        <h3>Surveys</h3>
                        <p><?php echo $totalSurveys; ?></p>
                    </div>
                    <div class="stat-card" tabindex="0">
                        <h3>Responses</h3>
                        <p><?php echo $totalResponses; ?></p>
                    </div>
                </div>

                <h2 tabindex="0">Recent Surveys</h2>
                
                <!-- NEW WRAPPER ADDED HERE -->
                <div class="table-responsive" tabindex="0" aria-label="Scrollable surveys table">
                    <table class="data-table" aria-label="Recent Surveys Table">
                        <thead>
                            <tr>
                                <th scope="col">Survey Name</th>
                                <th scope="col">Created By</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($recentSurveys) > 0): ?>
                                <?php foreach($recentSurveys as $survey): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($survey['title']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['creator_name']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['status']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No surveys created yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- END WRAPPER -->

            </main>
        </div>
    </div>

    <!-- Ensure accessibility script is loaded -->
    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>