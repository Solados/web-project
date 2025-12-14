<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$LOGGED_IN  = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$USER_NAME  = $_SESSION['user_name'] ?? "";
$USER_EMAIL = $_SESSION['user_email'] ?? "";

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>أسئلة عامة</title>
	<meta name="description" content="اكتشف العادات والتقاليد ومناطق المملكة العربية السعودية.">
    
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="assets/styles.css">

	<style>
		body { direction: rtl; text-align: right; }
		.navbar { flex-direction: row-reverse; }
		.nav-links { flex-direction: row-reverse; }
		.dropdown-content { text-align: right; right: 0; left: auto; }
		.carousel .content { direction: rtl !important; text-align: right; }
		.question-filter { direction: rtl; }
		.qs-pagination { direction: rtl; }
	</style>
</head>

<body class="search-page">

<!-- Header -->
<header class="site-header">
	<nav class="navbar">

		<a class="brand" href="index-ar.php" aria-label="العودة للرئيسية"> 
        <img src="image/Hawiyah.png" alt="Logo" class="site-logo">
      </a>

		<button class="menu-toggle">☰</button>

		<ul class="nav-links">
            
          <li class="nav-search">
            <button class="search-toggle" type="button" aria-label="Search" aria-expanded="false">🔍</button>
            <form class="nav-search-form" action="Search-ar.php" method="get" role="search">
              <input type="search" name="q" placeholder="اكتب كلمة..." autocomplete="off">
              <button type="submit">بحث</button>
            </form>
          </li>

       <?php if (!$LOGGED_IN): ?>
    <li><a href="/sign/SignUp_LogIn_Form.html">تسجيل الدخول</a></li>
<?php endif; ?>

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
      <?php if ($LOGGED_IN): ?>
    <li class="dropdown">
            <a class="dropbtn">ملفي الشخصي</a>
            <!-- Profile dropdown list -->
            <ul class="dropdown-content">
              <li><a href="dashboard-ar.php">ملفي الشخصي</a></li>
              <li><a href="Favorite-ar.php">المفضلة</a></li>
              
              <li><a href="sign/check_session.php?logout=true" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟')">تسجيل خروج</a></li>
            </ul>
          </li>
          <?php endif; ?>
      <li><a href="index.php" style="font-weight:700">English</a></li>

    </ul>

  </nav>
</header>

<main id="main" style="padding-top: 90px;">
  <section class="section section-intro">
    <div class="container">
      <h2>نتائج البحث</h2>

      <!-- ✅ سيرش بار صفحة النتائج (معبّى بالكلمة) -->
      <form class="page-search" action="Search-ar.php" method="get" role="search">
        <input id="pageSearchInput" type="search" name="q"
               value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>"
               placeholder="ابحث عن كلمة..." autocomplete="off">
        <button type="submit">بحث</button>
      </form>

      <p id="searchMeta" style="margin-top:10px;"></p>

      <!-- النتائج بتظهر هنا -->
      <div class="features"></div>
    </div>
  </section>
</main>

<!-- عدّل المسار إذا عندك مختلف -->
<script src="assets/script.js"></script>

<script>
const SEARCH_Q = <?= json_encode($q, JSON_UNESCAPED_UNICODE); ?>;
let ALL = [];

function prettyEnglishType(t){
  if(!t) return "";
  return t.replace(/\s+/g,' ').trim(); // مثال: mcq (one correct)
}

function prettyArabicType(t){
  if(!t) return "";
  const map = {
    "location_recognition_question":"تحديد الموقع",
    "cultural_interpretation_question":"تفسير ثقافي",
    "contextual_usage_question":"استخدام في سياق",
    "fill_in_blank_question":"اكمل الفراغ",
    "true_false_question":"صح / خطأ",
    "meaning_question":"المعنى"
  };
  return map[t] || t.replace(/_/g, " ");
}

async function loadResults(q) {
  const resp = await fetch(`api/search_questions.php?q=${encodeURIComponent(q)}&lang=all`);
  const data = await resp.json();
  return data.questions || [];
}

function createSlider(current, totalPages, changePage) {
  const wrap = document.createElement("div");
  wrap.className = "qs-pagination";

  const first = document.createElement("button");
  first.textContent = "«";
  first.disabled = current === 0;
  first.onclick = () => changePage(0);
  wrap.appendChild(first);

  const prev = document.createElement("button");
  prev.textContent = "‹";
  prev.disabled = current === 0;
  prev.onclick = () => changePage(current - 1);
  wrap.appendChild(prev);

  let start = current - 2;
  let end = current + 2;
  if (start < 0) { end += (0 - start); start = 0; }
  if (end >= totalPages) { start -= (end - totalPages + 1); end = totalPages - 1; }
  if (start < 0) start = 0;

  for (let i = start; i <= end; i++) {
    const btn = document.createElement("button");
    btn.textContent = i + 1;
    if (i === current) btn.classList.add("active");
    btn.onclick = () => changePage(i);
    wrap.appendChild(btn);
  }

  const next = document.createElement("button");
  next.textContent = "›";
  next.disabled = current === totalPages - 1;
  next.onclick = () => changePage(current + 1);
  wrap.appendChild(next);

  const last = document.createElement("button");
  last.textContent = "»";
  last.disabled = current === totalPages - 1;
  last.onclick = () => changePage(totalPages - 1);
  wrap.appendChild(last);

  return wrap;
}

async function render(pageIndex = 0) {
  const container = document.querySelector(".features");
  const metaText = document.getElementById("searchMeta");

  if (!SEARCH_Q || !SEARCH_Q.trim()) {
    metaText.textContent = "اكتب كلمة للبحث.";
    container.innerHTML = "";
    document.querySelectorAll(".qs-pagination").forEach(el => el.remove());
    return;
  }

  if (ALL.length === 0) ALL = await loadResults(SEARCH_Q);

  metaText.textContent = `الكلمة: "${SEARCH_Q}" — النتائج: ${ALL.length}`;

  const totalPages = Math.max(1, Math.ceil(ALL.length / 20));
  const safePage = Math.min(pageIndex, totalPages - 1);
  const pageItems = ALL.slice(safePage * 20, safePage * 20 + 20);

  document.querySelectorAll(".qs-pagination").forEach(el => el.remove());

  const topSlider = createSlider(safePage, totalPages, render);
  container.before(topSlider);

  container.innerHTML = "";

  if (ALL.length === 0) {
    container.innerHTML = `<p>لا توجد نتائج مطابقة.</p>`;
  } else {
    pageItems.forEach(q => {
      const card = document.createElement("article");
      card.className = "feature-card " + (q.lang === "english" ? "en" : "ar");

      // ✅ نوع السؤال
      const type = document.createElement("div");
      type.className = "q-meta";
      type.textContent = (q.lang === "arabic")
        ? prettyArabicType(q.arabic_type)
        : prettyEnglishType(q.english_type);

      const h3 = document.createElement("h3");
      const p  = document.createElement("p");

      if (q.lang === "arabic") {
        h3.textContent = "س: " + q.question;
        p.textContent  = "ج: " + q.answer;
      } else {
        h3.textContent = "Q: " + q.question;
        p.textContent  = "Answer: " + q.answer;
      }

      h3.dir = "auto";
      p.dir  = "auto";

      if (type.textContent.trim() !== "") card.appendChild(type);
      card.appendChild(h3);
      card.appendChild(p);

      container.appendChild(card);
    });
  }

  const bottomSlider = createSlider(safePage, totalPages, render);
  container.after(bottomSlider);
}

render(0);
</script>

</body>
</html>
