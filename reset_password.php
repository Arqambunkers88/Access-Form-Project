<?php
require_once 'includes/db_connection.php';
$msg = "";

if (!isset($_GET['token']) && !isset($_POST['token'])) {
    die("Invalid or missing password reset token.");
}

$token = isset($_GET['token']) ? $_GET['token'] : $_POST['token'];

// Verify Token and Expiry Time
$stmt = $pdo->prepare("SELECT user_id FROM user WHERE reset_token = :token AND token_expire > NOW()");
$stmt->execute([':token' => $token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'><h2>Expired Link</h2><p>This password reset link is invalid or has expired. Please request a new one.</p><a href='forgot_password.php'>Go back</a></div>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    
    // Exact same security rules as Registration
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[\W_]/", $password)) {
        $msg = "<div style='background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px; font-size:14px;'>
                <strong>Weak Password!</strong> It must be at least 8 characters long, contain 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.
                </div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear the token so it can't be used again
        $update = $pdo->prepare("UPDATE user SET password = :pass, reset_token = NULL, token_expire = NULL WHERE reset_token = :token");
        $update->execute([':pass' => $hashed_password, ':token' => $token]);
        
        echo "<script>alert('Password reset successfully! You can now login.'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password - Access Form</title>
    <!-- CSS version bump to prevent cache -->
    <link rel="stylesheet" href="assets/css/style.css?v=68">
    <style>
        /* Edge/Chrome default eye hide */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }

        /* Password Rules Styling */
        .pwd-rule { font-size: 0.85em; margin: 5px 0; color: #dc3545; transition: color 0.3s; font-weight: bold; text-align: left; }
        .pwd-rule.valid { color: #28a745; }
    </style>
</head>
<body>
    <main class="auth-card" role="main">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="assets/images/access_form_logo.png" alt="Access Form System Logo" style="width: 120px; height: auto;" tabindex="0">
        </div>
        
        <h2 style="text-align: center;">Create New Password</h2>
        
        <?php echo $msg; ?>

        <form action="" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <label for="password">New Password</label>
            
            <!-- Simple clean container (Accessibility.js will inject the SVG here automatically) -->
            <div class="password-container">
                <input type="password" id="password" name="password" required aria-required="true" data-automic="true" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}">
                <button type="button" id="toggle-password" class="password-toggle" aria-label="Show password toggle">👁️</button>
            </div>

            <!-- Password Requirements List -->
            <div id="pwd-rules" style="display: none; margin-top: 10px; background-color: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid var(--border-color);">
                <div id="rule-length" class="pwd-rule">❌ Minimum 8 characters</div>
                <div id="rule-upper" class="pwd-rule">❌ At least 1 uppercase letter</div>
                <div id="rule-lower" class="pwd-rule">❌ At least 1 lowercase letter</div>
                <div id="rule-number" class="pwd-rule">❌ At least 1 number</div>
                <div id="rule-special" class="pwd-rule">❌ At least 1 special character (@, #, $, etc.)</div>
            </div>

            <button type="submit" class="btn" style="margin-top: 20px;">Save New Password</button>
        </form>
    </main>

    <script src="assets/js/accessibility.js?v=68"></script>
    <script>
        const passwordInput = document.getElementById('password');
        const pwdRules = document.getElementById('pwd-rules');

        // Show rules only when field is focused
        passwordInput.addEventListener('focus', function() {
            pwdRules.style.display = 'block';
        });

        passwordInput.addEventListener('blur', function() {
            pwdRules.style.display = 'none';
        });

        // Real-time Ticks Validation (You requested this to stay, and it is 100% here!)
        passwordInput.addEventListener('input', function() {
            const val = this.value;
            
            const lengthRule = document.getElementById('rule-length');
            if(val.length >= 8) { lengthRule.innerHTML = '✅ Minimum 8 characters'; lengthRule.className = 'pwd-rule valid'; }
            else { lengthRule.innerHTML = '❌ Minimum 8 characters'; lengthRule.className = 'pwd-rule'; }

            const upperRule = document.getElementById('rule-upper');
            if(/[A-Z]/.test(val)) { upperRule.innerHTML = '✅ At least 1 uppercase letter'; upperRule.className = 'pwd-rule valid'; }
            else { upperRule.innerHTML = '❌ At least 1 uppercase letter'; upperRule.className = 'pwd-rule'; }

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