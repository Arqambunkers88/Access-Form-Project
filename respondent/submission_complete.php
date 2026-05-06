<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Complete - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
</head>
<body style="display: block; margin: 0; background-color: var(--bg-color);">

    <header class="top-header" role="banner">
        <h1 tabindex="0">Submission Complete</h1>
        <div class="header-a11y">
            <button id="decrease-font">A-</button><button id="increase-font">A+</button><button id="toggle-contrast">◐</button>
            <button type="button" id="toggle-colorblind">🎨</button><button id="toggle-speech">🔊</button>
        </div>
    </header>

    <main role="main" style="display: flex; justify-content: center; padding: 100px 20px; margin-top: 65px;">
        <div class="auth-card" style="text-align: center; max-width: 500px;">
            <h2 tabindex="0" style="color: #28a745; margin-bottom: 20px;">Thank You!</h2>
            <p tabindex="0" style="font-size: 1.1em;">Your response has been submitted successfully.</p>
            <p tabindex="0" style="margin-bottom: 30px; color: var(--border-color);">You may now return to the home screen.</p>
            
            <a href="../index.php" id="return-home" class="btn" tabindex="0">Return to Home</a>
        </div>
    </main>

    <script src="../assets/js/accessibility.js?v=60"></script>
    <script>
        // Jaise hi user wapis jaye ga, uski temporary accessibility settings khatam ho jayengi!
        document.getElementById('return-home').addEventListener('click', function() {
            localStorage.removeItem('theme');
            localStorage.removeItem('fontSize');
            localStorage.removeItem('screenReader');
            localStorage.removeItem('colorBlind');
        });
    </script>
</body>
</html>