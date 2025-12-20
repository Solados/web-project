<?php
// Start session before ANY output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hawiyyah | Contributors</title>
  <meta name="description" content="Meet the contributors who helped build Hawiyyah and what each person worked on.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Main site stylesheet -->
  <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <nav class="navbar" aria-label="Main navigation">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="image/Hawiyah-En.png" alt="Logo" class="site-logo">
    </a>

    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>

    <ul id="nav-links" class="nav-links">
      <?php include 'sign/check_login_status.php'; ?>

      <li class="nav-search">
        <button class="search-toggle" type="button" aria-label="Search" aria-expanded="false">🔍</button>
        <form class="nav-search-form" action="Search.php" method="get" role="search">
          <input type="search" name="q" placeholder="Search a word..." autocomplete="off">
          <button type="submit">Search</button>
        </form>
      </li>

      <?php if ($LOGGED_IN): ?>
        <li class="dropdown">
          <a class="dropbtn">My profile</a>
          <ul class="dropdown-content">
            <li><a href="dashboard.php">My profile</a></li>
            <li><a href="Favorite.php">Favorites</a></li>
            <li><a href="sign/check_session.php?logout=true" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
          </ul>
        </li>
      <?php else: ?>
        <li><a href="/sign/SignUp_LogIn_Form.html">Login</a></li>
      <?php endif; ?>

      <li><a href="quiz/QUIZ-en.php">Quizzes</a></li>

      <li class="dropdown">
        <a class="dropbtn">Questions</a>
        <ul class="dropdown-content">
          <li><a href="General.php">General Questions</a></li>
          <li><a href="North.php">Northern Questions</a></li>
          <li><a href="South.php">Southern Questions</a></li>
          <li><a href="West.php">Western Questions</a></li>
          <li><a href="East.php">Eastern Questions</a></li>
          <li><a href="Central.php">Central Questions</a></li>
        </ul>
      </li>

      <li><a href="index.php">Home</a></li>
      <li><a href="Contributors-ar.php" style="font-weight:700">العربية</a></li>
    </ul>
  </nav>
</header>

<main id="main" class="page-content">

  <section class="section section-intro">
    <div class="container">
      <div class="team-intro">
        <h1>Contributors</h1>
        <p>
          This page highlights the people who contributed to building Hawiyya and a short summary of what each person worked on.
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="team-grid" aria-label="Team members">

        <!-- Member 1 -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Hisham.jpg" alt="Member photo">
        </div>

        <h2 class="member-name">Hisham Al-Mufarreh</h2>
        <p class="member-role">Frontend Developer & Data Presentation</p>

        <ul class="member-tasks">
            <li>Analyzed data and improved how it is presented across the website.</li>
            <li>Reorganized the navbar and enhanced overall navigation.</li>
            <li>Developed the search bar and improved its functionality.</li>
            <li>Developed the English quiz page.</li>
            <li>Added filters for displaying data on the main pages.</li>
            <li>Created the Contributors page and documented each member’s role.</li>
            <li>Integrated user-submitted questions into the questions display.</li>
            <li>Developed the Southern Region page and updated the slider images to highlight key cities.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/HishamMufarreh" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/hisham-mufarreh" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:hs.mufarreh@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 2 -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Muhammed.jpeg" alt="Member photo">
        </div>

        <h2 class="member-name">Muhammed Almasoudi</h2>
        <p class="member-role">UI Developer & Data Visualization</p>

        <ul class="member-tasks">
            <li>Enhanced quiz statistics and improved data clarity and colors.</li>
            <li>Improved the quiz page design and refined the filters UI.</li>
            <li>Redesigned the login page to match the website theme.</li>
            <li>Developed the Western Region page and updated the slider images to highlight key cities.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/lLweesl" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="mailto:sasfe90@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 3 -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="Member photo">
        </div>

        <h2 class="member-name">Mazen Alzahrani</h2>
        <p class="member-role">Frontend Developer & Responsiveness</p>

        <ul class="member-tasks">
            <li>Implemented UI fixes and improvements (logo link to home, slider issues).</li>
            <li>Contributed to the Profile page development and UI consistency.</li>
            <li>Refactored and organized the codebase.</li>
            <li>Built key main pages and core UI components.</li>
            <li>Improved the website responsiveness across different screen sizes.</li>
            <li>Developed the Northern Region page and updated the slider images to highlight key cities.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/Mazen-Alzahrani" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/%D9%85%D8%A7%D8%B2%D9%86-%D8%A7%D9%84%D8%B2%D9%87%D8%B1%D8%A7%D9%86%D9%8A-850889274/" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:mazenhmz10@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 4 -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="Member photo">
        </div>

        <h2 class="member-name">Mansour Almuqati</h2>
        <p class="member-role">Backend Developer & Deployment</p>

        <ul class="member-tasks">
            <li>Deployed the website to hosting and ensured it runs properly online.</li>
            <li>Developed the user question submission feature.</li>
            <li>Fixed and improved the quiz page, linking quiz filters to the database.</li>
            <li>Developed a chatbot to assist users.</li>
            <li>Created the Profile page and integrated it with the rest of the website.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/Solados" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/manssor-almuqati-5414aa387/" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:manssorralmuqati@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 5 -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="Member photo">
        </div>

        <h2 class="member-name">Anas Alqaidi</h2>
        <p class="member-role">Frontend Features & UX</p>

        <ul class="member-tasks">
            <li>Implemented question interaction buttons (Favorite, Share, Copy).</li>
            <li>Linked favorites to a dedicated page and improved its UI to match the site theme.</li>
            <li>Implemented multi-language support and improved the language switch experience.</li>
            <li>Added quiz statistics.</li>
            <li>Developed social sharing for quiz results.</li>
            <li>Developed the Eastern Region page and updated the slider images to highlight key cities.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/Anas-dev11" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/anas-alqaidi-7177a8218/" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:anas34rt@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 6 -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="Member photo">
        </div>

        <h2 class="member-name">Abdulrahman Alhamdan</h2>
        <p class="member-role">Branding & Localization</p>

        <ul class="member-tasks">
            <li>Developed the website branding and ensured visual consistency across pages.</li>
            <li>Contributed to multi-language support and improved content presentation.</li>
            <li>Developed the Central Region page and updated the slider images to highlight key cities.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:">Gmail</a>
        </div>
        </article>

      </div>
    </div>
  </section>

</main>

<!-- Footer -->
<footer class="site-footer" aria-label="footer">
  <div class="container footer-grid">
    <div>
      <strong>Hawiyya</strong>
      <p>© 2025 All rights reserved</p>
      <p class="footer-sources">
        Sources:
        <a href="https://github.com/LamaAy/SaudiCulture-Dataset" target="_blank" rel="noopener noreferrer">SaudiCulture-Dataset</a>,
        Absher sources:
        <a href="https://docs.google.com/spreadsheets/d/1-O91eSIvOUJEuSIDnHoaS21OHMnVc3anpw0jAVw_krs/edit?usp=sharing" target="_blank" rel="noopener noreferrer">Absher (Words)</a>,
        <a href="https://docs.google.com/spreadsheets/d/1nwVsA24SzxqITv_-jVQ_rQWxQ4eqpGJmIifyxZq2jsY/edit?usp=sharing" target="_blank" rel="noopener noreferrer">Absher (Phrases)</a>,
        <a href="https://docs.google.com/spreadsheets/d/1HAUXQnbA8L4dhFNEx-XQA67OeOaO5lpwX5RUMgC3swQ/edit?usp=sharing" target="_blank" rel="noopener noreferrer">Absher (Proverbs)</a>,
        <a href="https://www.absher.sa" target="_blank" rel="noopener noreferrer">Absher website</a>.
      </p>
    </div>

    <ul class="footer-links">
      <li><a href="#main">Back to Top</a></li>
    </ul>

    <!-- New: Contributors button -->
    <div class="footer-cta">
      <a class="footer-contributors-btn" href="Contributors.php">Our Team</a>
    </div>
  </div>
</footer>

<script src="assets/script.js"></script>
</body>
</html>
