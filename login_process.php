<?php
session_start();
require_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM User WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            
            if ($user['is_disabled']) {
                echo "<script>alert('Your account has been disabled by the Admin.'); window.history.back();</script>";
                exit();
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // DETERMINE REDIRECT URL
            if ($user['role'] === 'Admin') {
                $url = "admin/dashboard.php";
            } elseif ($user['role'] === 'Form Creator') {
                $url = "creator/dashboard.php";
            } else {
                $url = "respondent/dashboard.php";
            }

            // APPLY ACCESSIBILITY ONLY FOR RESPONDENTS
            if ($user['role'] === 'Respondent') {
                $stmt_a11y = $pdo->prepare("SELECT * FROM AccessibilitySettings WHERE user_id = :uid");
                $stmt_a11y->execute([':uid' => $user['user_id']]);
                $a11y = $stmt_a11y->fetch(PDO::FETCH_ASSOC);

                $theme = ($a11y && $a11y['high_contrast'] == 1) ? 'dark' : 'light';
                $sr_setting = ($a11y && $a11y['screen_reader'] == 1) ? 'true' : 'false';
                $cb_setting = ($a11y && $a11y['color_blind'] == 1) ? 'true' : 'false';
                
                $font_size = 16; 
                if ($a11y) {
                    if ($a11y['font_size'] == 'Large') $font_size = 20;
                    elseif ($a11y['font_size'] == 'Extra Large') $font_size = 24;
                }

                echo "<script>
                        localStorage.setItem('theme', '$theme');
                        localStorage.setItem('fontSize', '$font_size');
                        localStorage.setItem('screenReader', '$sr_setting');
                        localStorage.setItem('colorBlind', '$cb_setting');
                        window.location.href = '$url';
                      </script>";
                exit();
            } else {
                // ADMIN AND FORM CREATOR GET STANDARD SYSTEM DEFAULTS
                echo "<script>
                        localStorage.setItem('theme', 'light');
                        localStorage.setItem('fontSize', '16');
                        localStorage.setItem('screenReader', 'false');
                        localStorage.setItem('colorBlind', 'false');
                        window.location.href = '$url';
                      </script>";
                exit();
            }

            // INJECT SETTINGS INTO BROWSER AND REDIRECT
            echo "<script>
                    localStorage.setItem('theme', '$theme');
                    localStorage.setItem('fontSize', '$font_size');
                    localStorage.setItem('screenReader', '$sr_setting');
                    localStorage.setItem('colorBlind', '$cb_setting');
                    window.location.href = '$url';
                  </script>";
            exit();

        } else {
            echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>