<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

$respondent_id = $_SESSION['user_id'];

// Fetch all published surveys AND check if the current user has already submitted a response
$sql = "
    SELECT s.survey_id, s.title, s.description, r.response_id 
    FROM Survey s 
    LEFT JOIN Response r ON s.survey_id = r.survey_id AND r.respondent_id = :rid 
    WHERE s.status = 'Published' 
    ORDER BY s.created_date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':rid' => $respondent_id]);
$publishedSurveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respondent Dashboard - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>

    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header" role="banner">
            <h1>Respondent Dashboard</h1>
            <div class="header-a11y" aria-label="Accessibility Controls">
                <button id="decrease-font" aria-label="Decrease Font Size">A-</button>
                <button id="increase-font" aria-label="Increase Font Size">A+</button>
                <button id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
                <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
                <button id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar for Respondent -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php" class="active" aria-current="page">Available Surveys</a>
                <a href="settings.php">Settings</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content" role="main">
                <h2 tabindex="0">Available Surveys</h2>
                
                <div class="table-responsive" tabindex="0" aria-label="Scrollable surveys table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Survey Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($publishedSurveys) > 0): ?>
                                <?php foreach($publishedSurveys as $survey): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($survey['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($survey['description']); ?></td>
                                    <td>
                                        <!-- DYNAMIC BUTTON LOGIC BASED ON YOUR EXCELLENT SUGGESTION -->
                                        <?php if ($survey['response_id']): ?>
                                            <span class="btn-secondary" style="background-color: #28a745; cursor: default; display:inline-block; width:auto; padding:8px 15px; margin-top:0;" aria-label="Survey Already Submitted">Submitted ✓</span>
                                        <?php else: ?>
                                            <a href="start_survey.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn" style="display:inline-block; width:auto; padding:8px 15px; margin-top:0;" aria-label="Start Survey <?php echo htmlspecialchars($survey['title']); ?>">Start Survey</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No surveys available right now. Please check back later.</td>
                                </tr>
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