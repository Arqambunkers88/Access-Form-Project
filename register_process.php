<?php
require_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // UPDATE: Backend security now checks for lowercase ([a-z]) as well!
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[\W_]/", $password)) {
        echo "<script>alert('Security Error: Password does not meet the strong password requirements. It must include an uppercase letter, lowercase letter, number, and special character.'); window.history.back();</script>";
        exit();;
    }

    // Everyone is automatically a Form Creator.
    $role = 'Form Creator'; 
    $profile = isset($_POST['disability']) ? $_POST['disability'] : 'none';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 1. Insert User
        $sql = "INSERT INTO user (name, email, password, role, disability_profile) VALUES (:name, :email, :password, :role, :profile)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name, 
            ':email' => $email, 
            ':password' => $hashed_password, 
            ':role' => $role,
            ':profile' => $profile
        ]);

        $new_user_id = $pdo->lastInsertId();

        // 2. SMART AUTO-CONFIGURATION
        $font_size = 'Normal';
        $high_contrast = 0;
        $screen_reader = 0;
        $color_blind = 0;

        if ($profile === 'visual') {
            $font_size = 'Extra Large';
            $high_contrast = 1;
            $screen_reader = 1;
        } elseif ($profile === 'colorblind') {
            $color_blind = 1;
        } elseif ($profile === 'physical') {
            $screen_reader = 1; 
        }

        $sql_settings = "INSERT INTO accessibilitysettings (user_id, font_size, high_contrast, screen_reader, color_blind) 
                         VALUES (:uid, :font, :hc, :sr, :cb)";
        $stmt_settings = $pdo->prepare($sql_settings);
        $stmt_settings->execute([
            ':uid' => $new_user_id,
            ':font' => $font_size,
            ':hc' => $high_contrast,
            ':sr' => $screen_reader,
            ':cb' => $color_blind
        ]);

        echo "<script>alert('Registration successful! Please login.'); window.location.href = 'login.php';</script>";

    } catch (PDOException $e) {
        echo "<script>alert('Error: Email might already be registered.'); window.history.back();</script>";
    }
}
?>