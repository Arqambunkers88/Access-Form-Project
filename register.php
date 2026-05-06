<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Access Form</title>
    <!-- Version bump to 36 -->
    <link rel="stylesheet" href="assets/css/style.css?v=60">
    <style>
        /* Password Rules Styling */
        .pwd-rule { font-size: 0.85em; margin: 5px 0; color: #dc3545; transition: color 0.3s; font-weight: bold; text-align: left; }
        .pwd-rule.valid { color: #28a745; }
    </style>
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
            <img src="assets/images/access_form_logo.png" 
                 alt="Access Form System Logo" 
                 style="width: 120px; height: auto;" tabindex="0">
        </div>

        <h2 id="register-title">Create Your Account</h2>

        <form action="register_process.php" method="POST" id="registerForm">
            
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required aria-required="true" aria-label="Full Name" data-automic="true">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required aria-required="true" aria-label="Email Address" data-automic="true">

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required aria-required="true" 
                       aria-label="Password" aria-describedby="pwd-rules" data-automic="true"
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                       title="Must contain at least one number, one uppercase letter, one special character, and be at least 8 characters long">
                <button type="button" id="toggle-password" class="password-toggle" aria-label="Show password toggle">👁️</button>
            </div>

            <!-- FIXED: Hidden by default (display: none;) -->
            <div id="pwd-rules" style="display: none; margin-top: 10px; background-color: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid var(--border-color);">
                <div id="rule-length" class="pwd-rule">❌ Minimum 8 characters</div>
                <div id="rule-upper" class="pwd-rule">❌ At least 1 uppercase letter</div>
                <div id="rule-lower" class="pwd-rule">❌ At least 1 lowercase letter</div>
                <div id="rule-number" class="pwd-rule">❌ At least 1 number</div>
                <div id="rule-special" class="pwd-rule">❌ At least 1 special character (@, #, $, etc.)</div>
            </div>

            <div>
                <label for="disability">Disability Profile (Auto-configures settings)</label>
                <select id="disability" name="disability" aria-required="true" aria-label="Disability Profile">
                    <option value="none">None / Prefer not to say</option>
                    <option value="visual">Visual Impairment</option>
                    <option value="colorblind">Color Blindness</option>
                    <option value="physical">Physical/Motor Impairment</option>
                </select>
            </div>

            <button type="submit" id="submit-btn" class="btn" style="margin-top: 20px;" aria-label="Register Button">Register</button>
        </form>

        <p class="helper-text">
            Already have an account? <a href="login.php" style="color: var(--primary-color);">Login here</a>.<br><br>
            <small>Accessible form · Keyboard navigation · Screen-reader friendly</small>
        </p>
    </main>

    <script src="assets/js/accessibility.js?v=60"></script>
    
    <script>
        const pwdInput = document.getElementById('password');
        const pwdRules = document.getElementById('pwd-rules');

        // FIXED: Show rules only when typing or focused
        pwdInput.addEventListener('focus', function() {
            pwdRules.style.display = 'block';
        });

        // Hide rules when clicking outside
        pwdInput.addEventListener('blur', function() {
            pwdRules.style.display = 'none';
        });

        pwdInput.addEventListener('input', function() {
            const val = this.value;
            
            const lengthRule = document.getElementById('rule-length');
            if(val.length >= 8) { lengthRule.innerHTML = '✅ Minimum 8 characters'; lengthRule.className = 'pwd-rule valid'; }
            else { lengthRule.innerHTML = '❌ Minimum 8 characters'; lengthRule.className = 'pwd-rule'; }

            const upperRule = document.getElementById('rule-upper');
            if(/[A-Z]/.test(val)) { upperRule.innerHTML = '✅ At least 1 uppercase letter'; upperRule.className = 'pwd-rule valid'; }
            else { upperRule.innerHTML = '❌ At least 1 uppercase letter'; upperRule.className = 'pwd-rule'; }
            
            // UPDATE: Lowercase checking logic added here
            const lowerRule = document.getElementById('rule-lower');
            if(/[a-z]/.test(val)) { lowerRule.innerHTML = '✅ At least 1 lowercase letter'; lowerRule.className = 'pwd-rule valid'; }
            else { lowerRule.innerHTML = '❌ At least 1 lowercase letter'; lowerRule.className = 'pwd-rule'; }

            const numberRule = document.getElementById('rule-number');
            if(/\d/.test(val)) { numberRule.innerHTML = '✅ At least 1 number'; numberRule.className = 'pwd-rule valid'; }
            else { numberRule.innerHTML = '❌ At least 1 number'; numberRule.className = 'pwd-rule'; }

            const specialRule = document.getElementById('rule-special');
            if(/[\W_]/.test(val)) { specialRule.innerHTML = '✅ At least 1 special character (@, #, $, etc.)'; specialRule.className = 'pwd-rule valid'; }
            else { specialRule.innerHTML = '❌ At least 1 special character (@, #, $, etc.)'; specialRule.className = 'pwd-rule'; }
        });
    </script>
</body>
</html>