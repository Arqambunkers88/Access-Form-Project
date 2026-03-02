<?php
require_once '../includes/auth_check.php';
require_once '../includes/db_connection.php';

if (!isset($_GET['survey_id'])) {
    header("Location: dashboard.php");
    exit();
}
$survey_id = intval($_GET['survey_id']);

// Fetch Survey Title
$survey_stmt = $pdo->prepare("SELECT title FROM Survey WHERE survey_id = :id AND status = 'Published'");
$survey_stmt->execute([':id' => $survey_id]);
$survey = $survey_stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('Survey is closed or unavailable.'); window.location.href='dashboard.php';</script>";
    exit();
}

// Fetch Questions
$q_stmt = $pdo->prepare("SELECT * FROM Question WHERE survey_id = :id ORDER BY question_id ASC");
$q_stmt->execute([':id' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Survey Questions - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=4">
    <style>
        .mic-btn {
            background-color: #ff4757; color: white; border: none; padding: 10px; border-radius: 50%;
            cursor: pointer; margin-left: 10px; font-size: 16px; display: inline-flex; align-items: center; justify-content: center;
        }
        .mic-btn:hover { background-color: #ff6b81; }
        .mic-btn:focus { outline: 3px solid var(--focus-outline); outline-offset: 2px; }
        .mic-btn.recording { animation: pulse 1.5s infinite; background-color: #2ed573; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
    </style>
</head>
<body class="dashboard-page">

    <header class="top-header" role="banner">
        <h1>Survey Questions</h1>
        <div class="header-a11y">
            <button type="button" id="decrease-font">A-</button>
            <button type="button" id="increase-font">A+</button>
            <button type="button" id="toggle-contrast">◐</button>
            <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
            <button type="button" id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
        </div>
    </header>

    <main role="main" style="display: flex; justify-content: center; padding: 40px 20px;">
        <div class="form-card" tabindex="0" style="width: 100%; max-width: 800px;">
            <h2><?php echo htmlspecialchars($survey['title']); ?></h2>
            
            <form action="submit_process.php" method="POST">
                <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">
                
                <?php $q_num = 1; foreach ($questions as $q): ?>
                    <?php 
                        // Clean the question text
                        $display_text = preg_replace('/\[Alt:.*?\]/', '', $q['question_text']); 
                        $clean_question = htmlspecialchars(trim(strip_tags($display_text)));
                        
                        // DYNAMICALLY BUILD THE SPOKEN TEXT FOR THE BLIND USER
                        $options_spoken = "";
                        if ($q['question_type'] == 'Multiple Choice') {
                            $options_spoken = "Available options are: Strongly Disagree, Disagree, Uncertain, Agree, Strongly Agree.";
                        } elseif ($q['question_type'] == 'Boolean') {
                            $options_spoken = "Available options are: Yes, Maybe, No.";
                        } elseif ($q['question_type'] == 'Rating') {
                            $options_spoken = "Available options are a rating from 1 to 5.";
                        } elseif ($q['question_type'] == 'Text') {
                            $options_spoken = "This is a text input answer.";
                        }

                        // Combine everything into one perfect spoken sentence
                        $aria_text = "Question " . $q_num . ". " . $clean_question . ". " . $options_spoken . " Press Tab to navigate options, or press Alt plus M to answer using your voice.";
                    ?>
                    
                    <div style="margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color);" 
                         role="group" 
                         tabindex="0"
                         aria-label="<?php echo $aria_text; ?>">
                        
                        <!-- Header containing Question Text AND the Universal Mic Button -->
                        <div style="display: flex; align-items: flex-start; justify-content: space-between;">
                            <label id="q<?php echo $q_num; ?>_label" style="font-size: 1.1em; display:block; margin-bottom: 10px;">
                                <strong><?php echo $q_num . ". " . htmlspecialchars(trim($display_text)); ?></strong>
                            </label>

                            <button type="button" class="mic-btn" 
                                    onclick="startSmartVoice(this, '<?php echo $q['question_id']; ?>', '<?php echo $q['question_type']; ?>')" 
                                    aria-label="Activate Microphone for Question <?php echo $q_num; ?>" title="Click or press Enter to speak">🎤</button>
                        </div>

                        <!-- 1. TEXT INPUT -->
                        <?php if ($q['question_type'] == 'Text'): ?>
                            <textarea name="answers[<?php echo $q['question_id']; ?>]" id="ans_<?php echo $q['question_id']; ?>" required placeholder="Type or use voice input..."></textarea>
                            <small class="helper-text" style="display:block; text-align:left; margin-top:5px;">Speak your answer clearly.</small>

                        <!-- 2. MULTIPLE CHOICE -->
                        <?php elseif ($q['question_type'] == 'Multiple Choice'): ?>
                            <div style="margin-top: 10px; padding-left: 5px;">
                                <?php $mcq_options =['Strongly Disagree', 'Disagree', 'Uncertain', 'Agree', 'Strongly Agree']; ?>
                                <?php foreach($mcq_options as $opt): ?>
                                    <label style="font-weight: normal; display: inline-block; margin-right: 15px; margin-top: 5px; cursor: pointer;">
                                        <input type="radio" name="answers[<?php echo $q['question_id']; ?>]" value="<?php echo $opt; ?>" required> <?php echo $opt; ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <small class="helper-text" style="display:block; text-align:left; margin-top:5px;">Speak an option (e.g. "Agree").</small>

                        <!-- 3. RATING -->
                        <?php elseif ($q['question_type'] == 'Rating'): ?>
                            <select id="ans_<?php echo $q['question_id']; ?>" name="answers[<?php echo $q['question_id']; ?>]" required>
                                <option value="">Select rating (1 to 5)</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Fair</option>
                                <option value="3">3 - Average</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                            <small class="helper-text" style="display:block; text-align:left; margin-top:5px;">Speak a number from 1 to 5.</small>

                        <!-- 4. BOOLEAN -->
                        <?php elseif ($q['question_type'] == 'Boolean'): ?>
                            <select id="ans_<?php echo $q['question_id']; ?>" name="answers[<?php echo $q['question_id']; ?>]" required>
                                <option value="">Select Option</option>
                                <option value="Yes">Yes</option>
                                <option value="Maybe">Maybe</option>
                                <option value="No">No</option>
                            </select>
                            <small class="helper-text" style="display:block; text-align:left; margin-top:5px;">Speak "Yes", "Maybe", or "No".</small>

                        <?php endif; ?>
                    </div>
                <?php $q_num++; endforeach; ?>

                <div class="action-buttons" style="justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 20px;">
                    <a href="dashboard.php" class="btn-secondary" style="margin-left:0; background-color: #ddd; color: #333;">Back</a>
                    <button type="submit" class="btn" style="width: auto;">Submit Survey</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Version bump to ensure the latest accessibility.js is loaded -->
    <script src="../assets/js/accessibility.js?v=4"></script>
    
    <!-- THE SMART VOICE ASSISTANT LOGIC -->
    <script>
        function speakFeedback(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                let utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'en-US';
                utterance.rate = 0.9;
                utterance.pitch = 1.6; // High pitch sound quality
                window.speechSynthesis.speak(utterance);
            }
        }

        // Global Shortcut for Microphone (Alt + M)
        document.addEventListener("keydown", function(e) {
            if (e.altKey && (e.key === 'm' || e.key === 'M')) {
                // Find the currently focused question block
                let activeQuestion = document.activeElement.closest("div[role='group']");
                if (activeQuestion) {
                    let micBtn = activeQuestion.querySelector(".mic-btn");
                    if (micBtn) {
                        e.preventDefault();
                        micBtn.click(); // Trigger the mic for this specific question
                    }
                } else {
                    speakFeedback("Please press Tab to highlight a question first, then press Alt M.");
                }
            }
        });

        function startSmartVoice(btnElement, questionId, type) {
            if (!window.SpeechRecognition && !window.webkitSpeechRecognition) {
                speakFeedback("Voice recognition is not supported in this browser.");
                return;
            }
            
            var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            var recognition = new SpeechRecognition();
            recognition.lang = "en-US";
            recognition.interimResults = false;

            btnElement.classList.add('recording');
            speakFeedback("Listening."); 
            
            // Wait 1 second for "Listening" to finish before opening the mic
            setTimeout(() => { 
                try { recognition.start(); } catch(err) { console.log(err); }
            }, 1000);

            recognition.onresult = function(e) {
                // Remove punctuation (like periods) to make matching perfect
                var transcript = e.results[0][0].transcript.toLowerCase().replace(/[.,?!]/g, "").trim();
                let matched = false;
                
                // 1. MULTIPLE CHOICE LOGIC (Fixed Overlap Bug)
                if (type === 'Multiple Choice') {
                    // Convert options to array and SORT BY LENGTH (Longest words first!)
                    let radios = Array.from(document.querySelectorAll('input[name="answers[' + questionId + ']"]'));
                    radios.sort((a, b) => b.value.length - a.value.length);

                    for (let radio of radios) {
                        let val = radio.value.toLowerCase();
                        
                        // If the spoken text includes this option, check it and STOP searching!
                        if (transcript.includes(val)) {
                            radio.checked = true;
                            matched = true;
                            speakFeedback("Selected option: " + radio.value);
                            break; // CRITICAL: This stops "Agree" from overriding "Strongly Disagree"
                        }
                    }
                } 
                // 2. RATING OR BOOLEAN LOGIC (Dropdowns)
                else if (type === 'Rating' || type === 'Boolean') {
                    let select = document.getElementById('ans_' + questionId);
                    
                    // Sort dropdown options by length as well
                    let optionsArray = Array.from(select.options).filter(opt => opt.value !== "");
                    optionsArray.sort((a, b) => b.text.length - a.text.length);

                    for (let opt of optionsArray) {
                        let optText = opt.text.toLowerCase();
                        let optVal = opt.value.toLowerCase();
                        
                        if (transcript.includes(optVal) || transcript.includes(optText)) {
                            select.value = opt.value;
                            matched = true;
                            speakFeedback("Selected: " + opt.text);
                            break; // CRITICAL: Stop searching
                        }
                    }
                } 
                // 3. TEXT LOGIC
                else if (type === 'Text') {
                    let textarea = document.getElementById('ans_' + questionId);
                    // Keep original capitalization for text fields
                    let originalTranscript = e.results[0][0].transcript.trim();
                    textarea.value = (textarea.value + " " + originalTranscript).trim();
                    matched = true;
                    speakFeedback("Text entered: " + originalTranscript);
                }
                
                if (!matched) {
                    speakFeedback("I heard '" + transcript + "', but it did not match any options. Please try again.");
                }
            };

            recognition.onerror = function(e) {
                speakFeedback("Microphone error. Please try again.");
                btnElement.classList.remove('recording');
            };

            recognition.onend = function() {
                btnElement.classList.remove('recording');
            };
        }
    </script>
</body>
</html>