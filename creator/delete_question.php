<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (isset($_GET['question_id']) && isset($_GET['survey_id'])) {
    $question_id = intval($_GET['question_id']);
    $survey_id = intval($_GET['survey_id']);

    $stmt = $pdo->prepare("DELETE FROM Question WHERE question_id = :qid AND survey_id = :sid");
    $stmt->execute([':qid' => $question_id, ':sid' => $survey_id]);

    header("Location: builder.php?survey_id=$survey_id");
    exit();
}
?>