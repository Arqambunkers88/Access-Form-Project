<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

$creator_id = $_SESSION['user_id'];

// Fetch stats specific to this creator
$mySurveysCount = $pdo->prepare("SELECT COUNT(*) FROM survey WHERE creator_id = :id");
$mySurveysCount->execute([':id' => $creator_id]);
$totalSurveys = $mySurveysCount->fetchColumn();

// Fetch this creator's recent surveys
$mySurveys = $pdo->prepare("SELECT survey_id, title, status, created_date FROM survey WHERE creator_id = :id ORDER BY created_date DESC LIMIT 5");
$mySurveys->execute([':id' => $creator_id]);
$recentSurveys = $mySurveys->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creator Dashboard - Access Form</title>
    <!-- Version bump to 26 -->
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page <?php echo isset($body_class) ? $body_class : ''; ?>" <?php echo isset($inline_style) ? $inline_style : ''; ?>>

    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header" role="banner">
            <h1 tabindex="0">Form Creator Dashboard</h1>
            
            <div class="header-a11y" aria-label="Accessibility Controls">
                <button type="button" id="decrease-font" aria-label="Decrease Font Size">A-</button>
                <button type="button" id="increase-font" aria-label="Increase Font Size">A+</button>
                <button type="button" id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
                <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
                <button type="button" id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar for Form Creator -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php" class="active" aria-current="page">Dashboard</a>
                <a href="create_survey.php">Create Survey</a>
                <a href="my_surveys.php">My Surveys</a>
                <a href="responses.php">Responses</a>
                <a href="settings.php">Settings</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content" role="main">
                
                <div class="stats-container">
                    <div class="stat-card" tabindex="0">
                        <h3>My Total Surveys</h3>
                        <p><?php echo $totalSurveys; ?></p>
                    </div>
                </div>

                <h2 tabindex="0">My Recent Surveys</h2>
                
                <div class="table-responsive" tabindex="0" aria-label="Scrollable surveys table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Survey Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($recentSurveys) > 0): ?>
                                <?php foreach($recentSurveys as $survey): ?>
                                <tr>
                                    <td tabindex="0"><?php echo htmlspecialchars($survey['title']); ?></td>
                                    <td tabindex="0" style="color: <?php echo $survey['status'] == 'Published' ? 'green' : ($survey['status'] == 'Draft' ? 'orange' : 'red'); ?>; font-weight: bold;">
                                        <?php echo htmlspecialchars($survey['status']); ?>
                                    </td>
                                    <td tabindex="0"><?php echo date('d M Y', strtotime($survey['created_date'])); ?></td>
                                    <td>
                                        <!-- ACTION BUTTONS LOGIC -->
                                        <?php if($survey['status'] == 'Draft'): ?>
                                            <a href="builder.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px;" aria-label="Edit Survey">Edit</a>
                                            
                                            <a href="preview.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn" style="padding: 5px 10px; font-size: 14px; width: auto; display: inline-block; margin: 0 5px;" aria-label="Preview Survey">Preview</a>
                                            
                                            <a href="publish_process.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #28a745;" onclick="return confirm('Are you sure you want to publish this survey?');" aria-label="Publish Survey">Publish</a>
                                            
                                            <a href="delete_survey.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #dc3545; margin-left: 5px;" onclick="return confirm('Are you sure you want to permanently delete this drafted survey?');" aria-label="Delete Survey">Delete</a>
                                            
                                        <?php else: ?>
                                            
                                            <a href="preview.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px;" aria-label="View Survey">View</a>
                                            
                                            <!-- NEW: COPY LINK BUTTON (Only for Published Surveys) -->
                                            <button type="button" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #17a2b8; margin-left: 5px; border: none; cursor: pointer;" onclick="copyPublicLink('<?php echo $survey['survey_id']; ?>')" aria-label="Copy Public Link to Clipboard">Copy Link</button>
                                            
                                            <a href="delete_survey.php?survey_id=<?php echo $survey['survey_id']; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #dc3545; margin-left: 5px;" onclick="return confirm('WARNING: This survey is published. Deleting it will also delete all user responses. Are you absolutely sure?');" aria-label="Delete Survey">Delete</a>
                                            
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" tabindex="0">You haven't created any surveys yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <!-- Accessibility JS -->
    <script src="../assets/js/accessibility.js?v=60"></script>
    
    <!-- NEW: Corrected Copy Link to Clipboard Logic -->
    <script>
        function copyPublicLink(surveyId) {
            // "new URL" automatically calculates the correct path by going one folder back (../) 
            // and entering the respondent folder, whether you are on localhost or live domain!
            const fullLink = new URL('../respondent/start_survey.php?survey_id=' + surveyId, window.location.href).href;
            
            // Copy to clipboard
            navigator.clipboard.writeText(fullLink).then(function() {
                alert("Public Link Copied Successfully!\n\nYou can now paste it in WhatsApp or Email.\n" + fullLink);
            }).catch(function(error) {
                alert("Failed to copy link. Error: " + error);
            });
        }
    </script>
</body>
</html>