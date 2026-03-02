<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

$creator_id = $_SESSION['user_id'];

// Fetch ALL surveys for this creator
$stmt = $pdo->prepare("SELECT survey_id, title, status, created_date FROM Survey WHERE creator_id = :id ORDER BY created_date DESC");
$stmt->execute([':id' => $creator_id]);
$allSurveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Surveys - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>
    <div class="app-container">
        <!-- Header -->
        <header class="top-header" role="banner">
            <h1>View Surveys</h1>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="my_surveys.php" class="active">View Surveys</a>
                <a href="create_survey.php">Create Survey</a>
                <a href="responses.php">Responses</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <div class="form-card" style="max-width: 100%;" tabindex="0">
                    <div class="table-responsive" tabindex="0">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Survey Name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($allSurveys) > 0): ?>
                                    <?php foreach($allSurveys as $survey): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($survey['title']); ?></td>
                                        <td style="color: <?php echo $survey['status'] == 'Published' ? '#28a745' : '#fd7e14'; ?>; font-weight: bold;">
                                            <?php echo htmlspecialchars($survey['status']); ?>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($survey['created_date'])); ?></td>
                                        <td>
                                            <?php if($survey['status'] == 'Draft'): ?>
                                                <a href="builder.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn" style="padding: 5px 10px; font-size: 14px; width: auto; display: inline-block;">Edit</a>
                                            <?php else: ?>
                                                <a href="preview.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn" style="padding: 5px 10px; font-size: 14px; width: auto; display: inline-block; background-color: #0056b3;">View</a>
                                                <span class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #6c757d; cursor: not-allowed; margin-left: 5px;">Edit</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4">No surveys found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Matches the "+ Add New Survey" button in Figure 27 -->
                    <div style="margin-top: 20px;">
                        <a href="create_survey.php" class="btn" style="width: auto; display: inline-block;">+ Add New Survey</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>