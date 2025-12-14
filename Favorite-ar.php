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
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>الأسئلة المفضلة</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles.css">

  <style>
    body { direction: rtl; text-align: right; }
    .navbar { flex-direction: row-reverse; }
    .nav-links { flex-direction: row-reverse; }
    .dropdown-content { right: 0; left: auto; text-align: right; }

    /* Prevent the page title from being hidden under the fixed header/navbar */
    main#main { padding-top: 90px; }

    .fav-user-name { font-weight: 800; font-size: 1.15rem; }
  </style>
</head>
<body class="rtl">
<header class="site-header">
  <nav class="navbar" aria-label="التنقل الرئيسي">
    <a class="brand" href="index-ar.php" aria-label="العودة للرئيسية">
      <img src="image/Hawiyah.png" alt="Logo" class="site-logo">
    </a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="قائمة">☰</button>

    <ul id="nav-links" class="nav-links">
      <li class="nav-search">
        <button class="search-toggle" type="button" aria-label="Search" aria-expanded="false">🔍</button>
        <form class="nav-search-form" action="Search-ar.php" method="get" role="search">
          <input type="search" name="q" placeholder="اكتب كلمة..." autocomplete="off">
          <button type="submit">بحث</button>
        </form>
      </li>

      <li class="dropdown">
        <a class="dropbtn">ملفي الشخصي</a>
        <ul class="dropdown-content">
          <li><a href="dashboard-ar.php">ملفي الشخصي</a></li>
          <li><a href="Favorite-ar.php">المفضلة</a></li>
          <li><a href="sign/check_session.php?logout=true" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟')">تسجيل خروج</a></li>
        </ul>
      </li>

      <li><a href="quiz/QUIZ-ar.php">الاختبارات</a></li>

      <li class="dropdown">
        <a class="dropbtn">الأسئلة</a>
        <ul class="dropdown-content">
          <li><a href="General-ar.php">أسئلة عامة</a></li>
          <li><a href="North-ar.php">أسئلة المنطقة الشمالية</a></li>
          <li><a href="South-ar.php">أسئلة المنطقة الجنوبية</a></li>
          <li><a href="West-ar.php">أسئلة المنطقة الغربية</a></li>
          <li><a href="East-ar.php">أسئلة المنطقة الشرقية</a></li>
          <li><a href="Central-ar.php">أسئلة المنطقة الوسطى</a></li>
        </ul>
      </li>

      <li><a href="index-ar.php">الرئيسية</a></li>

      <li><a href="Favorite.php" style="font-weight:700">English</a></li>
    </ul>
  </nav>
</header>

<main id="main">
  <section class="section section-intro">
    <div class="container">
      <h2>الأسئلة المفضلة ⭐</h2>
      <p>
        الأسئلة المحفوظة للحساب:
        <?php
            $safeName = htmlspecialchars(trim((string)$USER_NAME));
          $displayName = $safeName !== '' ? $safeName : 'مستخدم';
          echo '<span class="fav-user-name">' . $displayName . '</span>';
        ?>
      </p>
      <div class="features" id="favoritesContainer"></div>
      <p id="emptyMsg" style="display:none; text-align:center; opacity:.85;">لا توجد أسئلة مفضلة حالياً.</p>
    </div>
  </section>
</main>

<script src="assets/script.js"></script>
<script>
async function loadFavorites() {
  const resp = await fetch('api/favorite_questions.php?action=list', { credentials: 'same-origin' });
  if (!resp.ok) {
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
  regionLine.dir = 'rtl';
  regionLine.style.textAlign = 'right';
  regionLine.style.fontWeight = '700';
  regionLine.style.opacity = '.85';
  regionLine.style.marginBottom = '.35rem';
  const regionToArabic = (value) => {
    const key = (value || '').toString().trim().toUpperCase();
    const map = {
      GENERAL: 'العامة',
      NORTH: 'الشمالية',
      SOUTH: 'الجنوبية',
      EAST: 'الشرقية',
      WEST: 'الغربية',
      CENTRAL: 'الوسطى',
      CENTERAL: 'الوسطى'
    };
    return map[key] || (value || '').toString().trim();
  };

  const regionText = (fav.region || '').toString().trim();
  const regionArabic = regionToArabic(regionText);
  regionLine.textContent = regionArabic ? ('المنطقة: ' + regionArabic) : 'المنطقة: -';

  const h3 = document.createElement('h3');
  const p = document.createElement('p');
  h3.dir = 'auto';
  p.dir = 'auto';

  if ((fav.lang || '') === 'arabic') {
    h3.textContent = 'س: ' + (fav.question || '');
    p.textContent = 'ج: ' + (fav.answer || '');
    h3.dir = 'rtl';
    p.dir = 'rtl';
    h3.style.textAlign = 'right';
    p.style.textAlign = 'right';
  } else {
    h3.textContent = 'Q: ' + (fav.question || '');
    p.textContent = 'Answer: ' + (fav.answer || '');
    h3.dir = 'ltr';
    p.dir = 'ltr';
    h3.style.textAlign = 'left';
    p.style.textAlign = 'left';
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
  openBtn.textContent = 'فتح';
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
  delBtn.textContent = 'حذف';
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
