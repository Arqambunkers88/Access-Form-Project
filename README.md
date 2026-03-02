# ♿ Access Form - Accessible Survey Builder System

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![WCAG 2.1](https://img.shields.io/badge/WCAG_2.1-Compliant-success?style=for-the-badge)
![Virtual University](https://img.shields.io/badge/Virtual_University-Project-0056b3?style=for-the-badge)

**Access Form** is a web-based survey builder that follows WCAG 2.1 accessibility rules. It is specially designed to be easy to use for people with visual, physical, or learning disabilities, as well as elderly users. The system uses a secure Three-Tier Architecture to keep data safe and well-organized.

---

## 🌟 Main Features

### ♿ Accessibility Features (WCAG 2.1)
* **Screen Reader (Text-to-Speech):** A built-in voice reads the screen out loud. It uses a high-pitch voice to be heard clearly. It reads text, buttons, form options, and image descriptions (`alt` text) when you hover over them with a mouse or use the `Tab` key.
* **Smart Voice Assistant:** Users who cannot use a keyboard can fill out the whole survey using their voice (by pressing `Alt + M`). The system is smart enough to understand exact choices (like "Strongly Disagree") and automatically selects them.
* **Color-Blind Safe Theme:** Uses the IBM Color-Blind Safe Palette. It changes hard-to-read colors (like red and green) into clear colors (like magenta and purple) so color-blind users can see everything easily.
* **Smart Auto-Setup:** When a new user registers, they can select their disability type. The system will automatically turn on the right settings (like large text, dark mode, or screen reader) as soon as they log in.
* **Live Text & Color Settings:** Users can change text size (A-, A+) and turn on Dark Mode at any time. These settings are instantly saved to the database without reloading the page.

### 👥 User Roles (Dashboards)
1. **Admin:** Can view all surveys in the system, manage users (block or delete accounts), and download system reports.
2. **Form Creator:** Can easily create surveys with different question types (Text, Multiple Choice, Rating, Yes/No). They can view answers and download them as Excel (`.xls`) or PDF files.
3. **Respondent:** Can view available surveys and fill them out. Only respondents get the special accessibility buttons on their dashboard to keep the admin screens clean and simple.

---

## 🛠️ Technologies Used

* **Frontend (What the user sees):** HTML5, CSS3, and regular JavaScript. It uses built-in browser features for the voice reader.
* **Backend (The system logic):** PHP. It handles secure logins, saves settings, and manages user sessions.
* **Database (Where data is stored):** MySQL. It uses secure PDO connections to protect against hackers.

---

## 🚀 How to Install and Run on Your Computer
* **Download the Project:** git clone https://github.com/yourusername/access-form.git
* **Setup the Server:** Move the downloaded folder into your local web server folder (for example, the htdocs folder if you are using XAMPP).
* **Setup the Database:**
            * Open your browser and go to http://localhost/phpmyadmin/.
            * Create a new, empty database and name it "access_form".
            * Click "Import" and upload the access_form.sql file located in the /database/ folder.
* **Run the Project:** Open your web browser and go to: http://localhost/access-form/

## 📁 Project Folder Structure

Below is the map of all the project files exactly as they are structured.

```text
📦 access-form
├── 📂 admin/                # Admin dashboard, user management, and reporting pages
├── 📂 assets/               # CSS styles, system images, and the JavaScript Voice Engine
├── 📂 creator/              # Survey builder, response viewer, and Excel/PDF download files
├── 📂 database/             # MySQL database file (access_form.sql)
├── 📂 docs/                 # Official project documents (SRS and Design Document PDFs)
├── 📂 includes/             # Reusable PHP files (Database connection, login checks)
├── 📂 respondent/           # Survey filling pages and Smart Voice Assistant
├── 📄 index.php             # The main Login page
├── 📄 login_process.php     # Handles user login and database settings sync
├── 📄 logout.php            # Securely ends the user session
├── 📄 README.md             # This file (Project instructions and details)
├── 📄 register.php          # The Registration page with disability selection
└── 📄 register_process.php  # Handles new user creation and auto-setup


---
