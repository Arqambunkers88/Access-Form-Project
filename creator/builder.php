<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) {
    echo "<script>alert('Invalid Survey ID'); window.location.href='dashboard.php';</script>";
    exit();
}
$survey_id = intval($_GET['survey_id']);
$creator_id = $_SESSION['user_id'];

$survey_stmt = $pdo->prepare("SELECT title, status FROM survey WHERE survey_id = :id AND creator_id = :creator_id");
$survey_stmt->execute([':id' => $survey_id, ':creator_id' => $creator_id]);
$survey = $survey_stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Error: Survey not found.'); window.location.href='dashboard.php';</script>";
    exit();
}

if ($survey['status'] !== 'Draft') {
    echo "<script>alert('This survey is already Published and cannot be edited.'); window.location.href='dashboard.php';</script>";
    exit();
}

$questions_stmt = $pdo->prepare("SELECT * FROM question WHERE survey_id = :id ORDER BY question_id ASC");
$questions_stmt->execute([':id' => $survey_id]);
$existing_questions = $questions_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Builder - Access Form</title>
    <!-- Version 41 -->
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page">

    <div class="app-container">
        <header class="top-header" role="banner">
            <h1>Form Builder: <?php echo htmlspecialchars($survey['title']); ?></h1>
            <div class="header-a11y" aria-label="Accessibility Controls">
                <button type="button" id="decrease-font">A-</button>
                <button type="button" id="increase-font">A+</button>
                <button type="button" id="toggle-contrast">◐</button>
                <button type="button" id="toggle-colorblind">🎨</button>
                <button type="button" id="toggle-speech">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="builder.php?survey_id=<?php echo $survey_id; ?>" class="active">Form Builder</a>
                <a href="preview.php?survey_id=<?php echo $survey_id; ?>">Preview</a>
                <a href="publish_process.php?survey_id=<?php echo $survey_id; ?>" onclick="return confirm('Publish this survey?');">Publish</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                
                <!-- ADD NEW ITEM SECTION -->
                <div class="form-card" tabindex="0" style="max-width: 800px; margin-bottom: 40px; border-top: 4px solid var(--primary-color);">
                    <h2>Add New Item</h2>
                    <form action="add_question_process.php" method="POST">
                        <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey_id); ?>">

                        <label for="question_text">Text (Question or Section Title)</label>
                        <textarea id="question_text" name="question_text" required aria-required="true" style="min-height: 80px;"></textarea>

                        <label for="question_type">Type</label>
                        <select id="question_type" name="question_type" required aria-required="true" onchange="toggleBranching()">
                            <option value="Text">Text Answer</option>
                            <option value="Multiple Choice">Multiple Choice</option>
                            <option value="Rating">Rating (1 to 5)</option>
                            <option value="Boolean">Boolean (Yes / Maybe / No)</option>
                            <option value="Section" style="font-weight: bold; color: blue;">➡ Add Section Header (Group Divider)</option>
                        </select>

                        <label for="alt_text">Alt-Text (for screen readers)</label>
                        <input type="text" id="alt_text" name="alt_text" placeholder="Describe purpose or leave blank">

                        <!-- NEW: BRANCHING LOGIC UI -->
                        <div id="branching-section" style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border: 1px dashed var(--border-color); border-radius: 5px;">
                            <h3 style="margin-top: 0; font-size: 1.1em; color: var(--primary-color);">Branching Logic (Optional)</h3>
                            <p style="font-size: 0.9em; margin-bottom: 15px; color: #555;">Show this question ONLY IF a previous question has a specific answer.</p>
                            
                            <label>Show this ONLY IF Question:</label>
                            <select name="condition_question_id">
                                <option value="">Always Show (No Condition)</option>
                                <?php foreach($existing_questions as $eq): ?>
                                    <?php if($eq['question_type'] != 'Section'): ?>
                                        <?php $clean_q = htmlspecialchars(trim(strip_tags(preg_replace('/\[Alt:.*?\]/', '', $eq['question_text'])))); ?>
                                        <option value="<?php echo $eq['question_id']; ?>">Q: <?php echo substr($clean_q, 0, 60) . '...'; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>

                            <label>Equals Answer (e.g., Yes, Agree, 5):</label>
                            <input type="text" name="condition_answer" placeholder="Type exact answer (Leave blank if no condition)">
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn">Add Item</button>
                            <a href="preview.php?survey_id=<?php echo $survey_id; ?>" class="btn-secondary">Preview Form</a>
                        </div>
                    </form>
                </div>

                <!-- EXISTING QUESTIONS SECTION -->
                <?php if (count($existing_questions) > 0): ?>
                <div class="form-card" tabindex="0" style="max-width: 800px;">
                    <h2 style="margin-bottom: 20px;">Existing Questions & Sections</h2>
                    <ul style="list-style-type: none; padding: 0;">
                        <?php 
                        $q_num = 1;
                        foreach ($existing_questions as $index => $q): 
                            $display_text = preg_replace('/\[Alt:.*?\]/', '', $q['question_text']); 
                            
                            if ($q['question_type'] == 'Section'):
                        ?>
                            <li style="background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 15px; border-left: 5px solid var(--primary-color);">
                                <strong style="color: var(--primary-color); font-size: 1.1em;">Section Header:</strong> <br>
                                <?php echo htmlspecialchars(trim($display_text)); ?>
                                
                                <div style="margin-top: 10px;">
                                    <a href="edit_question.php?question_id=<?php echo $q['question_id']; ?>&survey_id=<?php echo $survey_id; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; margin-left: 0;">Edit</a>
                                    <a href="delete_question.php?question_id=<?php echo $q['question_id']; ?>&survey_id=<?php echo $survey_id; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #dc3545;" onclick="return confirm('Delete this section?');">Delete</a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                                <strong>Q<?php echo $q_num; ?>:</strong> <?php echo htmlspecialchars(trim($display_text)); ?> 
                                <br><small><em>Type: <?php echo htmlspecialchars($q['question_type']); ?></em></small>
                                
                                <!-- Show Branching Info if exists -->
                                <?php if(!empty($q['condition_question_id'])): ?>
                                    <br><span style="background-color: #ffc107; color: black; padding: 2px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold;">Branching Active: Shows only if Q-ID <?php echo $q['condition_question_id']; ?> = "<?php echo htmlspecialchars($q['condition_answer']); ?>"</span>
                                <?php endif; ?>

                                <div style="margin-top: 10px;">
                                    <a href="edit_question.php?question_id=<?php echo $q['question_id']; ?>&survey_id=<?php echo $survey_id; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; margin-left: 0;">Edit</a>
                                    <a href="delete_question.php?question_id=<?php echo $q['question_id']; ?>&survey_id=<?php echo $survey_id; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 14px; background-color: #dc3545;" onclick="return confirm('Delete this question?');">Delete</a>
                                </div>
                            </li>
                        <?php 
                            $q_num++; 
                            endif; 
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
    
    <script src="../assets/js/accessibility.js?v=60"></script>
    <script>
        // Section header par branching apply nahi hoti, is liye usay chupa dein
        function toggleBranching() {
            var type = document.getElementById('question_type').value;
            var branchingSection = document.getElementById('branching-section');
            if (type === 'Section') {
                branchingSection.style.display = 'none';
            } else {
                branchingSection.style.display = 'block';
            }
        }
        toggleBranching();
    </script>
</body>
</html>