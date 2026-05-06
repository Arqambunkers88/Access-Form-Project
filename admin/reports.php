<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Fetch only surveys that have actual responses for reporting
$sql = "SELECT s.survey_id, s.title, u.name as creator_name, COUNT(r.response_id) as response_count 
        FROM survey s 
        JOIN user u ON s.creator_id = u.user_id 
        JOIN response r ON s.survey_id = r.survey_id 
        GROUP BY s.survey_id 
        ORDER BY s.created_date DESC";
$stmt = $pdo->query($sql);
$reportSurveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Dashboard</title>
    <!-- Version 40 -->
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page">
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1 tabindex="0">System Reports</h1>
            <div class="header-a11y">
                <button type="button" id="decrease-font">A-</button>
                <button type="button" id="increase-font">A+</button>
                <button type="button" id="toggle-contrast">◐</button>
                <button type="button" id="toggle-colorblind">🎨</button>
                <button type="button" id="toggle-speech">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php">Users</a>
                <a href="surveys.php">Surveys</a>
                <a href="reports.php" class="active">Reports</a>
                <a href="settings.php">Settings</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <h2 tabindex="0">Generate Survey Reports</h2>
                
                <div class="table-responsive" tabindex="0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <!-- FIXED: Proportions set to 40-20-20-20 -->
                                <th style="width: 40%; min-width: 0;">Survey Name</th>
                                <th style="width: 20%; min-width: 0;">Creator</th>
                                <th style="width: 20%; min-width: 0; text-align: center;">Total Responses</th>
                                <th style="width: 20%; min-width: 0; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($reportSurveys) > 0): ?>
                                <?php foreach($reportSurveys as $survey): ?>
                                <tr>
                                    <td tabindex="0" style="vertical-align: middle;"><strong><?php echo htmlspecialchars($survey['title']); ?></strong></td>
                                    <td tabindex="0" style="vertical-align: middle;"><?php echo htmlspecialchars($survey['creator_name']); ?></td>
                                    
                                    <td tabindex="0" style="vertical-align: middle; text-align: center; font-size: 1.1em; font-weight: bold; color: var(--primary-color);">
                                        <?php echo $survey['response_count']; ?>
                                    </td>
                                    
                                    <td style="vertical-align: middle; text-align: center;">
                                        <a href="view_responses.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn" style="width: auto; display: inline-block; margin: 0; padding: 8px 20px; font-size: 14px; border-radius: 20px; background-color: #17a2b8;">View Data & Export</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" tabindex="0" style="text-align: center; padding: 30px;">No survey data available for reporting yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../assets/js/accessibility.js?v=60"></script>
</body>
</html>