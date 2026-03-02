<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Access Form</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
</head>
<body>

    <main class="auth-card" role="main" aria-labelledby="login-title">
        
        <!-- Accessibility Controls (Now Includes Speaker Icon!) -->
        <div class="a11y-controls" aria-label="Accessibility Controls">
            <button type="button" id="decrease-font" aria-label="Decrease Font Size">A-</button>
            <button type="button" id="increase-font" aria-label="Increase Font Size">A+</button>
            <button type="button" id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
            <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
            <button type="button" id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
        </div>

        <!-- WCAG 2.1 Alt Text Image Demonstration -->
        <!-- Virtual University Logo with ALT TEXT -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="assets/images/vu_logo.png" 
                 alt="Virtual University Logo. A blue and white emblem representing the university." 
                 style="width: 150px; height: auto;" tabindex="0">
        </div>

        <h2 id="login-title">Login to Access Form</h2>

        <form action="login_process.php" method="POST">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required aria-required="true">

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required aria-required="true">
                <button type="button" id="toggle-password" class="password-toggle" aria-label="Show password">👁️</button>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="helper-text">
            Don't have an account? <a href="register.php" style="color: var(--primary-color);">Register here</a>.<br><br>
            <small>Screen-reader supported · Keyboard navigation enabled · WCAG 2.1 compliant</small>
        </p>
    </main>

    <script src="assets/js/accessibility.js?v=3"></script>
</body>
</html>