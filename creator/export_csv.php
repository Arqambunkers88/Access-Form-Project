<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) die("Invalid Survey ID");
$survey_id = intval($_GET['survey_id']);

$stmt = $pdo->prepare("SELECT title FROM Survey WHERE survey_id = :sid");
$stmt->execute([':sid' => $survey_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) die("Survey not found");

$q_stmt = $pdo->prepare("SELECT question_id, question_text FROM Question WHERE survey_id = :sid ORDER BY question_id ASC");
$q_stmt->execute([':sid' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

$r_stmt = $pdo->prepare("
    SELECT r.response_id, a.question_id, a.answer_text 
    FROM Response r 
    JOIN Answer a ON r.response_id = a.response_id 
    WHERE r.survey_id = :sid 
    ORDER BY r.response_id ASC
");
$r_stmt->execute([':sid' => $survey_id]);
$answers_data = $r_stmt->fetchAll(PDO::FETCH_ASSOC);

$responses =[];
foreach ($answers_data as $row) {
    $responses[$row['response_id']][$row['question_id']] = $row['answer_text'];
}

$filename = preg_replace('/[^a-zA-Z0-9]/', '_', $survey['title']) . "_Admin_Report.csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// Force Excel to format the CSV into columns properly
fputs($output, "\xEF\xBB\xBF");

$headers = ['Response ID'];
foreach ($questions as $q) {
    $headers[] = trim(preg_replace('/\[Alt:.*?\]/', '', $q['question_text']));
}
fputcsv($output, $headers);

foreach ($responses as $resp_id => $ans_array) {
    $row =[str_pad($resp_id, 3, '0', STR_PAD_LEFT)];
    foreach ($questions as $q) {
        $row[] = isset($ans_array[$q['question_id']]) ? $ans_array[$q['question_id']] : '';
    }
    fputcsv($output, $row);
}

fclose($output);
exit();
?>