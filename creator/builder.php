<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) {
    echo "<script>alert('Invalid Survey ID'); window.location.href='dashboard.php';</script>";
    exit();
}
$survey_id = intval($_GET['survey_id']);
$creator_id = $_SESSION['user_id'];

// 1. Fetch Survey Status to ensure it's still a Draft
$survey_stmt = $pdo->prepare("SELECT title, status FROM Survey WHERE survey_id = :id AND creator_id = :creator_id");
$survey_stmt->execute([':id' => $survey_id, ':creator_id' => $creator_id]);
$survey = $survey_stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Error: Survey not found.'); window.location.href='dashboard.php';</script>";
    exit();
}

// Security: If Published, block editing!
if ($survey['status'] !== 'Draft') {
    echo "<script>alert('This survey is already Published and cannot be edited.'); window.location.href='dashboard.php';</script>";
    exit();
}

// 2. Fetch existing questions
$questions_stmt = $pdo->prepare("SELECT * FROM Question WHERE survey_id = :id ORDER BY question_id ASC");
$questions_stmt->execute([':id' => $survey_id]);
$existing_questions = $questions_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Builder - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>

    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header" role="banner">
            <h1>Form Builder: <?php echo htmlspecialchars($survey['title']); ?></h1>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="builder.php?survey_id=<?php echo $survey_id; ?>" class="active" aria-current="page">Form Builder</a>
                <a href="preview.php?survey_id=<?php echo $survey_id; ?>">Preview</a>
                <a href="publish_process.php?survey_id=<?php echo $survey_id; ?>" onclick="return confirm('Are you sure you want to publish this survey?');">Publish</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content" role="main">
                
                <!-- Section 1: Display Existing Questions -->
                <?php if (count($existing_questions) > 0): ?>
                <div class="form-card" tabindex="0">
                    <h2 style="margin-bottom: 20px;">Existing Questions</h2>
                    <ul style="list-style-type: none; padding: 0;">
                        <?php foreach ($existing_questions as $index => $q): ?>
                            <?php 
                                // Clean the question text for display
                                $display_text = preg_replace('/\[Alt:.*?\]/', '', $q['question_text']); 
                            ?>
                            <li style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                                <strong>Q<?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars(trim($display_text)); ?> 
                                <br><small><em>Type: <?php echo htmlspecialchars($q['question_type']); ?></em></small>
                                
                                <div style="margin-top: 10px;">
                                    <a href="edit_question.php?question_id=<?php echo $q['question_id']; ?>&survey_id=<?php echo $survey_id; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; margin-left: 0;">Edit</a>
                                    
                                    <a href="delete_question.php?question_id=<?php echo $q['question_id']; ?>&survey_id=<?php echo $survey_id; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #dc3545;" onclick="return confirm('Delete this question?');">Delete</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Section 2: Add New Question Form -->
                <div class="form-card" tabindex="0">
                    <h2>Add New Question</h2>
                    <form action="add_question_process.php" method="POST">
                        <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey_id); ?>">

                        <label for="question_text">Question Text</label>
                        <textarea id="question_text" name="question_text" required aria-required="true"></textarea>

                        <label for="question_type">Question Type</label>
                        <select id="question_type" name="question_type" required aria-required="true">
                            <option value="Text">Text Answer</option>
                            <option value="Multiple Choice">Multiple Choice</option>
                            <option value="Rating">Rating (1 to 5)</option>
                            <option value="Boolean">Boolean (Yes / Maybe / No)</option>
                        </select>

                        <label for="alt_text">Alt-Text (for screen readers)</label>
                        <input type="text" id="alt_text" name="alt_text" placeholder="Describe question purpose">

                        <div class="action-buttons">
                            <button type="submit" class="btn">Add Question</button>
                            <a href="preview.php?survey_id=<?php echo $survey_id; ?>" class="btn-secondary">Preview Form</a>
                        </div>
                    </form>
                </div>

            </main>
        </div>
    </div>
    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>