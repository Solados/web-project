# Saudi Culture — Website

Professional, bilingual informational website showcasing Saudi culture, regions, and interactive quizzes.

This repository contains the front-end pages and a light PHP-based profile/dashboard system used for user sign-up, login, and progress tracking.

## Key Features

- Clean, responsive design using a sand & green palette, optimized for Arabic and English content.
- Region pages (North, South, East, West, Central) with imagery and descriptions.
- Interactive quiz system and quiz pages.
- User profile dashboard with session-based authentication, persistent cookies, and a profile API.
- Lightweight CSV-backed user store (for small deployments / prototypes).

## Project Structure (important files)

- `index.php` — Home page
- `index-ar.php` — Home page (Arabic)
- `*.html` — Regional pages (North, South, East, West, Central)
- `dashboard.php` — User profile dashboard (requires login)
- `sign/` — Authentication handlers and forms
  - `sign/save_signup.php` — Signup handler (creates user and starts session)
  - `sign/login_check.php` — Login handler (verifies credentials, sets session + cookies)
  - `sign/check_session.php` — Session validator and logout handler
- `api/user_profile.php` — Minimal profile API (get profile, get stats, update profile)
- `data/user_data.csv` — CSV-backed user store (Full Name, Email, Password Hash, Quiz Record, Quiz Answered)
- `assets/` — CSS and JavaScript assets (`styles.css`, `script.js`, `quiz-parser.js`, etc.)
- `image/` — Image assets used across pages

## Quick Start (local)

1. Clone or copy the repository to your machine.
2. Start a simple PHP-capable server or use built-in PHP server for local testing:

```powershell
# from repository root
php -S localhost:8000
```

3. Open the site in a browser:

```text
http://localhost:8000/index.php
```

4. To test signup/login and the dashboard, use the form at `sign/Signup_Login_Form.html`.

Notes: If you prefer a static-only preview (no PHP features), open `index.php` directly in your browser, but PHP pages (dashboard, signup/login handlers) will not work.

## Authentication & Profile System

This project includes a simple session-based authentication flow implemented with PHP and CSV storage (good for demos/prototypes).

- Sessions are configured with persistent cookies (30-day lifetime) to keep users logged in across browser restarts.
- Passwords are stored as hashes using PHP's `password_hash()`.
- The dashboard (`dashboard.php`) includes `sign/check_session.php` to protect pages and supports logout via `?logout=true`.
- For production, migrate the user store to a database, enable HTTPS (`'secure' => true` for cookies), and consider additional protections (CSRF tokens, rate limiting, email verification).

## Developer Notes

- Styling and theme: `assets/styles.css` (variables for green/gold/sand palette).
- Main JavaScript: `assets/script.js` and `assets/quiz-parser.js`.
- API entrypoint for profile operations: `api/user_profile.php` — returns JSON for AJAX use in the dashboard.
- Test/debug helpers included:
  - `debug_profile.php` — view session and cookie state
  - `test_profile_system.html` — interactive test suite

## Security Considerations

This project is a prototype.

## License & Attribution

This project contains images sourced from public URLs (Unsplash) and uses the `Noto Kufi Arabic` font from Google Fonts. Replace assets with licensed or self-hosted alternatives before production.

If you'd like, I can also:

- Add a short Getting Started script to run the PHP server and open the site in the browser.
- Generate a small SQL schema and migration script to replace the CSV store.
- Harden authentication (CSRF tokens, email verification, password reset).

---

Maintainer: Solados — December 2025
