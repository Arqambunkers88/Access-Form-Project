<?php
session_start();
require_once '../includes/db_connection.php';

// Form Submission Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['start_guest'])) {
    $guest_email = trim($_POST['guest_email']);
    $survey_id = intval($_POST['survey_id']);

    // Check if this email already submitted this survey
    $check_email = $pdo->prepare("SELECT user_id FROM user WHERE email = :email");
    $check_email->execute([':email' => $guest_email]);
    $existing_user = $check_email->fetch();

    if ($existing_user) {
        $check_resp = $pdo->prepare("SELECT response_id FROM response WHERE survey_id = :sid AND respondent_id = :rid");
        $check_resp->execute([':sid' => $survey_id, ':rid' => $existing_user['user_id']]);
        if ($check_resp->fetch()) {
            echo "<script>alert('You have already submitted this survey. Multiple submissions are not allowed.'); window.history.back();</script>";
            exit();
        }
    }

    $_SESSION['guest_name'] = trim($_POST['guest_name']);
    $_SESSION['guest_email'] = $guest_email;
    $_SESSION['guest_disability'] = $_POST['guest_disability'];
    
    header("Location: fill_survey.php?survey_id=" . $survey_id);
    exit();
}

if (!isset($_GET['survey_id'])) {
    echo "<script>alert('Invalid Survey ID'); window.location.href='../index.php';</script>";
    exit();
}
$survey_id = intval($_GET['survey_id']);

$stmt = $pdo->prepare("SELECT title, description FROM survey WHERE survey_id = :id AND status = 'Published'");
$stmt->execute([':id' => $survey_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Survey is closed or unavailable.'); window.location.href='../index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Survey - Access Form</title>
    <!-- Version 43 for Mobile Updates -->
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
    
    <script>
        localStorage.setItem('screenReader', 'true');
    </script>
</head>
<body style="display: block; margin: 0; background-color: var(--bg-color);">

    <header class="top-header" role="banner">
        <h1 tabindex="0">Participant Information</h1>
        <div class="header-a11y" aria-label="Accessibility Controls">
            <button type="button" id="decrease-font">A-</button>
            <button type="button" id="increase-font">A+</button>
            <button type="button" id="toggle-contrast">◐</button>
            <button type="button" id="toggle-colorblind">🎨</button>
            <button type="button" id="toggle-speech">🔊</button>
        </div>
    </header>

    <main role="main" style="display: flex; justify-content: center; padding: 50px 20px;">
        <div class="auth-card" style="max-width: 600px; width: 100%;">
            <h2 tabindex="0" style="color: var(--primary-color); margin-bottom: 5px;"><?php echo htmlspecialchars($survey['title']); ?></h2>
            <p tabindex="0" style="margin-bottom: 25px; color: #555;"><?php echo htmlspecialchars($survey['description']); ?></p>
            <p tabindex="0" style="margin-bottom: 25px; font-weight: bold;">Please provide your details. Press Tab to move to the next field.</p>
            
            <form action="start_survey.php?survey_id=<?php echo $survey_id; ?>" method="POST">
                <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">
                <input type="hidden" name="start_guest" value="1">

                <label for="guest_name" tabindex="0">Full Name <span style="color:red;">*</span></label>
                <input type="text" id="guest_name" name="guest_name" required placeholder="Enter your full name" aria-label="Full Name" data-automic="true">

                <label for="guest_email" tabindex="0">Email Address <span style="color:red;">*</span></label>
                <input type="email" id="guest_email" name="guest_email" required placeholder="Enter your email address" aria-label="Email Address" data-automic="true">

                <label for="guest_disability" tabindex="0" style="margin-top: 20px;">Do you have any accessibility needs? <span style="color:red;">*</span></label>
                
                <!-- FIXED: No onchange event here anymore! -->
                <select id="guest_disability" name="guest_disability" required aria-label="Disability Profile">
                    <option value="none">No, I do not need assistance</option>
                    <option value="visual">Visual Impairment</option>
                    <option value="colorblind">Color Blindness</option>
                    <option value="physical">Physical Impairment</option>
                </select>
                <small tabindex="0" style="display:block; margin-top: 5px;">Use arrow keys to change option.</small>

                <button type="submit" class="btn" style="margin-top: 30px;" aria-label="Proceed to Survey">Proceed to Survey</button>
            </form>
        </div>
    </main>

    <script src="../assets/js/accessibility.js?v=60"></script>
    <!-- FIXED: Removed the auto-apply JS logic -->
</body>
</html>