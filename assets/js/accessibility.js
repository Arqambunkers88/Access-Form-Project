document.addEventListener("DOMContentLoaded", () => {
    
    // --- 1. NEW: AUTO-ENABLE SCREEN READER FOR NEW VISITORS ---
    if (localStorage.getItem("screenReader") === null) {
        localStorage.setItem("screenReader", "true");
    }

    if (localStorage.getItem("colorBlind") === "true") {
        document.body.classList.add("color-blind-mode");
    }

    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
    }
    
    let savedFontSize = localStorage.getItem("fontSize");
    if (savedFontSize) {
        document.documentElement.style.setProperty('--base-font-size', savedFontSize + 'px');
    }

    function syncSettingsToDB() {
        let theme = localStorage.getItem("theme") || "light";
        let fontSize = localStorage.getItem("fontSize") || 16;
        let screenReader = localStorage.getItem("screenReader") || "true"; // Default to true now
        let colorBlind = localStorage.getItem("colorBlind") || "false"; 

        let ajaxPath = window.location.pathname.includes('/admin/') || 
                       window.location.pathname.includes('/creator/') || 
                       window.location.pathname.includes('/respondent/') 
                       ? '../includes/save_a11y_ajax.php' 
                       : 'includes/save_a11y_ajax.php';

        fetch(ajaxPath, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ theme: theme, fontSize: fontSize, screenReader: screenReader, colorBlind: colorBlind })
        }).catch(err => console.log("Not logged in, silent sync skipped."));
    }

    // Toggles
    const contrastBtn = document.getElementById("toggle-contrast");
    if (contrastBtn) contrastBtn.addEventListener("click", () => { document.body.classList.toggle("dark-mode"); localStorage.setItem("theme", document.body.classList.contains("dark-mode") ? "dark" : "light"); syncSettingsToDB(); });

    const colorBlindBtn = document.getElementById("toggle-colorblind");
    if (colorBlindBtn) colorBlindBtn.addEventListener("click", () => { document.body.classList.toggle("color-blind-mode"); let isCb = document.body.classList.contains("color-blind-mode") ? "true" : "false"; localStorage.setItem("colorBlind", isCb); let cbCheckbox = document.getElementById("cb_checkbox"); if (cbCheckbox) cbCheckbox.checked = (isCb === "true"); syncSettingsToDB(); });

    const increaseFontBtn = document.getElementById("increase-font");
    if (increaseFontBtn) increaseFontBtn.addEventListener("click", () => { changeFontSize(2); });

    const decreaseFontBtn = document.getElementById("decrease-font");
    if (decreaseFontBtn) decreaseFontBtn.addEventListener("click", () => { changeFontSize(-2); });

    function changeFontSize(change) {
        let root = document.documentElement;
        let currentSize = parseFloat(getComputedStyle(root).getPropertyValue('--base-font-size')) || 16;
        let newSize = currentSize + change;
        if (newSize >= 12 && newSize <= 26) {
            root.style.setProperty('--base-font-size', newSize + 'px');
            localStorage.setItem("fontSize", newSize);
            syncSettingsToDB();
        }
    }

    const togglePasswordBtn = document.getElementById("toggle-password");
    const passwordInput = document.getElementById("password");
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener("click", () => {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            togglePasswordBtn.setAttribute("aria-label", type === "text" ? "Hide password" : "Show password");
            togglePasswordBtn.textContent = type === "text" ? "🙈" : "👁️";
        });
    }

    // --- SMART SCREEN READER ENGINE ---
    let userInteracted = false;
    let welcomePlayed = false;

    // Jaise hi user pehli dafa Tab ya Click dabayega, yeh audio unlock kar dega
    const unlockAudio = () => {
        if (!userInteracted) {
            userInteracted = true;
            if (localStorage.getItem("screenReader") === "true" && !welcomePlayed) {
                welcomePlayed = true;
                let u = new SpeechSynthesisUtterance("Welcome to Access Form. Screen reader is active.");
                u.pitch = 1.6;
                window.speechSynthesis.speak(u);
            }
        }
    };

    document.body.addEventListener("click", unlockAudio, { once: true });
    document.body.addEventListener("keydown", unlockAudio, { once: true });

    const speakText = (text, onEndCallback = null) => {
        if (localStorage.getItem("screenReader") !== "true") {
            if (onEndCallback) onEndCallback();
            return; 
        }
        if (!userInteracted) return; 
        
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); 
            setTimeout(() => {
                let utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'en-US';
                utterance.rate = 0.9;
                utterance.pitch = 1.6; 
                
                if (onEndCallback) {
                    utterance.onend = onEndCallback;
                    utterance.onerror = onEndCallback;
                }
                
                window.speechSynthesis.speak(utterance);
            }, 50); 
        } else {
            if (onEndCallback) onEndCallback();
        }
    };

    const toggleSpeechBtn = document.getElementById("toggle-speech");
    if (toggleSpeechBtn) {
        toggleSpeechBtn.addEventListener("click", (e) => {
            e.preventDefault(); 
            if (localStorage.getItem("screenReader") === "true") {
                localStorage.setItem("screenReader", "false");
                window.speechSynthesis.cancel();
                alert("Screen Reader Disabled");
            } else {
                localStorage.setItem("screenReader", "true");
                speakText("Screen Reader Enabled.");
            }
            syncSettingsToDB();
        });
    }

    const readableElements = document.querySelectorAll("h1, h2, h3, p, label, a, button, td, span, img, input, select, textarea, div[role='group']");

    readableElements.forEach(el => {
        const handleSpeak = (e) => {
            e.stopPropagation(); 
            let textToRead = "";
            let ariaLabel = el.getAttribute("aria-label") || ""; 

            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                if (el.type === 'radio' || el.type === 'checkbox') {
                    textToRead = ariaLabel + " Option: " + el.parentElement.innerText.trim() + (el.checked ? " is selected" : " is not selected");
                } else if (el.type === 'password') {
                    textToRead = ariaLabel + " input field. " + (el.value ? "Password entered." : "Empty. Press Alt M to use voice.");
                } else {
                    textToRead = ariaLabel + " input field. " + (el.value ? "Current value is " + el.value : "Empty. Press Alt M to use voice.");
                }
            } else if (el.tagName === 'SELECT') {
                textToRead = ariaLabel + " Dropdown menu. Current option: " + el.options[el.selectedIndex].text + ". Use UP or DOWN arrow keys to change.";
            } else {
                textToRead = ariaLabel || el.getAttribute("alt") || el.innerText;
            }

            if (textToRead && textToRead.trim() !== "") {
                let isAutoMic = el.getAttribute("data-automic") === "true";

                speakText(textToRead.trim(), () => {
                    if (isAutoMic && document.activeElement === el) {
                        startGlobalVoice(el.id, el, textToRead.trim());
                    }
                });
            }
        };

        el.addEventListener("focus", handleSpeak);
        el.addEventListener("mouseenter", handleSpeak);
        el.addEventListener("mouseleave", () => window.speechSynthesis.cancel());
        el.addEventListener("blur", () => window.speechSynthesis.cancel());

        if (el.tagName === 'SELECT') {
            el.addEventListener("change", () => {
                if (localStorage.getItem("screenReader") === "true") {
                    window.speechSynthesis.cancel();
                    setTimeout(() => {
                        let u = new SpeechSynthesisUtterance("Selected: " + el.options[el.selectedIndex].text);
                        u.pitch = 1.6;
                        window.speechSynthesis.speak(u);
                    }, 50);
                }
            });
        }
    });

    document.addEventListener("mouseup", () => {
        let selectedText = window.getSelection().toString().trim();
        if (selectedText.length > 0) speakText(selectedText);
    });
});

// GLOBAL VOICE-TO-TEXT FUNCTION 
function startGlobalVoice(inputId, targetEl) {
    if (!window.SpeechRecognition && !window.webkitSpeechRecognition) return;
    
    let SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    let recognition = new SpeechRecognition();
    recognition.lang = "en-US";
    recognition.interimResults = false;

    let originalBg = targetEl ? targetEl.style.backgroundColor : '';
    if(targetEl) targetEl.style.backgroundColor = '#d4edda';
    
    if (localStorage.getItem("screenReader") === "true") {
        window.speechSynthesis.cancel();
        let utterance = new SpeechSynthesisUtterance("Listening.");
        utterance.pitch = 1.6;
        utterance.onend = () => { try { recognition.start(); } catch(e){} };
        window.speechSynthesis.speak(utterance);
    } else {
        try { recognition.start(); } catch(e){}
    }

    recognition.onresult = function(e) {
        let transcript = e.results[0][0].transcript.trim();
        let targetField = document.getElementById(inputId);
        
        if (targetField) {
            if (targetField.tagName === 'SELECT') {
                let optionsArray = Array.from(targetField.options);
                for (let i = 0; i < optionsArray.length; i++) {
                    if (transcript.toLowerCase().includes(optionsArray[i].text.toLowerCase())) {
                        targetField.selectedIndex = i;
                        targetField.dispatchEvent(new Event('change'));
                        return;
                    }
                }
            } else {
                if(targetField.type === 'email') {
                    transcript = transcript.replace(/ at /g, "@").replace(/ dot /g, ".");
                    transcript = transcript.replace(/\s+/g, "").toLowerCase(); 
                }
                targetField.value = transcript;
                if (localStorage.getItem("screenReader") === "true") {
                    let u = new SpeechSynthesisUtterance("Entered: " + transcript);
                    u.pitch = 1.6; window.speechSynthesis.speak(u);
                }
            }
        }
    };

    recognition.onend = function() { if(targetEl) targetEl.style.backgroundColor = originalBg; };
    recognition.onerror = function() { if(targetEl) targetEl.style.backgroundColor = originalBg; };
}

// Global Alt+M Shortcut
document.addEventListener("keydown", function(e) {
    if (e.altKey && (e.key === 'm' || e.key === 'M')) {
        let activeEl = document.activeElement;
        if (activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'SELECT' || activeEl.tagName === 'TEXTAREA')) {
            e.preventDefault();
            startGlobalVoice(activeEl.id, activeEl);
        }
    }
});