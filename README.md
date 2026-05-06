# ♿ Access Form - Accessible Survey Builder System

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![WCAG 2.1](https://img.shields.io/badge/WCAG_2.1-Compliant-success?style=for-the-badge)
![Virtual University](https://img.shields.io/badge/Virtual_University-FYP-0056b3?style=for-the-badge)

**Access Form** is a web-based survey builder built as a Final Year Project at Virtual University of Pakistan. It follows WCAG 2.1 accessibility guidelines and is designed for people with visual, physical, or color-blindness disabilities. The system uses a Three-Tier Architecture (Presentation → Application → Data) and is live at **https://accessform.me**

---

## 🌟 Main Features

### ♿ Accessibility Features (WCAG 2.1)

* **Built-in Screen Reader:** Uses the browser's `SpeechSynthesis` API — no external library needed. It reads page headings, buttons, labels, radio button states, dropdown options, and image `alt` text when you hover or Tab through the page. The voice only activates after the first user interaction to follow browser autoplay rules.
* **Smart Voice Assistant (`Alt + M`):** Press `Alt + M` on any focused field to activate the microphone. For dropdown menus, it matches your spoken words to the option labels and selects automatically — say "Strongly Disagree" and it picks it. For email fields it converts "at" to `@` and "dot" to `.` and removes spaces.
* **Color-Blind Safe Palette:** Uses the IBM Color-Blind Safe palette applied as a `color-blind-mode` CSS class on the body. Replaces reds and greens with magentas and purples that work for all three main types of color blindness.
* **Smart Auto-Setup on Registration:** When registering, users pick one of four disability profiles — Visual Impairment, Color Blindness, Physical/Motor Impairment, or None. The system immediately writes the correct settings to the database. Visual impairment activates Extra Large font, dark mode, and screen reader together. Color blindness activates the IBM palette. Physical impairment activates the screen reader for keyboard and voice users.
* **Live Settings Sync:** Font size (12px to 26px in 2px steps using `--base-font-size` CSS variable) and dark mode are saved to `localStorage` instantly and synced to the database via AJAX (`save_a11y_ajax.php`) without any page reload. On next login, `login_process.php` injects these settings back into the browser before redirect so everything loads correctly from the first second.
* **Password Reset by Email:** The forgot password flow uses PHPMailer with SMTP to send a tokenized reset link stored in the database with a one-hour expiry.

### 👥 User Roles

1. **Admin:** Views all surveys across the platform, manages user accounts (block or delete), and downloads system-wide reports for surveys that have responses.
2. **Form Creator:** Builds surveys with five item types — Text, Multiple Choice, Rating (1–5), Boolean (Yes/Maybe/No), and Section Header. Can add conditional branching to questions, preview surveys, publish them, view individual responses, and export as XLS or PDF.
3. **Respondent:** Fills surveys after logging in or as a guest (name + email only, no account needed). Gets the full accessibility toolbar. Guest accounts are automatically saved to the database on first submission.

### 📋 Survey Builder Features

* **5 Question Types:** Text, Multiple Choice, Rating (1 to 5 stars), Boolean (Yes / Maybe / No), Section Header
* **Conditional Branching:** Any question can be set to only appear if a previous question was answered with a specific value. Stored as `condition_question_id` and `condition_answer` per question row, evaluated live on the fill page.
* **Section Headers:** A special `Section` question type that groups questions visually. In PDF exports, sections appear as styled blue headings rather than Q&A rows.
* **Export to XLS and PDF:** XLS is generated as a raw HTML table (no library). PDF uses `html2pdf.js` with `page-break-inside: avoid` so questions never get cut across pages.
* **Guest Survey Filling:** Surveys can be shared as a direct link. Anyone can fill them without an account by entering their name, email, and disability profile on the entry page.

---

## 🛠️ Technologies Used

* **Frontend:** HTML5, CSS3, plain JavaScript — no frameworks. Vanilla HTML was chosen deliberately because screen readers handle it more reliably than framework-generated markup.
* **Backend:** Core PHP with session management. No Composer packages except PHPMailer (included directly in `/PHPMailer/`).
* **Database:** MySQL with PDO prepared statements for all queries — protects against SQL injection.
* **PDF Export:** `html2pdf.js` loaded from CDN.
* **Email:** PHPMailer with SMTP for password reset emails.

---

## 🚀 How to Install and Run on Your Computer

1. **Clone the project:**
   ```
   git clone https://github.com/Arqambunkers88/Access-Form-Project.git
   ```
2. **Setup the server:** Move the folder into your web server root — for XAMPP that is the `htdocs` folder.
3. **Setup the database:**
   * Open `http://localhost/phpmyadmin/`
   * Create a new empty database named `access_form`
   * Click **Import** and upload `database/access_form.sql`
4. **Check DB credentials:** Open `includes/db_connection.php` and confirm your host, username, and password are correct.
5. **Run the project:** Open `http://localhost/Access-Form-Project/` in your browser.

> No build step, no npm, no Composer install — PHPMailer files are already inside the `/PHPMailer/` folder.

---

## 📄 Official Documentation

Full SRS, ERD diagrams, system design, and test cases are inside the `/docs/` folder:

* [Software Requirements Specification (SRS)](https://github.com/Arqambunkers88/Access-Form-Project/docs/SRS.pdf)
* [System Design Document](https://github.com/Arqambunkers88/Access-Form-Project/blob/main/docs/Design%20Document.pdf)

---

## 📁 Project Folder Structure

```text
📦 access-form
├── 📂 admin/                      # Admin dashboard, user management, system reports, export
├── 📂 assets/
│   ├── 📂 css/style.css           # All styles — dark mode, color-blind palette, responsive
│   ├── 📂 js/accessibility.js     # Screen reader engine, Alt+M voice typing, font/theme sync
│   └── 📂 images/                 # Logo and landing page images
├── 📂 creator/                    # Survey builder, preview, response viewer, XLS & PDF export
├── 📂 database/
│   └── 📄 access_form.sql         # Full schema with sample data — import this to get started
├── 📂 docs/                       # SRS and Design Document PDFs
├── 📂 includes/
│   ├── 📄 auth_check.php          # Session guard for all protected pages
│   ├── 📄 db_connection.php       # PDO connection — update credentials here
│   └── 📄 save_a11y_ajax.php      # AJAX endpoint — saves font/theme/SR settings live
├── 📂 respondent/
│   ├── 📄 start_survey.php        # Guest entry — name, email, disability profile
│   ├── 📄 fill_survey.php         # Survey page with branching logic and voice support
│   ├── 📄 submit_process.php      # Saves answers, creates guest user if email is new
│   └── 📄 submission_complete.php # Thank you confirmation page
├── 📄 index.php                   # Landing page with feature sections and mobile sidebar
├── 📄 login.php                   # Login form
├── 📄 login_process.php           # Validates login, injects accessibility settings to browser
├── 📄 register.php                # Registration form with disability profile selector
├── 📄 register_process.php        # Creates user + auto-writes accessibility settings to DB
├── 📄 forgot_password.php         # Sends PHPMailer SMTP reset link (1-hour token)
├── 📄 reset_password.php          # Validates token and updates password
└── 📄 logout.php                  # Destroys session securely
```

---

*Built by Muhammad Arqam — Virtual University of Pakistan, 2025–26*
