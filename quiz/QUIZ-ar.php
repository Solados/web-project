<<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// جلب حالة المستخدم
$LOGGED_IN = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$USER_NAME = $_SESSION['user_name'] ?? "";
$USER_EMAIL = $_SESSION['user_email'] ?? "";

// منع الوصول لغير المسجلين
if (!$LOGGED_IN) {
    header("Location: ../sign/SignUp_LogIn_Form.html");
    exit();
}
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>اختبر معلوماتك — الاختبار</title>
  <meta name="description" content="اختبر معلوماتك مع مجموعة أسئلة عشوائية">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/styles.css">
  <link rel="stylesheet" href="../assets/quiz-style.css">


  <!-- سريع: بعض تعديلات RTL خفيفة لضمان ترتيب-navbar والبطاقات -->
<style>
  /* override direction for the whole page */
  html, body { direction: rtl; }
  .navbar { direction: ltr; }
  .nav-links { display:flex; gap:1rem; align-items:center; }
  .navbar .brand { order: 0; }
  .feature-card h3 { text-align: right; }
  .quiz-gold-box { text-align: right; }
  .qs-pagination { direction: ltr; }

  /* تعديل شكل جميع select العربية */
.quiz-gold-select {
  width: 100%;
  padding: 12px 14px;
  border-radius: 12px;
  border: 1.8px solid rgba(215,181,109,0.55);
  background: linear-gradient(180deg, #fff9ef, #f7e9cd);
  color: #4b2d1b;
  font-size: 15px;
  appearance: none;
  cursor: pointer;
  transition: .25s ease;

  background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='%23b38a42' stroke-width='2'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: left 14px center;
}
</style>

</head>
<body class="rtl">
  <!-- Header -->
  <header class="site-header">
    <nav class="navbar" aria-label="Main navigation">
      <a class="brand" href="#top" aria-label="Back to top">ثقافة السعودية</a>
      <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <ul id="nav-links" class="nav-links">
        <!-- Language switch: links to the English page and current Arabic page -->


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
        <li><a href="QUIZ-ar.php">الاختبارات</a></li>

        <li class="dropdown">
          <a class="dropbtn">الأسئلة</a>
          <ul class="dropdown-content">
          <li><a href="../General-ar.php">أسئلة عامة</a></li>
            <li><a href="../North-ar.php">أسئلة المنطقة الشمالية</a></li>
            <li><a href="../South-ar.php">أسئلة المنطقة الجنوبية</a></li>
            <li><a href="../West-ar.php">أسئلة المنطقة الغربية</a></li>
            <li><a href="../East-ar.php">أسئلة المنطقة الشرقية</a></li>
            <li><a href="../Central-ar.php">أسئلة المنطقة الوسطى</a></li>
          </ul>
        </li>

        <li><a href="../index-ar.php">الرئيسية</a></li>
                <li><a href="QUIZ-en.php" style="font-weight:700">English</a></li>

      </ul>
    </nav>
  </header>

  <!-- Main -->
  <main id="main">
    <!-- Quiz intro -->
    <section class="quiz-section">
      <div class="quiz-gold-box">

        <h2 class="quiz-gold-title">اختبر معلوماتك</h2>
        <p class="quiz-gold-sub">اختر عدد الأسئلة ثم ابدأ الاختبار.</p>

        <label class="quiz-gold-label">عدد الأسئلة:</label>
        <div class="select-wrapper">
          <select id="questionCount" class="quiz-gold-select">
            <option value="5">5 أسئلة</option>
            <option value="10">10 أسئلة</option>
            <option value="15">15 سؤالاً</option>
            <option value="20">20 سؤالاً</option>
          </select>
        </div>

        <label class="quiz-gold-label">المصدر / المنطقة:</label>
        <div class="select-wrapper">
          <select id="regionFilter" class="quiz-gold-select">
            <option value="Words">كلمات</option>
            <option value="Phrases">عبارات</option>
            <option value="Proverbs">أمثال</option>
          </select>
        </div>

        <label class="quiz-gold-label">نوع السؤال:</label>
        <div class="select-wrapper">
          <select id="typeFilter" class="quiz-gold-select">
            <option value="all">كل الأنواع</option>
            <option value="mcq">اختيار من متعدد</option>
            <option value="fill">املأ الفراغ</option>
            <option value="multi">اختيارات متعددة</option>
          </select>
        </div>


        <!-- زر البداية -->
        <button id="startBtn" class="quiz-gold-btn">ابدأ الاختبار</button>

      </div>
    </section>

    <!-- Quiz Section -->
    <section id="quiz" class="section section-alt">
      <div class="container">
        <div id="quizContainer"></div>
        <div id="result" style="margin-top:1rem;font-weight:700"></div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer" aria-label="footer">
    <div class="container footer-grid">
      <ul class="footer-links">
        <li><a href="#main">العودة إلى الأعلى</a></li>
      </ul>
      <div style="text-align:left">
        <strong>ثقافة السعودية</strong>
        <p>© 2025 جميع الحقوق محفوظة</p>
      </div>
    </div>
  </footer>

  <!-- Scripts (نفس ملفات JS الموجودة لديك) -->
  <script src="../assets/script.js"></script>
  <script src="../assets/quiz-parser.js"></script>

  <!-- Quiz Script (مترجم واجهات فقط — الأسئلة لم تُلمس) -->
  <script>
  function shuffle(arr){
    for(let i=arr.length-1;i>0;i--){
      const j = Math.floor(Math.random()*(i+1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
  }

  let selectedQuestions = [];

  async function startQuiz(){
    const count = Number(document.getElementById('questionCount').value) || 5;
    const regionSelect = document.getElementById('regionFilter');
    const regionValue = regionSelect ? regionSelect.value : 'Words';
    // Arabic page only uses these datasets
    const regionFiles = ['Words','Phrases','Proverbs'];

    let source = 'Words';
    if (regionValue === 'RANDOM') {
      source = regionFiles[Math.floor(Math.random()*regionFiles.length)];
    } else if (regionValue && regionValue !== 'Words') {
      source = regionValue;
    }

    const resultEl = document.getElementById('result');
    resultEl.innerHTML = 'جارٍ تحميل الاختبار...';
    const lang = document.getElementById('langSelect') ? document.getElementById('langSelect').value : 'ar';

    let type = document.getElementById('typeFilter') ? document.getElementById('typeFilter').value : 'all';
    function mapTypeFilter(val) {
      if (!val) return 'all';
      const v = String(val).toLowerCase();
      if (v === 'all') return 'all';
      if (v === 'mcq') return 'MCQ (one correct)';
      if (v === 'multi' || v === 'multiple') return 'MCQ (multiple correct)';
      if (v === 'fill' || v === 'open' || v === 'open-ended') return 'Open-ended';
      return val;
    }
    const mappedType = mapTypeFilter(type);
    const category = document.getElementById('categoryFilter') ? document.getElementById('categoryFilter').value : 'all';

    if (typeof fetchQuestions === 'function'){
      try{
          const clientQ = await fetchQuestions(source, count, lang, mappedType, category);
        if(Array.isArray(clientQ) && clientQ.length>0){
          selectedQuestions = clientQ.map(q => ({
            question: q.question,
            choices: Array.isArray(q.choices) ? q.choices.slice() : [],
            answer: q.answer || null,
            type: q.type || ''
          }));
          selectedQuestions.forEach(q => shuffle(q.choices));
          displayQuestions();
          resultEl.innerHTML = '';
          window.location.hash = '#quiz';
          return;
        } else {
          resultEl.innerHTML = 'لم تُرجع أسئلة من المعالج المحلي، جارٍ المحاولة عبر الخادم...';
        }
      }catch(e){
        console.warn('client-side parser failed:', e);
      }
    }

    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 6000);

    try {
      const qs = new URLSearchParams({ source: source, count: String(count), type: mappedType === 'all' ? '' : mappedType, category: category || '' });
      const resp = await fetch(`quiz.php?${qs.toString()}`, { signal: controller.signal });
      clearTimeout(timeout);
      if (resp.ok) {
        const data = await resp.json();
        if (data && Array.isArray(data.questions) && data.questions.length > 0) {
          selectedQuestions = data.questions.map(q => ({
            question: q.question,
            choices: Array.isArray(q.choices) ? q.choices.slice() : [],
            answer: q.answer || null,
            type: q.type || ''
          }));
          selectedQuestions.forEach(q => shuffle(q.choices));
          displayQuestions();
          resultEl.innerHTML = '';
          window.location.hash = '#quiz';
          return;
        } else {
          resultEl.innerHTML = 'لم تُرجع أسئلة من الخادم، سيتم استخدام النسخة المحلية الاحتياطية.';
        }
      } else {
        resultEl.innerHTML = `الخادم أعاد الحالة ${resp.status}، سيتم استخدام النسخة الاحتياطية.`;
      }
    } catch (err) {
      clearTimeout(timeout);
      if (err.name === 'AbortError') {
        resultEl.innerHTML = 'انتهى وقت انتظار الخادم (6 ث). سيتم استخدام النسخة الاحتياطية.';
      } else {
        resultEl.innerHTML = 'تعذّر الوصول إلى نقطة نهاية الاختبار. سيتم استخدام النسخة الاحتياطية.';
      }
      console.warn('quiz.php fetch failed or timed out:', err);
    }

    // fallback to embedded questions when server-side fails
    if (typeof allQuestions !== 'undefined' && Array.isArray(allQuestions)) {
      selectedQuestions = allQuestions.slice();
      shuffle(selectedQuestions);
      selectedQuestions = selectedQuestions.slice(0, count);
      selectedQuestions.forEach(q => shuffle(q.choices));
    } else {
      selectedQuestions = []; // empty fallback
    }

    displayQuestions();
    setTimeout(() => { if (resultEl && resultEl.innerHTML.startsWith('جارٍ')) resultEl.innerHTML = ''; }, 300);
    window.location.hash = '#quiz';
  }

  function displayQuestions(){
    const container = document.getElementById('quizContainer');
    container.innerHTML = '';

    selectedQuestions.forEach((q, i) => {
      const box = document.createElement('div');
      box.className = 'feature-card';

      // keep the question text as-is (do not translate/change it)
      const inferredType = (q.type && q.type.trim()) ? q.type.trim() : (Array.isArray(q.choices) && q.choices.length > 0 ? 'MCQ' : 'Open-ended');

      // Note: preserve question text direction exactly as original dataset requested.
      // We'll keep dir="ltr" so dataset questions (likely English) render correctly.
      let html = `<h3 dir="ltr">${i+1}. ${q.question} <span class="q-badge" style="font-size:.7rem;padding:.15rem .4rem;margin-left:.6rem;border-radius:4px;background:#efefef;color:#333;border:1px solid #ddd">${inferredType}</span></h3>`;

      if (Array.isArray(q.choices) && q.choices.length > 0) {
        q.choices.forEach(choice => {
          // keep choice text untouched as well
          html += `
          <label style="display:block;margin:.25rem 0; text-align:right;" dir="rtl">
            <input type="radio" name="q${i}" value="${choice}"> ${choice}
          </label>`;
        });
      } else {
        html += `
          <div style="margin-top:.5rem">
            <textarea name="q${i}_open" placeholder="اكتب إجابتك هنا..." 
              style="width:100%;min-height:88px;padding:.5rem;border:1px solid #e0e0e0;border-radius:6px;font-size:1rem;font-family:inherit;resize:vertical"></textarea>
          </div>`;
      }

      box.innerHTML = html;
      container.appendChild(box);
    });

    // Add "Check Answers" button (Arabic)
    const checkBtn = document.createElement('a');
    checkBtn.className = 'btn btn-primary';
    checkBtn.textContent = 'تحقّق من الإجابات';
    checkBtn.href = '#result';
    checkBtn.style.display = 'inline-block';
    checkBtn.style.marginTop = '1rem';

    checkBtn.addEventListener('click', (e) => { 
      e.preventDefault(); 
      checkAnswers(); 
    });

    container.appendChild(checkBtn);
  }

function checkAnswers(){
    let score = 0;

    for(let i=0;i<selectedQuestions.length;i++){
      const q = selectedQuestions[i];
      let isCorrect = false;

      if (Array.isArray(q.choices) && q.choices.length > 0) {
        const sel = document.querySelector(`input[name="q${i}"]:checked`);
        if(sel && sel.value === q.answer){
            score++;
            isCorrect = true;
        }
      } else {
        const ta = document.querySelector(`textarea[name="q${i}_open"]`);
        if (ta) {
          const user = (ta.value || '').trim();
          const corr = (q.answer || '').trim();
          if (corr !== '') {
            if (user !== '' && user.toLowerCase() === corr.toLowerCase()){
                score++;
                isCorrect = true;
            }
          }
        }
      }

      // 🔥 تلوين صندوق السؤال بدون تغيير أي شيء ثاني
      const box = document.getElementById("quizContainer").children[i];
      if (isCorrect){
        box.style.background = "rgba(0,255,0,0.2)"; // أخضر شفاف
      } else {
        box.style.background = "rgba(255,0,0,0.2)"; // أحمر شفاف
      }
      box.style.transition = "0.3s";
    }

    const total = selectedQuestions.length || 1;
    const percent = Math.round((score/total)*100);

    // ⚠ استبدال طريقة كتابة النتيجة — لا نغيّر شيء آخر
    document.getElementById('result').innerHTML =
      `نتيجتك: <strong>${score} / ${total} (${percent}%)</strong><br><br>
       <canvas id="scoreChart" style="max-width:300px;margin:0 auto;display:block;"></canvas>`;

    // 🔥 تحميل Chart.js تلقائياً إذا لم يكن موجود
    function loadChart(callback){
        if (window.Chart){
            callback();
            return;
        }
        const s = document.createElement("script");
        s.src = "https://cdn.jsdelivr.net/npm/chart.js";
        s.onload = callback;
        document.body.appendChild(s);
    }

    loadChart(() => {
        const ctx = document.getElementById("scoreChart");

        // حذف أي شارت قديم
        if (window.quizChart){
            window.quizChart.destroy();
        }

        // رسم الشارت
        window.quizChart = new Chart(ctx, {
            type: "pie",
            data: {
                labels: ["صحيح", "خاطئ"],
                datasets: [{
                    data: [score, total - score],
                    backgroundColor: ["#4CAF50", "#F44336"]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });

//  إضافة أزرار المشاركة
const shareContainerId = "shareResultContainer";
let shareContainer = document.getElementById(shareContainerId);
if (!shareContainer){
    shareContainer = document.createElement("div");
    shareContainer.id = shareContainerId;
    shareContainer.style.textAlign = "center";
    shareContainer.style.marginTop = "15px";
    document.getElementById('result').appendChild(shareContainer);
}

// الرابط والنص للمشاركة
const shareText = `لقد سجلت  :${score} / ${total} (${percent}%): في الاختبار! جرب بنفسك ${window.location.href}`;
const encodedText = encodeURIComponent(shareText);
const encodedURL = encodeURIComponent(window.location.href);

// أزرار المشاركة HTML باللوقو الرسمي لكل منصة
shareContainer.innerHTML = `
  <a href="https://x.com/intent/tweet?text=${encodedText}" target="_blank" style="margin:0 5px;">
    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/x.svg" width="32" height="32" alt="x" style="vertical-align:middle; filter: invert(36%) sepia(97%) saturate(1595%) hue-rotate(176deg) brightness(93%) contrast(95%);" />
  </a>

  <a href="https://www.facebook.com/sharer/sharer.php?u=${encodedURL}" target="_blank" style="margin:0 5px;">
    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg" width="32" height="32" alt="Facebook" style="vertical-align:middle; filter: invert(29%) sepia(72%) saturate(900%) hue-rotate(182deg) brightness(90%) contrast(90%);" />
  </a>

  <a href="https://api.whatsapp.com/send?text=${encodedText}" target="_blank" style="margin:0 5px;">
    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/whatsapp.svg" width="32" height="32" alt="WhatsApp" style="vertical-align:middle; filter: invert(49%) sepia(92%) saturate(510%) hue-rotate(95deg) brightness(93%) contrast(95%);" />
  </a>
`;
    });

}

  document.addEventListener('DOMContentLoaded', () => {
    const startBtn = document.getElementById('startBtn');
    if(startBtn) startBtn.addEventListener('click', (e) => { 
      e.preventDefault(); 
      startQuiz(); 
    });

    async function populateTypeFilter() {
      const regionSelect = document.getElementById('regionFilter');
      const typeSelect = document.getElementById('typeFilter');
      if (!typeSelect || !regionSelect) return;
      const src = regionSelect.value || 'Words';
      typeSelect.innerHTML = '';
      const optAll = document.createElement('option'); optAll.value = 'all'; optAll.textContent = 'كل الأنواع';
      typeSelect.appendChild(optAll);
      // Always include the common UI filters for the Arabic page (Arabic labels)
      const staticLabels = [
        { v: 'mcq', t: 'اختيار من متعدد' },
        { v: 'multi', t: 'اختيارات متعددة (اختَر أكثر من واحد)' },
        { v: 'fill', t: 'املأ الفراغ' }
      ];
      for (const l of staticLabels) {
        const o = document.createElement('option'); o.value = l.v; o.textContent = l.t; typeSelect.appendChild(o);
      }

      // Try to discover data-driven types and append them (don't remove the static UI labels).
      let types = [];
      if (typeof fetchQuestionTypes === 'function') {
        try { types = await fetchQuestionTypes(src); } catch (e) { types = []; }
      }
      if ((!types || types.length === 0)) {
        try {
          const qs = new URLSearchParams({ action: 'types', source: src });
          const resp = await fetch(`quiz.php?${qs.toString()}`);
          if (resp.ok) {
            const data = await resp.json(); if (data && Array.isArray(data.types)) types = data.types;
          }
        } catch (e) { /* ignore */ }
      }

      if (types && types.length > 0) {
        // Avoid adding duplicates: only append types whose text/value is not already present
        const existing = new Set(Array.from(typeSelect.options).map(o => String(o.value)));
        for (const t of types) {
          if (!existing.has(String(t))) {
            const o = document.createElement('option'); o.value = t; o.textContent = t; typeSelect.appendChild(o);
            existing.add(String(t));
          }
        }
      }
    }

    populateTypeFilter();
    const regionSelectEl = document.getElementById('regionFilter');
    if (regionSelectEl) regionSelectEl.addEventListener('change', () => populateTypeFilter());
  });
  </script>

</body>
</html>
