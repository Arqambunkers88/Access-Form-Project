<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Survey - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>

    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header" role="banner">
            <h1>Create Survey</h1>
        </header>

        <div class="dashboard-body">
            <!-- Sidebar -->
            <nav class="sidebar" role="navigation" aria-label="Main Navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="create_survey.php" class="active" aria-current="page">Create Survey</a>
                <a href="my_surveys.php">My Surveys</a>
                <a href="responses.php">Responses</a>
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

    <script src="../assets/js/accessibility.js?v=3"></script>
</body>
</html>