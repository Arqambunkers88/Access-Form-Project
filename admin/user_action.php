<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Only Admin can perform these actions
    if ($_SESSION['role'] !== 'Admin') {
        die("Unauthorized Action");
    }

    $target_user_id = intval($_POST['user_id']);
    $action = $_POST['action'];

    try {
        if ($action === 'toggle_status') {
            // Flip the boolean status
            $new_status = $_POST['current_status'] ? 0 : 1; 
            $stmt = $pdo->prepare("UPDATE User SET is_disabled = :status WHERE user_id = :uid");
            $stmt->execute([':status' => $new_status, ':uid' => $target_user_id]);
            $msg = "User status updated.";

        } elseif ($action === 'delete') {
            // Delete the user (ON DELETE CASCADE in DB handles related data)
            $stmt = $pdo->prepare("DELETE FROM User WHERE user_id = :uid");
            $stmt->execute([':uid' => $target_user_id]);
            $msg = "User permanently deleted.";
        }

        echo "<script>alert('$msg'); window.location.href='users.php';</script>";

    } catch (PDOException $e) {
        die("Error processing user action: " . $e->getMessage());
    }
} else {
    header("Location: users.php");
}
?>