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
    // 3. Fetch Survey Details
    $survey_stmt = $pdo->prepare("SELECT * FROM survey WHERE survey_id = :id AND creator_id = :creator_id");
    $survey_stmt->execute([':id' => $survey_id, ':creator_id' => $creator_id]);
    $survey = $survey_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$survey) {
        echo "<script>alert('Error: Survey not found or you do not have permission to view it.'); window.location.href='dashboard.php';</script>";
        exit();
    }

    // 4. Fetch all Questions & Sections for this Survey
    $questions_stmt = $pdo->prepare("SELECT * FROM question WHERE survey_id = :id ORDER BY question_id ASC");
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
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page">

    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header" role="banner">
            <h1>Preview Form</h1>
            <div class="header-a11y" aria-label="Accessibility Controls">
                <button type="button" id="decrease-font">A-</button>
                <button type="button" id="increase-font">A+</button>
                <button type="button" id="toggle-contrast">◐</button>
                <button type="button" id="toggle-colorblind">🎨</button>
                <button type="button" id="toggle-speech">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="builder.php?survey_id=<?php echo $survey_id; ?>">Form Builder</a>
                <a href="preview.php?survey_id=<?php echo $survey_id; ?>" class="active" aria-current="page">Preview</a>
                <a href="dashboard.php" style="margin-top: 30px; border-top: 1px solid var(--border-color);">Exit to Dashboard</a>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content" role="main">
                
                <div class="form-card" tabindex="0" aria-label="Survey Preview Card" style="max-width: 800px;">
                    <h2 tabindex="0" style="margin-bottom: 30px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px;">
                        Survey Preview: <?php echo htmlspecialchars($survey['title']); ?>
                    </h2>
                    
                    <!-- Display Questions & Sections Accessibly -->
                    <?php if (count($questions) > 0): ?>
                        <?php $q_number = 1; ?>
                        <?php foreach ($questions as $q): ?>
                            
                            <!-- Clean Alt-text tags from the UI display -->
                            <?php 
                                $display_text = preg_replace('/\[Alt:.*?\]/', '', $q['question_text']); 
                            ?>
                            
                            <?php if ($q['question_type'] == 'Section'): ?>
                                <!-- ===================================== -->
                                <!-- FIXED: SECTION HEADER DESIGN          -->
                                <!-- ===================================== -->
                                <div style="margin-top: 40px; margin-bottom: 25px; padding-bottom: 5px; border-bottom: 2px solid var(--primary-color);" tabindex="0">
                                    <h3 style="margin: 0; color: var(--text-color); font-size: 1.4em;">
                                        <?php echo htmlspecialchars(trim($display_text)); ?>
                                    </h3>
                                </div>
                            <?php else: ?>
                                <!-- ===================================== -->
                                <!-- NORMAL QUESTION DESIGN                -->
                                <!-- ===================================== -->
                                <div style="margin-bottom: 30px;" tabindex="0" aria-label="Question <?php echo $q_number; ?>">
                                    <label style="font-weight: normal; margin-bottom: 10px; display: block;">
                                        <strong><?php echo $q_number; ?>. <?php echo htmlspecialchars(trim($display_text)); ?></strong>
                                    </label>
                                    
                                    <?php if ($q['question_type'] == 'Text'): ?>
                                        <textarea disabled placeholder="User will type text here..." style="background-color: #f9f9f9; cursor: not-allowed; width: 100%;"></textarea>
                                    
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
                                        <select disabled style="background-color: #f9f9f9; cursor: not-allowed; width: 100%; max-width: 300px;">
                                            <option>Select rating (1 to 5)</option>
                                        </select>

                                    <?php elseif ($q['question_type'] == 'Boolean'): ?>
                                        <div style="margin-top: 10px; padding-left: 5px;">
                                            <label style="font-weight: normal; display: inline-block; margin-right: 15px;"><input type="radio" disabled> Yes</label>
                                            <label style="font-weight: normal; display: inline-block; margin-right: 15px;"><input type="radio" disabled> Maybe</label>
                                            <label style="font-weight: normal; display: inline-block;"><input type="radio" disabled> No</label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php $q_number++; // <--- FIXED: Only increment if it is an actual question! ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 4px; margin-bottom: 20px;">
                            <strong>Notice:</strong> You haven't added any items to this survey yet.
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <form action="publish_process.php" method="POST" class="action-buttons" style="margin-top: 40px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                        <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey_id); ?>">
                        
                        <button type="submit" class="btn" style="width: auto;" aria-label="Publish this survey">Publish Survey</button>
                        <a href="builder.php?survey_id=<?php echo htmlspecialchars($survey_id); ?>" class="btn-secondary" aria-label="Go back to edit survey">Back to Edit</a>
                    </form>
                </div>

            </main>
        </div>
    </div>

    <!-- Accessibility JavaScript -->
    <script src="../assets/js/accessibility.js?v=60"></script>
</body>
</html>