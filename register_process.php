<?php
require_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // NEW: Grab the disability profile string
    $profile = isset($_POST['disability']) ? $_POST['disability'] : 'none';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 1. Insert User AND their disability profile
        $sql = "INSERT INTO User (name, email, password, role, disability_profile) VALUES (:name, :email, :password, :role, :profile)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name, 
            ':email' => $email, 
            ':password' => $hashed_password, 
            ':role' => $role,
            ':profile' => $profile // Save it to the database!
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

        $sql_settings = "INSERT INTO AccessibilitySettings (user_id, font_size, high_contrast, screen_reader, color_blind) 
                         VALUES (:uid, :font, :hc, :sr, :cb)";
        $stmt_settings = $pdo->prepare($sql_settings);
        $stmt_settings->execute([
            ':uid' => $new_user_id,
            ':font' => $font_size,
            ':hc' => $high_contrast,
            ':sr' => $screen_reader,
            ':cb' => $color_blind
        ]);

        echo "<script>alert('Registration successful! Please login.'); window.location.href = 'index.php';</script>";

    } catch (PDOException $e) {
        echo "<script>alert('Error: Email might already be registered.'); window.history.back();</script>";
    }
}
?>