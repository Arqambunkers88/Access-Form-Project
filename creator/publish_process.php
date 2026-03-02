<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

// Accept both GET (from sidebar) and POST (from Preview page button)
$survey_id = isset($_POST['survey_id']) ? $_POST['survey_id'] : (isset($_GET['survey_id']) ? $_GET['survey_id'] : null);
$creator_id = $_SESSION['user_id'];

if (!$survey_id) {
    echo "<script>alert('Invalid Survey ID'); window.location.href='dashboard.php';</script>";
    exit();
}

try {
    // Check if survey actually has questions before publishing
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM Question WHERE survey_id = :id");
    $check_stmt->execute([':id' => $survey_id]);
    $question_count = $check_stmt->fetchColumn();

    if ($question_count == 0) {
        echo "<script>alert('Business Rules Violation: Cannot publish a survey without questions.'); window.location.href='builder.php?survey_id=$survey_id';</script>";
        exit();
    }

    // Update survey status to Published
    $sql = "UPDATE Survey SET status = 'Published' WHERE survey_id = :id AND creator_id = :creator_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $survey_id,
        ':creator_id' => $creator_id
    ]);

    // Redirect to dashboard with success message
    echo "<script>
            alert('Survey Published Successfully! It is now available to respondents.');
            window.location.href = 'dashboard.php';
          </script>";

} catch (PDOException $e) {
    die("Error publishing survey: " . $e->getMessage());
}
?>