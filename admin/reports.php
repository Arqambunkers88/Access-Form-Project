<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Fetch only surveys that have actual responses for reporting
$sql = "SELECT s.survey_id, s.title, u.name as creator_name, COUNT(r.response_id) as response_count 
        FROM Survey s 
        JOIN User u ON s.creator_id = u.user_id 
        JOIN Response r ON s.survey_id = r.survey_id 
        GROUP BY s.survey_id 
        ORDER BY s.created_date DESC";
$stmt = $pdo->query($sql);
$reportSurveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page">
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1>System Reports</h1>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php">Users</a>
                <a href="surveys.php">Surveys</a>
                <a href="reports.php" class="active">Reports</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <h2 tabindex="0">Generate Survey Reports</h2>
                <div class="table-responsive" tabindex="0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Survey Name</th>
                                <th>Creator</th>
                                <th>Total Responses</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($reportSurveys) > 0): ?>
                                <?php foreach($reportSurveys as $survey): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($survey['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($survey['creator_name']); ?></td>
                                    <td><?php echo $survey['response_count']; ?></td>
                                    <td>
                                        <!-- FIXED LINK: Now securely points to Admin's View Responses -->
                                        <a href="view_responses.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn" style="width: auto; padding: 5px 15px; font-size: 14px; background-color: #17a2b8;">View Data & Export</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4">No survey data available for reporting yet.</td></tr>
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