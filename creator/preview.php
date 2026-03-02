<?php
// 1. Authentication & Database Connection
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// 2. Validate Survey ID from URL
if (!isset($_GET['survey_id']) || empty($_GET['survey_id'])) {
    echo "<script>alert('Error: No Survey ID provided.'); window.location.href='dashboard.php';</script>";
    exit();
}

$survey_id = intval($_GET['survey_id']);
$creator_id = $_SESSION['user_id'];

try {
    // 3. Fetch Survey Details (Ensuring it belongs to the logged-in Form Creator)
    $survey_stmt = $pdo->prepare("SELECT * FROM Survey WHERE survey_id = :id AND creator_id = :creator_id");
    $survey_stmt->execute([':id' => $survey_id, ':creator_id' => $creator_id]);
    $survey = $survey_stmt->fetch(PDO::FETCH_ASSOC);

    // If survey doesn't exist or belongs to someone else
    if (!$survey) {
        echo "<script>alert('Error: Survey not found or you do not have permission to view it.'); window.location.href='dashboard.php';</script>";
        exit();
    }

    // 4. Fetch all Questions for this Survey
    $questions_stmt = $pdo->prepare("SELECT * FROM Question WHERE survey_id = :id ORDER BY question_id ASC");
    $questions_stmt->execute([':id' => $survey_id]);
    $questions = $questions_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Form - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>

    <div class="app-container">
        <!-- Top Header (Matches UI) -->
        <header class="top-header" role="banner">
            <h1>Preview Form</h1>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar (Matches Figure 23) -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="builder.php?survey_id=<?php echo $survey_id; ?>">Form Builder</a>
                <a href="preview.php?survey_id=<?php echo $survey_id; ?>" class="active" aria-current="page">Preview</a>
                <!-- We pass the ID via a form/POST in the main area to publish, but keep the sidebar simple -->
                <a href="dashboard.php" style="margin-top: 30px; border-top: 1px solid var(--border-color);">Exit to Dashboard</a>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content" role="main">
                
                <div class="form-card" tabindex="0" aria-label="Survey Preview Card">
                    <h2 tabindex="0">Survey Preview</h2>
                    
                    <!-- Display Questions Accessibly -->
                    <?php if (count($questions) > 0): ?>
                        <?php $q_number = 1; ?>
                        <?php foreach ($questions as $q): ?>
                            
                            <!-- Clean Alt-text tags from the UI display if they exist -->
                            <?php 
                                $display_text = preg_replace('/\[Alt:.*?\]/', '', $q['question_text']); 
                            ?>
                            
                            <div style="margin-bottom: 25px;" tabindex="0" aria-label="Question <?php echo $q_number; ?>">
                                <label style="font-weight: normal; margin-bottom: 5px; display: block;">
                                    <?php echo $q_number; ?>. <?php echo htmlspecialchars(trim($display_text)); ?>
                                </label>
                                
                                <?php if ($q['question_type'] == 'Text'): ?>
                                    <input type="text" disabled placeholder="User will type text here..." style="background-color: #f9f9f9; cursor: not-allowed;">
                                
                                <?php elseif ($q['question_type'] == 'Multiple Choice'): ?>
                                    <div style="margin-top: 10px; padding-left: 5px;">
                                        <label style="font-weight: normal; display: inline-block; margin-right: 15px; margin-top: 5px;">
                                            <input type="radio" disabled> Strongly Disagree
                                        </label>

                                        <label style="font-weight: normal; display: inline-block; margin-right: 15px; margin-top: 5px;">
                                            <input type="radio" disabled> Disagree
                                        </label>

                                        <label style="font-weight: normal; display: inline-block; margin-right: 15px; margin-top: 5px;">
                                            <input type="radio" disabled> Uncertain
                                        </label>

                                        <label style="font-weight: normal; display: inline-block; margin-right: 15px; margin-top: 5px;">
                                            <input type="radio" disabled> Agree
                                        </label>

                                        <label style="font-weight: normal; display: inline-block; margin-top: 5px;">
                                            <input type="radio" disabled> Strongly Agree
                                        </label>
                                        
                                    </div>
                                
                                <?php elseif ($q['question_type'] == 'Rating'): ?>
                                    <select disabled style="background-color: #f9f9f9; cursor: not-allowed;">
                                        <option>Select rating (1 to 5)</option>
                                        <option>1 - Poor</option>
                                        <option>2 - Fair</option>
                                        <option>3 - Average</option>
                                        <option>4 - Good</option>
                                        <option>5 - Excellent</option>
                                    </select>

                                <?php elseif ($q['question_type'] == 'Boolean'): ?>
                                    <select disabled style="background-color: #f9f9f9; cursor: not-allowed;">
                                        <option>Select Boolean (Yes / Maybe / No)</option>
                                        <option>Yes</option>
                                        <option>Maybe</option>
                                        <option>No</option>
                                    </select>
                                <?php endif; ?>
                            </div>
                            <?php $q_number++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 4px; margin-bottom: 20px;">
                            <strong>Notice:</strong> You haven't added any questions to this survey yet.
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons (Matches Figure 23) -->
                    <form action="publish_process.php" method="POST" class="action-buttons" style="margin-top: 30px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                        <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey_id); ?>">
                        
                        <button type="submit" class="btn" style="width: auto;" aria-label="Publish this survey">Publish Survey</button>
                        
                        <a href="builder.php?survey_id=<?php echo htmlspecialchars($survey_id); ?>" class="btn-secondary" aria-label="Go back to edit survey">Back to Edit</a>
                    </form>

                    <p class="helper-text" style="text-align: left; margin-top: 20px;">
                        <small>Preview mode · Keyboard accessible · Screen-reader supported · WCAG 2.1 compliant</small>
                    </p>
                </div>

            </main>
        </div>
    </div>

    <!-- Accessibility JavaScript -->
    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>