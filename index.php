<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Form - Home</title>
    <link rel="stylesheet" href="assets/css/style.css?v=46">
    
    <!-- ==========================================
         MOBILE SMART SIDEBAR & SIGNUP BUTTON CSS
         ========================================== -->
    <style>
        /* Desktop Sign Up Button */
        .header-signup-btn {
            background-color: var(--primary-color);
            color: #ffffff !important;
            padding: 8px 25px;
            border-radius: 25px;
            font-weight: bold;
            text-decoration: none;
            border: 2px solid #ffffff;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-signup-btn:hover {
            background-color: #ffffff;
            color: var(--primary-color) !important;
        }

        /* Dark Mode Specific for Sign Up Button */
        body.dark-mode .header-signup-btn {
            background-color: var(--primary-color) !important;
            color: #000000 !important;
            border-color: #000000 !important;
        }
        body.dark-mode .header-signup-btn:hover {
            background-color: transparent !important;
            color: var(--primary-color) !important;
            border: 2px solid var(--primary-color) !important;
        }

        /* Mobile Sidebar Links (Hidden on Desktop) */
        .mobile-nav-links { display: none; }

        /* Hidden on Desktop by default */
        .menu-toggle { display: none; background: transparent; border: none; color: #ffffff; font-size: 1.8rem; cursor: pointer; margin-right: 15px; padding: 0; }
        .sidebar-header-mobile { display: none; }
        .sidebar-overlay { display: none; position: fixed; top: 65px; left: 0; width: 100%; height: calc(100vh - 65px); background: rgba(0,0,0,0.6); z-index: 998; }
        .header-a11y-container { display: flex; align-items: center; }

        /* ACTIVE ON MOBILE SCREENS (< 900px) */
        @media (max-width: 900px) {
            .menu-toggle { display: block; } 
            
            /* Hide Desktop Login & Signup Buttons on Mobile */
            .header-login-btn, .header-signup-btn { display: none !important; }
            
            /* Convert Top Buttons into a Sidebar */
            .header-a11y-container {
                position: fixed; top: 65px; left: -300px; width: 280px; height: calc(100vh - 65px);
                background-color: var(--card-bg); z-index: 999; flex-direction: column;
                transition: left 0.3s ease; border-right: 1px solid var(--border-color);
                box-shadow: 2px 0 10px rgba(0,0,0,0.1); align-items: stretch; overflow-y: auto;
            }
            
            body.sidebar-open .header-a11y-container { left: 0; }
            body.sidebar-open .sidebar-overlay { display: block; }
            
            /* Sidebar Header (Settings & Pin) */
            .sidebar-header-mobile {
                display: flex; justify-content: space-between; align-items: center; 
                padding: 15px 20px; border-bottom: 1px solid var(--border-color); margin-bottom: 20px;
            }
            .sidebar-header-mobile h3 { margin: 0; font-size: 1.2em; color: var(--text-color); }
            
            /* Professional SVG Pin Icon */
            .pin-toggle { background: transparent; border: none; cursor: pointer; color: #888888; transition: all 0.3s ease; padding: 5px; display: flex; align-items: center; justify-content: center; }
            .pin-toggle svg { width: 18px; height: 18px; fill: currentColor; transition: transform 0.3s ease; }
            .pin-toggle.active { color: var(--primary-color); }
            .pin-toggle.active svg { transform: rotate(45deg); }
            
            /* Adjusting Buttons for Mobile Sidebar */
            .wdaa-header .header-a11y { flex-wrap: wrap; justify-content: center; padding: 0 15px; gap: 10px; }
            .wdaa-header .header-a11y button { 
                flex: 1 1 40%; color: var(--text-color) !important; border-color: var(--border-color) !important; 
                padding: 12px; background: var(--bg-color) !important; border-radius: 5px !important;
            }
            .wdaa-header .header-a11y button:hover { background-color: var(--primary-color) !important; color: var(--button-text) !important; }
            
            /* NEW: Mobile Navigation Links in Sidebar */
            .mobile-nav-links {
                display: flex; flex-direction: column; padding: 20px; gap: 12px;
                border-top: 1px solid var(--border-color); margin-top: 20px;
            }
            .mobile-nav-links a {
                text-decoration: none; color: var(--text-color); font-size: 1.1em; font-weight: bold;
                padding: 12px; border-radius: 5px; text-align: center; border: 1px solid var(--border-color);
            }
            .mobile-nav-links a:hover { background-color: var(--primary-color); color: #ffffff; }
            .mobile-nav-links a.highlight { background-color: var(--primary-color); color: #ffffff; border: none; }

            /* Dark Mode Support for Mobile Sidebar */
            body.dark-mode .header-a11y-container { background-color: #1a1a1a !important; border-color: #555 !important; }
            body.dark-mode .sidebar-header-mobile h3 { color: #ffd700 !important; }
            body.dark-mode .wdaa-header .header-a11y button { background-color: #000 !important; color: #fff !important; border-color: #555 !important; }
            body.dark-mode .wdaa-header .header-a11y button:hover { background-color: #ffd700 !important; color: #000 !important; }
            body.color-blind-mode .sidebar-header-mobile h3 { color: #648fff !important; }

            body.dark-mode .mobile-nav-links a { color: #ffffff; border-color: #555555; }
            body.dark-mode .mobile-nav-links a:hover { background-color: var(--primary-color); color: #000000; }
            body.dark-mode .mobile-nav-links a.highlight { background-color: var(--primary-color); color: #000000; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; display: block; overflow-x: hidden;">

    <!-- TOP HEADER -->
    <header class="wdaa-header" role="banner">
        
        <div style="display: flex; align-items: center;">
            <!-- MOBILE ONLY: Hamburger Menu -->
            <button id="menu-toggle" class="menu-toggle" aria-label="Open Menu" tabindex="0">☰</button>
            
            <!-- Logo Area -->
            <div class="logo-area" tabindex="0" aria-label="Access Form Logo" style="display: flex; align-items: center; gap: 12px;">
                <img src="assets/images/access_form_logo_1.png" alt="Access Form Logo" style="height: 40px; width: auto;">
                <span style="color: white; font-weight: bold; font-size: 1.2em;">Access Form</span>
            </div>
        </div>

        <!-- Controls Area (Desktop vs Mobile) -->
        <div style="display: flex; gap: 15px; align-items: center;">
            
            <!-- Mobile Overlay -->
            <div id="sidebar-overlay" class="sidebar-overlay"></div>
            
            <!-- Accessibility Buttons Container -->
            <div id="a11y-menu" class="header-a11y-container">
                
                <!-- MOBILE ONLY: Sidebar Header & Pin -->
                <div class="sidebar-header-mobile">
                    <h3 tabindex="0">Settings & Menu</h3>
                    <button id="pin-toggle" class="pin-toggle" aria-label="Pin Sidebar" title="Pin Sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16,5 L16,9.5 L19,14 L19,16 L13,16 L13,22 L12,23 L11,22 L11,16 L5,16 L5,14 L8,9.5 L8,5 L16,5 Z"></path></svg>
                    </button>
                </div>

                <div class="header-a11y" aria-label="Accessibility Controls" style="background: transparent; padding: 0; margin: 0;">
                    <button type="button" id="decrease-font" aria-label="Decrease Font Size">A-</button>
                    <button type="button" id="increase-font" aria-label="Increase Font Size">A+</button>
                    <button type="button" id="toggle-contrast" aria-label="Toggle High Contrast">◐</button>
                    <button type="button" id="toggle-colorblind" aria-label="Toggle Color-Blind Palette" title="Color-Blind Safe Palette">🎨</button>
                    <button type="button" id="toggle-speech" aria-label="Toggle Screen Reader">🔊</button>
                </div>

                <!-- NEW: Mobile Navigation Links (Only visible inside Mobile Sidebar) -->
                <div class="mobile-nav-links">
                    <a href="index.php">Home</a>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="highlight">Sign Up</a>
                </div>
            </div>
            
            <!-- DESKTOP BUTTONS (Hidden on Mobile) -->
            <a href="login.php" class="header-login-btn">Login</a>
            <a href="register.php" class="header-signup-btn">Sign Up</a>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="wdaa-hero" role="main">
        <h1 tabindex="0">Accessibility Review & Surveys</h1>
        <p tabindex="0">
            We invite you to enhance the accessibility of your data collection. Our team has built a platform that allows you to create and fill out surveys using advanced tools like keyboard navigation, high contrast themes, and a smart voice assistant. Experience a seamless journey designed for everyone.
        </p>
    </section>

    <!-- THE 3 SIDE-BY-SIDE SECTIONS -->
    <section class="wdaa-feature">
        <div class="text">
            <h2 tabindex="0">Smart Voice Typing</h2>
            <p tabindex="0">Cannot use a keyboard? No problem. You can fill out the entire survey using your voice. Just press Alt + M on your keyboard, speak your answer loudly, and the system will automatically select the right option for you.</p>
            <a href="register.php" class="btn-orange">Get Started</a>
        </div>
        <div class="image">
            <img src="assets/images/voice_typing.png" alt="A Man using voice typing to fill a survey on a laptop." tabindex="0">
        </div>
    </section>

    <section class="wdaa-feature reverse">
        <div class="text">
            <h2 tabindex="0">Color-Blind Safe Mode</h2>
            <p tabindex="0">Red and green colors can be hard to see. By clicking the paint palette button at the top of the screen, the system instantly changes confusing colors into bright purple, yellow, and magenta so everyone can read them clearly.</p>
            <a href="register.php" class="btn-orange">Get Started</a>
        </div>
        <div class="image">
            <img src="assets/images/color_palette.jpg" alt="A colorful painting palette showing different bright colors mixed together." tabindex="0">
        </div>
    </section>

    <section class="wdaa-feature">
        <div class="text">
            <h2 tabindex="0">Full Keyboard Control</h2>
            <p tabindex="0">You do not need a mouse to use this website. You can move through all the questions, menus, and buttons just by pressing the Tab key and Arrow keys on your keyboard safely and securely.</p>
            <a href="register.php" class="btn-orange">Get Started</a>
        </div>
        <div class="image">
            <img src="assets/images/keyboard.jpg" alt="Close up of hands typing on a modern computer keyboard." tabindex="0">
        </div>
    </section>

    <!-- THE BLUE CONTACT BANNER -->
    <div class="wdaa-banner-container">
        <section class="wdaa-banner">
            <div class="content">
                <h2 tabindex="0">Get Personalized<br>Accessibility Guidance</h2>
                <p class="subtitle" tabindex="0">Connect with our experts to find the best solutions for your needs.</p>
                <p tabindex="0">Whether you're exploring accessibility solutions or looking for the right options, our team is here to guide you. Let us help you navigate our offerings and tailor an approach that ensures the best experience for you and your organization.</p>
                <a href="register.php" class="btn-orange" aria-label="Reach out today by registering">Reach out today!</a>
            </div>
            <div class="graphic">
                <img src="assets/images/access_form_logo_1.png" alt="Access Form Graphic Logo" tabindex="0">
            </div>
        </section>
    </div>

    <!-- THE 3 BOTTOM CARDS -->
    <section class="wdaa-explore">
        <h2 tabindex="0">Keep Exploring</h2>
        <p tabindex="0" style="color: #555; font-size: 1.1em;">Discover more features in Access Form</p>
        
        <div class="explore-grid">
            <div class="explore-card">
                <img src="assets/images/screen_reader.png" alt="A person wearing headphones and listening closely." tabindex="0">
                <h3 tabindex="0">Built-in Screen Reader</h3>
                <p tabindex="0">You do not need to download any extra software. Our system has a clear, high-pitch voice that reads the screen out loud for you automatically.</p>
                <a href="register.php" aria-label="Read more about Built in Screen Reader">Read More &rarr;</a>
            </div>
            
            <div class="explore-card">
                <img src="assets/images/team_meeting.jpg" alt="A diverse group of professionals sitting around a table." tabindex="0">
                <h3 tabindex="0">Inclusive Data Collection</h3>
                <p tabindex="0">Learn not just how to meet accessibility standards, but how to be a pioneer in implementing them. Our platform ensures everyone can share their voices.</p>
                <a href="register.php" aria-label="Read more about Inclusive Data Collection">Read More &rarr;</a>
            </div>

            <div class="explore-card">
                <img src="assets/images/smart_auto_setup.jpeg" alt="A person comfortably setting up their profile on a laptop." tabindex="0">
                <h3 tabindex="0">Smart Auto-Setup</h3>
                <p tabindex="0">When you create an account, you can tell us about your specific disability. The system will automatically set up the tools you need.</p>
                <a href="register.php" aria-label="Read more about Smart Auto Setup">Read More &rarr;</a>
            </div>
        </div>
    </section>

    <footer class="wdaa-footer" role="contentinfo">
        <p tabindex="0">&copy; 2024-2026 Access Form & Virtual University. All Rights Reserved.</p>
        <p tabindex="0">Developed with WCAG 2.1 Compliance in mind.</p>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/accessibility.js?v=47"></script>
    
    <!-- ==========================================
         MOBILE SIDEBAR & PIN LOGIC
         ========================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const overlay = document.getElementById('sidebar-overlay');
            const pinToggle = document.getElementById('pin-toggle');

            if (menuToggle) {
                menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.body.classList.toggle('sidebar-open');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    if (!document.body.classList.contains('sidebar-pinned')) {
                        document.body.classList.remove('sidebar-open');
                    }
                });
            }

            if (pinToggle) {
                pinToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.body.classList.toggle('sidebar-pinned');
                    this.classList.toggle('active');
                    
                    if (document.body.classList.contains('sidebar-pinned')) {
                        overlay.style.display = 'none'; 
                    } else {
                        overlay.style.display = 'block';
                    }
                });
            }
        });
    </script>
</body>
</html>