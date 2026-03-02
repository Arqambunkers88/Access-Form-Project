document.addEventListener("DOMContentLoaded", () => {
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
        let screenReader = localStorage.getItem("screenReader") || "false";
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
    if (contrastBtn) {
        contrastBtn.addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");
            localStorage.setItem("theme", document.body.classList.contains("dark-mode") ? "dark" : "light");
            syncSettingsToDB();
        });
    }

    const colorBlindBtn = document.getElementById("toggle-colorblind");
    if (colorBlindBtn) {
        colorBlindBtn.addEventListener("click", () => {
            document.body.classList.toggle("color-blind-mode");
            let isCb = document.body.classList.contains("color-blind-mode") ? "true" : "false";
            localStorage.setItem("colorBlind", isCb);
            let cbCheckbox = document.getElementById("cb_checkbox");
            if (cbCheckbox) cbCheckbox.checked = (isCb === "true");
            syncSettingsToDB();
        });
    }

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

    // --- SCREEN READER ENGINE ---
    let userInteracted = false;
    document.body.addEventListener("click", () => { userInteracted = true; }, { once: true });
    
    // Global Keyboard Shortcuts (Alt+S for Speech)
    document.body.addEventListener("keydown", (e) => { 
        userInteracted = true; 
        
        // ALT + S : Toggle Screen Reader
        if (e.altKey && (e.key === 's' || e.key === 'S')) {
            let isEnabled = localStorage.getItem("screenReader") === "true";
            if (isEnabled) {
                localStorage.setItem("screenReader", "false");
                window.speechSynthesis.cancel();
            } else {
                localStorage.setItem("screenReader", "true");
                speakText("Screen reader enabled. Press Alt plus M to activate microphone.");
            }
            syncSettingsToDB();
        }
    });

    const toggleSpeechBtn = document.getElementById("toggle-speech");
    if (toggleSpeechBtn) {
        toggleSpeechBtn.addEventListener("click", (e) => {
            e.preventDefault(); 
            let isEnabled = localStorage.getItem("screenReader") === "true";
            if (isEnabled) {
                localStorage.setItem("screenReader", "false");
                window.speechSynthesis.cancel();
                alert("Screen Reader Disabled");
            } else {
                localStorage.setItem("screenReader", "true");
                speakText("Screen Reader Enabled. Press Alt plus M to activate microphone.");
            }
            syncSettingsToDB();
        });
    }

    const speakText = (text) => {
        if (localStorage.getItem("screenReader") !== "true") return; 
        if (!userInteracted) return; 
        
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); 
            setTimeout(() => {
                let utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'en-US';
                utterance.rate = 0.9;
                utterance.pitch = 1.6; 
                window.speechSynthesis.speak(utterance);
            }, 50); 
        }
    };

    // Advanced Element Reader (Supports Form Inputs for blind users)
    const readableElements = document.querySelectorAll("h1, h2, h3, p, label, a, button, td, span, img, input, select, textarea, div[role='group']");

    readableElements.forEach(el => {
        const handleSpeak = (e) => {
            e.stopPropagation(); 
            if (localStorage.getItem("screenReader") !== "true") return;

            let textToRead = el.getAttribute("aria-label") || el.getAttribute("alt");
            
            if (!textToRead) {
                if (el.tagName === 'INPUT') {
                    if (el.type === 'radio' || el.type === 'checkbox') {
                        textToRead = "Option: " + el.parentElement.innerText.trim() + (el.checked ? " is selected" : " is not selected");
                    } else {
                        textToRead = el.value || el.placeholder || "Text input field";
                    }
                } else if (el.tagName === 'SELECT') {
                    textToRead = "Dropdown menu. Current option: " + el.options[el.selectedIndex].text;
                } else {
                    textToRead = el.innerText;
                }
            }

            if (textToRead && textToRead.trim() !== "") speakText(textToRead.trim());
        };

        el.addEventListener("focus", handleSpeak);
        el.addEventListener("mouseenter", handleSpeak);
        el.addEventListener("mouseleave", () => window.speechSynthesis.cancel());
        el.addEventListener("blur", () => window.speechSynthesis.cancel());
    });

    document.addEventListener("mouseup", () => {
        if (localStorage.getItem("screenReader") !== "true") return;
        let selectedText = window.getSelection().toString().trim();
        if (selectedText.length > 0) speakText(selectedText);
    });
});