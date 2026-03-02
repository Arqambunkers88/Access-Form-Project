<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Access Form</title>
    <!-- Version 7 for CSS cache busting -->
    <link rel="stylesheet" href="assets/css/style.css?v=7">
</head>
<body>

    <main class="auth-card" role="main" aria-labelledby="login-title">
        
        <div class="a11y-controls" aria-label="Accessibility Controls">
            <button type="button" id="decrease-font" aria-label="Decrease Font Size">A-</button>
            <button type="button" id="increase-font" aria-label="Increase Font Size">A+</button>
            <button type="button" id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
            <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
            <button type="button" id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <img src="assets/images/access_form_logo.svg" 
                 alt="Access Form System Logo. A white survey document with the universal accessibility symbol on a blue background." 
                 style="width: 120px; height: auto;" tabindex="0">
        </div>

        <h2 id="login-title">Login to Access Form</h2>

        <form action="login_process.php" method="POST">
            
            <label for="email">Email Address</label>
            <!-- data-automic="true" added here -->
            <input type="email" id="email" name="email" required aria-required="true" aria-label="Email Address" data-automic="true">

            <label for="password">Password</label>
            <div class="password-container">
                <!-- data-automic="true" added here -->
                <input type="password" id="password" name="password" required aria-required="true" aria-label="Password" data-automic="true">
                <button type="button" id="toggle-password" class="password-toggle" aria-label="Show password toggle">👁️</button>
            </div>

            <button type="submit" class="btn" style="margin-top: 20px;" aria-label="Login Button">Login</button>
        </form>

        <p class="helper-text">
            Don't have an account? <a href="register.php" style="color: var(--primary-color);">Register here</a>.<br><br>
            <small>Screen-reader supported · Keyboard navigation enabled · WCAG 2.1 compliant</small>
        </p>
    </main>

    <!-- Version 7 forces browser to load the latest JS engine -->
    <script src="assets/js/accessibility.js?v=7"></script>
</body>
</html>