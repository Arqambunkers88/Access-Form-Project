<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) {
    header("Location: reports.php");
    exit();
}
$survey_id = intval($_GET['survey_id']);

// Table name lower case
$stmt = $pdo->prepare("SELECT title FROM survey WHERE survey_id = :sid");
$stmt->execute([':sid' => $survey_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Survey not found.'); window.location.href='reports.php';</script>";
    exit();
}
$safe_title = htmlspecialchars($survey['title']);

// Table name lower case
$q_stmt = $pdo->prepare("SELECT question_id, question_text FROM question WHERE survey_id = :sid ORDER BY question_id ASC");
$q_stmt->execute([':sid' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

// Table names lower case
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Responses - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
    <style>
        .export-modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center; }
        .modal-content { background-color: var(--card-bg); padding: 30px; border-radius: 8px; width: 90%; max-width: 450px; text-align: center; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .close-btn { position: absolute; right: 20px; top: 15px; font-size: 28px; font-weight: bold; cursor: pointer; color: var(--text-color); }
        .close-btn:hover { color: #dc3545; }
    </style>
</head>
<body class="dashboard-page">

    <div id="exportModal" class="export-modal" role="dialog" aria-hidden="true">
        <div class="modal-content" tabindex="0">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Export Survey Data</h2>
            <p>Select an offline export format below:</p>
            <div style="display: flex; gap: 15px; justify-content: center; margin-top: 25px;">
                <a href="export_csv.php?survey_id=<?php echo $survey_id; ?>" class="btn" style="width: auto; background-color: #28a745; margin: 0;">Export Excel (.xls)</a>
                <!-- NEW ONLINE PDF BUTTON LINK -->
                <a href="export_pdf.php?survey_id=<?php echo $survey_id; ?>" target="_blank" onclick="closeModal()" class="btn" style="width: auto; background-color: #dc3545; margin: 0;">Export .PDF</a>
            </div>
        </div>
    </div>

    <div class="app-container">
        <header class="top-header" role="banner">
            <h1><?php echo $safe_title; ?> – Admin Report</h1>
            <div class="header-a11y">
                <button type="button" id="decrease-font">A-</button>
                <button type="button" id="increase-font">A+</button>
                <button type="button" id="toggle-contrast">◐</button>
                <button type="button" id="toggle-colorblind">🎨</button>
                <button type="button" id="toggle-speech">🔊</button>
            </div>
        </header>

        <div class="dashboard-body">
            <main class="main-content" role="main">
                <a href="reports.php" class="btn-secondary" style="margin-bottom: 20px; display: inline-block;">← Back to Reports</a>

                <div class="form-card" style="max-width: 100%;" tabindex="0">
                    <div id="printable-section">
                        <div class="table-responsive" tabindex="0">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Response ID</th>
                                        <th>Respondent Name</th>
                                        <th>Email Address</th>
                                        <?php foreach($questions as $q): ?>
                                            <th><?php echo htmlspecialchars(preg_replace('/\[Alt:.*?\]/', '', $q['question_text'])); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $display_counter = 1;
                                    foreach($responses as $resp_id => $data): 
                                    ?>
                                    <tr>
                                        <td><strong><?php echo str_pad($display_counter, 3, '0', STR_PAD_LEFT); ?></strong></td>
                                        <td><strong><?php echo htmlspecialchars($data['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($data['email']); ?></td>
                                        <?php foreach($questions as $q): ?>
                                            <td><?php echo isset($data['answers'][$q['question_id']]) ? htmlspecialchars($data['answers'][$q['question_id']]) : '-'; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php 
                                    $display_counter++;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <button onclick="openModal()" class="btn" style="width: auto; background-color: #0056b3;">Export Data</button>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../assets/js/accessibility.js?v=60"></script>
    <script>
        function openModal() { document.getElementById('exportModal').style.display = 'flex'; }
        function closeModal() { document.getElementById('exportModal').style.display = 'none'; }
        window.onclick = function(event) { if (event.target == document.getElementById('exportModal')) closeModal(); }
    </script>
</body>
</html>