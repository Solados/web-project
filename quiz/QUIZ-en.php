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
  <!-- Header -->
  <header class="site-header">
    <!-- Header -->
    <!-- Navigation -->
    <nav class="navbar" aria-label="Main navigation">
     <a class="brand" href="#top" aria-label="Back to top">Saudi Culture</a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <!-- Nav list -->
      <ul id="nav-links" class="nav-links">

     <?php if ($LOGGED_IN): ?>
    <li><a href="/dashboard.php">My Profile</a></li>
    <li><a href="/sign/check_session.php?logout=true" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
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
        <option value="food">Food</option>
        <option value="clothes">Clothes</option>
        <option value="celebration">Celebration</option>
        <option value="games">Entertainment</option>
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
  <script src="../assets/quiz-parser.js"></script>

  <!-- Quiz Script -->
  <script>
  /*
    Array of quiz questions.
    Each question object contains:
    - question: The question text
    - choices: Multiple-choice answers
    - answer: The correct answer
  */
  
  /*
    Function to shuffle array elements
    (Used to randomize question order and choice order)
  */
  function shuffle(arr){
    for(let i=arr.length-1;i>0;i--){
      const j = Math.floor(Math.random()*(i+1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
  }

  // Array to store randomly selected questions
  let selectedQuestions = [];

  async function startQuiz(){
    const count = Number(document.getElementById('questionCount').value) || 5;
    const regionSelect = document.getElementById('regionFilter');
    const regionValue = regionSelect ? regionSelect.value : 'Words';

    // helper list of known region CSVs (matches files under data/)
    const regionFiles = ['GENERAL','CENTERAL','NORTH','SOUTH','EAST','WEST'];

    // determine source to request from server
    let source = 'Words';
    if (regionValue === 'RANDOM') {
      source = regionFiles[Math.floor(Math.random()*regionFiles.length)];
    } else if (regionValue && regionValue !== 'Words') {
      source = regionValue;
    }

    const resultEl = document.getElementById('result');
    resultEl.innerHTML = 'Loading quiz...';
    const lang = document.getElementById('langSelect') ? document.getElementById('langSelect').value : 'ar';

    // read UI filters
    let type = document.getElementById('typeFilter') ? document.getElementById('typeFilter').value : 'all';
    // Map UI shorthand to CSV Question Type strings used in the data files
    function mapTypeFilter(val) {
      if (!val) return 'all';
      const v = String(val).toLowerCase();
      if (v === 'all') return 'all';
      if (v === 'mcq') return 'MCQ (one correct)';
      if (v === 'multi' || v === 'multiple') return 'MCQ (multiple correct)';
      if (v === 'fill' || v === 'open' || v === 'open-ended') return 'Open-ended';
      // allow passing exact CSV strings as well
      return val;
    }
    const mappedType = mapTypeFilter(type);
    const category = document.getElementById('categoryFilter') ? document.getElementById('categoryFilter').value : 'all';

    // try client-side CSV loader first (works without PHP)
    if (typeof fetchQuestions === 'function'){
      try{
          const clientQ = await fetchQuestions(source, count, lang, mappedType, category);
        if(Array.isArray(clientQ) && clientQ.length>0){
          selectedQuestions = clientQ.map(q => ({
            question: q.question,
            choices: Array.isArray(q.choices) ? q.choices.slice() : [],
            answer: q.answer || null
          }));
          selectedQuestions.forEach(q => shuffle(q.choices));
          displayQuestions();
          resultEl.innerHTML = '';
          window.location.hash = '#quiz';
          return;
        } else {
          resultEl.innerHTML = 'No questions returned from client parser, trying server...';
        }
      }catch(e){
        console.warn('client-side parser failed:', e);
      }
    }

    // try server-side endpoint first with a timeout
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 6000); // 6s timeout

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
            answer: q.answer || null
          }));
          // shuffle choices for each question
          selectedQuestions.forEach(q => shuffle(q.choices));
          displayQuestions();
          resultEl.innerHTML = '';
          window.location.hash = '#quiz';
          return;
        } else {
          resultEl.innerHTML = 'No questions returned from server, using local fallback.';
        }
      } else {
        resultEl.innerHTML = `Server returned ${resp.status}, using local fallback.`;
      }
    } catch (err) {
      clearTimeout(timeout);
      if (err.name === 'AbortError') {
        resultEl.innerHTML = 'Server request timed out (6s). Using local fallback.';
      } else {
        resultEl.innerHTML = 'Could not reach quiz endpoint. Using local fallback.';
      }
      console.warn('quiz.php fetch failed or timed out:', err);
    }

    // fallback to embedded questions when server-side generation fails or times out
    selectedQuestions = allQuestions.slice();
    shuffle(selectedQuestions);
    selectedQuestions = selectedQuestions.slice(0, count);
    selectedQuestions.forEach(q => shuffle(q.choices));
    displayQuestions();
    // clear any lingering loading text
    setTimeout(() => { if (resultEl && resultEl.innerHTML.startsWith('Loading')) resultEl.innerHTML = ''; }, 300);
    window.location.hash = '#quiz';
  }

  /*
    Displays the selected questions on the page
  */
  function displayQuestions(){
    const container = document.getElementById('quizContainer');
    container.innerHTML = '';

    selectedQuestions.forEach((q, i) => {
      const box = document.createElement('div');
      box.className = 'feature-card';

      // determine a short type label for the badge
      const inferredType = (q.type && q.type.trim()) ? q.type.trim() : (Array.isArray(q.choices) && q.choices.length > 0 ? 'MCQ' : 'Open-ended');
      let html = `<h3 dir="ltr">${i+1}. ${q.question} <span class="q-badge" style="font-size:.7rem;padding:.15rem .4rem;margin-left:.6rem;border-radius:4px;background:#efefef;color:#333;border:1px solid #ddd">${inferredType}</span></h3>`;

      // If the question has choices, render radios; otherwise render an open-answer textarea
      if (Array.isArray(q.choices) && q.choices.length > 0) {
        q.choices.forEach(choice => {
          html += `
          <label style="display:block;margin:.25rem 0">
            <input type="radio" name="q${i}" value="${choice}"> ${choice}
          </label>`;
        });
      } else {
        // open / fill-in-the-blank input
        html += `
          <div style="margin-top:.5rem">
            <textarea name="q${i}_open" placeholder="Write your answer here..." 
              style="width:100%;min-height:88px;padding:.5rem;border:1px solid #e0e0e0;border-radius:6px;font-size:1rem;font-family:inherit;resize:vertical"></textarea>
          </div>`;
      }

      box.innerHTML = html;
      container.appendChild(box);
    });

    // Add "Check Answers" button
    const checkBtn = document.createElement('a');
    checkBtn.className = 'btn btn-primary';
    checkBtn.textContent = 'Check Answers';
    checkBtn.href = '#result';
    checkBtn.style.display = 'inline-block';
    checkBtn.style.marginTop = '1rem';

    // Prevent default link behavior and check answers instead
    checkBtn.addEventListener('click', (e) => { 
      e.preventDefault(); 
      checkAnswers(); 
    });

    container.appendChild(checkBtn);
  }

  /*
    Checks the user's answers:
    - Counts correct responses
    - Calculates percentage
    - Colors the score based on performance
  */
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

      //  تلوين صندوق السؤال
      const box = document.getElementById("quizContainer").children[i];
      if (isCorrect){
        box.style.background = "rgba(0,255,0,0.2)";
      } else {
        box.style.background = "rgba(255,0,0,0.2)";
      }
      box.style.transition = "0.3s";
    }

    const total = selectedQuestions.length || 1;
    const percent = Math.round((score/total)*100);

    document.getElementById('result').innerHTML =
      `Your score: <strong>${score} / ${total} (${percent}%)</strong><br><br>
       <canvas id="scoreChart" style="max-width:300px;margin:0 auto;display:block;"></canvas>`;

    //  تحميل Chart.js تلقائياً إذا لم يكن موجود
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
                labels: ["Correct", "Wrong"],
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
const shareText = `I scored ${score} / ${total} (${percent}%) on the quiz! Try it yourself: ${window.location.href}`;
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

  /*
    Activates the Start button on page load
  */
  document.addEventListener('DOMContentLoaded', () => {
    const startBtn = document.getElementById('startBtn');
    if(startBtn) startBtn.addEventListener('click', (e) => { 
      e.preventDefault(); 
      startQuiz(); 
    });

    // Populate typeFilter dynamically using client discovery with server fallback
    async function populateTypeFilter() {
      const regionSelect = document.getElementById('regionFilter');
      const typeSelect = document.getElementById('typeFilter');
      if (!typeSelect || !regionSelect) return;
      const src = regionSelect.value || 'Words';
      // clear existing options
      typeSelect.innerHTML = '';
      const optAll = document.createElement('option'); optAll.value = 'all'; optAll.textContent = 'All Types';
      typeSelect.appendChild(optAll);

      let types = [];
      if (typeof fetchQuestionTypes === 'function') {
        try { types = await fetchQuestionTypes(src); } catch (e) { types = []; }
      }
      // server fallback
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
        for (const t of types) {
          const o = document.createElement('option'); o.value = t; o.textContent = t; typeSelect.appendChild(o);
        }
      } else {
        // fallback static options
        const labels = [{v:'mcq',t:'Multiple Choice'},{v:'multi',t:'Multiple Answers (Choose more than one)'},{v:'fill',t:'Fill in the Blank'}];
        for (const l of labels){ const o=document.createElement('option'); o.value=l.v; o.textContent=l.t; typeSelect.appendChild(o); }
      }
    }

    // run on load and when region changes
    populateTypeFilter();
    const regionSelectEl = document.getElementById('regionFilter');
    if (regionSelectEl) regionSelectEl.addEventListener('change', () => populateTypeFilter());
  });
  </script>

 </body>
</html>
