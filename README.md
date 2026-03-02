# ♿ Access Form - WCAG 2.1 Accessible Survey Architecture

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![WCAG 2.1](https://img.shields.io/badge/WCAG_2.1-Compliant-success?style=for-the-badge)
![Virtual University](https://img.shields.io/badge/Virtual_University-Project-0056b3?style=for-the-badge)

**Access Form** is an enterprise-grade, WCAG 2.1 compliant survey builder and management system. Engineered specifically to bridge the digital divide, this platform provides a seamless data collection experience for users with visual, physical, and cognitive impairments. 

Built on a robust **Three-Tier Architecture**, the system dynamically handles Role-Based Access Control (RBAC), real-time DOM manipulation for accessibility features, and asynchronous database state synchronization.

---

## 🌟 Core Technical Features

### ♿ Advanced Accessibility (WCAG 2.1)
* **Smart Voice Assistant (Speech-to-Text):** Integrated Web Speech API allowing physically impaired users to navigate and answer MCQs, Ratings, Booleans, and text fields entirely via voice (`Alt + M` global shortcut). Utilizes heuristic string matching (longest-word-first) to prevent option-overlap bugs.
* **Native Screen Reader (TTS Engine):** Custom text-to-speech engine with **high-pitch audio tuning** for clearer auditory feedback. Automatically reads DOM elements, form input states, and image `alt` attributes upon keyboard `Tab` focus or mouse hover.
* **IBM Color-Blind Safe Palette:** CSS-variable driven color manipulation that dynamically overrides standard UI colors (Reds/Greens) to high-visibility Magenta/Purple for users with deuteranomaly/protanomaly.
* **Smart Registration Auto-Configuration:** The registration pipeline detects user disability profiles (Visual, Physical, Color-blind) and automatically injects corresponding accessibility configurations into the database and browser `localStorage` upon their first login.
* **Dynamic UI Scaling & Contrast:** Real-time font resizing and Dark Mode toggling with instant background AJAX synchronization to the user's database profile.

### 👥 Role-Based Access Control (RBAC)
1. **Admin Portal:** Global overview of system metrics, survey monitoring, and strict user management (enable/disable/delete). Generates administrative analytical reports.
2. **Form Creator:** Advanced drag-and-drop style survey builder supporting Text, Multiple Choice, Likert Rating, and Boolean data types. Features robust reporting tools with offline exports to **Excel (.xls) and Native PDF**.
3. **Respondent Portal:** A clean, zero-distraction interface for survey completion. Accessibility settings are restricted exclusively to respondents to maintain standard UI flows for administrative staff.

---

## 🏗️ System Architecture & Tech Stack

* **Presentation Layer (Frontend):** HTML5, CSS3, Vanilla JavaScript (ES6). Utilizes CSS variables for dynamic theme switching and Web APIs for speech synthesis/recognition.
* **Application Layer (Backend):** PHP 8.x. Handles secure session management, password hashing (Bcrypt), and AJAX endpoints for state persistence.
* **Data Layer (Database):** MySQL. Interacted via PDO (PHP Data Objects) to prevent SQL injection. Features strict relational integrity using `ON DELETE CASCADE`.

---

## 📁 Repository Structure

Below is the architectural mapping of the Access Form application. *(Navigate through these directories using GitHub's native file explorer).*

```text
📦 access-form
├── 📂 admin/          # Admin dashboard, user management, and global reporting
├── 📂 assets/         # CSS styles, system images, and the core Accessibility JS Engine
├── 📂 creator/        # Survey builder logic, response aggregation, and offline exporters
├── 📂 database/       # MySQL database schema and relational export (access_form.sql)
├── 📂 docs/           # Official Software Requirements Specification (SRS) & Design Docs
├── 📂 includes/       # Reusable PHP components (PDO connections, Auth wrappers, AJAX)
├── 📂 respondent/     # Survey execution interfaces and Voice Assistant integrations
├── 📄 index.php       # Main system authentication portal
└── 📄 register.php    # User onboarding with Disability Profile mapping
