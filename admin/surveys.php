<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Fetch all surveys across the system with their creator's name and response count
$sql = "SELECT s.survey_id, s.title, s.status, s.created_date, u.name as creator_name, 
        (SELECT COUNT(*) FROM Response r WHERE r.survey_id = s.survey_id) as response_count 
        FROM Survey s 
        JOIN User u ON s.creator_id = u.user_id 
        ORDER BY s.created_date DESC";
$stmt = $pdo->query($sql);
$allSurveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Surveys - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1>Monitor System Surveys</h1>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php">Users</a>
                <a href="surveys.php" class="active">Surveys</a>
                <a href="reports.php">Reports</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <h2 tabindex="0">All System Surveys</h2>
                <div class="table-responsive" tabindex="0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Survey Name</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th>Total Responses</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($allSurveys) > 0): ?>
                                <?php foreach($allSurveys as $survey): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($survey['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($survey['creator_name']); ?></td>
                                    <td style="color: <?php echo $survey['status'] == 'Published' ? 'green' : ($survey['status'] == 'Draft' ? 'orange' : 'red'); ?>; font-weight: bold;">
                                        <?php echo htmlspecialchars($survey['status']); ?>
                                    </td>
                                    <td><?php echo $survey['response_count']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($survey['created_date'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5">No surveys found in the system.</td></tr>
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