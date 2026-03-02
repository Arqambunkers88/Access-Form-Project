<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Check if a survey ID was provided
if (isset($_GET['survey_id'])) {
    $survey_id = intval($_GET['survey_id']);
    $creator_id = $_SESSION['user_id']; // For security

    try {
        // We ensure that the survey is ONLY deleted if it belongs to the logged-in creator
        $stmt = $pdo->prepare("DELETE FROM Survey WHERE survey_id = :sid AND creator_id = :cid");
        $stmt->execute([
            ':sid' => $survey_id,
            ':cid' => $creator_id
        ]);

        // Redirect back to dashboard with a success message
        echo "<script>
                alert('Survey deleted successfully.');
                window.location.href = 'dashboard.php';
              </script>";

    } catch (PDOException $e) {
        die("Error deleting survey: " . $e->getMessage());
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='dashboard.php';</script>";
}
?>