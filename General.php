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
<html lang="en" dir="ltr">
 <!-- Head -->
 <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>General Questions</title>
		<meta name="description" content="Discover customs, traditions, and regions of the Kingdom of Saudi Arabia.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="assets/styles.css">
	<style>	
  .feature-card {
  position: relative;
  padding-bottom: 52px; /* مساحة محفوظة للأزرار */
}

/* حاوية الأزرار */
.card-actions {
  position: absolute;
  bottom: 10px;
  left: 10px;   /* يسار أسفل */
  display: flex;
  gap: 8px;
  align-items: center;
}

/* أيقونات الأزرار */
.card-actions img,
.card-actions svg {
  width: 20px;
  height: 20px;
}

/* زر النسخ */
.card-actions button {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 4px;
}
	
		.icon-btn {
  width: 20px;
  height: 20px;
  margin-right: 5px;
  vertical-align: middle;
}

.card-actions button {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 5px 10px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 600;
  transition: 0.3s;
}

.copyBtn { background: #2196F3; color: white; }
.copyBtn:hover { background: #1976D2; }

.card-actions button:nth-child(2) { background: #3b5998; color: white; } /* Facebook */
.card-actions button:nth-child(2):hover { background: #2d4373; }

.card-actions button:nth-child(3) { background: #25D366; color: white; } /* WhatsApp */
.card-actions button:nth-child(3):hover { background: #1ebe57; }

.card-actions button:nth-child(4) { background: #1DA1F2; color: white; } /* Twitter */
.card-actions button:nth-child(4):hover { background: #0d95e8; }

.card-actions button:nth-child(5) { background: #0077B5; color: white; } /* LinkedIn */
.card-actions button:nth-child(5):hover { background: #005983; }
.copy-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  background-color: #4CAF50; /* أخضر جذاب */
  color: white;
  border: none;
  border-radius: 6px;
  padding: 5px 10px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}

.copy-btn:hover {
  background-color: #45a049;
}

.copy-btn .icon-btn {
  width: 18px;
  height: 18px;
}

	</style>
 </head>
 <!-- Body -->
 <body>
	<header class="site-header">
		<!-- Header -->
		<!-- Navigation -->
		<nav class="navbar" aria-label="Main navigation">
		 <a class="brand" href="#top" aria-label="Back to top">
			          <img src="image/Hawiyah-En.png" alt="Logo" class="site-logo">

		 </a>
		<button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
			<!-- Nav list -->
			<ul id="nav-links" class="nav-links">
			<!-- Language switcher -->

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
              <li><a href="Favorites.php">Favorites</a></li>
              
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
					 <li><a href="General-ar.php" style="font-weight:700">اللغة العربية</a></li>
		</ul>
	 </nav>
	</header>

	<!-- Slider -->
	<div id="top" class="carousel">
		<!-- list item -->
		<div class="list">
			<!-- (Central slide) -->
			<div class="item">
					<img src="image/Central.jpeg" alt="Central Region">
				<div class="content" dir="ltr">
					<div class="author">Kingdom of Saudi Arabia</div>
					<div class="topic">Central Region</div>
					<div class="des">
						The Central Region centers on Riyadh and Al-Qassim, blending modern urban life with fertile oases and historic sites. Visitors find museums, markets, traditional crafts, and festivals that showcase Saudi culture, cuisine, and hospitality alongside modern amenities and growing cultural attractions.
					</div>
					<!-- Link: Central -->
					<div class="buttons">
						<button type="button" onclick="location.href='Central.php'">Explore</button>
					</div>
				</div>
			</div>
			<!-- (Northern slide) -->
			<div class="item">
					<img src="image/North.jpg" alt="Northern Region">
				<div class="content" dir="ltr">
					<div class="author">Kingdom of Saudi Arabia</div>
					<div class="topic">Northern Region</div>
					<div class="des">
						The Northern Region includes Tabuk, Al-Jouf, and vast desert areas with archaeological sites and olive groves. Travelers discover ancient ruins, rock art, natural valleys, guided tours showcasing local cuisine and crafts, seasonal landscapes, and authentic rural hospitality.
					</div>
					<!-- Link: North -->
					<div class="buttons">
						<button type="button" onclick="location.href='North.php'">Explore</button>
					</div>
				</div>
			</div>
			<!-- (Western slide) -->
			<div class="item">
					<img src="image/West.jpeg" alt="Western Region">
				<div class="content" dir="ltr">
					<div class="author">Kingdom of Saudi Arabia</div>
					<div class="topic">Western Region</div>
					<div class="des">
						The Western Region features Makkah, Madinah, Jeddah and Taif, combining sacred sites, historic neighborhoods, Red Sea coastline, and mountain retreats. Visitors experience pilgrimage services, coastal promenades, cultural heritage, rose gardens, festivals and markets throughout the year.
					</div>
					<!-- Link: West -->
					<div class="buttons">
						<button type="button" onclick="location.href='West.php'">Explore</button>
					</div>
				</div>
			</div>
			<!-- (Southern slide) -->
			<div class="item">
					<img src="image/South.jpeg" alt="Southern Region">
				<div class="content" dir="ltr">
					<div class="author">Kingdom of Saudi Arabia</div>
					<div class="topic">Southern Region</div>
					<div class="des">
						The Southern Region offers Asir, Jazan, Najran, and Al-Baha with mountains, terraced farms, tropical islands, and historic forts. Visitors enjoy hiking, festivals, coastal excursions, traditional markets, unique architecture, and local crafts that highlight regional biodiversity and cultural traditions throughout the year.
					</div>
					<!-- Link: South -->
					<div class="buttons">
						<button type="button" onclick="location.href='South.php'">Explore</button>
					</div>
				</div>
			</div>
			<!-- (Eastern slide) -->
			<div class="item">
					<img src="image/East.jpeg" alt="Eastern Region">
				<div class="content" dir="ltr">
					<div class="author">Kingdom of Saudi Arabia</div>
					<div class="topic">Eastern Region</div>
					<div class="des">
						The Eastern Province includes Dammam, Khobar, Qatif, and Al-Ahsa, blending modern cities, industrial centers, and a vast historic oasis. Visitors find waterfront promenades, heritage sites, date palms, cultural festivals, and coastal attractions reflecting economic growth and traditional life throughout the year.
					</div>
					<!-- Link: East -->
					<div class="buttons">
						<button type="button" onclick="location.href='East.php'">Explore</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Thumbnails -->
		<div class="thumbnail">
			<div class="item">
				<!-- Thumb: Central -->
				<img src="image/Central.jpeg" alt="Central Region">
				<div class="content" dir="ltr">
					<div class="title">Central Region</div>
					<div class="description">Riyadh</div>
				</div>
			</div>
			<div class="item">
				<!-- Thumb: North -->
				<img src="image/North.jpg" alt="Northern Region">
				<div class="content" dir="ltr">
					<div class="title">Northern Region</div>
					<div class="description">Tabuk</div>
				</div>
			</div>
			<div class="item">
				<!-- Thumb: West -->
				<img src="image/West.jpeg" alt="Western Region">
				<div class="content" dir="ltr">
					<div class="title">Western Region</div>
					<div class="description">Makkah</div>
				</div>
			</div>
			<div class="item">
				<!-- Thumb: South -->
				<img src="image/South.jpeg" alt="Southern Region">
				<div class="content" dir="ltr">
					<div class="title">Southern Region</div>
					<div class="description">Asir</div>
				</div>
			</div>
			<div class="item">
				<!-- Thumb: East -->
				<img src="image/East.jpeg" alt="Eastern Region">
				<div class="content" dir="ltr">
					<div class="title">Eastern Region</div>
					<div class="description">Dammam</div>
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
		<h2 dir="ltr">General Questions About Saudi Arabia</h2>
	 	<p dir="ltr">Browse general knowledge questions about Saudi Arabia.</p>
    
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
	<!-- Chatbase Script -->
	<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="PkSRl6nFY3Csgenh8koIS";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>

	<!-- Scripts -->
	<script src="assets/script.js"></script>
    
	<!-- Page script -->
  <script>
const REGION_FILE = "GENERAL";   // which data file to load
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

pageItems.forEach(q => {
  const card = document.createElement("article");
  card.className = "feature-card " + (q.lang === "english" ? "en" : "ar");
  card.style.position = "relative";

  const h3 = document.createElement("h3");
  const p = document.createElement("p");

  const textForShare =
    q.lang === "arabic"
      ? `س: ${q.question}\nج: ${q.answer}`
      : `Q: ${q.question}\nAnswer: ${q.answer}`;

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

  /* ======================
     أزرار أسفل يسار
  ====================== */
  const actions = document.createElement("div");
  actions.className = "card-actions";
  actions.style.position = "absolute";
  actions.style.bottom = "10px";
  actions.style.left = "10px";
  actions.style.display = "flex";
  actions.style.gap = "6px";

  /* ===== زر النسخ ===== */
const copyBtn = document.createElement("button");
copyBtn.title = "Copy";

copyBtn.innerHTML = `
<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
     xmlns="http://www.w3.org/2000/svg">
  <rect x="9" y="9" width="13" height="13" rx="2"
        stroke="currentColor" stroke-width="2"/>
  <rect x="3" y="3" width="13" height="13" rx="2"
        stroke="currentColor" stroke-width="2"/>
</svg>
`;

copyBtn.style.background = "transparent";
copyBtn.style.border = "none";
copyBtn.style.cursor = "pointer";
copyBtn.style.padding = "4px";

  copyBtn.onclick = () => {
    navigator.clipboard.writeText(textForShare);

    const toast = document.createElement("div");
    toast.textContent = " Copyed! ";
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

  /* ===== زر المشاركة ===== */
  const shareBtn = document.createElement("button");
  shareBtn.textContent = "🔗";
  shareBtn.title = "Share";

  /* ===== نافذة المشاركة ===== */
  const shareMenu = document.createElement("div");
  shareMenu.style.position = "absolute";
  shareMenu.style.bottom = "40px";
  shareMenu.style.left = "0";
  shareMenu.style.background = "#fff";
  shareMenu.style.borderRadius = "10px";
  shareMenu.style.padding = "6px";
  shareMenu.style.display = "none";
  shareMenu.style.gap = "8px";
  shareMenu.style.boxShadow = "0 4px 12px rgba(0,0,0,0.15)";
  shareMenu.style.display = "flex";

  /* إخفاء مبدئي */
  shareMenu.style.visibility = "hidden";

  shareBtn.onclick = () => {
    shareMenu.style.visibility =
      shareMenu.style.visibility === "hidden" ? "visible" : "hidden";
  };

  /* ===== أيقونات المشاركة ===== */
  const createIcon = (href, img) => {
    const a = document.createElement("a");
    a.href = href;
    a.target = "_blank";
    a.innerHTML = `<img src="${img}" style="width:22px;height:22px">`;
    return a;
  };

  shareMenu.appendChild(
    createIcon(
      `https://x.com/intent/tweet?text=${encodeURIComponent(textForShare)}`,
      "image/X_logo.jpg.webp"
    )
  );

  shareMenu.appendChild(
    createIcon(
      `https://api.whatsapp.com/send?text=${encodeURIComponent(textForShare)}`,
      "https://cdn-icons-png.flaticon.com/512/733/733585.png"
    )
  );

  shareMenu.appendChild(
    createIcon(
      `https://www.facebook.com/sharer/sharer.php?quote=${encodeURIComponent(textForShare)}`,
      "https://cdn-icons-png.flaticon.com/512/733/733547.png"
    )
  );

  actions.appendChild(copyBtn);
  actions.appendChild(shareBtn);
  actions.appendChild(shareMenu);

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
