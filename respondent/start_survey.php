<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) {
    echo "<script>alert('Invalid Survey ID'); window.location.href='dashboard.php';</script>";
    exit();
}
$survey_id = intval($_GET['survey_id']);

// Fetch Survey Title
$stmt = $pdo->prepare("SELECT title FROM Survey WHERE survey_id = :id AND status = 'Published'");
$stmt->execute([':id' => $survey_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Survey is not available.'); window.location.href='dashboard.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Instructions - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body style="display: block; margin: 0; background-color: var(--bg-color);">

    <!-- Top Header matches Figure 24 -->
    <header class="top-header" role="banner">
        <h1>Survey Instructions</h1>
        <div class="header-a11y" aria-label="Accessibility Controls">
            <button id="decrease-font" aria-label="Decrease Font Size">A-</button>
            <button id="increase-font" aria-label="Increase Font Size">A+</button>
            <button id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
            <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
            <button id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
        </div>
    </header>

    <main role="main" style="display: flex; justify-content: center; padding: 50px 20px;">
        <div class="auth-card" tabindex="0" style="max-width: 500px;" aria-labelledby="survey-title">
            <h2 id="survey-title"><?php echo htmlspecialchars($survey['title']); ?></h2>
            <p>Please read the instructions before starting:</p>
            <ul>
                <li>You can complete this survey using keyboard only.</li>
                <li>Font size and contrast can be adjusted anytime.</li>
                <li><strong>Voice Submission is supported for text answers.</strong></li>
                <li>Your responses are secure.</li>
                <li>Estimated time: 3–5 minutes.</li>
            </ul>

            <a href="fill_survey.php?survey_id=<?php echo $survey_id; ?>" class="btn" style="text-align: center; margin-top: 30px;" aria-label="Start Survey">Start Survey</a>
        </div>
    </main>

    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>