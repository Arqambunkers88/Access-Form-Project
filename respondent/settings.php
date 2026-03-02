<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $font_size = $_POST['font_size'];
    $high_contrast = isset($_POST['high_contrast']) ? 1 : 0;
    $screen_reader = isset($_POST['screen_reader']) ? 1 : 0;
    $color_blind = isset($_POST['color_blind']) ? 1 : 0; // NEW Color Blind DB Save
    
    $update_stmt = $pdo->prepare("UPDATE AccessibilitySettings SET font_size = :font, high_contrast = :hc, screen_reader = :sr, color_blind = :cb WHERE user_id = :uid");
    $update_stmt->execute([
        ':font' => $font_size,
        ':hc' => $high_contrast,
        ':sr' => $screen_reader,
        ':cb' => $color_blind,
        ':uid' => $user_id
    ]);
    
    // Map DB settings to JS variables to update screen immediately
    $pixel_size = 16;
    if ($font_size == 'Large') $pixel_size = 20;
    if ($font_size == 'Extra Large') $pixel_size = 24;
    
    $theme = $high_contrast ? 'dark' : 'light';
    $sr_setting = $screen_reader ? 'true' : 'false';
    $cb_setting = $color_blind ? 'true' : 'false'; // NEW LocalStorage Sync

    echo "<script>
            localStorage.setItem('theme', '$theme');
            localStorage.setItem('fontSize', '$pixel_size');
            localStorage.setItem('screenReader', '$sr_setting');
            localStorage.setItem('colorBlind', '$cb_setting');
            alert('Accessibility settings saved successfully!');
            window.location.href = 'settings.php';
          </script>";
    exit();
}

// Fetch Current Settings
$stmt = $pdo->prepare("SELECT * FROM AccessibilitySettings WHERE user_id = :uid");
$stmt->execute([':uid' => $user_id]);
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$settings) {
    $settings =['font_size' => 'Normal', 'high_contrast' => 0, 'screen_reader' => 0, 'color_blind' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Respondent Settings - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page">
    <div class="app-container">
        <header class="top-header" role="banner">
            <h1>Accessibility Settings</h1>
            <div class="header-a11y">
                <button id="decrease-font">A-</button>
                <button id="increase-font">A+</button>
                <button id="toggle-contrast">◐</button>
                <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
                <button id="toggle-speech">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar with Settings Tab -->
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Available Surveys</a>
                <a href="settings.php" class="active">Settings</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <div class="form-card" tabindex="0" style="max-width: 600px;">
                    <h2>System Preferences</h2>
                    <p>Adjust your accessibility settings below. These will be saved to your profile.</p>
                    
                    <form action="settings.php" method="POST">
                        <label for="font_size">Default Font Size</label>
                        <select id="font_size" name="font_size">
                            <option value="Normal" <?php echo $settings['font_size'] == 'Normal' ? 'selected' : ''; ?>>Normal (16px)</option>
                            <option value="Large" <?php echo $settings['font_size'] == 'Large' ? 'selected' : ''; ?>>Large (20px)</option>
                            <option value="Extra Large" <?php echo $settings['font_size'] == 'Extra Large' ? 'selected' : ''; ?>>Extra Large (24px)</option>
                        </select>

                        <div style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; font-weight: normal; cursor: pointer;">
                                <input type="checkbox" name="high_contrast" value="1" <?php echo $settings['high_contrast'] ? 'checked' : ''; ?> style="width: auto; margin: 0 10px 0 0;">
                                Enable High Contrast Mode by Default
                            </label>
                        </div>

                        <div style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; font-weight: normal; cursor: pointer;">
                                <input type="checkbox" name="screen_reader" value="1" <?php echo $settings['screen_reader'] ? 'checked' : ''; ?> style="width: auto; margin: 0 10px 0 0;">
                                Enable Screen Reader Mode by Default
                            </label>
                        </div>

                        <div style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; font-weight: normal; cursor: pointer;">
                                <input type="checkbox" name="color_blind" value="1" <?php echo $settings['color_blind'] ? 'checked' : ''; ?> style="width: auto; margin: 0 10px 0 0;">
                                Enable Color-Blind Safe Palette
                            </label>
                        </div>

                        <div class="action-buttons" style="margin-top: 30px;">
                            <button type="submit" class="btn">Save Settings</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>