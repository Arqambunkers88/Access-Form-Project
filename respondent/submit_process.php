<?php
session_start();
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $survey_id = intval($_POST['survey_id']);
    $answers = $_POST['answers']; 

    try {
        $guest_name = $_SESSION['guest_name'];
        $guest_email = $_SESSION['guest_email'];
        $guest_disability = $_SESSION['guest_disability'];

        // 1. Check if email exists
        $check_email = $pdo->prepare("SELECT user_id FROM user WHERE email = :email");
        $check_email->execute([':email' => $guest_email]);
        $existing_user = $check_email->fetch();

        if ($existing_user) {
            $respondent_id = $existing_user['user_id'];
        } else {
            // Save guest to database!
            $insert_guest = $pdo->prepare("INSERT INTO user (name, email, password, role, disability_profile) VALUES (:n, :e, 'guest_no_pass', 'Respondent', :d)");
            $insert_guest->execute([':n' => $guest_name, ':e' => $guest_email, ':d' => $guest_disability]);
            $respondent_id = $pdo->lastInsertId();
        }

        // 2. Prevent Duplicates
        $check_stmt = $pdo->prepare("SELECT response_id FROM response WHERE survey_id = :sid AND respondent_id = :rid");
        $check_stmt->execute([':sid' => $survey_id, ':rid' => $respondent_id]);
        if ($check_stmt->fetch()) {
            echo "<script>alert('You have already submitted this survey.'); window.location.href='../index.php';</script>";
            exit();
        }

        // 3. Save Response
        $resp_stmt = $pdo->prepare("INSERT INTO response (survey_id, respondent_id) VALUES (:sid, :rid)");
        $resp_stmt->execute([':sid' => $survey_id, ':rid' => $respondent_id]);
        $response_id = $pdo->lastInsertId();

        // 4. Save Answers
        $ans_stmt = $pdo->prepare("INSERT INTO answer (response_id, question_id, answer_text) VALUES (:rid, :qid, :text)");
        foreach ($answers as $q_id => $ans_text) {
            $ans_stmt->execute([':rid' => $response_id, ':qid' => $q_id, ':text' => htmlspecialchars(trim($ans_text))]);
        }

        // CLEAR GUEST SESSION SO THEY CAN TAKE ANOTHER SURVEY FRESHLY LATER
        session_unset();
        session_destroy();

        header("Location: submission_complete.php");
        exit();

    } catch (PDOException $e) {
        die("Error submitting survey: " . $e->getMessage());
    }
}
?>