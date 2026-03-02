<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = $_POST['question_id'];
    $survey_id = $_POST['survey_id'];
    $question_text = $_POST['question_text'];
    $question_type = $_POST['question_type'];
    
    $alt_text = !empty($_POST['alt_text']) ? "[Alt: " . $_POST['alt_text'] . "]" : "";
    $final_question_text = $question_text . " " . $alt_text;

    $stmt = $pdo->prepare("UPDATE Question SET question_text = :text, question_type = :type WHERE question_id = :qid AND survey_id = :sid");
    $stmt->execute([
        ':text' => trim($final_question_text),
        ':type' => $question_type,
        ':qid' => $question_id,
        ':sid' => $survey_id
    ]);

    header("Location: builder.php?survey_id=$survey_id");
    exit();
}
?>