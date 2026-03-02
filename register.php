<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Access Form</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
</head>
<body>

    <main class="auth-card" role="main" aria-labelledby="register-title">
        
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
            <img src="assets/images/access_form_logo.svg" 
                 alt="Access Form Logo. A white survey document with the universal accessibility symbol on a blue background." 
                 style="width: 120px; height: auto;" tabindex="0">
        </div>

        <h2 id="register-title">Create Your Account</h2>

        <form action="register_process.php" method="POST">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required aria-required="true">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required aria-required="true">

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required aria-required="true">
                <button type="button" id="toggle-password" class="password-toggle" aria-label="Show password">👁️</button>
            </div>

            <label for="role">User Role</label>
            <select id="role" name="role" required aria-required="true">
                <option value="Respondent">Respondent (Fill Surveys)</option>
                <option value="Form Creator">Form Creator (Make Surveys)</option>
                <option value="Admin">Admin (Manage System)</option>
            </select>

            <!-- NEW: Disability Profile Auto-Configuration -->
            <label for="disability">Disability Profile (Auto-configures settings)</label>
            <select id="disability" name="disability" aria-required="true">
                <option value="none">None / Prefer not to say</option>
                <option value="visual">Visual Impairment (Auto-enables Screen Reader & High Contrast)</option>
                <option value="colorblind">Color Blindness (Auto-enables Color-Blind Palette)</option>
                <option value="physical">Physical/Motor Impairment (Relies on Voice-to-Text)</option>
            </select>

            <button type="submit" class="btn" style="margin-top: 20px;">Register</button>
        </form>

        <p class="helper-text">
            Already have an account? <a href="index.php" style="color: var(--primary-color);">Login here</a>.<br><br>
            <small>Accessible form · Keyboard navigation · Screen-reader friendly · WCAG 2.1 compliant</small>
        </p>
    </main>

    <script src="assets/js/accessibility.js?v=3"></script>
</body>
</html>