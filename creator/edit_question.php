<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['question_id']) || !isset($_GET['survey_id'])) {
    echo "<script>alert('Invalid Request'); window.location.href='dashboard.php';</script>";
    exit();
}

$question_id = intval($_GET['question_id']);
$survey_id = intval($_GET['survey_id']);

$stmt = $pdo->prepare("SELECT * FROM question WHERE question_id = :qid AND survey_id = :sid");
$stmt->execute([':qid' => $question_id, ':sid' => $survey_id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    echo "<script>alert('Item not found.'); window.location.href='builder.php?survey_id=$survey_id';</script>";
    exit();
}

$clean_text = preg_replace('/\[Alt:.*?\]/', '', $question['question_text']);
preg_match('/\[Alt:(.*?)\]/', $question['question_text'], $matches);
$alt_text = isset($matches[1]) ? trim($matches[1]) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page">
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1>Edit Item</h1>
        </header>

        <div class="dashboard-body">
            <main class="main-content" style="padding-top: 50px; display: flex; justify-content: center;">
                <div class="form-card" style="width: 100%; max-width: 600px;">
                    <h2>Update Information</h2>
                    <form action="edit_question_process.php" method="POST">
                        <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question_id); ?>">
                        <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey_id); ?>">

                        <label>Text</label>
                        <textarea name="question_text" required><?php echo htmlspecialchars(trim($clean_text)); ?></textarea>

                        <label>Type</label>
                        <select name="question_type" required>
                            <option value="Text" <?php if($question['question_type'] == 'Text') echo 'selected'; ?>>Text Answer</option>
                            <option value="Multiple Choice" <?php if($question['question_type'] == 'Multiple Choice') echo 'selected'; ?>>Multiple Choice</option>
                            <option value="Rating" <?php if($question['question_type'] == 'Rating') echo 'selected'; ?>>Rating (1 to 5)</option>
                            <option value="Boolean" <?php if($question['question_type'] == 'Boolean') echo 'selected'; ?>>Boolean (Yes / Maybe / No)</option>
                            <option value="Section" <?php if($question['question_type'] == 'Section') echo 'selected'; ?> style="font-weight: bold; color: blue;">➡ Section Header (Group Divider)</option>
                        </select>

                        <label>Alt-Text (for screen readers)</label>
                        <input type="text" name="alt_text" value="<?php echo htmlspecialchars($alt_text); ?>">

                        <div class="action-buttons" style="margin-top: 20px;">
                            <button type="submit" class="btn" style="width: auto;">Save Changes</button>
                            <a href="builder.php?survey_id=<?php echo $survey_id; ?>" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>