# ♿ Access Form - Accessible Survey Builder System

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![WCAG 2.1](https://img.shields.io/badge/WCAG_2.1-Compliant-success?style=for-the-badge)
![Virtual University](https://img.shields.io/badge/Virtual_University-Project-0056b3?style=for-the-badge)

**Access Form** is a highly accessible, WCAG 2.1 compliant web-based survey builder designed specifically for users with disabilities (visual, physical, and cognitive) and the elderly. It utilizes a Three-Tier Architecture to provide a seamless, secure, and inclusive data collection experience.

## 📁 Repository Structure & File Links

Use the table below to quickly navigate the uploaded project files, source code, and official documentation:

| File / Directory | Type | Description | Quick Link |
| :--- | :---: | :--- | :--- |
| **`/docs/SRS.pdf`** | 📄 PDF | Software Requirements Specification document. | [View File](./docs/SRS_Access_Form.pdf) |
| **`/docs/Design_Document.pdf`**| 📄 PDF | System Design Document (ERD, Architecture, DB). | [View File](./docs/Design_Document_Access_Form.pdf) |
| **`/database/access_form.sql`**| 💾 SQL | MySQL database export file (Tables & Relations). | [View File](./database/access_form.sql) |
| **`/admin/`** | 📁 Folder | Admin dashboard, user management, and report generation. | [View Folder](./admin/) |
| **`/creator/`** | 📁 Folder | Form Creator dashboard, survey builder, and response viewer. | [View Folder](./creator/) |
| **`/respondent/`** | 📁 Folder | Respondent dashboard, survey filling, and Smart Voice Assistant. | [View Folder](./respondent/) |
| **`/includes/`** | 📁 Folder | Reusable PHP components (DB connection, Auth checks, AJAX). | [View Folder](./includes/) |
| **`/assets/`** | 📁 Folder | CSS styles, Accessibility JS (TTS Engine), and Logos. | [View Folder](./assets/) |
| **`index.php`** | 📄 PHP | Main system Login portal. | [View File](./index.php) |
| **`register.php`** | 📄 PHP | User Registration portal with Disability Auto-Configuration. | [View File](./register.php) |

---

## 🌟 Key Features

### ♿ Accessibility (WCAG 2.1 Compliant)
* **Native Screen Reader (Text-to-Speech):** Utilizes the Web Speech API with High-Pitch audio tuning to read screen content, image `alt` text, and form inputs loudly.
* **Smart Voice Assistant:** Allows users with physical disabilities to answer MCQs, Ratings, Booleans, and text questions via microphone (`Alt + M` shortcut).
* **Color-Blind Safe Palette:** Implements the IBM Color-Blind Safe Palette, dynamically swapping reds/greens for high-visibility magenta/purple.
* **Auto-Configuration:** Registration detects user disability profiles and automatically applies necessary DB settings (Large fonts, Dark mode, Voice features) on login.

### 👥 Role-Based Dashboards
1. **Admin Panel:** Monitor system-wide surveys, manage (enable/disable/delete) user accounts, and export administrative analytical reports.
2. **Form Creator:** Build surveys using dynamic question types (Text, Multiple Choice, Likert Rating, Boolean), apply accessibility settings, and export offline reports (CSV/Excel & Native PDF).
3. **Respondent:** View available surveys, track completed surveys (`Submitted ✓`), and fill forms using inclusive web and voice tools.

## 🚀 Installation & Setup (Localhost)

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/access-form.git
