# 🎮 Full-Stack PHP Hangman Web Application

A dynamic, session-state-driven Hangman game built from scratch using PHP and a MySQL database backend. This application showcases clean backend architectural logic, secure data tracking, robust server-side form validation, and relational database management—all without relying on external libraries or client-side JavaScript.

---

## 🚀 Key Features & Architectural Highlights

*   **Dynamic Session Management:** Leverages PHP state handling to maintain user profiles, track active games, and handle letter-guessing mechanics securely across multiple page loads.
*   **Relational Database Backend:** Utilizes a custom MySQL architecture consisting of a pre-populated algorithmic word bank segmented by difficulty tier and a persistent scoring table tracking real-time match analytics.
*   **Advanced Game Logic & Ruleset Evaluation:** 
    *   Dual-input validation supporting both single-letter guesses and high-risk full-word guesses (with custom guess deductions).
    *   Automated algorithmic dynamic scoring: `Score = (Letters Revealed × 5) + (Remaining Guesses × 10) + (15 Win Bonus)`.
*   **Server-Side Security & Data Validation:** Features strict PHP input sanitization ensuring no numbers/symbols are accepted, while executing standard raw secure connection best practices to avoid hardcoded credentials.

---

## 📊 Database Schema Design

The data layers are cleanly isolated across two critical relational tables managed through SQL queries:

### 1. Word Bank (`cois3430_assn1_words`)
Handles scalable lookup tiers using random boundary allocation matching the selected configuration.
*   `id` (INT, Primary Key) - Auto-incremented identifier.
*   `word` (VARCHAR) - Target strings to be evaluated by the loop array.
*   `difficulty` (ENUM) - Categorized systematically as **Easy** (3-5 letters), **Medium** (6-8 letters), or **Hard** (9+ letters).

### 2. Analytics & Leaderboard (`cois3430_assn1_scores`)
Saves completed sessions to construct individual difficulty metrics on request.
*   `username` (VARCHAR) - Player identity stored persistently via server sessions.
*   `difficulty` / `result` / `word` - Structural metadata captured at game termination.
*   `score` (INT) - Formulaic point tally computed server-side.
*   `play_date` (DATETIME) - Automatic timestamp markers.

---

## Project Structure & Page Flow

The application is structured linearly across modular, single-responsibility templates to ensure maintainability:

1.  **`start.php` (Session Initialization):** Captures user profiles using raw form parsing; locks valid strings into application sessions before unlocking routing loops.
2.  **`difficulty.php` (Dynamic Data Fetching):** Collects tiers and queries the word matrix utilizing `ORDER BY RAND() LIMIT 1` for lightweight, randomized index selection.
3.  **`play.php` (Self-Processing Game Engine):** Core logic core. Handles nested string scanning, checks for duplicate array tracking, decreases guesses (`-1` for letter, `-2` for incorrect word), and triggers score evaluations instantly upon game completion.
4.  **`scores.php` (Leaderboards & Reporting):** Queries historical records to generate 3 isolated difficulty dashboards using clean semantic data rendering.

---

## 💻 Tech Stack & Constraints

*   **Backend:** PHP 8.x
*   **Database:** MySQL
*   **Frontend:** Semantic HTML5 (with strict `novalidate` server testing forms), Custom CSS layout wrappers.
*   **Version Control:** Git & GitHub Workflow.
