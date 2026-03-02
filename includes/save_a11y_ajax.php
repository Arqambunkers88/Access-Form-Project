<?php
session_start();
require_once 'db_connection.php';

// Only update if the user is currently logged in
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the JSON data sent from our JavaScript
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['user_id'];
    
    $font_size = isset($data['fontSize']) ? intval($data['fontSize']) : 16;
    $high_contrast = ($data['theme'] === 'dark') ? 1 : 0;
    $screen_reader = ($data['screenReader'] === 'true') ? 1 : 0;

    // Convert pixel size back to the words our database expects
    $db_font_size = 'Normal';
    if ($font_size >= 24) {
        $db_font_size = 'Extra Large';
    } elseif ($font_size >= 20) {
        $db_font_size = 'Large';
    }

    try {
        $color_blind = ($data['colorBlind'] === 'true') ? 1 : 0;

    try {
        $stmt = $pdo->prepare("UPDATE AccessibilitySettings SET font_size = :font, high_contrast = :hc, screen_reader = :sr, color_blind = :cb WHERE user_id = :uid");
        $stmt->execute([
            ':font' => $db_font_size,
            ':hc' => $high_contrast,
            ':sr' => $screen_reader,
            ':cb' => $color_blind,
            ':uid' => $user_id
        ]);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'unauthorized']);
}
?>