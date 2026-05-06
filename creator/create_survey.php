<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Survey - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>

    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header" role="banner">
            <h1>Create Survey</h1>

            <!-- ADDED: Global Accessibility Controls -->
            <div class="header-a11y" aria-label="Accessibility Controls">
                <button type="button" id="decrease-font" aria-label="Decrease Font Size">A-</button>
                <button type="button" id="increase-font" aria-label="Increase Font Size">A+</button>
                <button type="button" id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
                <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
                <button type="button" id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="create_survey.php" class="active" aria-current="page">Create Survey</a>
                <a href="my_surveys.php">My Surveys</a>
                <a href="responses.php">Responses</a>
                <a href="settings.php">Settings</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content" role="main">
                
                <div class="form-card" tabindex="0">
                    <form action="create_survey_process.php" method="POST">
                        <label for="title">Survey Title</label>
                        <input type="text" id="title" name="title" required aria-required="true">

                        <label for="description">Survey Description</label>
                        <textarea id="description" name="description" aria-required="false"></textarea>

                        <label for="category">Survey Category</label>
                        <select id="category" name="category">
                            <option value="Feedback">Feedback</option>
                            <option value="Education">Education</option>
                            <option value="Health">Health</option>
                            <option value="General">General</option>
                        </select>

                        <div class="action-buttons">
                            <button type="submit" class="btn">Create Survey</button>
                        </div>
                    </form>

                    <p class="helper-text" style="text-align: left; margin-top: 20px;">
                        <small>Fully accessible form · Keyboard friendly · Screen-reader supported · WCAG 2.1 compliant</small>
                    </p>
                </div>

            </main>
        </div>
    </div>

    <script src="../assets/js/accessibility.js?v=60"></script>
</body>
</html>