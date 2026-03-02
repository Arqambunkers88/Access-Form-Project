<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) {
    header("Location: reports.php");
    exit();
}
$survey_id = intval($_GET['survey_id']);

// Fetch Survey Details
$stmt = $pdo->prepare("SELECT title FROM Survey WHERE survey_id = :sid");
$stmt->execute([':sid' => $survey_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Survey not found.'); window.location.href='reports.php';</script>";
    exit();
}
$safe_title = htmlspecialchars($survey['title']);

// Fetch Questions (Headers)
$q_stmt = $pdo->prepare("SELECT question_id, question_text FROM Question WHERE survey_id = :sid ORDER BY question_id ASC");
$q_stmt->execute([':sid' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Responses
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Survey Responses - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
    
    <!-- CSS for the Popup Modal and Offline PDF Print -->
    <style>
        .export-modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center; }
        .modal-content { background-color: var(--card-bg); padding: 30px; border-radius: 8px; width: 90%; max-width: 450px; text-align: center; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .close-btn { position: absolute; right: 20px; top: 15px; font-size: 28px; font-weight: bold; cursor: pointer; color: var(--text-color); }
        .close-btn:hover { color: #dc3545; }
        
        /* Print formatting for Offline PDF */
        @media print { 
            body * { visibility: hidden; } 
            #printable-section, #printable-section * { visibility: visible; } 
            #printable-section { position: absolute; left: 0; top: 0; width: 100%; } 
            .top-header, .sidebar, .btn, .export-modal { display: none !important; } 
        }
    </style>
</head>
<body class="dashboard-page">

    <!-- THE EXPORT POPUP MODAL -->
    <div id="exportModal" class="export-modal" role="dialog" aria-hidden="true">
        <div class="modal-content" tabindex="0">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Export Survey Data</h2>
            <p>Select an offline export format below:</p>
            <div style="display: flex; gap: 15px; justify-content: center; margin-top: 25px;">
                <!-- Link to the Admin's Export Excel Script -->
                <a href="export_csv.php?survey_id=<?php echo $survey_id; ?>" class="btn" style="width: auto; background-color: #28a745; margin: 0;">Export Excel (.xls)</a>
                <!-- Link to PDF Print Script -->
                <button onclick="exportPDF()" class="btn" style="width: auto; background-color: #dc3545; margin: 0;">Export .PDF</button>
            </div>
        </div>
    </div>

    <div class="app-container">
        <header class="top-header" role="banner">
            <h1><?php echo $safe_title; ?> – Admin Report</h1>
        </header>

        <div class="dashboard-body">
            <!-- ADMIN SIDEBAR -->
            <nav class="sidebar" role="navigation">
                <a href="dashboard.php">Dashboard</a>
                <a href="users.php">Users</a>
                <a href="surveys.php">Surveys</a>
                <a href="reports.php" class="active">Reports</a>
                <a href="../logout.php">Logout</a>
            </nav>

            <main class="main-content" role="main">
                <a href="reports.php" class="btn-secondary" style="margin-bottom: 20px; display: inline-block;">← Back to Reports</a>
                
                <div class="form-card" style="max-width: 100%;" tabindex="0">
                    <div id="printable-section">
                        <h2 style="display: none;" class="print-only">Admin Report: <?php echo $safe_title; ?></h2>
                        <div class="table-responsive" tabindex="0">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Response ID</th>
                                        <?php foreach($questions as $q): ?>
                                            <th><?php echo htmlspecialchars(preg_replace('/\[Alt:.*?\]/', '', $q['question_text'])); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($responses as $resp_id => $ans_array): ?>
                                    <tr>
                                        <td><strong><?php echo str_pad($resp_id, 3, '0', STR_PAD_LEFT); ?></strong></td>
                                        <?php foreach($questions as $q): ?>
                                            <td><?php echo isset($ans_array[$q['question_id']]) ? htmlspecialchars($ans_array[$q['question_id']]) : '-'; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- THE EXPORT BUTTON -->
                    <div style="margin-top: 20px;">
                        <button onclick="openModal()" class="btn" style="width: auto; background-color: #0056b3;">Export Data</button>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../assets/js/accessibility.js?v=3"></script>

    <!-- MODAL & OFFLINE EXPORT JAVASCRIPT -->
    <script>
        function openModal() { document.getElementById('exportModal').style.display = 'flex'; }
        function closeModal() { document.getElementById('exportModal').style.display = 'none'; }
        window.onclick = function(event) { if (event.target == document.getElementById('exportModal')) closeModal(); }
        
        function exportPDF() {
            closeModal();
            document.querySelector('.print-only').style.display = 'block';
            window.print();
            setTimeout(() => { document.querySelector('.print-only').style.display = 'none'; }, 1000);
        }
    </script>
</body>
</html>