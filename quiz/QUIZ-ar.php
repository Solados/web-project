<?php
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

<style>
  /* override direction for the whole page */
  html, body { direction: rtl; }
  .navbar { direction: rtl; }
  .nav-links { display:flex; gap:1rem; align-items:center; }
  .navbar .brand { order: 0; }
  .feature-card h3 { text-align: right; }
  .quiz-gold-box { text-align: right; }
  [dir="rtl"] .qs-pagination { direction: ltr; }

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
  <div id="toast" 
     style="position:fixed; bottom:20px; right:20px; background:#4CAF50; 
            color:white; padding:12px 20px; border-radius:8px; 
            display:none; box-shadow:0 4px 12px rgba(0,0,0,0.2); 
            font-family:'Noto Kufi Arabic', sans-serif; font-size:0.95rem; z-index:999; transition: opacity 0.5s ease;">
</div>
  <!-- Header -->
  <header class="site-header">
    <nav class="navbar" aria-label="Main navigation">
      <a class="brand" href="../index-ar.php" aria-label="Back to top">
      <img src="../image/Hawiyah.png" alt="Logo" class="site-logo">
     </a>
      <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <ul id="nav-links" class="nav-links">

      <?php if ($LOGGED_IN): ?>
        <li class="dropdown">
                <a class="dropbtn">ملفي الشخصي</a>
                <!-- Profile dropdown list -->
                <ul class="dropdown-content">
                  <li><a href="../dashboard-ar.php">ملفي الشخصي</a></li>
                  <li><a href="../Favorite-ar.php">المفضلة</a></li>
                  
                  <li><a href="../sign/check_session.php?logout=true" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟')">تسجيل خروج</a></li>
                </ul>
              </li>
              <?php endif; ?>

       <?php if (!$LOGGED_IN): ?>
    <li><a href="../sign/Signup_LogIn_Form_ar.html">تسجيل الدخول</a></li>
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
      
      <li><a href="Quiz-en.php" style="font-weight:700">English</a></li>

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

        <label class="quiz-gold-label"> فئة السؤال :</label>
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
            <option value="Location_Recognition_question">تعرّف على الموقع</option>
            <option value="Cultural_Interpretation_question">التفسير الثقافي</option>
            <option value="Contextual_Usage_question">الاستخدام السياقي</option>
            <option value="Fill_in_Blank_question">املأ الفراغ (حقل تعبئة)</option>
            <option value="True_False_question">صح أم خطأ</option>
            <option value="Meaning_question">معنى الكلمة</option>
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
        <strong>هويّة</strong>
        <p>© 2025 جميع الحقوق محفوظة</p>
        <p class="footer-sources">المصادر: <a href="https://github.com/LamaAy/SaudiCulture-Dataset" target="_blank" rel="noopener noreferrer">SaudiCulture-Dataset</a>، مصادر أبشر: <a href="https://docs.google.com/spreadsheets/d/1-O91eSIvOUJEuSIDnHoaS21OHMnVc3anpw0jAVw_krs/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (كلمات)</a>، <a href="https://docs.google.com/spreadsheets/d/1nwVsA24SzxqITv_-jVQ_rQWxQ4eqpGJmIifyxZq2jsY/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (عبارات)</a>، <a href="https://docs.google.com/spreadsheets/d/1HAUXQnbA8L4dhFNEx-XQA67OeOaO5lpwX5RUMgC3swQ/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (أمثال)</a>، <a href="https://www.absher.sa" target="_blank" rel="noopener noreferrer">موقع أبشر</a>.</p>
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

  function parseChoice(choiceStr){
    const s = String(choiceStr || '').trim();
    const m = s.match(/^([A-D])\.\s*(.*)$/i);
    if (!m) return { key: s, text: s, raw: s };
    return { key: String(m[1]).toUpperCase(), text: m[2], raw: s };
  }

  function sortChoicesABCD(choices){
    const order = { A: 0, B: 1, C: 2, D: 3 };
    const keyOf = (choiceStr) => {
      const s = String(choiceStr || '').trim();
      const m = s.match(/^([A-D])\./i);
      if (!m) return 99;
      const k = String(m[1]).toUpperCase();
      return (k in order) ? order[k] : 99;
    };
    return Array.isArray(choices)
      ? choices.slice().sort((a, b) => keyOf(a) - keyOf(b))
      : [];
  }

  function showToast(message, bgColor = "#4CAF50") {
    const toast = document.getElementById("toast");
    if (!toast) return;
    toast.textContent = message;
    toast.style.background = bgColor;
    toast.style.display = "block";
    toast.style.opacity = "1";
    setTimeout(() => {
      toast.style.opacity = "0";
      setTimeout(() => { toast.style.display = "none"; }, 500);
    }, 3000);
  }

  const ICON_COPY = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <rect x="9" y="9" width="11" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
      <rect x="4" y="4" width="11" height="11" rx="2" stroke="currentColor" stroke-width="2" opacity="0.9"/>
    </svg>`;
  const ICON_SHARE = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M12 3v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <path d="M8 7l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M5 14v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>`;

  function applyCircleIconButtonStyles(btn) {
    btn.classList.add("qa-icon-btn");
    btn.style.width = "42px";
    btn.style.height = "42px";
    btn.style.borderRadius = "999px";
    btn.style.padding = "0";
    btn.style.display = "inline-flex";
    btn.style.alignItems = "center";
    btn.style.justifyContent = "center";
    btn.style.gap = "0";
    btn.style.fontSize = "0";
    btn.style.lineHeight = "0";
  }

  let selectedQuestions = [];

  async function startQuiz(){
    const count = Number(document.getElementById('questionCount').value) || 5;
    const regionSelect = document.getElementById('regionFilter');
    const regionValue = regionSelect ? regionSelect.value : 'Words';
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

    // Prefer server-side fetching for Arabic datasets when a specific filter is selected
    const arabicDatasets = ['Words','Phrases','Proverbs'];
    const preferServerForArabicFilter = arabicDatasets.includes(source);

    if (!preferServerForArabicFilter && typeof fetchQuestions === 'function'){
      try{
          const clientQ = await fetchQuestions(source, count, lang, mappedType, category);
        if(Array.isArray(clientQ) && clientQ.length>0){
          selectedQuestions = clientQ.map(q => ({
            question: q.question,
            choices: Array.isArray(q.choices) ? q.choices.slice() : [],
            answer: q.answer || null,
            type: q.type || ''
          }));
          shuffle(selectedQuestions); // Always shuffle, even for 'all'
          selectedQuestions.forEach(q => { if (Array.isArray(q.choices)) q.choices = sortChoicesABCD(q.choices); });
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
      // Build query params; for Arabic datasets we may send `arabic_filter` for dialect/block filters
      const qsObj = { source: source, count: String(count), type: mappedType === 'all' ? '' : mappedType, category: category || '' };
      if (['Words','Phrases','Proverbs'].includes(source) && mappedType && mappedType !== 'all') {
        const blockCols = ['Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];
        if (blockCols.includes(mappedType) || !['MCQ (one correct)','MCQ (multiple correct)','Open-ended'].includes(mappedType)) {
          qsObj.arabic_filter = mappedType;
          qsObj.type = '';
        }
      }
      const qs = new URLSearchParams(qsObj);
      const resp = await fetch(`quiz.php?${qs.toString()}`, { signal: controller.signal });
      clearTimeout(timeout);
      if (resp.ok) {
        const data = await resp.json();
        if (data && Array.isArray(data.questions) && data.questions.length > 0) {
          selectedQuestions = data.questions.map(q => ({
          question: q.question,
          choices: Array.isArray(q.choices) ? q.choices.slice() : [],
          answer: q.answer || null,
          type: q.type || '',
          arabic_type: q.arabic_type || ''   // ✅ أضف هذا
        }));

          shuffle(selectedQuestions); // Always shuffle, even for 'all'
          selectedQuestions.forEach(q => { if (Array.isArray(q.choices)) q.choices = sortChoicesABCD(q.choices); });
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
      selectedQuestions.forEach(q => { if (Array.isArray(q.choices)) q.choices = sortChoicesABCD(q.choices); });
    } else {
      selectedQuestions = []; // empty fallback
    }

    displayQuestions();
    setTimeout(() => { if (resultEl && resultEl.innerHTML.startsWith('جارٍ')) resultEl.innerHTML = ''; }, 300);
    window.location.hash = '#quiz';
  }

  function getBlockLabel(key){
  const k = String(key || '').trim();
  if (!k) return '';
  // تطابق مباشر
  if (blockLabels[k]) return blockLabels[k];
  // تطابق بدون حساسية لحالة الأحرف (لأن السيرفر يرسل lower-case)
  const found = Object.keys(blockLabels).find(x => x.toLowerCase() === k.toLowerCase());
  return found ? blockLabels[found] : k;
}

  function displayQuestions(){
    const container = document.getElementById('quizContainer');
    container.innerHTML = '';

    selectedQuestions.forEach((q, i) => {
      const box = document.createElement('div');
      box.className = 'feature-card';

      const inferredType = (q.type && q.type.trim())
        ? q.type.trim()
        : (Array.isArray(q.choices) && q.choices.length > 0 ? 'MCQ' : 'Open-ended');

      const badgeText = q.arabic_type ? getBlockLabel(q.arabic_type) : inferredType;

      let html = `<h3 dir="rtl">${i+1}. ${q.question}
        <span class="q-badge" style="font-size:.7rem;padding:.15rem .4rem;margin-left:.6rem;border-radius:4px;background:#efefef;color:#333;border:1px solid #ddd">
          ${badgeText}
        </span>
      </h3>`;

      if (Array.isArray(q.choices) && q.choices.length > 0) {
        html += `<div class="answers">`;
        const sortedChoices = sortChoicesABCD(q.choices);
        sortedChoices.forEach(choice => {
          html += `
          <label style="display:block;margin:.25rem 0; text-align:right;" dir="rtl">
            <input type="radio" name="q${i}" value="${choice}"> ${choice}
          </label>`;
        });
        html += `</div>`;
      } else {
        html += `
          <div style="margin-top:.5rem">
            <textarea name="q${i}_open" placeholder="اكتب إجابتك هنا..."
              style="width:100%;min-height:88px;padding:.5rem;border:1px solid #e0e0e0;border-radius:6px;font-size:1rem;font-family:inherit;resize:vertical"></textarea>
          </div>`;
      }

      box.innerHTML = html;

      // actions inside card
      const actions = document.createElement('div');
      actions.style.marginTop = '10px';
      actions.style.display = 'flex';
      actions.style.gap = '10px';
      actions.style.flexWrap = 'wrap';

      // Copy button
      const copyBtn = document.createElement('button');
      copyBtn.title = "نسخ السؤال";
      copyBtn.innerHTML = ICON_COPY;
      copyBtn.setAttribute("aria-label", "نسخ السؤال");
      copyBtn.style.background = "var(--gold-500)";
      copyBtn.style.color = "#1a1a1a";
      copyBtn.style.boxShadow = "var(--shadow-md)";
      copyBtn.style.fontWeight = "700";
      copyBtn.style.padding = ".75rem 1.1rem";
      copyBtn.style.borderRadius = ".8rem";
      copyBtn.style.border = "1px solid transparent";
      copyBtn.style.cursor = "pointer";
      copyBtn.style.transition = "transform .15s ease, box-shadow .15s ease, background .2s ease";
      copyBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
      applyCircleIconButtonStyles(copyBtn);
      copyBtn.onmouseover = () => { copyBtn.style.transform = "translateY(-2px)"; copyBtn.style.boxShadow = "var(--shadow-md)"; };
      copyBtn.onmouseout = () => { copyBtn.style.transform = "translateY(0)"; copyBtn.style.boxShadow = "var(--shadow-md)"; };
      copyBtn.onclick = () => copyQuestion(i);

      // Share button
      const shareBtn = document.createElement('button');
      shareBtn.innerHTML = ICON_SHARE;
      shareBtn.setAttribute("aria-label", "مشاركة");
      shareBtn.style.background = "var(--gold-500)";
      shareBtn.style.color = "#1a1a1a";
      shareBtn.style.boxShadow = "var(--shadow-md)";
      shareBtn.style.fontWeight = "700";
      shareBtn.style.padding = ".75rem 1.1rem";
      shareBtn.style.borderRadius = ".8rem";
      shareBtn.style.border = "1px solid transparent";
      shareBtn.style.cursor = "pointer";
      shareBtn.style.transition = "transform .15s ease, box-shadow .15s ease, background .2s ease";
      shareBtn.style.position = "relative";
      shareBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
      applyCircleIconButtonStyles(shareBtn);
      shareBtn.onmouseover = () => { shareBtn.style.transform = "translateY(-2px)"; shareBtn.style.boxShadow = "var(--shadow-md)"; };
      shareBtn.onmouseout = () => { shareBtn.style.transform = "translateY(0)"; shareBtn.style.boxShadow = "var(--shadow-md)"; };

      // Dropdown
      const popup = document.createElement('div');
      popup.style.position = 'absolute';
      popup.style.top = '0';
      popup.style.right = '100%';
      popup.style.background = '#fff';
      popup.style.border = '1px solid #ddd';
      popup.style.borderRadius = '8px';
      popup.style.padding = '6px 10px';
      popup.style.display = 'none';
      popup.style.gap = '8px';
      popup.style.boxShadow = '0 4px 12px rgba(0,0,0,.15)';
      popup.style.flexWrap = 'nowrap';
      popup.style.zIndex = '100';

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

        a.appendChild(img);
        a.onclick = (e) => { e.preventDefault(); shareQuestion(i, p.id); popup.style.display = 'none'; };
        popup.appendChild(a);
      });

      shareBtn.appendChild(popup);
      shareBtn.onclick = (e) => {
        e.stopPropagation();
        popup.style.display = popup.style.display === 'none' ? 'flex' : 'none';
      };

      popup.addEventListener('mouseleave', () => {
        popup.style.display = 'none';
      });

      document.addEventListener('click', (e) => {
        if (!shareBtn.contains(e.target)) popup.style.display = 'none';
      });

      actions.appendChild(copyBtn);
      actions.appendChild(shareBtn);

      box.appendChild(actions);

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

  // ===== favorites/copy/share =====
  function copyQuestion(index) {
    const box = document.getElementsByClassName('feature-card')[index];
    const h3 = box.querySelector('h3');
    const badge = h3.querySelector('.q-badge');
    let questionText = h3.textContent;
    if (badge) {
      questionText = questionText.replace(badge.textContent, '').trim();
    }
    const answersDiv = box.querySelector('.answers');
    let answersText = '';
    if (answersDiv) {
      const labels = answersDiv.querySelectorAll('label');
      labels.forEach(label => {
        answersText += label.textContent.trim() + '\n';
      });
      const ta = answersDiv.querySelector('textarea');
      if (ta && ta.value) answersText += ta.value + '\n';
    }
    const text = questionText + '\n\n' + answersText;
    navigator.clipboard.writeText(text).then(() => {
      showToast("تم نسخ السؤال! 📋");
    }).catch(err => {
      console.error("Error copying question:", err);
      showToast("خطأ في نسخ السؤال ❌", "#F44336");
    });
  }

  function shareQuestion(index, platform) {
    const box = document.getElementsByClassName('feature-card')[index];
    const h3 = box.querySelector('h3');
    const badge = h3.querySelector('.q-badge');
    let questionText = h3.textContent;
    if (badge) {
      questionText = questionText.replace(badge.textContent, '').trim();
    }
    const answersDiv = box.querySelector('.answers');
    let answersText = '';
    if (answersDiv) {
      const labels = answersDiv.querySelectorAll('label');
      labels.forEach(label => {
        answersText += label.textContent.trim() + '\n';
      });
      const ta = answersDiv.querySelector('textarea');
      if (ta && ta.value) answersText += ta.value + '\n';
    }
    const text = encodeURIComponent(questionText + '\n\n' + answersText + "\n" + window.location.href);
    let url = "";
    if(platform === 'x') url = `https://x.com/intent/tweet?text=${text}`;
    else if(platform === 'facebook') url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`;
    else if(platform === 'whatsapp') url = `https://api.whatsapp.com/send?text=${text}`;
    window.open(url, "_blank");
  }

 const centerTextPlugin = {
  id: 'centerText',
  beforeDraw(chart) {
    const { width, height, ctx } = chart;
    ctx.save();

    const text = chart.config.data.centerText;
    if (!text) return;
    const fontSize = height / 8;
    ctx.font = `bold ${fontSize}px Noto Kufi Arabic`;
    ctx.fillStyle = "#000";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(text, width / 2, height / 2 - fontSize * 0.3);

    ctx.restore();
  }
};
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

      const box = document.getElementById("quizContainer").children[i];
      if (box) {
        if (isCorrect){
          box.style.background = "rgba(0,255,0,0.2)";
        } else {
          box.style.background = "rgba(255,0,0,0.2)";
        }
        box.style.transition = "0.3s";
      }
    }

    const total = selectedQuestions.length || 1;
    const percent = Math.round((score/total)*100);


  document.getElementById('result').innerHTML = `
<div class="score-box animate" data-correct="${score}" data-wrong="${total - score}" data-total="${total}">
    <h2 class="score-title">نتيجتك</h2>
    <div class="score-values">
        <span class="score-main">${score} / ${total}</span>
    </div>

    <div class="tooltip">
        الإجابات الصحيحة: ${score}<br>
        الإجابات الخاطئة: ${total - score}<br>
        إجمالي الأسئلة: ${total}
    </div>
</div>

<canvas id="scoreChart" style="max-width:300px;margin:20px auto;display:block;"></canvas>


`;

    // تحميل Chart.js تلقائياً إذا لم يكن موجود
    function loadChart(callback){
        if (window.Chart){
            callback();
            return;
        }
        const s = document.createElement("script");
        s.src = "https://cdn.jsdelivr.net/npm/chart.js";
        s.onload = callback;
        s.onerror = function(){ console.error('فشل تحميل Chart.js من CDN'); callback(); };
        document.body.appendChild(s);
    }

    loadChart(() => {
        const canvas = document.getElementById("scoreChart");
        if (!canvas) return;

        // تدمير الشارت السابق إذا وجد
        if (window.quizChart){
            window.quizChart.destroy();
        }

        // أنشئ الشارت (مرّر عنصر الكانفس مباشرة)
        window.quizChart = new Chart(canvas, {
            type: "doughnut",
            data: {
                labels: ["صحيح", "خاطئ"],
                datasets: [{
                    data: [score, Math.max(0, total - score)],
                    backgroundColor: ["#1A7F3C", "#e4645aff"],
                    borderWidth: 2,
                    hoverOffset: 10
                }],
                centerText: percent + "%"
            },
            options: {
                responsive: true,
                 cutout: "65%", 
                plugins: {
                    legend: { position: "bottom" }
                }
              },
              plugins: [centerTextPlugin]
        });
        // إضافة أزرار المشاركة
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
<div style="display:flex;gap:12px;align-items:center;margin-top:20px;flex-wrap:wrap;">
  <button title="Copy result" aria-label="نسخ النتيجة" style="background:var(--gold-500);color:#1a1a1a;box-shadow:var(--shadow-md);font-weight:700;width:42px;height:42px;padding:0;border-radius:999px;border:1px solid transparent;cursor:pointer;transition:transform .15s, box-shadow .15s, background .2s;display:inline-flex;align-items:center;justify-content:center;font-size:0;line-height:0;font-family:'Noto Kufi Arabic', sans-serif;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <rect x="9" y="9" width="11" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
      <rect x="4" y="4" width="11" height="11" rx="2" stroke="currentColor" stroke-width="2" opacity="0.9"/>
    </svg>
  </button>
  <button aria-label="مشاركة" style="background:var(--gold-500);color:#1a1a1a;box-shadow:var(--shadow-md);font-weight:700;width:42px;height:42px;padding:0;border-radius:999px;border:1px solid transparent;cursor:pointer;transition:transform .15s, box-shadow .15s, background .2s;position:relative;display:inline-flex;align-items:center;justify-content:center;font-size:0;line-height:0;font-family:'Noto Kufi Arabic', sans-serif;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M12 3v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <path d="M8 7l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M5 14v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <div style="position:absolute;bottom:45px;left:0;background:#fff;border:1px solid #ddd;border-radius:8px;padding:6px 10px;display:none;gap:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);flex-wrap:wrap;z-index:100;">
      <a href="https://x.com/intent/tweet?text=${encodedText}" target="_blank" title="X" style="margin:2px;display:inline-block;"><img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/x.svg" width="26" height="26" style="transition:transform 0.2s;"></a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=${encodedURL}" target="_blank" title="Facebook" style="margin:2px;display:inline-block;"><img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg" width="26" height="26" style="transition:transform 0.2s;"></a>
      <a href="https://api.whatsapp.com/send?text=${encodedText}" target="_blank" title="WhatsApp" style="margin:2px;display:inline-block;"><img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/whatsapp.svg" width="26" height="26" style="transition:transform 0.2s;"></a>
    </div>
  </button>
</div>
`;

// Add event listeners
const copyBtn = shareContainer.querySelector('button[title="Copy result"]');
copyBtn.addEventListener('click', () => {
  navigator.clipboard.writeText(shareText).then(() => {
    showToast("النتيجة تم نسخها! 📋");
  }).catch(err => {
    console.error('Copy failed', err);
    showToast("خطأ في نسخ النتيجة ❌", "#F44336");
  });
});

const shareBtn = shareContainer.querySelector('button:nth-child(2)');
shareBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  const dropdown = shareBtn.querySelector('div');
  dropdown.style.display = dropdown.style.display === 'none' ? 'flex' : 'none';
});

document.addEventListener('click', (e) => {
  const dropdown = shareBtn.querySelector('div');
  if (!shareBtn.contains(e.target)) {
    dropdown.style.display = 'none';
  }
});
    });

  // إرسال النتيجة إلى الخادم لحفظها في ملف الملف الشخصي
  (async function sendResult() {
    try {
      const form = new URLSearchParams();
      form.append('score', String(score));
      form.append('total', String(total));
      const regionEl = document.getElementById('regionFilter');
      if (regionEl) form.append('source', regionEl.value || '');

      const resp = await fetch('save_quiz_result.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: form.toString()
      });
      const data = await resp.json().catch(() => ({}));
      if (resp.ok && data.success) {
        // عرض إشعار بسيط
        const t = document.getElementById('toast');
        if (t){ t.textContent = 'تم حفظ النتيجة في ملفك الشخصي ✅'; t.style.display='block'; setTimeout(()=>{t.style.display='none'},3000); }
      } else {
        const t = document.getElementById('toast');
        if (t){ t.textContent = 'تعذّر حفظ النتيجة'; t.style.background='#F44336'; t.style.display='block'; setTimeout(()=>{t.style.display='none'},3000); }
      }
    } catch (e) {
      console.warn('save result failed', e);
    }
  })();

}

  // Human-friendly Arabic labels for the block question column names
  const blockLabels = {
    Location_Recognition_question: 'تعرّف على الموقع',
    Cultural_Interpretation_question: 'التفسير الثقافي',
    Contextual_Usage_question: 'الاستخدام السياقي',
    Fill_in_Blank_question: 'املأ الفراغ',
    True_False_question: 'صح أم خطأ',
    Meaning_question: 'معنى الكلمة'
  };

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
      // Preserve any hardcoded options in the select and only append missing items
      const existing = new Set(Array.from(typeSelect.options || []).map(o => String(o.value)));
      if (!existing.has('all')) {
        const optAll = document.createElement('option'); optAll.value = 'all'; optAll.textContent = 'كل الأنواع';
        typeSelect.insertBefore(optAll, typeSelect.firstChild || null);
        existing.add('all');
      }
      // Do not auto-insert MCQ/multi/fill UI options here; preserve only hardcoded block types

      // If Arabic dataset, try to extract dialects and block-question columns
      const arabicDatasets = ['Words','Phrases','Proverbs'];
      if (arabicDatasets.includes(src)) {
        try {
          const csvUrl = `../data/${src}.csv`;
          const resp = await fetch(csvUrl);
          if (resp.ok) {
            const txt = await resp.text();
            const lines = txt.split(/\r?\n/);
            if (lines.length > 0) {
              const header = lines[0].split(/,(?=(?:[^"]*"[^"]*")*[^"]*$)/).map(h => h.replace(/^\"|\"$/g,'').trim());
              const dialectIdx = header.findIndex(h => h.toLowerCase() === 'dialect type' || h.toLowerCase() === 'dialect_type');
              const blockCols = ['Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];

              // collect dialects set (only short, sensible values — avoid appending long question blocks)
              if (dialectIdx >= 0) {
                const set = new Set();
                for (let i=1;i<lines.length;i++){
                  if (!lines[i]) continue;
                  const cols = lines[i].split(/,(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/);
                  let val = (cols[dialectIdx] || '').trim().replace(/^\"|\"$/g,'');
                  if (!val) continue;
                  // discard overly long values (likely question text) and values with punctuation
                  if (val.length > 3) continue;
                  // accept letters, digits, spaces, parentheses and hyphens
                  if (!/^[\p{L}\d\-\s()]+$/u.test(val)) continue;
                  set.add(val);
                  if (set.size >= 20) break; // safety cap
                }
                for (const d of Array.from(set)) {
                  if (!existing.has(d)) {
                    const o = document.createElement('option'); o.value = d; o.textContent = d; typeSelect.appendChild(o);
                    existing.add(d);
                  }
                }
              }

              for (const col of blockCols) {
                const found = header.find(h => h && h.toLowerCase() === col.toLowerCase());
                if (found && !existing.has(col)) {
                  const o = document.createElement('option'); o.value = col; o.textContent = blockLabels[col] || col; typeSelect.appendChild(o);
                  existing.add(col);
                }
              }
            }
          }
        } catch (e) { /* ignore CSV parse errors */ }
      }
    }

    populateTypeFilter();
    const regionSelectEl = document.getElementById('regionFilter');
    if (regionSelectEl) regionSelectEl.addEventListener('change', () => populateTypeFilter());
  });
  </script>

</body>
</html>
