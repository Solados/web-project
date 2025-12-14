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
  <title>Saudi Culture | South Page</title>
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
                <img src="image/Hawiyah-En.png" alt="Logo" class="site-logo">

     </a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <!-- Nav list -->
      <ul id="nav-links" class="nav-links">

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
            <!-- Profile dropdown list -->
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
          <li><a href="South-ar.php" style="font-weight:700">اللغة العربية</a></li>

    </ul>
   </nav>
  </header>
  
  <!-- Slider -->
  <div id="top" class="carousel">
    <!-- list item -->
    <div class="list">
      <!-- (Abha slide) -->
      <div class="item">
        <img src="image/abha.jpeg" alt="Central Region">
        <div class="content" dir="ltr">
          <div class="author">Southern of Saudi Arabia</div>
          <div class="topic">Abha</div>
          <div class="des">
            Abha is a charming mountain city with a mild climate year-round, featuring cultural and heritage sites as well as modern tourist attractions, making it a favored destination in the south. Visitors enjoy cable cars, scenic viewpoints, local festivals, traditional architecture, handicraft souks, mountain hiking trails, family-friendly parks, and seasonal flower displays that enrich the cultural experience throughout the year for travelers seeking cooler weather.
          </div>
          
        </div>
      </div>
      <!-- (Khamis Mushait slide) -->
      <div class="item">
        <img src="image/Khamis Mushait.jpg" alt="Northern Region">
        <div class="content" dir="ltr">
          <div class="author">Southern of Saudi Arabia</div>
          <div class="topic">Khamis Mushait</div>
          <div class="des">
            Khamis Mushait is a thriving urban city both economically and structurally, hosting modern markets and shopping centers, which strengthens its position as a key economic hub in the southern region. It features transport links, business services, local industries, educational institutions, and lively weekly markets; visitors find restaurants, cafes, and cultural events that reflect the area's modern growth and traditions throughout the year.
          </div>
          
        </div>
      </div>
      <!-- (Al-baha slide) -->
      <div class="item">
        <img src="image/albaha.JPG" alt="Western Region">
        <div class="content" dir="ltr">
          <div class="author">Southern of Saudi Arabia</div>
          <div class="topic">Al-baha</div>
          <div class="des">
            Al Baha is a green mountain city with a moderate climate, featuring terraced agricultural landscapes, heritage villages, and natural tourist sites, making it one of the prominent destinations in the south. Visitors enjoy historic villages, scenic lookout points, outdoor hiking, local markets with traditional crafts, seasonal festivals celebrating local culture, comfortable lodgings, and accessible routes for family travel throughout the year for visitors.
          </div>
          
        </div>
      </div>
      <!-- (Jazan slide) -->
      <div class="item">
        <img src="image/jazan.JPG" alt="Southern Region">
        <div class="content" dir="ltr">
          <div class="author">Southern of Saudi Arabia</div>
          <div class="topic">Jazan</div>
          <div class="des">
            Jazan is a coastal city with diverse natural landscapes, located near the famous Farasan Islands, giving it significant touristic and economic importance in the southern Kingdom. Visitors take boat trips to Farasan, dive among coral reefs, explore mangrove walks, coastal markets, savor traditional seafood dishes, attend seasonal festivals, see birdlife, and experience warm local hospitality that celebrates maritime culture and traditions throughout the year.
          </div>
          
        </div>
      </div>
      <!-- (Najran slide) -->
      <div class="item">
        <img src="image/najran.jpeg" alt="Eastern Region">
        <div class="content" dir="ltr">
          <div class="author">Southern of Saudi Arabia</div>
          <div class="topic">Najran</div>
          <div class="des">
            Najran is a city with a rich historical heritage, characterized by vast deserts and the famous Najran Valley, featuring many archaeological sites and heritage landmarks reflecting the region’s ancient civilizations. Visitors can explore fortresses, traditional markets, ancient inscriptions, oasis settlements, local crafts, seasonal festivals, and guided tours that explain the area's long history and strong cultural traditions throughout the year.
          </div>
          
        </div>
      </div>
    </div>
    <!-- Thumbnails -->
    <div class="thumbnail">
      <!-- Thumb: Abha -->
      <div class="item">
        <img src="image/abha.jpeg" alt="Central Region">
        <div class="content" dir="ltr">
          <div class="title">Abha</div>
          <div class="description">Rijal Alma</div>
        </div>
      </div>
      <!-- Thumb: Khamis Mushait -->
      <div class="item">
        <img src="image/Khamis Mushait.jpg" alt="Northern Region">
        <div class="content" dir="ltr">
          <div class="title">Khamis Mushait</div>
          <div class="description">Water Tower</div>
        </div>
      </div>
      <!-- Thumb: Al-Baha -->
      <div class="item">
        <img src="image/albaha.JPG" alt="Western Region">
        <div class="content" dir="ltr">
          <div class="title">Al-Baha</div>
          <div class="description">Al-Ays Bridge</div>
        </div>
      </div>
      <!-- Thumb: Jazan -->
      <div class="item">
        <img src="image/jazan.JPG" alt="Southern Region">
        <div class="content" dir="ltr">
          <div class="title">Jazan</div>
          <div class="description">Farasan Island</div>
        </div>
      </div>
      <!-- Thumb: Najran -->
      <div class="item">
        <img src="image/najran.jpeg" alt="Eastern Region">
        <div class="content" dir="ltr">
          <div class="title">Najran</div>
          <div class="description">Raum Castle</div>
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
    <h2 dir="ltr">Southern Region Questions</h2>
     <p dir="ltr">Questions about the Southern Region of Saudi Arabia.</p>
     
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
     <h2>Challenge your knowledge of the Southern Region</h2>
     <p>Test your knowledge about the Southern Region, its history, and heritage</p>
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
const REGION_FILE = "SOUTH";   // which data file to load
let currentFilter = "all";       // all | english | arabic
let ALL_QUESTIONS = [];
const IS_LOGGED_IN = <?php echo $LOGGED_IN ? 'true' : 'false'; ?>;

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
    card.style.position = "relative";
    card.style.paddingBottom = "80px";

    const h3 = document.createElement("h3");
    const p = document.createElement("p");

    const textForShare = (q.lang === "arabic" ? "س: " : "Q: ") + q.question + '\n\n' + (q.lang === "arabic" ? "ج: " : "Answer: ") + q.answer;
    const textForShareWithURL = textForShare + '\n' + window.location.href;

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

    // ===== Actions (Copy & Share) =====
    const actions = document.createElement("div");
    actions.className = "card-actions";
    actions.style.position = "absolute";
    actions.style.bottom = "10px";
    actions.style.left = "50%";
    actions.style.transform = "translateX(-50%)";
    actions.style.display = "flex";
    actions.style.gap = "4px";
    actions.style.justifyContent = "center";
    actions.style.flexWrap = "nowrap";
    actions.style.maxWidth = "calc(100% - 20px)";
    actions.style.overflow = "visible";

    // Copy Button
    const copyBtn = document.createElement("button");
    copyBtn.title = "Copy";
    copyBtn.innerHTML = " Copy 📄";
    copyBtn.style.background = "var(--gold-500)";
    copyBtn.style.color = "#1a1a1a";
    copyBtn.style.boxShadow = "var(--shadow-md)";
    copyBtn.style.fontWeight = "700";
    copyBtn.style.fontSize = ".85rem";
    copyBtn.style.lineHeight = "1.2";
    copyBtn.style.padding = ".5rem .75rem";
    copyBtn.style.borderRadius = ".8rem";
    copyBtn.style.border = "1px solid transparent";
    copyBtn.style.cursor = "pointer";
    copyBtn.style.transition = "transform .15s ease, box-shadow .15s ease, background .2s ease";
    copyBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
    copyBtn.onmouseover = () => { copyBtn.style.transform = "translateY(-2px)"; copyBtn.style.boxShadow = "var(--shadow-gold-hover)"; };
    copyBtn.onmouseout = () => { copyBtn.style.transform = "translateY(0)"; copyBtn.style.boxShadow = "var(--shadow-md)"; };

    copyBtn.onclick = () => {
      navigator.clipboard.writeText(textForShare);

      const toast = document.createElement("div");
      toast.textContent = " Copied! ";
      toast.style.position = "fixed";
      toast.style.bottom = "90px";
      toast.style.right = "20px";
      toast.style.background = "#4CAF50";
      toast.style.color = "#fff";
      toast.style.padding = "8px 14px";
      toast.style.borderRadius = "6px";
      toast.style.opacity = "0";
      toast.style.transition = "0.3s";
      document.body.appendChild(toast);

      setTimeout(() => (toast.style.opacity = "1"), 10);
      setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
      }, 2000);
    };

    // Favorite Button
    const favBtn = document.createElement("button");
    favBtn.title = "Favorite";
    favBtn.innerHTML = " Favorite ⭐";
    favBtn.style.background = "var(--gold-500)";
    favBtn.style.color = "#1a1a1a";
    favBtn.style.boxShadow = "var(--shadow-md)";
    favBtn.style.fontWeight = "700";
    favBtn.style.fontSize = ".85rem";
    favBtn.style.lineHeight = "1.2";
    favBtn.style.padding = ".5rem .75rem";
    favBtn.style.borderRadius = ".8rem";
    favBtn.style.border = "1px solid transparent";
    favBtn.style.cursor = "pointer";
    favBtn.style.transition = "transform .15s ease, box-shadow .15s ease, background .2s ease";
    favBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
    favBtn.onmouseover = () => { favBtn.style.transform = "translateY(-2px)"; favBtn.style.boxShadow = "var(--shadow-gold-hover)"; };
    favBtn.onmouseout = () => { favBtn.style.transform = "translateY(0)"; favBtn.style.boxShadow = "var(--shadow-md)"; };
    favBtn.onclick = async () => {
      if (!IS_LOGGED_IN) {
        window.location.href = "/sign/SignUp_LogIn_Form.html";
        return;
      }

      const showToast = (message, bg = "#4CAF50") => {
        const toast = document.createElement("div");
        toast.textContent = message;
        toast.style.position = "fixed";
        toast.style.bottom = "90px";
        toast.style.right = "20px";
        toast.style.background = bg;
        toast.style.color = "#fff";
        toast.style.padding = "8px 14px";
        toast.style.borderRadius = "6px";
        toast.style.opacity = "0";
        toast.style.transition = "0.3s";
        toast.style.zIndex = "9999";
        document.body.appendChild(toast);

        setTimeout(() => (toast.style.opacity = "1"), 10);
        setTimeout(() => {
          toast.style.opacity = "0";
          setTimeout(() => toast.remove(), 300);
        }, 2000);
      };

      try {
        const resp = await fetch("api/favorite_questions.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({
            action: "add",
            region: REGION_FILE,
            lang: q.lang,
            question: q.question,
            answer: q.answer,
            url: window.location.href
          })
        });
        if (!resp.ok) throw new Error("Request failed");
        const data = await resp.json().catch(() => ({}));
        if (data && data.ok === false) throw new Error(data.error || "Failed");
        showToast("Added to Favorites!");
      } catch (e) {
        showToast("Could not add to Favorites", "#e53935");
      }
    };

    // Share Button
    const shareBtn = document.createElement('button');
    shareBtn.textContent = "Share 🔗";
    shareBtn.style.background = "var(--gold-500)";
    shareBtn.style.color = "#1a1a1a";
    shareBtn.style.boxShadow = "var(--shadow-md)";
    shareBtn.style.fontWeight = "700";
    shareBtn.style.fontSize = ".85rem";
    shareBtn.style.lineHeight = "1.2";
    shareBtn.style.padding = ".5rem .75rem";
    shareBtn.style.borderRadius = ".8rem";
    shareBtn.style.border = "1px solid transparent";
    shareBtn.style.cursor = "pointer";
    shareBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
    shareBtn.style.transition = "transform .15s ease, box-shadow .15s ease, background .2s ease";
    shareBtn.style.position = "relative";

    shareBtn.onmouseover = () => { shareBtn.style.transform = "translateY(-2px)"; shareBtn.style.boxShadow = "var(--shadow-gold-hover)"; };
    shareBtn.onmouseout = () => { shareBtn.style.transform = "translateY(0)"; shareBtn.style.boxShadow = "var(--shadow-md)"; };

    // Share Menu
    const shareMenu = document.createElement("div");
    shareMenu.style.position = 'absolute';
    shareMenu.style.bottom = '110%'; // show above the button
    shareMenu.style.left = '50%';
    shareMenu.style.transform = 'translateX(-50%)';
    shareMenu.style.background = '#fff';
    shareMenu.style.border = '1px solid #ddd';
    shareMenu.style.borderRadius = '8px';
    shareMenu.style.padding = '8px 12px';
    shareMenu.style.display = 'none';
    shareMenu.style.gap = '12px';
    shareMenu.style.boxShadow = '0 4px 16px rgba(0,0,0,.18)';
    shareMenu.style.flexDirection = 'row';
    shareMenu.style.flexWrap = 'nowrap';
    shareMenu.style.zIndex = '100';

    shareBtn.onclick = (e) => {
      e.stopPropagation();
      shareMenu.style.display = shareMenu.style.display === 'none' ? 'flex' : 'none';
    };

    // Hide share menu if mouse leaves the menu or the button
    let shareMenuHideTimeout;
    function hideShareMenuSoon() {
      shareMenuHideTimeout = setTimeout(() => {
        shareMenu.style.display = 'none';
      }, 120);
    }
    function cancelHideShareMenu() {
      clearTimeout(shareMenuHideTimeout);
    }
    shareMenu.addEventListener('mouseleave', hideShareMenuSoon);
    shareMenu.addEventListener('mouseenter', cancelHideShareMenu);
    shareBtn.addEventListener('mouseleave', hideShareMenuSoon);
    shareBtn.addEventListener('mouseenter', cancelHideShareMenu);

    document.addEventListener('click', (e) => {
      if (!shareBtn.contains(e.target) && !shareMenu.contains(e.target)) shareMenu.style.display = 'none';
    });

    // Share Platforms
    const platforms = [
      { name: 'X', icon: 'https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/x.svg', id: 'x' },
      { name: 'Facebook', icon: 'https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg', id: 'facebook' },
      { name: 'WhatsApp', icon: 'https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/whatsapp.svg', id: 'whatsapp' }
    ];

    platforms.forEach(p => {
      const a = document.createElement('a');
      a.href = '#';
      a.title = p.name;
      a.style.margin = '2px';
      a.style.display = 'inline-block';

      const img = document.createElement('img');
      img.src = p.icon;
      img.width = 26;
      img.height = 26;
      img.style.transition = 'transform 0.2s';
      img.onmouseover = () => img.style.transform = 'scale(1.2)';
      img.onmouseout = () => img.style.transform = 'scale(1)';

      let href;
      if (p.id === 'x') {
        href = `https://x.com/intent/tweet?text=${encodeURIComponent(textForShareWithURL)}`;
      } else if (p.id === 'whatsapp') {
        href = `https://api.whatsapp.com/send?text=${encodeURIComponent(textForShareWithURL)}`;
      } else if (p.id === 'facebook') {
        href = `https://www.facebook.com/sharer/sharer.php?quote=${encodeURIComponent(textForShareWithURL)}`;
      }

      a.appendChild(img);
      a.onclick = (e) => { e.preventDefault(); window.open(href, '_blank'); shareMenu.style.display = 'none'; };
      shareMenu.appendChild(a);
    });

    shareBtn.appendChild(shareMenu);

    actions.appendChild(copyBtn);
    actions.appendChild(favBtn);
    actions.appendChild(shareBtn);

    card.appendChild(actions);
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


