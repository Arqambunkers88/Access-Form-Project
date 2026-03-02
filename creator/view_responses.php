<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) {
    header("Location: responses.php");
    exit();
}
$survey_id = intval($_GET['survey_id']);
$creator_id = $_SESSION['user_id'];

// 1. Fetch Survey Details
$stmt = $pdo->prepare("SELECT title FROM Survey WHERE survey_id = :sid AND creator_id = :cid");
$stmt->execute([':sid' => $survey_id, ':cid' => $creator_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Survey not found or access denied.'); window.location.href='responses.php';</script>";
    exit();
}
$safe_title = htmlspecialchars($survey['title']);

// 2. Fetch Questions (Table Headers)
$q_stmt = $pdo->prepare("SELECT question_id, question_text FROM Question WHERE survey_id = :sid ORDER BY question_id ASC");
$q_stmt->execute([':sid' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Fetch Responses and Answers
$r_stmt = $pdo->prepare("
    SELECT r.response_id, a.question_id, a.answer_text 
    FROM Response r 
    JOIN Answer a ON r.response_id = a.response_id 
    WHERE r.survey_id = :sid 
    ORDER BY r.response_id ASC
");
$r_stmt->execute([':sid' => $survey_id]);
$answers_data = $r_stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize answers by response_id
$responses =[];
foreach ($answers_data as $row) {
    $responses[$row['response_id']][$row['question_id']] = $row['answer_text'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Survey Responses - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
    
    <!-- CSS for the Popup Modal and Offline PDF Print -->
    <style>
        /* Modal Background */
        .export-modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; top: 0; 
            width: 100%; height: 100%; 
            background-color: rgba(0,0,0,0.6); 
            justify-content: center; align-items: center;
        }
        /* Modal Box */
        .modal-content {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 8px;
            width: 90%; max-width: 450px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .close-btn {
            position: absolute; right: 20px; top: 15px;
            font-size: 28px; font-weight: bold; cursor: pointer;
            color: var(--text-color);
        }
        .close-btn:hover { color: #dc3545; }

        /* Print formatting for Offline PDF */
        @media print {
            body * { visibility: hidden; } /* Hide everything */
            #printable-section, #printable-section * { visibility: visible; } /* Show only table */
            #printable-section { position: absolute; left: 0; top: 0; width: 100%; }
            .top-header, .sidebar, .btn, .export-modal { display: none !important; }
        }
    </style>
</head>
<body class="dashboard-page <?php echo $body_class; ?>" <?php echo $inline_style; ?>>

    <!-- THE EXPORT POPUP MODAL -->
    <div id="exportModal" class="export-modal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-content" tabindex="0">
            <span class="close-btn" onclick="closeModal()" aria-label="Close popup">&times;</span>
            <h2 id="modalTitle">Export Survey Data</h2>
            <p>Select an offline export format below:</p>
            
            <div style="display: flex; gap: 15px; justify-content: center; margin-top: 25px;">
                <!-- CSV native PHP export -->
                <a href="export_csv.php?survey_id=<?php echo $survey_id; ?>" class="btn" style="width: auto; background-color: #28a745; margin: 0;">Export .CSV (Excel)</a>
                
                <!-- PDF Offline Print Export -->
                <button onclick="exportPDF()" class="btn" style="width: auto; background-color: #dc3545; margin: 0;">Export .PDF</button>
            </div>
        </div>
    </div>

    <div class="app-container">
        <!-- Header -->
        <header class="top-header" role="banner">
            <h1><?php echo $safe_title; ?> – Responses</h1>
        </header>

        <div class="dashboard-body">
            <nav class="sidebar" role="navigation">
                <a href="responses.php" class="btn-secondary" style="margin: 10px; display: block; text-align: center;">← Back</a>
            </nav>

            <main class="main-content" role="main">
                
                <div class="form-card" style="max-width: 100%;" tabindex="0">
                    
                    <!-- We wrap the table in an ID for the PDF print logic -->
                    <div id="printable-section">
                        <h2 style="display: none;" class="print-only">Responses for: <?php echo $safe_title; ?></h2>
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

                    <!-- THE SINGLE EXPORT BUTTON -->
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
        // Open Modal
        function openModal() {
            document.getElementById('exportModal').style.display = 'flex';
            document.querySelector('.modal-content').focus(); // Accessibility focus
        }

        // Close Modal
        function closeModal() {
            document.getElementById('exportModal').style.display = 'none';
        }

        // Close if user clicks outside the box
        window.onclick = function(event) {
            var modal = document.getElementById('exportModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Offline PDF Export Trigger
        function exportPDF() {
            closeModal();
            // Show the hidden title specifically for the printed PDF
            document.querySelector('.print-only').style.display = 'block';
            
            // Trigger browser's native print engine (user just selects "Save as PDF")
            window.print();
            
            // Hide the title again for the web view
            setTimeout(() => {
                document.querySelector('.print-only').style.display = 'none';
            }, 1000);
        }
    </script>
</body>
</html>