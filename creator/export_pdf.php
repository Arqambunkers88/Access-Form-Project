<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) die("Invalid Survey ID");
$survey_id = intval($_GET['survey_id']);

$stmt = $pdo->prepare("SELECT title FROM survey WHERE survey_id = :sid");
$stmt->execute([':sid' => $survey_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) die("Survey not found");

$q_stmt = $pdo->prepare("SELECT question_id, question_text FROM question WHERE survey_id = :sid ORDER BY question_id ASC");
$q_stmt->execute([':sid' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Export PDF - <?= htmlspecialchars($survey['title']) ?></title>
    <!-- Auto PDF Generator Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #e9ecef; margin: 0; padding: 20px; }
        
        #pdf-content { 
            background: #fff; 
            padding: 20px; 
            width: 100%; 
            max-width: 750px; 
            margin: 0 auto; 
            box-sizing: border-box;
        }
        
        .header { text-align: center; border-bottom: 2px solid #0056b3; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #0056b3; margin: 0; font-size: 24px; }
        .header h2 { color: #333; margin: 10px 0 0 0; font-size: 18px; }
        .header p { color: #555; font-size: 12px; margin-top: 5px; }
        
        /* Box ko page break karne ki ijazat de di taake lamba survey aaram se fit ho */
        .response-block { 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            margin-bottom: 30px; 
            padding: 20px; 
        }
        .response-header { background: #f4f6f9; padding: 10px; border-bottom: 1px solid #ddd; margin-bottom: 15px; font-weight: bold; color: #0056b3; font-size: 16px; }
        
        /* Lekin Q&A item ko beech mein se katne se rok diya */
        .qa-item { 
            margin-bottom: 15px; 
            page-break-inside: avoid; /* Yeh sawal ko half katne se rokega */
        }
        
        .question { 
            font-weight: bold; color: #333; font-size: 13px; margin-bottom: 4px; 
            word-wrap: break-word; overflow-wrap: break-word; white-space: normal; 
        }
        .answer { 
            color: #28a745; font-size: 13px; font-weight: bold; padding-left: 15px; border-left: 3px solid #28a745; 
            word-wrap: break-word; overflow-wrap: break-word; white-space: normal; 
        }
        
        .loading { text-align: center; margin-top: 100px; font-size: 22px; color: #0056b3; font-weight: bold; }
    </style>
</head>
<body>

<div class="loading" id="loading-msg">
    ⏳ Generating PDF File... Please wait.<br>
    <span style="font-size: 14px; color: #555;">Document is being formatted with proper margins.</span>
</div>

<div style="display: none;">
    <div id="pdf-content">
        <div class="header">
            <h1>Survey Responses Report</h1>
            <h2><?= htmlspecialchars($survey['title']) ?></h2>
            <p>Generated on: <?= date('d M Y, h:i A') ?></p>
        </div>

        <?php
        $counter = 1;
        foreach ($responses as $resp_id => $data):
        ?>
            <div class="response-block">
                <div class="response-header">
                    Response #<?= str_pad($counter, 3, '0', STR_PAD_LEFT) ?> | 
                    Respondent: <?= htmlspecialchars($data['name']) ?> (<?= htmlspecialchars($data['email']) ?>)
                </div>
                
                <?php foreach ($questions as $q): ?>
                    <div class="qa-item">
                        <div class="question">Q: <?= htmlspecialchars(trim(preg_replace('/\[Alt:.*?\]/', '', $q['question_text']))) ?></div>
                        <div class="answer">A: <?= isset($data['answers'][$q['question_id']]) ? htmlspecialchars($data['answers'][$q['question_id']]) : 'No Answer Provided' ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php 
        $counter++;
        endforeach; 
        
        if (empty($responses)) {
            echo "<p style='text-align:center;'>No responses found for this survey.</p>";
        }
        ?>
    </div>
</div>

<script>
    window.onload = function() {
        var element = document.getElementById('pdf-content');
        var opt = {
            // [Top, Right, Bottom, Left] margins in mm
            margin:[15, 10, 15, 10], 
            filename:     '<?= preg_replace('/[^a-zA-Z0-9]/', '_', $survey['title']) ?>_Report.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
            // Pagebreak logic: item ko katne se bacha kar next page par shift karega
            pagebreak:    { mode:['avoid-all', 'css', 'legacy'] }
        };

        html2pdf().set(opt).from(element).save().then(function() {
            document.getElementById('loading-msg').innerHTML = "✅ PDF Downloaded Successfully!<br><span style='font-size:14px; color:#555;'>You can close this tab now.</span>";
        });
    };
</script>
</body>
</html>