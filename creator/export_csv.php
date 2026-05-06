<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) die("Invalid Survey ID");
$survey_id = intval($_GET['survey_id']);
$creator_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT title FROM survey WHERE survey_id = :sid AND creator_id = :cid");
$stmt->execute([':sid' => $survey_id, ':cid' => $creator_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) die("Survey not found");

$q_stmt = $pdo->prepare("SELECT question_id, question_text FROM question WHERE survey_id = :sid ORDER BY question_id ASC");
$q_stmt->execute([':sid' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

// JOIN User table
$r_stmt = $pdo->prepare("
    SELECT r.response_id, u.name as respondent_name, u.email as respondent_email, a.question_id, a.answer_text 
    FROM response r 
    JOIN user u ON r.respondent_id = u.user_id
    JOIN answer a ON r.response_id = a.response_id 
    WHERE r.survey_id = :sid 
    ORDER BY r.response_id ASC
");
$r_stmt->execute([':sid' => $survey_id]);
$answers_data = $r_stmt->fetchAll(PDO::FETCH_ASSOC);

$responses =[];
foreach ($answers_data as $row) {
    $responses[$row['response_id']]['name'] = $row['respondent_name'];
    $responses[$row['response_id']]['email'] = $row['respondent_email'];
    $responses[$row['response_id']]['answers'][$row['question_id']] = $row['answer_text'];
}

$filename = preg_replace('/[^a-zA-Z0-9]/', '_', $survey['title']) . "_Creator_Report.xls";
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
echo '<head><meta http-equiv="Content-type" content="text/html;charset=utf-8"></head>';
echo '<body>';
echo '<table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif;">';

// Headers
echo '<thead><tr>';
echo '<th style="background-color: #0056b3; color: #ffffff; padding: 10px; font-weight: bold; text-align: center; border: 1px solid #000000;">Response ID</th>';
echo '<th style="background-color: #0056b3; color: #ffffff; padding: 10px; font-weight: bold; text-align: left; border: 1px solid #000000;">Respondent Name</th>';
echo '<th style="background-color: #0056b3; color: #ffffff; padding: 10px; font-weight: bold; text-align: left; border: 1px solid #000000;">Email Address</th>';
foreach ($questions as $q) {
    echo '<th style="background-color: #0056b3; color: #ffffff; padding: 10px; font-weight: bold; text-align: left; border: 1px solid #000000;">' . htmlspecialchars(trim(preg_replace('/\[Alt:.*?\]/', '', $q['question_text']))) . '</th>';
}
echo '</tr></thead>';

// Data Rows
echo '<tbody>';
$display_counter = 1; 

foreach ($responses as $resp_id => $data) {
    $bg_color = ($display_counter % 2 == 0) ? '#f4f6f9' : '#ffffff';
    echo '<tr style="background-color: ' . $bg_color . ';">';
    echo '<td style="padding: 8px; text-align: center; border: 1px solid #cccccc;"><strong>' . str_pad($display_counter, 3, '0', STR_PAD_LEFT) . '</strong></td>';
    echo '<td style="padding: 8px; text-align: left; border: 1px solid #cccccc; font-weight: bold;">' . htmlspecialchars($data['name']) . '</td>';
    echo '<td style="padding: 8px; text-align: left; border: 1px solid #cccccc;">' . htmlspecialchars($data['email']) . '</td>';
    
    foreach ($questions as $q) {
        $ans = isset($data['answers'][$q['question_id']]) ? htmlspecialchars($data['answers'][$q['question_id']]) : '-';
        echo '<td style="padding: 8px; text-align: left; border: 1px solid #cccccc; vertical-align: top;">' . $ans . '</td>';
    }
    echo '</tr>';
    $display_counter++; 
}

echo '</tbody></table></body></html>';
exit();
?>