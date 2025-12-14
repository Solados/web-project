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

<!DOCTYPE html>
<!-- Head -->
<html lang="en" dir="ltr">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Test Your Knowledge — Quiz</title>
  <meta name="description" content="Test your knowledge with a random set of Arabic questions">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/styles.css">
  <link rel="stylesheet" href="../assets/quiz-style.css">

 </head>
 <!-- Body -->
 <body>
  <div id="toast" 
     style="position:fixed; bottom:20px; right:20px; background:#4CAF50; 
            color:white; padding:12px 20px; border-radius:8px; 
            display:none; box-shadow:0 4px 12px rgba(0,0,0,0.2); 
            font-family:'Noto Kufi Arabic', sans-serif; font-size:0.95rem; z-index:999;">
</div>
  <!-- Site wrapper --> 
  <!-- Header -->
  <header class="site-header">
    <!-- Header -->
    <!-- Navigation -->
    <nav class="navbar" aria-label="Main navigation">
    <a class="brand" href="#top" aria-label="Back to top">
                <img src="../image/Hawiyah.png" alt="Logo" class="site-logo">
     </a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <!-- Nav list -->
      <ul id="nav-links" class="nav-links">

     <?php if ($LOGGED_IN): ?>
   <li class="dropdown">
            <a class="dropbtn">My profile</a>
            <!-- Profile dropdown list -->
            <ul class="dropdown-content">
              <li><a href="../dashboard.php">My profile</a></li>
              <li><a href="Favorites.php">Favorites</a></li>
              
              <li><a href="../sign/check_session.php?logout=true" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
            </ul>
          </li>
<?php else: ?>
    <li><a href="../sign/SignUp_LogIn_Form.html">Login</a></li>
<?php endif; ?>
      <li><a href="QUIZ-en.php">Quizzes</a></li>
          <li class="dropdown">
        <a class="dropbtn">Questions</a>
        <ul class="dropdown-content">
                      <li><a href="../General.php">General Questions</a></li>
                        <li><a href="../North.php">Northern Questions</a></li>
                        <li><a href="../South.php">Southern Questions</a></li>
                        <li><a href="../West.php">Western Questions</a></li>
                        <li><a href="../East.php">Eastern Questions</a></li>
                        <li><a href="../Central.php">Central Questions</a></li>
        </ul>
      </li>
     <li><a href="../index.php">Home</a></li>
                  <li><a href="QUIZ-ar.php" style="font-weight:700">اللغة العربية</a></li>

    </ul>
   </nav>
  </header>

  <!-- Main -->
  <main id="main">
   <!-- Quiz intro -->
   <section class="quiz-section">
  <div class="quiz-gold-box">

    <h2 class="quiz-gold-title">Test your knowledge</h2>
    <p class="quiz-gold-sub">
      Choose the number of questions then start the quiz.
    </p>

    <label class="quiz-gold-label">Select number of questions:</label>
    <div class="select-wrapper">
      <select id="questionCount" class="quiz-gold-select">
        <option value="5">5 questions</option>
        <option value="10">10 questions</option>
        <option value="15">15 questions</option>
        <option value="20">20 questions</option>
      </select>
    </div>

    <label class="quiz-gold-label">Select region/source:</label>
    <div class="select-wrapper">
      <select id="regionFilter" class="quiz-gold-select">
        <option value="GENERAL">GENERAL</option>
        <option value="CENTERAL">Central</option>
        <option value="NORTH">North</option>
        <option value="SOUTH">South</option>
        <option value="EAST">East</option>
        <option value="WEST">West</option>
        <option value="RANDOM">Random</option>
      </select>
    </div>

    <label class="quiz-gold-label">Select question type:</label>
    <div class="select-wrapper">
      <select id="typeFilter" class="quiz-gold-select">
        <option value="all">All Types</option>
        <option value="mcq">Multiple Choice</option>
        <option value="fill">Fill in the Blank</option>
        <option value="multi">Multiple Answers</option>
      </select>
    </div>
    <label class="quiz-gold-label">Select category:</label>
    <div class="select-wrapper">
      <select id="categoryFilter" class="quiz-gold-select">
        <option value="all">All Categories</option>
      </select>
    </div>

    <!-- زر البداية يجب أن يكون هنا داخل الصندوق -->
    <button id="startBtn" class="quiz-gold-btn">Start Quiz</button>

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
     <li><a href="#main">Back to Top</a></li>
    </ul>
    <div style="text-align:right">
     <strong>Saudi Culture</strong>
     <p>© 2025 All rights reserved</p>
    </div>
   </div>
  </footer>
  <!-- Scripts -->
  <script src="../assets/script.js"></script>

  <!-- Quiz Script -->
<script>
  function showToast(message, bgColor = "#4CAF50") {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.style.background = bgColor;
    toast.style.display = "block";
    toast.style.opacity = "1";

    setTimeout(() => {
      toast.style.transition = "opacity 0.5s ease";
      toast.style.opacity = "0";
      setTimeout(() => { toast.style.display = "none"; toast.style.transition = ""; }, 500);
    }, 3000);
  }

  function shuffle(arr){
    for(let i=arr.length-1;i>0;i--){
      const j = Math.floor(Math.random()*(i+1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
  }

  // ===== NEW: English quiz only =====
  let selectedQuestions = [];

  function mapTypeFilter(val){
    if(!val) return 'all';
    const v = String(val).trim();
    const low = v.toLowerCase();
    if (low === 'all') return 'all';
    if (low === 'mcq') return 'MCQ (one correct)';
    if (low === 'multi' || low === 'multiple') return 'MCQ (multiple correct)';
    if (low === 'fill' || low === 'open' || low === 'open-ended') return 'Open-ended';
    // لو القائمة صارت ترجع النص الكامل
    return v;
  }

  function normTypeFromQuestion(q){
  const t = String(q.english_type_norm || q.english_type || q.type || '').toLowerCase().trim();

  // ✅ يدعم القيم المختصرة اللي يرسلها السيرفر: multi/one/open
  if (t === 'multi' || t.includes('multiple correct') || t.includes('multiple')) return 'multi';
  if (t === 'one'  || t.includes('one correct')      || t.includes('one'))      return 'one';
  if (t === 'open' || t.includes('open-ended')       || t.includes('open'))     return 'open';

  const hasChoices = Array.isArray(q.choices) && q.choices.length >= 2;
  return hasChoices ? 'one' : 'open';
}

  function parseChoice(choiceStr){
    const s = String(choiceStr || '').trim();
    const m = s.match(/^([A-D])\.\s*(.*)$/);
    if (!m) return { key: s, text: s, raw: s };
    return { key: m[1], text: m[2], raw: s };
  }

  async function fetchEnglishQuestions({file, count, type, category}){
    const qs = new URLSearchParams();
    qs.set('file', file);
    qs.set('lang', 'english');
    qs.set('count', String(count));
    if (type && type !== 'all') qs.set('type', type);
    if (category && category !== 'all') qs.set('category', category);

    const resp = await fetch(`quiz.php?${qs.toString()}`);
    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
    const data = await resp.json();
    return (data && Array.isArray(data.questions)) ? data.questions : [];
  }

  async function fetchEnglishMeta(file){
    // نجيب كمية كبيرة بدون فلتر ثم نجمع الأنواع/التصنيفات
    const qs = new URLSearchParams();
    qs.set('file', file);
    qs.set('lang', 'english');
    qs.set('count', '5000');

    const resp = await fetch(`quiz.php?${qs.toString()}`);
    if (!resp.ok) return { types: [], categories: [] };
    const data = await resp.json();
    const qsArr = (data && Array.isArray(data.questions)) ? data.questions : [];

    const typeSet = new Map();
    const catSet  = new Map();

    for (const q of qsArr){
      const tr = String(q.english_type || '').trim();
      const cr = String(q.english_category || '').trim();
      if (tr) typeSet.set(tr.toLowerCase(), tr);
      if (cr) catSet.set(cr.toLowerCase(), cr);
    }

    const types = Array.from(typeSet.values()).sort((a,b)=>a.localeCompare(b));
    const categories = Array.from(catSet.values()).sort((a,b)=>a.localeCompare(b));
    return { types, categories };
  }

  async function populateTypeAndCategory(){
    const regionSelect = document.getElementById('regionFilter');
    const typeSelect = document.getElementById('typeFilter');
    const catSelect = document.getElementById('categoryFilter');
    if (!regionSelect || !typeSelect || !catSelect) return;

    const regionValue = regionSelect.value || 'GENERAL';
    const regionFiles = ['GENERAL','CENTERAL','NORTH','SOUTH','EAST','WEST'];
    const src = (regionValue === 'RANDOM')
      ? regionFiles[Math.floor(Math.random()*regionFiles.length)]
      : regionValue;

    // reset
    typeSelect.innerHTML = '';
    const optAllT = document.createElement('option');
    optAllT.value = 'all';
    optAllT.textContent = 'All Types';
    typeSelect.appendChild(optAllT);

    catSelect.innerHTML = '';
    const optAllC = document.createElement('option');
    optAllC.value = 'all';
    optAllC.textContent = 'All Categories';
    catSelect.appendChild(optAllC);

    const meta = await fetchEnglishMeta(src);

    // types
    for (const t of meta.types){
      const o = document.createElement('option');
      // نخلي value شورت عشان startQuiz يعمل mapTypeFilter
      if (t.toLowerCase().includes('multiple')) o.value = 'multi';
      else if (t.toLowerCase().includes('one')) o.value = 'mcq';
      else if (t.toLowerCase().includes('open')) o.value = 'fill';
      else o.value = t;
      o.textContent = t;
      typeSelect.appendChild(o);
    }

    // categories
    for (const c of meta.categories){
      const o = document.createElement('option');
      o.value = c;
      o.textContent = c;
      catSelect.appendChild(o);
    }
  }

  async function startQuiz(){
    const count = Number(document.getElementById('questionCount').value) || 5;

    const regionSelect = document.getElementById('regionFilter');
    const regionValue = regionSelect ? regionSelect.value : 'GENERAL';
    const regionFiles = ['GENERAL','CENTERAL','NORTH','SOUTH','EAST','WEST'];

    let source = 'GENERAL';
    if (regionValue === 'RANDOM') source = regionFiles[Math.floor(Math.random()*regionFiles.length)];
    else source = regionValue || 'GENERAL';

    const typeVal = document.getElementById('typeFilter') ? document.getElementById('typeFilter').value : 'all';
    const mappedType = mapTypeFilter(typeVal);

    const categoryVal = document.getElementById('categoryFilter') ? document.getElementById('categoryFilter').value : 'all';

    const resultEl = document.getElementById('result');
    resultEl.innerHTML = 'Loading quiz...';

    try{
      const questions = await fetchEnglishQuestions({
        file: source,
        count,
        type: mappedType,
        category: categoryVal
      });

      if (!questions || questions.length === 0){
        resultEl.innerHTML = 'No questions found for your filters.';
        selectedQuestions = [];
        document.getElementById('quizContainer').innerHTML = '';
        return;
      }

      selectedQuestions = questions;
      // shuffle for variety
      shuffle(selectedQuestions);
      // shuffle choices per question (keep "A. ..." as item; shuffle doesn't break parsing)
      selectedQuestions.forEach(q => { if (Array.isArray(q.choices)) shuffle(q.choices); });

      displayQuestions();
      resultEl.innerHTML = '';
      window.location.hash = '#quiz';
    }catch(e){
      console.error(e);
      resultEl.innerHTML = 'Could not load quiz questions.';
    }
  }

  function displayQuestions(){
    const container = document.getElementById('quizContainer');
    container.innerHTML = '';

    selectedQuestions.forEach((q, i) => {
      const box = document.createElement('div');
      box.className = 'feature-card';

      const qTypeNorm = normTypeFromQuestion(q);
      const hasChoices = Array.isArray(q.choices) && q.choices.length >= 2;

      // header row
      const titleRow = document.createElement('div');
      titleRow.style.display = 'flex';
      titleRow.style.justifyContent = 'space-between';
      titleRow.style.gap = '10px';
      titleRow.style.alignItems = 'center';

      const h3 = document.createElement('h3');
      h3.style.margin = '0';
      h3.textContent = `${i + 1}. ${q.question || ''}`;

      const badge = document.createElement('span');
      badge.className = 'q-badge';
      badge.style.fontSize = '.7rem';
      badge.style.padding = '.15rem .4rem';
      badge.style.marginLeft = '.6rem';
      badge.style.borderRadius = '4px';
      badge.style.background = '#efefef';
      badge.style.color = '#333';
      badge.style.border = '1px solid #ddd';

      if (qTypeNorm === 'multi') badge.textContent = 'MCQ (multiple correct)';
      else if (qTypeNorm === 'one' && hasChoices) badge.textContent = 'MCQ (one correct)';
      else badge.textContent = 'Open-ended';

      titleRow.appendChild(h3);
      titleRow.appendChild(badge);
      box.appendChild(titleRow);

      const answersWrap = document.createElement('div');
      answersWrap.className = 'answers';
      answersWrap.style.marginTop = '12px';

      // Open-ended
      if (qTypeNorm === 'open' || !hasChoices){
        const ta = document.createElement('textarea');
        ta.name = `q${i}_open`;
        ta.rows = 3;
        ta.placeholder = 'Write your answer here...';
        ta.style.width = '100%';
        ta.style.resize = 'vertical';
        ta.style.padding = '.5rem';
        ta.style.border = '1px solid #e0e0e0';
        ta.style.borderRadius = '6px';
        ta.style.fontSize = '1rem';
        ta.style.fontFamily = 'inherit';
        answersWrap.appendChild(ta);
      } else {
        const multi = (qTypeNorm === 'multi');
        q.choices.forEach((choiceStr) => {
          const c = parseChoice(choiceStr);

          const label = document.createElement('label');
          label.style.display = 'flex';
          label.style.alignItems = 'center';
          label.style.gap = '10px';
          label.style.margin = '.25rem 0';

          const input = document.createElement('input');
          input.type = multi ? 'checkbox' : 'radio';
          input.name = `q${i}`;
          // القيمة = الحرف (A/B/C/D) للتصحيح
          input.value = c.key;

          const span = document.createElement('span');
          span.textContent = c.raw; // يعرض A. النص كما في الملف

          label.appendChild(input);
          label.appendChild(span);
          answersWrap.appendChild(label);
        });
      }

      box.appendChild(answersWrap);

      // actions inside card (same idea as قبل)
      const actions = document.createElement('div');
      actions.style.marginTop = '10px';
      actions.style.display = 'flex';
      actions.style.gap = '10px';
      actions.style.flexWrap = 'wrap';
      // Copy button
      const copyBtn = document.createElement('button');
      copyBtn.title = "Copy question";
      copyBtn.innerHTML = " Copy 📄";
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
      copyBtn.onmouseover = () => { copyBtn.style.transform = "translateY(-2px)"; copyBtn.style.boxShadow = "var(--shadow-gold-hover)"; };
      copyBtn.onmouseout = () => { copyBtn.style.transform = "translateY(0)"; copyBtn.style.boxShadow = "var(--shadow-md)"; };
      copyBtn.onclick = () => copyQuestion(i);

      // Share button
      const shareBtn = document.createElement('button');
      shareBtn.textContent = "Share 🔗";
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
      shareBtn.onmouseover = () => { shareBtn.style.transform = "translateY(-2px)"; shareBtn.style.boxShadow = "var(--shadow-gold-hover)"; };
      shareBtn.onmouseout = () => { shareBtn.style.transform = "translateY(0)"; shareBtn.style.boxShadow = "var(--shadow-md)"; };

      // Dropdown
      const popup = document.createElement('div');
      popup.style.position = 'absolute';
      popup.style.top = '0';
      popup.style.left = '100%';
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

      document.addEventListener('click', (e) => {
        if (!shareBtn.contains(e.target)) popup.style.display = 'none';
      });

      actions.appendChild(copyBtn);
      actions.appendChild(shareBtn);
      box.appendChild(actions);

      container.appendChild(box);
    });

    // ===== bottom buttons (لا تختفي) =====
    const actionBar = document.createElement('div');
    actionBar.style.display = 'flex';
    actionBar.style.gap = '10px';
    actionBar.style.flexWrap = 'wrap';
    actionBar.style.justifyContent = 'flex-start';
    actionBar.style.marginTop = '1rem';

    const checkBtn = document.createElement('a');
    checkBtn.className = 'btn btn-primary';
    checkBtn.textContent = 'Check Answers';
    checkBtn.href = '#result';
    checkBtn.addEventListener('click', (e)=>{ e.preventDefault(); checkAnswers(); });

    actionBar.appendChild(checkBtn);
    container.appendChild(actionBar);
  }

  // ===== favorites/copy/share (same) =====
  function favoriteQuestion(index) {
    const box = document.getElementsByClassName('feature-card')[index];
    const htmlContent = box.outerHTML;

    fetch('save_favorite.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'html=' + encodeURIComponent(htmlContent)
    })
    .then(res => res.text())
    .then(() => { showToast("The question has been added to favorites! ⭐"); })
    .catch(err => { console.error("Error saving favorite:", err); showToast("Error saving question ❌", "#F44336"); });
  }

  function copyQuestion(index) {
    const box = document.getElementsByClassName('feature-card')[index];
    const questionText = box.querySelector('h3').innerText;
    const answersDiv = box.querySelector('.answers');
    let answersText = '';
    if (answersDiv) {
      const labels = answersDiv.querySelectorAll('label');
      labels.forEach(label => {
        const span = label.querySelector('span');
        if (span) answersText += span.textContent + '\n';
      });
      const ta = answersDiv.querySelector('textarea');
      if (ta && ta.value) answersText += ta.value + '\n';
    }
    const text = questionText + '\n\n' + answersText;
    navigator.clipboard.writeText(text).then(() => {
      showToast("Question copied! 📋");
    }).catch(err => {
      console.error("Error copying question:", err);
      showToast("Error copying question ❌", "#F44336");
    });
  }

  function shareQuestion(index, platform) {
    const box = document.getElementsByClassName('feature-card')[index];
    const questionText = box.querySelector('h3').innerText;
    const answersDiv = box.querySelector('.answers');
    let answersText = '';
    if (answersDiv) {
      const labels = answersDiv.querySelectorAll('label');
      labels.forEach(label => {
        const span = label.querySelector('span');
        if (span) answersText += span.textContent + '\n';
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

  // ===== chart center text plugin (same idea) =====
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
    const cards = document.querySelectorAll('#quizContainer .feature-card');

    for(let i=0;i<selectedQuestions.length;i++){
      const q = selectedQuestions[i];
      const typeNorm = normTypeFromQuestion(q);

      let isCorrect = false;

      if (typeNorm === 'open') {
        const ta = document.querySelector(`textarea[name="q${i}_open"]`);
        const user = (ta ? ta.value : '').trim().toLowerCase();
        const corr = String(q.answer_text || '').trim().toLowerCase();
        if (corr && user && user === corr) {
          score++; isCorrect = true;
        }
      } else {
        const expected = Array.isArray(q.correct_letters) ? q.correct_letters.map(x=>String(x).trim().toUpperCase()).filter(Boolean) : [];
        // إذا ما عندنا expected واضح: نعتبرها غلط (عشان ما يصير “بدون إجابة = صح”)
        if (expected.length > 0) {
          if (typeNorm === 'multi') {
            const sels = Array.from(document.querySelectorAll(`input[name="q${i}"]:checked`))
              .map(el => String(el.value || '').trim().toUpperCase())
              .filter(Boolean);

            const aSet = new Set(sels);
            const bSet = new Set(expected);
            const equal = aSet.size === bSet.size && [...aSet].every(v => bSet.has(v));
            if (equal) { score++; isCorrect = true; }
          } else {
            const sel = document.querySelector(`input[name="q${i}"]:checked`);
            const v = sel ? String(sel.value || '').trim().toUpperCase() : '';
            if (v && v === expected[0]) { score++; isCorrect = true; }
          }
        }
      }

      // color card
      const box = cards[i];
      if (box) {
        box.style.background = isCorrect ? "rgba(0,255,0,0.2)" : "rgba(255,0,0,0.2)";
        box.style.transition = "0.3s";
      }
    }

    const total = selectedQuestions.length || 1;
    const percent = Math.round((score/total)*100);

    document.getElementById('result').innerHTML = `
      <div class="score-box animate" data-correct="${score}" data-wrong="${total - score}" data-total="${total}">
        <h2 class="score-title">Your Result</h2>
        <div class="score-values">
          <span class="score-main">${score} / ${total}</span>
        </div>
        <div class="tooltip">
          Correct: ${score}<br>
          Wrong: ${total - score}<br>
          Total: ${total}
        </div>
      </div>
      <canvas id="scoreChart" style="max-width:300px;margin:20px auto;display:block;"></canvas>
    `;
    (function() {
  const container = document.getElementById('result');
  if (!container) return;

  // Wrapper للأزرار
  const btnWrapper = document.createElement('div');
  btnWrapper.style.display = 'flex';
  btnWrapper.style.gap = '12px';
  btnWrapper.style.alignItems = 'center';
  btnWrapper.style.marginTop = '20px';
  btnWrapper.style.flexWrap = 'wrap';

  // ===== زر النسخ =====
  const copyBtn = document.createElement('button');
  copyBtn.title = "Copy result";
  copyBtn.innerHTML = " Copy 📄"; // أيقونة صفحتين متتالية
  copyBtn.style.background = "var(--gold-500)";
  copyBtn.style.color = "#1a1a1a";
  copyBtn.style.boxShadow = "var(--shadow-md)";
  copyBtn.style.fontWeight = "700";
  copyBtn.style.padding = ".75rem 1.1rem";
  copyBtn.style.borderRadius = ".8rem";
  copyBtn.style.border = "1px solid transparent";
  copyBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
  copyBtn.style.cursor = "pointer";
  copyBtn.style.transition = "transform .15s ease, box-shadow .15s ease, background .2s ease";
  copyBtn.onmouseover = () => { copyBtn.style.transform = "translateY(-2px)"; copyBtn.style.boxShadow = "var(--shadow-gold-hover)"; };
  copyBtn.onmouseout = () => { copyBtn.style.transform = "translateY(0)"; copyBtn.style.boxShadow = "var(--shadow-md)"; };

  copyBtn.onclick = () => {
    const scoreText = document.querySelector('.score-main') ? document.querySelector('.score-main').innerText : 'My Quiz Result';
    const shareText = `I scored ${scoreText} on this quiz! 🧠✨`;
    navigator.clipboard.writeText(`${shareText}\n${window.location.href}`);
    showToast("Result copied! 📋");
  };
  btnWrapper.appendChild(copyBtn);

  // ===== زر المشاركة =====
  const shareBtn = document.createElement('button');
  shareBtn.textContent = "Share 🔗";
  shareBtn.style.background = "var(--gold-500)";
  shareBtn.style.color = "#1a1a1a";
  shareBtn.style.boxShadow = "var(--shadow-md)";
  shareBtn.style.fontWeight = "700";
  shareBtn.style.padding = ".75rem 1.1rem";
  shareBtn.style.borderRadius = ".8rem";
  shareBtn.style.border = "1px solid transparent";
  shareBtn.style.cursor = "pointer";
  shareBtn.style.fontFamily = "'Noto Kufi Arabic', sans-serif";
  shareBtn.style.transition = "transform .15s ease, box-shadow .15s ease, background .2s ease";
  shareBtn.style.position = "relative";

  shareBtn.onmouseover = () => { shareBtn.style.transform = "translateY(-2px)"; shareBtn.style.boxShadow = "var(--shadow-gold-hover)"; };
  shareBtn.onmouseout = () => { shareBtn.style.transform = "translateY(0)"; shareBtn.style.boxShadow = "var(--shadow-md)"; };

  // نافذة أيقونات المشاركة
  const popup = document.createElement('div');
  popup.style.position = 'absolute';
  popup.style.bottom = '45px';
  popup.style.left = '0';
  popup.style.background = '#fff';
  popup.style.border = '1px solid #ddd';
  popup.style.borderRadius = '8px';
  popup.style.padding = '6px 10px';
  popup.style.display = 'none';
  popup.style.gap = '8px';
  popup.style.boxShadow = '0 4px 12px rgba(0,0,0,.15)';
  popup.style.flexWrap = 'nowrap';
  popup.style.display = 'flex';
  popup.style.zIndex = '100';

  const platforms = [
    { name: 'X', icon: 'https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/x.svg', id: 'x', color: '#1DA1F2' },
    { name: 'Facebook', icon: 'https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg', id: 'facebook', color: '#1877F2' },
    { name: 'WhatsApp', icon: 'https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/whatsapp.svg', id: 'whatsapp', color: '#25D366' }
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
    img.style.filter = ""; // إزالة أي فلتر أسود
    img.style.transition = 'transform 0.2s';
    img.onmouseover = () => img.style.transform = 'scale(1.2)';
    img.onmouseout = () => img.style.transform = 'scale(1)';

    a.appendChild(img);
    a.onclick = function(e) {
      e.preventDefault();
      const scoreText = document.querySelector('.score-main') ? document.querySelector('.score-main').innerText : 'My Quiz Result';
      const shareText = `I scored ${scoreText} on this quiz! 🧠✨`;
      const text = encodeURIComponent(shareText + "\n" + window.location.href);
      let url = '';
      if (p.id === 'x') url = `https://x.com/intent/tweet?text=${text}`;
      else if (p.id === 'facebook') url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}&quote=${text}`;
      else if (p.id === 'whatsapp') url = `https://api.whatsapp.com/send?text=${text}`;
      window.open(url, '_blank');
      popup.style.display = 'none';
    };
    popup.appendChild(a);
  });

  shareBtn.appendChild(popup);
  shareBtn.onclick = (e) => {
    e.stopPropagation();
    popup.style.display = popup.style.display === 'none' ? 'flex' : 'none';
  };

  document.addEventListener('click', (e) => {
    if (!shareBtn.contains(e.target)) popup.style.display = 'none';
  });

  btnWrapper.appendChild(shareBtn);
  container.appendChild(btnWrapper);
})();



    function loadChart(callback){
      if (window.Chart){ callback(); return; }
      const s = document.createElement("script");
      s.src = "https://cdn.jsdelivr.net/npm/chart.js";
      s.onload = callback;
      document.body.appendChild(s);
    }

    loadChart(() => {
      const ctx = document.getElementById("scoreChart");
      if (window.quizChart){ window.quizChart.destroy(); }

      window.quizChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: ["Correct", "Wrong"],
          datasets: [{
            data: [score, total - score],
            backgroundColor: ["#1A7F3C", "#e4645aff"],
            borderWidth: 2,
            hoverOffset: 10
          }],
          centerText: percent + "%"
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "bottom",
              labels: { font: {size: 14, family: "Noto Kufi Arabic"}, padding: 15 }
            },
            tooltip: {
              bodyFont: { family: "Noto Kufi Arabic" },
              titleFont: { family: "Noto Kufi Arabic" }
            }
          },
          cutout: "65%"
        },
        plugins: [centerTextPlugin]
      });
    });

    // save result
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
        if (resp.ok && data.success) showToast('Result saved to your profile ✅');
        else showToast('Could not save result to profile', '#F44336');
      } catch (e) {
        console.warn('save result failed', e);
        showToast('Could not save result to profile', '#F44336');
      }
    })();

    window.location.hash = '#result';
  }

  document.addEventListener('DOMContentLoaded', async () => {
    const startBtn = document.getElementById('startBtn');
    if (startBtn) startBtn.addEventListener('click', (e)=>{ e.preventDefault(); startQuiz(); });

    await populateTypeAndCategory();

    const regionSelectEl = document.getElementById('regionFilter');
    if (regionSelectEl) {
      regionSelectEl.addEventListener('change', async () => {
        await populateTypeAndCategory();
      });
    }
  });
  
</script>

 </body>
</html>
