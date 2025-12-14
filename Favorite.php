<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$LOGGED_IN = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$USER_NAME = $_SESSION['user_name'] ?? "";
$USER_EMAIL = $_SESSION['user_email'] ?? "";

if (!$LOGGED_IN) {
  header('Location: /sign/SignUp_LogIn_Form.html');
  exit();
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Favorite Questions</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles.css">

  <style>
    /* Prevent the page title from being hidden under the fixed header/navbar */
    main#main { padding-top: 90px; }

    .fav-user-name { font-weight: 800; font-size: 1.15rem; }
  </style>
</head>
<body>
<header class="site-header">
  <nav class="navbar" aria-label="Main navigation">
    <a class="brand" href="#top" aria-label="Back to top">
      <img src="image/Hawiyah-En.png" alt="Logo" class="site-logo">
    </a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
    <ul id="nav-links" class="nav-links">
      <li class="nav-search">
        <button class="search-toggle" type="button" aria-label="Search" aria-expanded="false">🔍</button>
        <form class="nav-search-form" action="Search.php" method="get" role="search">
          <input type="search" name="q" placeholder="Search a word..." autocomplete="off">
          <button type="submit">Search</button>
        </form>
      </li>
      <li class="dropdown">
        <a class="dropbtn">My profile</a>
        <ul class="dropdown-content">
          <li><a href="dashboard.php">My profile</a></li>
          <li><a href="Favorite.php">Favorites</a></li>
          <li><a href="sign/check_session.php?logout=true" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
        </ul>
      </li>

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
      <li><a href="Favorite-ar.php" style="font-weight:700">اللغة العربية</a></li>
    </ul>
  </nav>
</header>

<main id="main">
  <section class="section section-intro">
    <div class="container">
      <h2>Favorite Questions ⭐</h2>
      <p>
        Saved questions for
        <?php
            $safeName = htmlspecialchars(trim((string)$USER_NAME));
          $displayName = $safeName !== '' ? $safeName : 'User';
          echo '<span class="fav-user-name">' . $displayName . '</span>';
        ?>
      </p>
      <div class="features" id="favoritesContainer"></div>
      <p id="emptyMsg" style="display:none; text-align:center; opacity:.85;">You currently have no favorite questions.</p>
    </div>
  </section>
</main>

<script src="assets/script.js"></script>
<script>
async function loadFavorites() {
  const resp = await fetch('api/favorite_questions.php?action=list', { credentials: 'same-origin' });
  if (!resp.ok) {
    // If not logged in or error, redirect to login
    window.location.href = '/sign/SignUp_LogIn_Form.html';
    return [];
  }
  const data = await resp.json();
  return data.favorites || [];
}

async function removeFavorite(id) {
  const resp = await fetch('api/favorite_questions.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'same-origin',
    body: JSON.stringify({ action: 'remove', id })
  });
  return resp.ok;
}

function renderFavoriteCard(fav) {
  const card = document.createElement('article');
  card.className = 'feature-card';
  card.style.position = 'relative';
  card.style.paddingBottom = '90px';

  const regionLine = document.createElement('div');
  regionLine.dir = 'auto';
  regionLine.style.fontWeight = '700';
  regionLine.style.opacity = '.85';
  regionLine.style.marginBottom = '.35rem';
  const regionText = (fav.region || '').toString().trim();
  regionLine.textContent = regionText ? ('Region: ' + regionText) : 'Region: -';

  const h3 = document.createElement('h3');
  const p = document.createElement('p');
  h3.dir = 'auto';
  p.dir = 'auto';

  if ((fav.lang || '') === 'arabic') {
    h3.textContent = 'س: ' + (fav.question || '');
    p.textContent = 'ج: ' + (fav.answer || '');
  } else {
    h3.textContent = 'Q: ' + (fav.question || '');
    p.textContent = 'Answer: ' + (fav.answer || '');
  }

  card.appendChild(regionLine);
  card.appendChild(h3);
  card.appendChild(p);

  const actions = document.createElement('div');
  actions.style.position = 'absolute';
  actions.style.bottom = '10px';
  actions.style.left = '50%';
  actions.style.transform = 'translateX(-50%)';
  actions.style.display = 'flex';
  actions.style.gap = '8px';
  actions.style.justifyContent = 'center';
  actions.style.flexWrap = 'nowrap';
  actions.style.maxWidth = 'calc(100% - 20px)';
  actions.style.overflow = 'hidden';

  const openBtn = document.createElement('button');
  openBtn.textContent = 'Open';
  openBtn.style.background = 'var(--gold-500)';
  openBtn.style.color = '#1a1a1a';
  openBtn.style.boxShadow = 'var(--shadow-md)';
  openBtn.style.fontWeight = '700';
  openBtn.style.fontSize = '1rem';
  openBtn.style.lineHeight = '1.2';
  openBtn.style.padding = '.75rem 1.1rem';
  openBtn.style.borderRadius = '.8rem';
  openBtn.style.border = '1px solid transparent';
  openBtn.style.cursor = 'pointer';
  openBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
  openBtn.onclick = () => {
    if (fav.url) window.location.href = fav.url;
  };

  const delBtn = document.createElement('button');
  delBtn.textContent = 'Delete';
  delBtn.style.background = 'var(--gold-500)';
  delBtn.style.color = '#1a1a1a';
  delBtn.style.boxShadow = 'var(--shadow-md)';
  delBtn.style.fontWeight = '700';
  delBtn.style.fontSize = '1rem';
  delBtn.style.lineHeight = '1.2';
  delBtn.style.padding = '.75rem 1.1rem';
  delBtn.style.borderRadius = '.8rem';
  delBtn.style.border = '1px solid transparent';
  delBtn.style.cursor = 'pointer';
  delBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
  delBtn.onclick = async () => {
    await removeFavorite(fav.id);
    await render();
  };

  actions.appendChild(openBtn);
  actions.appendChild(delBtn);
  card.appendChild(actions);

  return card;
}

async function render() {
  const container = document.getElementById('favoritesContainer');
  const emptyMsg = document.getElementById('emptyMsg');
  container.innerHTML = '';

  const favorites = await loadFavorites();
  if (!favorites || favorites.length === 0) {
    emptyMsg.style.display = 'block';
    return;
  }
  emptyMsg.style.display = 'none';

  favorites.slice().reverse().forEach(fav => {
    container.appendChild(renderFavoriteCard(fav));
  });
}

render();
</script>
</body>
</html>
