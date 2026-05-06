# Access Form — Accessible Survey Builder

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![WCAG 2.1](https://img.shields.io/badge/WCAG_2.1-Compliant-success?style=for-the-badge)
![Virtual University](https://img.shields.io/badge/Virtual_University-FYP-0056b3?style=for-the-badge)

I built this for my Final Year Project at Virtual University. The core idea was simple — almost every survey tool out there breaks when you try to use it with a screen reader or without a mouse. I wanted to build one where accessibility was not a checkbox at the end, but the whole point from the start.

The live version is hosted at: **https://accessform.great-site.net**

---

## What the system actually does

**Guest survey filling — no account needed**
Anyone can fill a survey without registering. They just enter their name, email, and optionally their disability profile on a start page. If they submit and their email is not already in the system, the app quietly saves them as a Respondent in the database. This made sharing surveys much easier during testing.

**Smart auto-configuration on registration**
When someone signs up, they pick a disability profile from four options: None, Visual Impairment, Color Blindness, or Physical/Motor Impairment. The `register_process.php` file reads that choice and immediately writes the right settings to the `accessibilitysettings` table. Visual impairment turns on Extra Large font, high contrast, and screen reader together. Color blindness turns on the color-blind palette. Physical impairment turns on the screen reader since keyboard and voice navigation matter more. When they log in next time, `login_process.php` fetches those saved settings and injects them into `localStorage` via JavaScript before redirecting, so everything loads correctly from the first second.

**Conditional branching in surveys**
The form builder lets creators attach a condition to any question — "only show this question if Question X was answered with Y." The fill survey page checks these conditions in real time and hides or shows questions based on previous answers. It is stored as `condition_question_id` and `condition_answer` on each question row.

**Section headers as a question type**
Survey creators can add Section headers as a special question type called `Section` in the database. These divide the survey into named groups. The export PDF file checks `question_type` and renders sections as styled blue headings instead of Q&A pairs, so the exported report looks clean.

**Password reset by email**
The forgot password flow uses PHPMailer with SMTP to send a tokenized reset link. The token is stored in the database with a one-hour expiry. `reset_password.php` validates the token before allowing the change.

**Export to XLS and PDF**
Creators can download responses as an `.xls` file (built with raw HTML table output, no extra library needed) or as a PDF using the `html2pdf.js` library. The PDF groups answers by respondent, shows section headings correctly, and uses `page-break-inside: avoid` so questions do not get cut in half across pages.

**Mobile responsive with a pinnable sidebar**
The dashboard uses a hamburger menu on screens under 900px. There is also a pin toggle that keeps the sidebar open even when you tap outside it, which came in handy for users who navigate with a switch or keyboard.

---

## Accessibility features (the actual implementation)

**Screen reader — `assets/js/accessibility.js`**
The screen reader is built entirely on the browser's `SpeechSynthesis` API — no external library. It attaches `focus` and `mouseenter` listeners to every meaningful element on the page. For radio buttons it reads whether they are selected or not. For dropdowns it reads the current option and tells the user to use arrow keys. For images it reads the `alt` text. The voice only starts after the first user interaction (click or keydown) to get around browser autoplay restrictions.

**Voice typing — `Alt + M`**
Pressing `Alt + M` while focused on any input field or dropdown opens the `SpeechRecognition` API. For dropdown menus it matches the spoken transcript against the option labels and selects the closest match automatically. For email fields it converts "at" to "@" and "dot" to "." and strips spaces. The microphone gives a green background while listening and speaks "Listening" before starting.

**Color-blind safe palette**
The IBM Color-Blind Safe palette is applied as a CSS class `color-blind-mode` on the body. This swaps out reds and greens for magentas and purples that are distinguishable across the three main types of color blindness.

**Font size and dark mode**
Font size changes go from 12px to 26px in 2px steps using a CSS custom property `--base-font-size`. Dark mode toggles the `dark-mode` class on the body. Both are saved to `localStorage` and synced to the database via an AJAX call to `includes/save_a11y_ajax.php` so they persist across devices.

**Password visibility toggle**
The password field has an SVG eye icon that toggles between show and hide. The original version used an emoji which had alignment problems on some browsers — this was fixed by replacing it with an inline SVG.

---

## User roles

**Admin** — sees all surveys across the platform, manages users (block or delete), and downloads system-wide reports showing which surveys have responses.

**Form Creator** — builds surveys with five item types: Text, Multiple Choice, Rating (1 to 5), Boolean (Yes / Maybe / No), and Section Header. Can preview surveys, publish them, view individual responses, and export as XLS or PDF.

**Respondent** — fills surveys either by logging in or as a guest. Gets the full accessibility toolbar. After submitting, sees a confirmation page.

---

## Tech stack

- **Frontend:** HTML5, CSS3, plain JavaScript. No frameworks. This was intentional — screen readers handle vanilla HTML better than framework-generated markup.
- **Backend:** Core PHP with sessions. No Composer packages except PHPMailer for email.
- **Database:** MySQL, all queries use PDO prepared statements.
- **PDF export:** html2pdf.js (loaded from CDN)
- **Email:** PHPMailer with SMTP (files included directly in `/PHPMailer/` folder)

---

## How to run it locally

1. Clone the repo:
   ```
   git clone https://github.com/Arqambunkers88/Access-Form-Project.git
   ```

2. Put the folder inside `htdocs` (XAMPP) or your server's web root.

3. Open phpMyAdmin, create a database called `access_form`, and import `database/access_form.sql`.

4. Open `includes/db_connection.php` and update the database credentials if needed.

5. Go to `http://localhost/Access-Form-Project/` in your browser.

No build step, no npm, no Composer install needed — everything is already included.

---

## Folder structure

```
access-form/
├── PHPMailer/               → PHPMailer library (included directly, no Composer)
├── admin/                   → Admin dashboard, user management, reports, export
├── assets/
│   ├── css/style.css        → All styles including dark mode, color-blind, responsive
│   ├── js/accessibility.js  → Screen reader, voice typing, font/theme controls
│   └── images/              → Logo and landing page images
├── creator/                 → Survey builder, preview, response viewer, XLS/PDF export
├── database/access_form.sql → Full database schema with sample data
├── docs/                    → SRS and Design Document PDFs
├── includes/
│   ├── auth_check.php       → Session validation for protected pages
│   ├── db_connection.php    → PDO connection setup
│   └── save_a11y_ajax.php   → AJAX endpoint for saving accessibility settings live
├── respondent/
│   ├── start_survey.php     → Guest entry form with disability profile selection
│   ├── fill_survey.php      → Survey fill page with branching logic and voice support
│   ├── submit_process.php   → Saves answers, creates guest user account if needed
│   └── submission_complete.php → Confirmation page after submission
├── forgot_password.php      → Sends reset link via PHPMailer SMTP
├── reset_password.php       → Validates token and updates password
├── index.php                → Landing page with feature sections and mobile sidebar
├── login.php / login_process.php
├── register.php / register_process.php
└── logout.php
```

---

## Documentation

Full SRS, ERD, system design diagrams, and test cases are in the `/docs/` folder:

- [Software Requirements Specification (SRS)](https://github.com/Arqambunkers88/Access-Form-Project/blob/b95cf3cce4281ad3b5caab3215e98811183e40f1/docs/SRS.pdf)
- [System Design Document](https://github.com/Arqambunkers88/Access-Form-Project/blob/c4e647c10d38d9cfa314cb507b9f08c1e756bbe4/docs/Design%20Document.pdf)

---

*Built by Muhammad Arqam — Virtual University of Pakistan, 2025–26*
