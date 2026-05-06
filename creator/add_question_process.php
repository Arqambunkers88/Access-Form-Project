<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $survey_id = $_POST['survey_id'];
    $question_text = $_POST['question_text'];
    $question_type = $_POST['question_type'];
    
    $alt_text = !empty($_POST['alt_text']) ? "[Alt: " . $_POST['alt_text'] . "]" : "";
    $final_question_text = $question_text . " " . $alt_text;

    // NEW: Branching Logic Variables
    $condition_question_id = !empty($_POST['condition_question_id']) ? intval($_POST['condition_question_id']) : null;
    $condition_answer = !empty($_POST['condition_answer']) ? trim($_POST['condition_answer']) : null;

    try {
        // FIXED: Insert Question with Branching Logic
        $sql = "INSERT INTO question (question_text, question_type, survey_id, condition_question_id, condition_answer) 
                VALUES (:question_text, :question_type, :survey_id, :c_qid, :c_ans)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':question_text' => trim($final_question_text),
            ':question_type' => $question_type,
            ':survey_id' => $survey_id,
            ':c_qid' => $condition_question_id,
            ':c_ans' => $condition_answer
        ]);

        echo "<script>
                alert('Item added successfully!');
                window.location.href = 'builder.php?survey_id=" . $survey_id . "';
              </script>";

    } catch (PDOException $e) {
        die("Error adding question: " . $e->getMessage());
    }
}
?>