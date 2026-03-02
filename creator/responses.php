<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

$creator_id = $_SESSION['user_id'];

// Fetch surveys that have responses
$sql = "SELECT s.survey_id, s.title, COUNT(r.response_id) as response_count 
        FROM Survey s 
        LEFT JOIN Response r ON s.survey_id = r.survey_id 
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
    <title>Select Survey - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1>Select Survey to View Responses</h1>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="create_survey.php">Create Survey</a>
                <a href="my_surveys.php">My Surveys</a>
                <a href="responses.php" class="active">Responses</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <h2 tabindex="0">Surveys with Responses</h2>
                <div class="table-responsive" tabindex="0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Survey Name</th>
                                <th>Total Responses</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($surveys) > 0): ?>
                                <?php foreach($surveys as $s): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($s['title']); ?></td>
                                    <td><?php echo $s['response_count']; ?></td>
                                    <td>
                                        <?php if($s['response_count'] > 0): ?>
                                            <a href="view_responses.php?survey_id=<?php echo $s['survey_id']; ?>" class="btn">View Data</a>
                                        <?php else: ?>
                                            <span style="color: gray;">No responses yet</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3">You have no published surveys.</td></tr>
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