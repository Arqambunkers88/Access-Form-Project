<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['guest_email']) && !isset($_SESSION['user_id'])) {
    header("Location: start_survey.php?survey_id=" . intval($_GET['survey_id']));
    exit();
}

$survey_id = intval($_GET['survey_id']);
$survey_stmt = $pdo->prepare("SELECT title FROM survey WHERE survey_id = :id AND status = 'Published'");
$survey_stmt->execute([':id' => $survey_id]);
$survey = $survey_stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    echo "<script>alert('This survey is closed.'); window.location.href='../index.php';</script>";
    exit();
}

$q_stmt = $pdo->prepare("SELECT * FROM question WHERE survey_id = :id ORDER BY question_id ASC");
$q_stmt->execute([':id' => $survey_id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

$user_disability = $_SESSION['guest_disability'] ?? 'none';

// SMART PAGINATION LOGIC
$pages =[];
$current_page = 0;
$pages[$current_page]['title'] = $survey['title']; 
$pages[$current_page]['questions'] = [];

foreach ($questions as $q) {
    if ($q['question_type'] == 'Section') {
        $display_text = preg_replace('/\[Alt:.*?\]/', '', $q['question_text']);
        if (empty($pages[$current_page]['questions']) && $current_page == 0) {
            $pages[$current_page]['title'] = htmlspecialchars(trim(strip_tags($display_text)));
        } else {
            $current_page++;
            $pages[$current_page]['title'] = htmlspecialchars(trim(strip_tags($display_text)));
            $pages[$current_page]['questions'] = [];
        }
    } else {
        $pages[$current_page]['questions'][] = $q;
    }
}
$total_pages = count($pages);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey - Access Form</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=60">
    <style>
        .mic-btn { background-color: #ff4757; color: white; border: none; padding: 10px; border-radius: 50%; cursor: pointer; margin-left: 10px; display: inline-flex; }
        .mic-btn:focus { outline: 3px solid var(--focus-outline); outline-offset: 2px; }
        .mic-btn.recording { animation: pulse 1.5s infinite; background-color: #2ed573; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
    </style>
</head>
<body class="dashboard-page">

    <header class="top-header" role="banner">
        <h1 tabindex="0">Access Form - Public Survey</h1>
        <div class="header-a11y">
            <button type="button" id="decrease-font">A-</button>
            <button type="button" id="increase-font">A+</button>
            <button type="button" id="toggle-contrast">◐</button>
            <button type="button" id="toggle-colorblind">🎨</button>
            <button type="button" id="toggle-speech">🔊</button>
        </div>
    </header>

    <main role="main" style="display: flex; justify-content: center; padding: 40px 20px; margin-top: 65px;">
        <div class="form-card" style="width: 100%; max-width: 800px;">
            <p tabindex="0" style="color: green; margin-bottom: 20px;">Welcome, <?php echo htmlspecialchars($_SESSION['guest_name']); ?>!</p>
            <p tabindex="0" style="margin-bottom: 20px; font-weight:bold;">Press Tab to read each question. The microphone will turn on automatically for you to answer.</p>

            <form action="submit_process.php" method="POST" id="surveyForm">
                <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">
                
                <?php 
                $q_num = 1; 
                foreach ($pages as $index => $page): 
                ?>
                    <div class="survey-step" id="step_<?php echo $index; ?>" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                        
                        <h2 tabindex="-1" id="step_title_<?php echo $index; ?>" style="border-bottom: 2px solid var(--primary-color); padding-bottom: 10px; margin-bottom: 30px; outline: none;">
                            <?php echo $page['title']; ?> <span style="font-size: 0.6em; color: gray; float: right;">Page <?php echo $index + 1; ?> of <?php echo $total_pages; ?></span>
                        </h2>

                        <?php foreach ($page['questions'] as $q): ?>
                            <?php 
                                $display_text = preg_replace('/\[Alt:.*?\]/', '', $q['question_text']); 
                                $clean_question = htmlspecialchars(trim(strip_tags($display_text)));
                                
                                $options_spoken = "";
                                if ($q['question_type'] == 'Multiple Choice') $options_spoken = "Options are: Strongly Disagree, Disagree, Uncertain, Agree, Strongly Agree.";
                                elseif ($q['question_type'] == 'Rating') $options_spoken = "Options are a rating from 1 to 5.";
                                elseif ($q['question_type'] == 'Boolean') $options_spoken = "Options are: Yes, Maybe, No.";
                                elseif ($q['question_type'] == 'Text') $options_spoken = "This is a text answer.";

                                $aria_text = "Question " . $q_num . ". " . $clean_question . ". " . $options_spoken;

                                $cond_attr = "";
                                if (!empty($q['condition_question_id'])) {
                                    $cond_id = $q['condition_question_id'];
                                    $cond_ans = htmlspecialchars(strtolower(trim($q['condition_answer'])));
                                    $cond_attr = "data-cond-id='$cond_id' data-cond-ans='$cond_ans'";
                                }
                            ?>
                            
                            <div class="question-group" style="margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color);" 
                                 role="group" tabindex="0" aria-label="<?php echo $aria_text; ?>" data-automic="true" 
                                 data-qid="<?php echo $q['question_id']; ?>" data-qtype="<?php echo $q['question_type']; ?>"
                                 id="q_group_<?php echo $q['question_id']; ?>" <?php echo $cond_attr; ?>>
                                
                                <div style="display: flex; align-items: flex-start; justify-content: space-between;">
                                    <label style="font-size: 1.1em; display:block; margin-bottom: 10px;" aria-hidden="true">
                                        <strong><?php echo $q_num . ". " . htmlspecialchars(trim($display_text)); ?> <span style="color:red;">*</span></strong>
                                    </label>
                                    <button type="button" class="mic-btn" id="mic_btn_<?php echo $q['question_id']; ?>" aria-hidden="true" tabindex="-1">🎤</button>
                                </div>

                                <?php if ($q['question_type'] == 'Text'): ?>
                                    <textarea name="answers[<?php echo $q['question_id']; ?>]" id="ans_<?php echo $q['question_id']; ?>" required tabindex="-1" class="input-field"></textarea>
                                
                                <?php elseif ($q['question_type'] == 'Multiple Choice'): ?>
                                    <?php foreach(['Strongly Disagree', 'Disagree', 'Uncertain', 'Agree', 'Strongly Agree'] as $opt): ?>
                                        <label style="font-weight: normal; display: inline-block; margin-right: 15px;">
                                            <input type="radio" tabindex="-1" class="input-field" name="answers[<?php echo $q['question_id']; ?>]" value="<?php echo $opt; ?>" required> <?php echo $opt; ?>
                                        </label>
                                    <?php endforeach; ?>
                                
                                <?php elseif ($q['question_type'] == 'Rating'): ?>
                                    <select id="ans_<?php echo $q['question_id']; ?>" name="answers[<?php echo $q['question_id']; ?>]" class="input-field" required tabindex="-1">
                                        <option value="">Select rating (1 to 5)</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
                                    </select>
                                
                                <?php elseif ($q['question_type'] == 'Boolean'): ?>
                                    <select id="ans_<?php echo $q['question_id']; ?>" name="answers[<?php echo $q['question_id']; ?>]" class="input-field" required tabindex="-1">
                                        <option value="">Select Option</option><option value="Yes">Yes</option><option value="Maybe">Maybe</option><option value="No">No</option>
                                    </select>
                                <?php endif; ?>
                            </div>
                        <?php $q_num++; endforeach; ?>

                        <div class="action-buttons" style="justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 20px;">
                            <div>
                                <?php if($index == 0): ?>
                                    <a href="../index.php" class="btn-secondary" style="background-color: #ddd; color: #333;" tabindex="0">Cancel</a>
                                <?php else: ?>
                                    <button type="button" class="btn-secondary" onclick="changeStep(<?php echo $index; ?>, -1)" tabindex="0">Back</button>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <?php if($index == $total_pages - 1): ?>
                                    <button type="submit" class="btn" style="width: auto;" tabindex="0">Submit Survey</button>
                                <?php else: ?>
                                    <button type="button" class="btn" style="width: auto;" onclick="changeStep(<?php echo $index; ?>, 1)" tabindex="0">Next</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </form>
        </div>
    </main>

    <script src="../assets/js/accessibility.js?v=60"></script>
    <script>
        function evaluateBranching() {
            document.querySelectorAll('.question-group').forEach(group => {
                let condId = group.getAttribute('data-cond-id');
                let condAns = group.getAttribute('data-cond-ans');

                if (condId && condAns) {
                    let isMatch = false;
                    let textInput = document.getElementById('ans_' + condId);
                    let radioChecked = document.querySelector('input[name="answers[' + condId + ']"]:checked');

                    let actualValue = "";
                    if (radioChecked) actualValue = radioChecked.value.toLowerCase().trim();
                    else if (textInput) actualValue = textInput.value.toLowerCase().trim();

                    if (actualValue === condAns) isMatch = true;

                    if (isMatch) {
                        group.style.display = 'block';
                        group.querySelectorAll('.input-field').forEach(el => {
                            if(el.hasAttribute('data-required-cache')) { el.setAttribute('required', 'required'); }
                            el.disabled = false; 
                        });
                    } else {
                        group.style.display = 'none';
                        group.querySelectorAll('.input-field').forEach(el => {
                            if(el.hasAttribute('required')) { 
                                el.setAttribute('data-required-cache', 'true'); 
                                el.removeAttribute('required'); 
                            }
                            el.disabled = true; 
                        });
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            evaluateBranching(); 
            document.getElementById('surveyForm').addEventListener('change', evaluateBranching);
            document.getElementById('surveyForm').addEventListener('input', evaluateBranching);
        });

        function changeStep(currentIndex, direction) {
            let currentStepEl = document.getElementById('step_' + currentIndex);
            
            if (direction === 1) {
                let inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
                for (let input of inputs) {
                    if (!input.disabled && !input.checkValidity()) {
                        input.reportValidity(); 
                        return; 
                    }
                }
            }

            currentStepEl.style.display = 'none';
            let nextIndex = currentIndex + direction;
            let nextStepEl = document.getElementById('step_' + nextIndex);
            nextStepEl.style.display = 'block';

            let titleEl = document.getElementById('step_title_' + nextIndex);
            if (titleEl) {
                titleEl.focus();
                if (localStorage.getItem("screenReader") === "true") {
                    speakFeedback("Page " + (nextIndex + 1) + ". " + titleEl.innerText);
                }
            }
            window.scrollTo(0, 0); 
        }

        const profile = "<?php echo $user_disability; ?>";
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.remove('dark-mode', 'color-blind-mode');
            localStorage.setItem('theme', 'light');
            localStorage.setItem('colorBlind', 'false');
            localStorage.setItem('screenReader', 'false');
            document.documentElement.style.setProperty('--base-font-size', '16px');
            localStorage.setItem('fontSize', '16');

            if (profile === 'visual') {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
                document.documentElement.style.setProperty('--base-font-size', '20px');
                localStorage.setItem('fontSize', '20');
                localStorage.setItem('screenReader', 'true');
                setTimeout(() => speakFeedback("Visual impairment profile applied. Press Tab to navigate."), 1000);
            } else if (profile === 'colorblind') {
                document.body.classList.add('color-blind-mode');
                localStorage.setItem('colorBlind', 'true');
            } else if (profile === 'physical') {
                localStorage.setItem('screenReader', 'true');
                setTimeout(() => speakFeedback("Physical impairment profile applied. Voice typing is active."), 1000);
            }

            document.querySelectorAll("div[role='group']").forEach(group => {
                group.addEventListener('focus', function(e) {
                    if (localStorage.getItem('screenReader') === 'true') {
                        e.stopImmediatePropagation(); 
                        let qId = this.getAttribute('data-qid');
                        let qType = this.getAttribute('data-qtype');
                        let ariaLabel = this.getAttribute("aria-label");
                        let micBtn = document.getElementById("mic_btn_" + qId);

                        if (micBtn && 'speechSynthesis' in window) {
                            window.speechSynthesis.cancel();
                            let u = new SpeechSynthesisUtterance(ariaLabel);
                            u.lang = 'en-US'; 
                            u.pitch = 1.0; // FIXED: Natural Voice
                            u.rate = 0.9;
                            u.onend = () => { setTimeout(() => { startSmartVoice(micBtn, qId, qType); }, 300); }; 
                            window.speechSynthesis.speak(u);
                        }
                    }
                });
            });
        });

        function speakFeedback(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                let utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'en-US'; 
                utterance.rate = 0.9; 
                utterance.pitch = 1.0; // FIXED: Natural Voice
                window.speechSynthesis.speak(utterance);
            }
        }
        
        function startSmartVoice(btnElement, questionId, type) {
            if (!window.SpeechRecognition && !window.webkitSpeechRecognition) return;
            var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            var recognition = new SpeechRecognition();
            
            recognition.lang = "en-US";
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            btnElement.classList.add('recording');
            speakFeedback("Listening.");
            
            let checkSpeaking = setInterval(() => {
                if (!window.speechSynthesis.speaking) {
                    clearInterval(checkSpeaking);
                    try { recognition.start(); } catch(err) {}
                }
            }, 200);

            recognition.onresult = function(e) {
                var transcript = e.results[0][0].transcript.toLowerCase().replace(/[.,?!]/g, "").trim();
                let matched = false;
                
                if (type === 'Multiple Choice') {
                    let radios = Array.from(document.querySelectorAll('input[name="answers[' + questionId + ']"]'));
                    radios.sort((a, b) => b.value.length - a.value.length);
                    for (let radio of radios) { 
                        if (transcript.includes(radio.value.toLowerCase())) { 
                            radio.checked = true; matched = true; speakFeedback("Selected " + radio.value); 
                            evaluateBranching(); 
                            break; 
                        } 
                    }
                } else if (type === 'Rating' || type === 'Boolean') {
                    let select = document.getElementById('ans_' + questionId);
                    let optionsArray = Array.from(select.options).filter(opt => opt.value !== "");
                    optionsArray.sort((a, b) => b.text.length - a.text.length);
                    for (let opt of optionsArray) { 
                        if (transcript.includes(opt.value.toLowerCase()) || transcript.includes(opt.text.toLowerCase())) { 
                            select.value = opt.value; matched = true; speakFeedback("Selected " + opt.text); 
                            evaluateBranching(); 
                            break; 
                        } 
                    }
                } else if (type === 'Text') {
                    let textarea = document.getElementById('ans_' + questionId);
                    textarea.value = (textarea.value + " " + e.results[0][0].transcript.trim()).trim();
                    matched = true; speakFeedback("Text entered.");
                    evaluateBranching(); 
                }
                
                if (!matched) speakFeedback("I heard '" + transcript + "', please try again.");
            };
            recognition.onend = function() { btnElement.classList.remove('recording'); };
        }

        document.addEventListener("keydown", function(e) {
            if (e.altKey && (e.key === 'm' || e.key === 'M')) {
                let activeQuestion = document.activeElement.closest("div[role='group']");
                if (activeQuestion) {
                    let qId = activeQuestion.getAttribute('data-qid');
                    let qType = activeQuestion.getAttribute('data-qtype');
                    let micBtn = document.getElementById("mic_btn_" + qId);
                    if (micBtn) { e.preventDefault(); startSmartVoice(micBtn, qId, qType); }
                }
            }
        });
    </script>
</body>
</html>