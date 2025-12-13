<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// جلب حالة المستخدم من الجلسة
$LOGGED_IN = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$USER_NAME = $_SESSION['user_name'] ?? "";
$USER_EMAIL = $_SESSION['user_email'] ?? "";
?>
<!doctype html>
<!-- Head -->
<html lang="en" dir="ltr">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Saudi Culture | North Page</title>
  <meta name="description" content="Discover customs, traditions, and regions of the Kingdom of Saudi Arabia.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles.css">
 </head>
 <!-- Body -->
 <body>
  <!-- Header -->
  <header class="site-header">
   <!-- Navigation -->
    <nav class="navbar" aria-label="Main navigation">
     <a class="brand" href="#top" aria-label="Back to top">
                <img src="image/Hawiyah.png" alt="Logo" class="site-logo">

     </a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <!-- Nav list -->
      <ul id="nav-links" class="nav-links">


     <?php if ($LOGGED_IN): ?>
    <li class="dropdown">
            <a class="dropbtn">My profile</a>
            <!-- Profile dropdown list -->
            <ul class="dropdown-content">
              <li><a href="dashboard.php">My profile</a></li>
              <li><a href="Favorites.php">Favorites</a></li>
              <li><a href="My_quizzes.php">My Quizzes</a></li>
              <li><a href="sign/check_session.php?logout=true" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
            </ul>
          </li>
<?php else: ?>
    <li><a href="/sign/SignUp_LogIn_Form.html">Login</a></li>
<?php endif; ?>

      <li><a href="quiz/QUIZ-en.php">Quizzes</a></li>
          <li class="dropdown">
            <a class="dropbtn">Questions</a>
            <!-- Questions dropdown list -->
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
             <li><a href="North-ar.php" style="font-weight:700">اللغة العربية</a></li>
    </ul>
   </nav>
  </header>

  <!-- Slider -->
  <div id="top" class="carousel">
    <!-- list item -->
    <div class="list">
      <!-- (Hail slide) -->
      <div class="item">
        <img src="image/Hail.jpg" alt="Hail">
        <div class="content" dir="ltr">
          <div class="author">Northern of Saudi Arabia</div>
          <div class="topic">Hail</div>
          <div class="des">
            Hail features ancient forts and UNESCO-listed rock art near Jubbah, with wide desert landscapes and seasonal wildflowers. Visitors enjoy archaeological sites, traditional souks, tribal festivals, and guided tours that highlight centuries of desert history, local crafts, and authentic northern Saudi culture.
          </div>
        </div>
      </div>
      <!-- (Tabuk slide) -->
      <div class="item">
        <img src="image/Tabuk.jpg" alt="Tabuk">
        <div class="content" dir="ltr">
          <div class="author">Northern of Saudi Arabia</div>
          <div class="topic">Tabuk</div>
          <div class="des">
            Tabuk sits at the kingdom's northwest edge, offering Al-Lawz Mountains, Wadi Al Disah, and historical forts. Visitors discover hiking trails, ancient ruins, a mild climate, coastal access to the Red Sea, and emerging development projects that combine nature, heritage, and modern opportunities.
          </div>
        </div>
      </div>
      <!-- (Sakaka slide) -->
      <div class="item">
        <img src="image/sakaka.jpg" alt="Sakaka">
        <div class="content" dir="ltr">
          <div class="author">Northern of Saudi Arabia</div>
          <div class="topic">Sakaka</div>
          <div class="des">
            Sakaka in Al-Jouf is known for expansive olive groves, the Rajajil standing stones, museums, and rural markets. Visitors explore archaeological sites, taste traditional olive-oil cuisine, attend seasonal festivals, and experience local hospitality through guided tours and handicrafts exhibitions.
          </div>
        </div>
      </div>
      <!-- (Arar slide) -->
      <div class="item">
        <img src="image/Arar.jpg" alt="Arar">
        <div class="content" dir="ltr">
          <div class="author">Northern of Saudi Arabia</div>
          <div class="topic">Arar</div>
          <div class="des">
            Arar is a frontier city with strong Bedouin traditions, lively bazaars, and broad desert panoramas. Wadi Arar runs about 190 kilometers nearby. Visitors enjoy local markets, nomadic crafts, seasonal festivals, authentic cultural experiences, and welcoming northern hospitality.
          </div>
        </div>
      </div>
      <!-- (Umluj slide) -->
      <div class="item">
        <img src="image/Umluj.jpg" alt="Umluj">
        <div class="content" dir="ltr">
          <div class="author">Northern of Saudi Arabia</div>
          <div class="topic">Umluj</div>
          <div class="des">
            Umluj is a coastal Red Sea town famed for clear waters, coral reefs, and scattered islands ideal for snorkeling and boat trips. Visitors find sandy beaches, fishing villages, seafood, island excursions, and tranquil marine scenery that contrasts with inland desert destinations.
          </div>
        </div>
      </div>
    </div>
    <!-- Thumbnails -->
    <div class="thumbnail">
      <!-- Thumb: Hail -->
      <div class="item">
        <img src="image/Hail.jpg" alt="Central Region">
        <div class="content" dir="ltr">
          <div class="topic">Hail</div>
          <div class="description">Historic forts & rock art</div>
        </div>
      </div>
      <!-- Thumb: Tabuk -->
      <div class="item">
        <img src="image/Tabuk.jpg" alt="Northern Region">
        <div class="content" dir="ltr">
          <div class="topic">Tabuk</div>
          <div class="description">Snowy peaks & ancient valleys</div>
        </div>
      </div>
      <!-- Thumb: Sakaka -->
      <div class="item">
        <img src="image/sakaka.jpg" alt="Western Region">
        <div class="content" dir="ltr">
          <div class="topic">Sakaka</div>
          <div class="description">Olive groves & archaeology</div>
        </div>
      </div>
      <!-- Thumb: Arar -->
      <div class="item">
        <img src="image/Arar.jpg" alt="Southern Region">
        <div class="content" dir="ltr">
          <div class="topic">Arar</div>
          <div class="description">Wadi Arar & desert markets</div>
        </div>
      </div>
      <div class="item">
        <img src="image/Umluj.jpg" alt="Eastern Region">
        <div class="content" dir="ltr">
          <div class="topic">Umluj</div>
          <div class="description">Red Sea islands & Umluj</div>
        </div>
      </div>
    </div>
    <!-- next prev -->
    <div class="arrows">
      <button id="prev"><</button>
      <button id="next">></button>
    </div>
    <!-- time running -->
    <div class="time"></div>
  </div>

  <!-- Main -->
  <main id="main">
   <!-- Overview section -->
   <section id="overview" class="section section-intro">
    <div class="container">
    <h2 dir="ltr">Northern Region Questions</h2>
     <p dir="ltr">Questions about the Northern Region of Saudi Arabia.</p>
     
      <!-- Main language filter -->
      <div class="question-filter">
        <label>Language:</label>
        <select id="langFilter">
          <option value="all">All</option>
          <option value="arabic">Arabic</option>
          <option value="english">English</option>
        </select>
      </div>

      <!-- Arabic type filter -->
      <div id="arabicFilter" class="sub-filter" style="display:none; margin-top:10px;">
        <label>Arabic Question Type:</label>
        <select id="arabicType">
          <option value="all">All</option>
        </select>
      </div>

      <!-- English filters -->
      <div id="englishFilters" class="sub-filter" style="display:none; margin-top:10px;">
        <label>English Type:</label>
        <select id="englishType">
          <option value="all">All</option>
        </select>

        <label style="margin-left:15px;">Category:</label>
        <select id="englishCategory">
          <option value="all">All</option>
        </select>
        </div>
     
       <div class="features"></div>
    </div>
   </section>


  <!-- CTA section -->
  <section id="visit" class="section section-cta">
    <div class="container cta">
     <h2>Challenge your knowledge of Saudi culture</h2>
     <p>.Test your knowledge of Saudi culture with an engaging, authentic experience</p>
    <a class="btn btn-primary" href="quiz/QUIZ-en.php">Start now</a>
    </div>
   </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer" aria-label="footer">
   <div class="container footer-grid">
    <div>
     <strong>Saudi Culture</strong>
     <p>© 2025 All rights reserved</p>
    </div>
    <ul class="footer-links">
     <li><a href="#top">Back to Top</a></li>
     <li><a href="#main">Questions & Answers</a></li>
    </ul>
   </div>
  </footer>

  <script src="assets/script.js"></script>
<!-- Chatbase Script -->
  <script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="PkSRl6nFY3Csgenh8koIS";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>

<!-- Page script -->
  <script>
const REGION_FILE = "NORTH";   // which data file to load
let currentFilter = "all";       // all | english | arabic
let ALL_QUESTIONS = [];

// fetch data from backend
async function loadAllQuestions() {
  const resp = await fetch(`api/region_questions.php?file=${REGION_FILE}&lang=all`);
  const data = await resp.json();
  return data.questions;
}

// slider (pagination) builder
function createSlider(current, totalPages, changePage) {
  const wrap = document.createElement("div");
  wrap.className = "qs-pagination";

  // first page button
  const first = document.createElement("button");
  first.textContent = "«";
  first.disabled = current === 0;
  first.onclick = () => changePage(0);
  wrap.appendChild(first);

  // previous page button
  const prev = document.createElement("button");
  prev.textContent = "‹";
  prev.disabled = current === 0;
  prev.onclick = () => changePage(current - 1);
  wrap.appendChild(prev);

  // page numbers (current ± 2)
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

  // next page button
  const next = document.createElement("button");
  next.textContent = "›";
  next.disabled = current === totalPages - 1;
  next.onclick = () => changePage(current + 1);
  wrap.appendChild(next);

  // last page button
  const last = document.createElement("button");
  last.textContent = "»";
  last.disabled = current === totalPages - 1;
  last.onclick = () => changePage(totalPages - 1);
  wrap.appendChild(last);

  return wrap;
}

// ===========================================
// filter system (language + types + category)
// ===========================================

// Filter states
let currentArabicType = "all";
let currentEnglishType = "all";
let currentEnglishCategory = "all";

// Sets for auto-populating filter dropdowns
let discoveredArabicTypes = new Set();
let discoveredEnglishTypes = new Set();
let discoveredEnglishCategories = new Set();

// Extract all types/categories from loaded questions
function populateDynamicFilters(allQuestions) {
  discoveredArabicTypes.clear();
  discoveredEnglishTypes.clear();
  discoveredEnglishCategories.clear();

  allQuestions.forEach(q => {
    if (q.lang === "arabic" && q.arabic_type) {
      discoveredArabicTypes.add(q.arabic_type.toLowerCase());
    }
    if (q.lang === "english") {
      if (q.english_type)      discoveredEnglishTypes.add(q.english_type.toLowerCase());
      if (q.english_category)  discoveredEnglishCategories.add(q.english_category.toLowerCase());
    }
  });

  // Arabic dropdown
  const arabSel = document.getElementById("arabicType");
  arabSel.innerHTML = `<option value="all">All</option>`;
  discoveredArabicTypes.forEach(t => {
    arabSel.innerHTML += `<option value="${t}">${t}</option>`;
  });
  // لو القيمة الحالية مو موجودة ضمن الأنواع، رجّعها all
  if (currentArabicType !== "all" && !discoveredArabicTypes.has(currentArabicType)) {
    currentArabicType = "all";
  }
  arabSel.value = currentArabicType;

  // English Type dropdown
  const engTypeSel = document.getElementById("englishType");
  engTypeSel.innerHTML = `<option value="all">All</option>`;
  discoveredEnglishTypes.forEach(t => {
    engTypeSel.innerHTML += `<option value="${t}">${t}</option>`;
  });
  if (currentEnglishType !== "all" && !discoveredEnglishTypes.has(currentEnglishType)) {
    currentEnglishType = "all";
  }
  engTypeSel.value = currentEnglishType;

  // English Category dropdown
  const engCatSel = document.getElementById("englishCategory");
  engCatSel.innerHTML = `<option value="all">All</option>`;
  discoveredEnglishCategories.forEach(c => {
    engCatSel.innerHTML += `<option value="${c}">${c}</option>`;
  });
  if (currentEnglishCategory !== "all" && !discoveredEnglishCategories.has(currentEnglishCategory)) {
    currentEnglishCategory = "all";
  }
  engCatSel.value = currentEnglishCategory;
}

// Apply filters before rendering
function applyFilters(items) {

  // 1️⃣ اللغة
  if (currentFilter !== "all") {
    items = items.filter(q => q.lang === currentFilter);
  }

  // 2️⃣ العربية
  if (currentFilter === "arabic") {
    if (currentArabicType !== "all") {
      items = items.filter(q =>
        q.arabic_type &&
        q.arabic_type.toLowerCase() === currentArabicType.toLowerCase()
      );
    }
  }

  // 3️⃣ الإنجليزية
  if (currentFilter === "english") {

    if (currentEnglishType !== "all") {
      items = items.filter(q =>
        q.english_type &&
        q.english_type.toLowerCase() === currentEnglishType.toLowerCase()
      );
    }

    if (currentEnglishCategory !== "all") {
      items = items.filter(q =>
        q.english_category &&
        q.english_category.toLowerCase() === currentEnglishCategory.toLowerCase()
      );
    }
  }

  return items;
}

// render questions in the page
async function render(pageIndex = 0) {
  const container = document.querySelector(".features");

  // load all questions once
  if (ALL_QUESTIONS.length === 0) {
    ALL_QUESTIONS = await loadAllQuestions();
  }

  // build dropdowns
  populateDynamicFilters(ALL_QUESTIONS);

  // apply filters
  let filtered = applyFilters(ALL_QUESTIONS);

  // calculate pages
  let totalPages = Math.ceil(filtered.length / 20);
  let pageItems = filtered.slice(pageIndex * 20, pageIndex * 20 + 20);

  // clear old sliders
  document.querySelectorAll(".qs-pagination").forEach(el => el.remove());

  const topSlider = createSlider(pageIndex, totalPages, render);
  container.before(topSlider);

  container.innerHTML = "";

  // render cards
  pageItems.forEach(q => {
    const card = document.createElement("article");
    card.className = "feature-card";

    const h3 = document.createElement("h3");
    const p = document.createElement("p");

    if (q.lang === "arabic") {
      h3.textContent = "س: " + q.question;
      p.textContent = "ج: " + q.answer;
    } else {
      h3.textContent = "Q: " + q.question;
      p.textContent = "Answer: " + q.answer;
    }

    h3.dir = "auto";
    p.dir = "auto";

    card.appendChild(h3);
    card.appendChild(p);
    container.appendChild(card);
  });

  const bottomSlider = createSlider(pageIndex, totalPages, render);
  container.after(bottomSlider);
}

document.getElementById("arabicType").addEventListener("change", (e) => {
  currentArabicType = e.target.value;
  render(0);
});

document.getElementById("englishType").addEventListener("change", (e) => {
  currentEnglishType = e.target.value;
  render(0);
});

document.getElementById("englishCategory").addEventListener("change", (e) => {
  currentEnglishCategory = e.target.value;
  render(0);
});

// Show/Hide filters based on language
document.getElementById("langFilter").addEventListener("change", (e) => {
  currentFilter = e.target.value;

  // Reset ALL sub-filters when switching language
  currentArabicType = "all";
  currentEnglishType = "all";
  currentEnglishCategory = "all";

  // Reset UI dropdowns too
  document.getElementById("arabicType").value = "all";
  document.getElementById("englishType").value = "all";
  document.getElementById("englishCategory").value = "all";

  // Show/Hide blocks
  document.getElementById("arabicFilter").style.display =
    currentFilter === "arabic" ? "block" : "none";

  document.getElementById("englishFilters").style.display =
    currentFilter === "english" ? "block" : "none";

  render(0);
});

// initial load
render(0);
</script>

  
 </body>
 </html>
