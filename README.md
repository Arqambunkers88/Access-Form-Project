# ♿ Access Form - Accessible Survey Builder System

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![WCAG 2.1](https://img.shields.io/badge/WCAG_2.1-Compliant-success?style=for-the-badge)
![Virtual University](https://img.shields.io/badge/Virtual_University-Project-0056b3?style=for-the-badge)

**Access Form** is a highly accessible, WCAG 2.1 compliant web-based survey builder designed specifically for users with disabilities (visual, physical, and cognitive) and the elderly. It utilizes a Three-Tier Architecture to provide a seamless, secure, and inclusive data collection experience.

---

## 📁 Repository Structure

Below is the architectural mapping of the Access Form application. *(Note: You can navigate through these directories using GitHub's native file explorer at the top of this page).*

```text
📦 access-form
├── 📂 admin/          # Admin dashboard, user management, and report generation
├── 📂 assets/         # Global CSS styles, Accessibility JS (TTS Engine), and Logos
├── 📂 creator/        # Form Creator dashboard, survey builder, and response viewer
├── 📂 database/       # MySQL database export file (access_form.sql)
├── 📂 docs/           # Official SRS and System Design Document (PDFs)
├── 📂 includes/       # Reusable PHP components (DB connection, Auth checks, AJAX)
├── 📂 respondent/     # Respondent dashboard, survey filling, and Voice Assistant
├── 📄 index.php       # Main system Login portal
└── 📄 register.php    # User Registration portal with Disability Auto-Configuration


🌟 Key Features
♿ Accessibility (WCAG 2.1 Compliant)
Native Screen Reader (Text-to-Speech): Utilizes the Web Speech API with High-Pitch audio tuning to clearly read screen content, image alt text, and form inputs loudly.
Smart Voice Assistant: Allows users with physical/visual disabilities to answer MCQs, Ratings, Booleans, and text questions via microphone (Alt + M shortcut).
Color-Blind Safe Palette: Implements the IBM Color-Blind Safe Palette, dynamically swapping reds/greens for high-visibility magenta/purple.
Auto-Configuration: Registration detects user disability profiles and automatically applies necessary DB settings (Large fonts, Dark mode, Voice features) upon login.
👥 Role-Based Dashboards
Admin Panel: Monitor system-wide surveys, manage (enable/disable/delete) user accounts, and export administrative analytical reports.
Form Creator: Build surveys using dynamic question types (Text, Multiple Choice, Likert Rating, Boolean), apply accessibility settings, and export offline reports (CSV/Excel & Native PDF).
Respondent: View available surveys, track completed surveys (Submitted ✓), and fill forms using inclusive web and voice tools.
🛠️ Tech Stack
Frontend: HTML5, CSS3 (CSS Variables for dynamic theming), Vanilla JavaScript (ES6).
Backend: PHP (Session Management, Secure Hashing, AJAX Sync).
Database: MySQL (PDO for SQL Injection prevention, Relational tables with ON DELETE CASCADE).
Architecture: Three-Tier Architecture (Presentation, Application/Business Logic, Data Layer).
🚀 Installation & Setup (Localhost)
Clone the repository:
code
Bash
git clone https://github.com/yourusername/access-form.git
