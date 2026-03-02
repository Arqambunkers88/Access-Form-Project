<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Access Form</title>
    <link rel="stylesheet" href="assets/css/style.css?v=6">
</head>
<body>

    <main class="auth-card" role="main" aria-labelledby="register-title">
        
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

        <h2 id="register-title">Create Your Account</h2>

        <form action="register_process.php" method="POST">
            
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required aria-required="true" aria-label="Full Name" data-automic="true">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required aria-required="true" aria-label="Email Address" data-automic="true">

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required aria-required="true" aria-label="Password" data-automic="true">
                <button type="button" id="toggle-password" class="password-toggle" aria-label="Show password toggle">👁️</button>
            </div>

            <label for="role">User Role</label>
            <!-- Added onchange event here -->
            <select id="role" name="role" required aria-required="true" aria-label="User Role" onchange="toggleDisability()">
                <option value="Respondent">Respondent (Fill Surveys)</option>
                <option value="Form Creator">Form Creator (Make Surveys)</option>
                <option value="Admin">Admin (Manage System)</option>
            </select>

            <!-- Wrapped in a div so we can hide/show it -->
            <div id="disability-box">
                <label for="disability">Disability Profile (Auto-configures settings)</label>
                <select id="disability" name="disability" aria-required="true" aria-label="Disability Profile">
                    <option value="none">None / Prefer not to say</option>
                    <option value="visual">Visual Impairment</option>
                    <option value="colorblind">Color Blindness</option>
                    <option value="physical">Physical/Motor Impairment</option>
                </select>
            </div>

            <button type="submit" class="btn" style="margin-top: 20px;" aria-label="Register Button">Register</button>
        </form>

        <p class="helper-text">
            Already have an account? <a href="index.php" style="color: var(--primary-color);">Login here</a>.<br><br>
            <small>Accessible form · Keyboard navigation · Screen-reader friendly · WCAG 2.1 compliant</small>
        </p>
    </main>

    <!-- Version 6 forces browser to load new JS -->
    <script src="assets/js/accessibility.js?v=6"></script>
    
    <!-- Script to hide/show Disability field -->
    <script>
        function toggleDisability() {
            var role = document.getElementById("role").value;
            var disabilityBox = document.getElementById("disability-box");
            var disabilitySelect = document.getElementById("disability");
            
            if (role === "Respondent") {
                disabilityBox.style.display = "block";
            } else {
                disabilityBox.style.display = "none";
                disabilitySelect.value = "none"; // Reset value if hidden
            }
        }
        // Run once when page loads
        toggleDisability();
    </script>
</body>
</html>