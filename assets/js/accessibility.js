document.addEventListener("DOMContentLoaded", () => {
    
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
        let screenReader = localStorage.getItem("screenReader") || "true"; 
        let colorBlind = localStorage.getItem("colorBlind") || "false"; 

        let ajaxPath = window.location.pathname.includes('/admin/') || window.location.pathname.includes('/creator/') || window.location.pathname.includes('/respondent/') ? '../includes/save_a11y_ajax.php' : 'includes/save_a11y_ajax.php';

        fetch(ajaxPath, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ theme: theme, fontSize: fontSize, screenReader: screenReader, colorBlind: colorBlind })
        }).catch(err => console.log("Not logged in, silent sync skipped."));
    }

    // =========================================================
    // THE ULTIMATE MOBILE FIX: FORCE STYLING VIA JAVASCRIPT
    // =========================================================
    let topHeader = document.querySelector('.top-header');
    let sidebar = document.querySelector('.sidebar');
    let dashBody = document.querySelector('.dashboard-body');
    
    if (topHeader && sidebar && dashBody && !document.getElementById('mobile-menu-btn')) {
        
        // 1. Create Hamburger Menu 
        let menuBtn = document.createElement('button');
        menuBtn.id = 'mobile-menu-btn';
        menuBtn.innerHTML = '☰';
        menuBtn.setAttribute('aria-label', 'Open Sidebar Menu');
        
        // Apply Forceful CSS directly via JS so it works on ALL pages instantly
        menuBtn.style.cssText = 'background: transparent; border: none; color: #ffffff; font-size: 1.8rem; cursor: pointer; margin-right: 15px; padding: 0; display: none;';
        
        topHeader.insertBefore(menuBtn, topHeader.firstChild);

        // 2. Create Sidebar Overlay
        let overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        overlay.style.cssText = 'display: none; position: fixed; top: 65px; left: 0; width: 100%; height: calc(100vh - 65px); background: rgba(0,0,0,0.6); z-index: 998;';
        dashBody.insertBefore(overlay, sidebar);

        // 3. Dynamic Screen Logic (Desktop vs Mobile)
        function applyMobileForceFixes() {
            let a11yControls = document.querySelector('.header-a11y');
            let sidebarMobileA11y = document.querySelector('.sidebar-mobile-a11y');

            if (window.innerWidth <= 900) {
                // MOBILE VIEW: Force Hamburger to show
                menuBtn.style.display = 'inline-block';
                topHeader.style.justifyContent = 'flex-start';
                
                let h1 = topHeader.querySelector('h1');
                if(h1) h1.style.marginLeft = '10px';

                // Force Sidebar settings for mobile
                sidebar.style.position = 'fixed';
                sidebar.style.top = '65px';
                sidebar.style.left = sidebar.classList.contains('open') ? '0' : '-300px';
                sidebar.style.height = 'calc(100vh - 65px)';
                sidebar.style.width = '250px';
                sidebar.style.zIndex = '1000';
                sidebar.style.transition = 'left 0.3s ease';

                // Move Accessibility Buttons INSIDE the Sidebar and FORCE Dark Colors
                if (a11yControls && !sidebarMobileA11y) {
                    let newContainer = document.createElement('div');
                    newContainer.className = 'sidebar-mobile-a11y';
                    newContainer.style.cssText = 'padding: 15px; background-color: #f4f6f9; border-bottom: 1px solid #ccc; display: flex; justify-content: center;';
                    
                    a11yControls.style.cssText = 'display: flex; flex-wrap: wrap; gap: 10px; width: 100%; justify-content: center;';
                    
                    let btns = a11yControls.querySelectorAll('button');
                    btns.forEach(b => {
                        b.style.cssText = 'flex: 1 1 40%; background-color: #333333 !important; color: #ffffff !important; border: none !important; padding: 10px; border-radius: 5px; font-size: 1.1rem;';
                    });
                    
                    newContainer.appendChild(a11yControls);
                    sidebar.insertBefore(newContainer, sidebar.firstChild);
                }
            } else {
                // DESKTOP VIEW: Force Hamburger to hide, restore original look
                menuBtn.style.display = 'none';
                topHeader.style.justifyContent = 'space-between';
                sidebar.style.position = 'sticky';
                sidebar.style.left = '0';
                sidebar.style.width = '200px';
                
                if (sidebarMobileA11y && a11yControls) {
                    a11yControls.style.cssText = 'display: flex; gap: 10px;';
                    let btns = a11yControls.querySelectorAll('button');
                    btns.forEach(b => {
                        b.style.cssText = 'background: transparent; border: 1px solid var(--button-text, #ffffff); color: var(--button-text, #ffffff); padding: 5px 10px; cursor: pointer; font-size: 16px; font-weight: bold;';
                    });
                    topHeader.appendChild(a11yControls);
                    sidebarMobileA11y.remove();
                }
            }
        }

        // Run instantly and on window resize
        applyMobileForceFixes();
        window.addEventListener('resize', applyMobileForceFixes);

        // 4. Open/Close Menu Logic
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('open');
            sidebar.style.left = sidebar.classList.contains('open') ? '0' : '-300px';
            overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebar.style.left = '-300px';
            overlay.style.display = 'none';
        });
    }

    // --- ACCESSIBILITY TOGGLES ---
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

    // =========================================================
    // BUG FIXED YAHAN KIYA HAI: EMOJI KI JAGAH SVG ICON LAGA DIYA
    // =========================================================
    const togglePasswordBtn = document.getElementById("toggle-password");
    const passwordInput = document.getElementById("password");
    if (togglePasswordBtn && passwordInput) {
        
        const eyeOpenSVG = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
        const eyeClosedSVG = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;

        // Professional alignment for the button globally
        togglePasswordBtn.style.display = "flex";
        togglePasswordBtn.style.alignItems = "center";
        togglePasswordBtn.style.justifyContent = "center";

        // Agar purana Emoji mojud hai to default SVG set kar dein
        if (!togglePasswordBtn.innerHTML.includes('<svg')) {
            togglePasswordBtn.innerHTML = eyeOpenSVG;
        }

        togglePasswordBtn.addEventListener("click", (e) => {
            e.preventDefault();
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            togglePasswordBtn.setAttribute("aria-label", type === "text" ? "Hide password" : "Show password");
            
            // Emoji mita kar SVG dal diya
            togglePasswordBtn.innerHTML = type === "text" ? eyeClosedSVG : eyeOpenSVG;
        });
    }

    // --- SMART SCREEN READER ENGINE ---
    let userInteracted = false;
    let welcomePlayed = false;

    const unlockAudio = () => {
        if (!userInteracted) {
            userInteracted = true;
            if (localStorage.getItem("screenReader") === "true" && !welcomePlayed) {
                welcomePlayed = true;
                let u = new SpeechSynthesisUtterance("Welcome to Access Form. Screen reader is active.");
                u.pitch = 1.0; 
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
                utterance.pitch = 1.0; 
                
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

            if (el.id === 'decrease-font') textToRead = "Decrease text size";
            else if (el.id === 'increase-font') textToRead = "Increase text size";
            else if (el.id === 'toggle-contrast') textToRead = "Toggle high contrast dark mode";
            else if (el.id === 'toggle-colorblind') textToRead = "Toggle color blind safe palette";
            else if (el.id === 'toggle-speech') textToRead = "Toggle screen reader voice";
            else if (el.id === 'mobile-menu-btn') textToRead = "Toggle sidebar menu"; 
            else if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
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
                        u.pitch = 1.0; 
                        window.speechSynthesis.speak(u);
                    }, 50);
                }
            });
        }
    });

    document.addEventListener("mouseup", () => {
        if (localStorage.getItem("screenReader") !== "true") return;
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
        utterance.pitch = 1.0; 
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
                    u.pitch = 1.0; 
                    window.speechSynthesis.speak(u);
                }
            }
        }
    };
    recognition.onend = function() { if(targetEl) targetEl.style.backgroundColor = originalBg; };
    recognition.onerror = function() { if(targetEl) targetEl.style.backgroundColor = originalBg; };
}

document.addEventListener("keydown", function(e) {
    if (e.altKey && (e.key === 'm' || e.key === 'M')) {
        let activeEl = document.activeElement;
        if (activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'SELECT' || activeEl.tagName === 'TEXTAREA')) {
            e.preventDefault();
            startGlobalVoice(activeEl.id, activeEl);
        }
    }
});