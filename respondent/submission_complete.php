<?php require_once '../includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submission Complete - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body style="display: block; margin: 0; background-color: var(--bg-color);">

    <header class="top-header" role="banner">
        <h1>Submission Complete</h1>
        <div class="header-a11y">
            <button id="decrease-font">A-</button>
            <button id="increase-font">A+</button>
            <button id="toggle-contrast">◐</button>
            <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
            <button id="toggle-speech">🔊</button>
        </div>
    </header>

    <main role="main" style="display: flex; justify-content: center; padding: 100px 20px;">
        <div class="auth-card" tabindex="0" style="text-align: center; max-width: 500px;" aria-labelledby="thank-you-title">
            <h2 id="thank-you-title" style="color: #28a745; margin-bottom: 20px;">Thank You!</h2>
            <p style="font-size: 1.1em;">Your response has been submitted successfully.</p>
            <p style="margin-bottom: 30px; color: var(--border-color);">You may now close this page or return to the home screen.</p>
            
            <a href="dashboard.php" class="btn" aria-label="Return to Dashboard">Return to Home</a>
        </div>
    </main>

    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>