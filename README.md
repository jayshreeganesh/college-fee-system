# 🚀 College Fee Management System

![College Fee System Banner](https://img.shields.io/badge/Architecture-Native%20MVC-blue)
![PHP Version](https://img.shields.io/badge/PHP-%5E8.0-777BB4?logo=php)
![Database](https://img.shields.io/badge/Database-SQLite-003B57?logo=sqlite)
![CI/CD](https://img.shields.io/badge/CI%2FCD-GitHub%20Actions-2088FF?logo=github-actions)
![Testing](https://img.shields.io/badge/Testing-Playwright%20%7C%20PHPUnit-45ba4b?logo=playwright)

An enterprise-grade, zero-dependency College Fee Management System engineered using Native PHP, SQLite, and Tailwind CSS. Built from the ground up to demonstrate mastery of raw backend architecture, secure data handling, and automated DevOps pipelines without relying on heavy frameworks like Laravel or Symfony.

🌐 **Live Demo:** [college-fee-system.iceiy.com](https://college-fee-system.iceiy.com)  
*(Admin Login: `demo_admin` / `password` | Student Login: `CS2026-001` / `password123`)*

---

## ✨ Technical Highlights

### 🛡️ Security & Zero-Dependency Architecture
- **Native PSR-4 Autoloader:** Bypassed Composer entirely for production by writing a highly optimized, custom PSR-4 class loader in `index.php`.
- **Strict RBAC Routing:** Custom front controller perfectly segregates the Admin Dashboard from the Student Portal.
- **Data Integrity:** 100% PDO Prepared Statements protect against SQL Injection, while `bcrypt` securely hashes all user credentials.

### 🧪 Automated E2E Testing & CI/CD Pipeline
- **Playwright Headless Testing:** Fully automated scripts navigate through the UI and capture responsive UI screenshots (Desktop, Tablet, Mobile) to ensure absolute visual consistency.
- **GitHub Actions Workflow:** Automatically spins up an ephemeral SQLite environment, runs PHPUnit backend assertions, and triggers Playwright E2E UI validations on every push.

### 📦 Smart Deployment Engine
- Features a custom PHP-based ZIP compiler (`exportProject()`) that aggressively strips out development files (`vendor`, `.git`, tests) and converts OS paths dynamically to ensure hyper-lightweight compatibility with strict shared-hosting environments (cPanel/Linux).

---

## 🛠️ Tech Stack
- **Backend:** Native PHP 8 (Procedural + OOP MVC Hybrid)
- **Database:** SQLite (Zero configuration needed)
- **Frontend UI:** Vanilla JS, Tailwind CSS, Glassmorphism Aesthetics
- **Testing:** PHPUnit, Playwright
- **DevOps:** GitHub Actions

---

## 📂 Architecture & ERD Schema

The database relies on a clean, relational structure prioritizing `UNIQUE` constraints and foreign keys to prevent orphan records:

* `admins` (id, username, password, role)
* `students` (id, enrollment_number, name, email, course, password)
* `fee_categories` (id, name, amount)
* `transactions` (id, student_id, fee_category_id, amount, status, created_at)
* `settings` (key, value)

---

## 🚀 Local Setup Instructions

Want to run the project locally? It takes less than 60 seconds.

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/jayshreeganesh/college-fee-system.git
   cd college-fee-system
   ```

2. **Initialize the Database:**
   ```bash
   php database/seeder.php
   ```
   *This script generates `app.sqlite` and securely populates it with all demo data.*

3. **Start the Native PHP Server:**
   ```bash
   php -S localhost:8000
   ```

4. **Explore the App:**
   - Admin Portal: `http://localhost:8000/login`
   - Student Portal: `http://localhost:8000/student/login`

---

## 🤝 Open to Opportunities
I am actively seeking Full-Stack and Backend Engineering roles. If your team values clean architecture, rigorous automated testing, and secure code, I would love to connect!

*Built with ❤️ by Jayshree Ganesh.*
