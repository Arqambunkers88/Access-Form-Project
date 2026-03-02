<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $survey_id = intval($_POST['survey_id']);
    $respondent_id = $_SESSION['user_id'];
    $answers = $_POST['answers']; // This is an array:[question_id => answer_text]

    try {
        // 1. Check if user already submitted this survey to prevent duplicates
        $check_stmt = $pdo->prepare("SELECT response_id FROM Response WHERE survey_id = :sid AND respondent_id = :rid");
        $check_stmt->execute([':sid' => $survey_id, ':rid' => $respondent_id]);
        if ($check_stmt->fetch()) {
            echo "<script>alert('You have already submitted this survey.'); window.location.href='dashboard.php';</script>";
            exit();
        }

        // 2. Insert into Response Table
        $resp_stmt = $pdo->prepare("INSERT INTO Response (survey_id, respondent_id) VALUES (:sid, :rid)");
        $resp_stmt->execute([':sid' => $survey_id, ':rid' => $respondent_id]);
        $response_id = $pdo->lastInsertId();

        // 3. Insert each answer into Answer Table
        $ans_stmt = $pdo->prepare("INSERT INTO Answer (response_id, question_id, answer_text) VALUES (:rid, :qid, :text)");
        
        foreach ($answers as $q_id => $ans_text) {
            $ans_stmt->execute([
                ':rid' => $response_id,
                ':qid' => $q_id,
                ':text' => htmlspecialchars(trim($ans_text))
            ]);
        }

        // 4. Redirect to the Completion Screen
        header("Location: submission_complete.php");
        exit();

    } catch (PDOException $e) {
        die("Error submitting survey: " . $e->getMessage());
    }
}
?>