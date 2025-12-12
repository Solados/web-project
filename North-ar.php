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
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ثقافة السعودية | المنطقة الشمالية</title>
  <meta name="description" content="اكتشف عادات وتقاليد ومواقع المنطقة الشمالية في المملكة.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="assets/styles.css">

  <style>
    /* محلي: تعديلات RTL لضمان توافق العناصر */
    body { direction: rtl; text-align: right; }
    .navbar { flex-direction: row-reverse; }
    .nav-links { flex-direction: row-reverse; }
    .dropdown-content { right: 0; left: auto; text-align: right; }
    .carousel .content { text-align: right; direction: rtl; }
    .thumbnail .content { text-align: right; direction: rtl; }
    /* حافظت على بعض عناصر الـ qs-pagination لعرض أرقام الصفحات بشكل منطقي */
    .qs-pagination { direction: ltr; }
  </style>
</head>
<body class="rtl">
  <!-- Header -->
  <header class="site-header">
    <nav class="navbar" aria-label="التنقل الرئيسي">
<<<<<<< HEAD:North-ar.html
      <a class="brand" href="index-ar.html" aria-label="العودة للرئيسية">
                  <img src="image/Hawiyah.png" alt="Logo" class="site-logo">

      </a>
=======
      <a class="brand" href="index-ar.php" aria-label="العودة للرئيسية">الثقافة السعودية</a>
>>>>>>> 75d80716f1f392d2bc2b409371740e55bbbfe0c1:North-ar.php
      <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="قائمة">☰</button>

      <ul id="nav-links" class="nav-links">
        <?php if ($LOGGED_IN): ?>
    <li class="dropdown">
            <a class="dropbtn">حسابي</a>
            <!-- Profile dropdown list -->
            <ul class="dropdown-content">
              <li><a href="dashboard.php">حسابي</a></li>
              <li><a href="Favorites.php">المفضلة</a></li>
              <li><a href="My_quizzes.php">اختباراتي</a></li>
              <li><a href="sign/check_session.php?logout=true" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟')">تسجيل خروج</a></li>
            </ul>
          </li>
<?php else: ?>
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

        <li><a href="index-ar.php">الرئيسية</a></li>

        <!-- زر تبديل اللغة — يفتح الصفحة الإنجليزية المطابقة -->
        <li><a href="North.php" style="font-weight:700">English</a></li>
      </ul>
    </nav>
  </header>

  <!-- Slider (مبني كما في الصفحة الأصلية، مع ترجمة الوصف) -->
  <!-- Slider -->
  <div id="top" class="carousel">
    <!-- list item -->
    <div class="list">
      <!-- (Hail slide) -->
      <div class="item">
        <img src="image/Hail.jpg" alt="حائل">
        <div class="content" >
          <div class="author">شمال المملكة العربية السعودية</div>
          <div class="topic">حائل</div>
          <div class="des">
تتميز حائل بحصونها الأثرية وفنونها الصخرية المدرجة في قائمة اليونسكو بالقرب من جبة، مع مناظر صحراوية خلابة وأزهار برية موسمية. يستمتع الزوار بالمواقع الأثرية والأسواق التقليدية والمهرجانات القبلية والجولات المصحوبة بمرشدين والتي تُبرز قرونًا من تاريخ الصحراء والحرف اليدوية المحلية والثقافة الأصيلة لشمال السعودية.          </div>
        </div>
      </div>
      <!-- (Tabuk slide) -->
      <div class="item">
        <img src="image/Tabuk.jpg" alt="تبوك">
        <div class="content" >
          <div class="author">شمال المملكة العربية السعودية</div>
          <div class="topic">تبوك</div>
          <div class="des">
تقع تبوك على الحافة الشمالية الغربية للمملكة، وتتميز بجبال اللوز ووادي الديسة والحصون التاريخية. يستكشف الزوار مسارات المشي لمسافات طويلة، والآثار القديمة، ومناخها المعتدل، وساحلها المطل على البحر الأحمر، ومشاريعها التنموية الناشئة التي تجمع بين الطبيعة والتراث والفرص العصرية.          </div>
        </div>
      </div>
      <!-- (Sakaka slide) -->
      <div class="item">
        <img src="image/sakaka.jpg" alt="سكاكا">
        <div class="content" >
          <div class="author">شمال المملكة العربية السعودية</div>
          <div class="topic">سكاكا</div>
          <div class="des">
تشتهر سكاكا في الجوف ببساتين الزيتون الشاسعة، وأحجار الرجاجيل، والمتاحف، والأسواق الريفية. يستكشف الزوار المواقع الأثرية، ويتذوقون أطباق زيت الزيتون التقليدية، ويحضرون المهرجانات الموسمية، ويختبرون كرم الضيافة المحلية من خلال الجولات المصحوبة بمرشدين ومعارض الحرف اليدوية.          </div>
        </div>
      </div>
      <!-- (Arar slide) -->
      <div class="item">
        <img src="image/Arar.jpg" alt="عرعر">
        <div class="content">
          <div class="author">شمال المملكة العربية السعودية</div>
          <div class="topic">عرعر</div>
          <div class="des">
عرعر مدينة حدودية تزخر بتقاليد بدوية راسخة، وأسواق نابضة بالحياة، ومناظر صحراوية خلابة. يمتد وادي عرعر على مسافة 190 كيلومترًا تقريبًا. يستمتع الزوار بالأسواق المحلية، والحرف اليدوية البدوية، والمهرجانات الموسمية، والتجارب الثقافية الأصيلة، وكرم الضيافة الشمالية الأخّاذ.          </div>
        </div>
      </div>
      <!-- (Umluj slide) -->
      <div class="item">
        <img src="image/Umluj.jpg" alt="املج">
        <div class="content">
          <div class="author">شمال المملكة العربية السعودية</div>
          <div class="topic">املج</div>
          <div class="des">
أملج مدينة ساحلية على البحر الأحمر، تشتهر بمياهها الصافية وشعابها المرجانية وجزرها المتفرقة، مثالية للغطس ورحلات القوارب. يستمتع الزوار بشواطئها الرملية وقرى الصيد والمأكولات البحرية ورحلاتها في الجزر، بالإضافة إلى مناظرها البحرية الهادئة التي تتناقض مع الوجهات الصحراوية الداخلية.          </div>
        </div>
      </div>
    </div>
    <!-- Thumbnails -->
    <div class="thumbnail">
      <!-- Thumb: Hail -->
      <div class="item">
        <img src="image/Hail.jpg" alt="حائل">
        <div class="content" dir="rtl">
          <div class="topic">حائل</div>
          <div class="description">الحصون التاريخية والفنون الصخرية</div>
        </div>
      </div>
      <!-- Thumb: Tabuk -->
      <div class="item">
        <img src="image/Tabuk.jpg" alt="تبوك">
        <div class="content" dir="rtl">
          <div class="topic">تبوك</div>
          <div class="description">القمم الثلجية والوديان القديمة</div>
        </div>
      </div>
      <!-- Thumb: Sakaka -->
      <div class="item">
        <img src="image/sakaka.jpg" alt="سكاكا">
        <div class="content" dir="rtl">
          <div class="topic">سكاكا</div>
          <div class="description">بساتين الزيتون والآثار</div>
        </div>
      </div>
      <!-- Thumb: Arar -->
      <div class="item">
        <img src="image/Arar.jpg" alt="عرعر">
        <div class="content" dir="rtl">
          <div class="topic">عرعر</div>
          <div class="description">وادي عرعر والأسواق الصحراوية</div>
        </div>
      </div>
      <div class="item">
        <img src="image/Umluj.jpg" alt="املج">
        <div class="content" dir="rtl">
          <div class="topic">املج</div>
          <div class="description">جزر البحر الأحمر وأملج</div>
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

    <!-- Overview / نظرة عامة -->
    <section id="overview" class="section section-intro">
      <div class="container">
        <h2>نظرة عامة عن المنطقة الشمالية</h2>

        <p>
          تضم المنطقة الشمالية تضاريس متنوعة ومواقع أثرية غنية، وتعتبر نقطة التقاء بين الحياة البدوية والتراث الزراعي في بعض وديانها.
        </p>

        <!-- فلتر اللغة -->
            <div class="question-filter">
            <label>اللغة:</label>
            <select id="langFilter">
                <option value="all">الكل</option>
                <option value="arabic">عربي</option>
                <option value="english">إنجليزي</option>
            </select>
            </div>

            <!-- فلتر نوع السؤال العربي -->
            <div id="arabicFilter" style="display:none; margin-top:10px;" class="question-filter">
                <label>نوع السؤال:</label>
                <select id="arabicType">
                    <option value="all">الكل</option>
                </select>
            </div>

            <!-- فلاتر الأسئلة الإنجليزية -->
            <div id="englishFilters" style="display:none; margin-top:10px;" class="question-filter">

                <label>نوع السؤال:</label>
                <select id="englishType">
                    <option value="all">الكل</option>
                </select>

                <label style="margin-left:15px;">الفئة:</label>
                <select id="englishCategory">
                    <option value="all">الكل</option>
                </select>
            </div>

        <div class="features"></div>
      </div>
    </section>

    <!-- CTA -->
    <section id="visit" class="section section-cta">
      <div class="container cta">
        <h2>تعرّف على ثقافة وشواهد المنطقة الشمالية</h2>
        <p>استكشف المواقع الأثرية، الفعاليات التقليدية، والأسواق المحلية.</p>
        <a class="btn btn-primary" href="quiz/QUIZ-ar.php">ابدأ الاختبار</a>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="site-footer" aria-label="تذييل الصفحة">
    <div class="container footer-grid">
      <div>
        <strong>الثقافة السعودية</strong>
        <p>© 2025 جميع الحقوق محفوظة</p>
      </div>
      <ul class="footer-links">
        <li><a href="#top">العودة للأعلى</a></li>
        <li><a href="#main">الأسئلة والأجوبة</a></li>
      </ul>
    </div>
  </footer>

 
  <script src="assets/script.js"></script>

  
  <!-- Page script -->
  <script>

// ==========================
// قاموس الترجمة للفلاتر
// ==========================
const TRANSLATIONS = {
  "location_recognition_question": "أسئلة تحديد الموقع",
  "cultural_interpretation_question": "أسئلة التفسير الثقافي",
  "contextual_usage_question": "أسئلة الاستخدام السياقي",
  "fill_in_blank_question": "أسئلة املأ الفراغ",
  "true_false_question": "أسئلة الصح والخطأ",
  "meaning_question": "أسئلة اختيار المعنى",

  "open-ended": "سؤال مقالي",
  "mcq (one correct)": "اختيار من متعدد (إجابة واحدة)",
  "mcq (multiple correct)": "اختيار من متعدد (عدة إجابات)",

  "languages and communication": "اللغات والتواصل",
  "celebration": "الاحتفالات",
  "entertainment": "الترفيه",
  "crafts and work": "الحرف والعمل",
  "architecture": "العمارة",
  "food": "الطعام",
  "clothes": "الملابس",
  "dating": "التعارف والعلاقات"
};

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
  arabSel.innerHTML = `<option value="all">الكل</option>`;
  discoveredArabicTypes.forEach(t => {
    arabSel.innerHTML += `<option value="${t}">${TRANSLATIONS[t] || t}</option>`;
  });
  // لو القيمة الحالية مو موجودة ضمن الأنواع، رجّعها all
  if (currentArabicType !== "all" && !discoveredArabicTypes.has(currentArabicType)) {
    currentArabicType = "all";
  }
  arabSel.value = currentArabicType;

  // English Type dropdown
  const engTypeSel = document.getElementById("englishType");
  engTypeSel.innerHTML = `<option value="all">الكل</option>`;
  discoveredEnglishTypes.forEach(t => {
    engTypeSel.innerHTML += `<option value="${t}">${TRANSLATIONS[t] || t}</option>`;
  });
  if (currentEnglishType !== "all" && !discoveredEnglishTypes.has(currentEnglishType)) {
    currentEnglishType = "all";
  }
  engTypeSel.value = currentEnglishType;

  // English Category dropdown
  const engCatSel = document.getElementById("englishCategory");
  engCatSel.innerHTML = `<option value="all">الكل</option>`;
  discoveredEnglishCategories.forEach(c => {
    engCatSel.innerHTML += `<option value="${c}">${TRANSLATIONS[c] || c}</option>`;
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
    card.className = "feature-card " + (q.lang === "english" ? "en" : "ar");

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
