<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

$creator_id = $_SESSION['user_id'];

// Fetch surveys that have responses
$sql = "SELECT s.survey_id, s.title, COUNT(r.response_id) as response_count 
        FROM survey s 
        LEFT JOIN response r ON s.survey_id = r.survey_id 
        WHERE s.creator_id = :cid AND s.status != 'Draft'
        GROUP BY s.survey_id
        ORDER BY s.created_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':cid' => $creator_id]);
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Survey - Access Form</title>
    <!-- Version 40 -->
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page">
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1 tabindex="0">Select Survey to View Responses</h1>
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
                <a href="create_survey.php">Create Survey</a>
                <a href="my_surveys.php">My Surveys</a>
                <a href="responses.php" class="active">Responses</a>
                <a href="settings.php">Settings</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <h2 tabindex="0">Surveys with Responses</h2>
                
                <div class="table-responsive" tabindex="0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <!-- FIXED: Perfect width distribution & removing min-width constraint -->
                                <th style="width: 60%; min-width: 0;">Survey Name</th>
                                <th style="width: 20%; min-width: 0; text-align: center;">Total Responses</th>
                                <th style="width: 20%; min-width: 0; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($surveys) > 0): ?>
                                <?php foreach($surveys as $s): ?>
                                <tr>
                                    <!-- FIXED: Vertical align middle to look clean -->
                                    <td tabindex="0" style="vertical-align: middle;"><strong><?php echo htmlspecialchars($s['title']); ?></strong></td>
                                    
                                    <td tabindex="0" style="vertical-align: middle; text-align: center; font-size: 1.1em; font-weight: bold; color: var(--primary-color);">
                                        <?php echo $s['response_count']; ?>
                                    </td>
                                    
                                    <td style="vertical-align: middle; text-align: center;">
                                        <?php if($s['response_count'] > 0): ?>
                                            <a href="view_responses.php?survey_id=<?php echo $s['survey_id']; ?>" class="btn" style="width: auto; display: inline-block; margin: 0; padding: 8px 25px; font-size: 14px; border-radius: 20px;">View Data</a>
                                        <?php else: ?>
                                            <span style="color: gray; font-style: italic;">No responses yet</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" tabindex="0" style="text-align: center; padding: 30px;">You have no published surveys with responses.</td></tr>
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