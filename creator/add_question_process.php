<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $survey_id = $_POST['survey_id'];
    $question_text = $_POST['question_text'];
    $question_type = $_POST['question_type'];
    
    // Alt-text is included in the UI. We append it as hidden helper text inside the question text for screen readers.
    $alt_text = !empty($_POST['alt_text']) ? "[Alt: " . $_POST['alt_text'] . "]" : "";
    $final_question_text = $question_text . $alt_text;

    try {
        // Insert Question into Database
        $sql = "INSERT INTO Question (question_text, question_type, survey_id) VALUES (:question_text, :question_type, :survey_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':question_text' => $final_question_text,
            ':question_type' => $question_type,
            ':survey_id' => $survey_id
        ]);

        // Success! Reload the builder page so they can add another question.
        echo "<script>
                alert('Question added successfully!');
                window.location.href = 'builder.php?survey_id=" . $survey_id . "';
              </script>";

    } catch (PDOException $e) {
        die("Error adding question: " . $e->getMessage());
    }
}
?>