<?php
// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login manually
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // CORRECT filename: Signup_Login_Form.html
    echo '<script>window.location.href = "../sign/Signup_Login_Form.html";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=../sign/Signup_Login_Form.html"></noscript>';
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
     <a class="brand" href="#top" aria-label="Back to top">Saudi Culture</a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <!-- Nav list -->
      <ul id="nav-links" class="nav-links">

     <li><a href="../sign/SignUp_LogIn_Form.html">Login</a></li>
      <li><a href="QUIZ-en.php">Quizzes</a></li>
          <li class="dropdown">
        <a class="dropbtn">Questions</a>
        <ul class="dropdown-content">
                      <li><a href="../General.html">General Questions</a></li>
                        <li><a href="../North.html">Northern Questions</a></li>
                        <li><a href="../South.html">Southern Questions</a></li>
                        <li><a href="../West.html">Western Questions</a></li>
                        <li><a href="../East.html">Eastern Questions</a></li>
                        <li><a href="../Central.html">Central Questions</a></li>
        </ul>
      </li>
     <li><a href="../index.html">Home</a></li>
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
 function showToast(message, bgColor = "#4CAF50") {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.style.background = bgColor; // يمكن تغيير اللون حسب نوع الرسالة
    toast.style.display = "block";
    toast.style.opacity = "1";
    
    // إخفاء التنبيه تدريجيًا بعد 3 ثواني
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
        html += `
          <div style="margin-top:.5rem">
            <textarea name="q${i}_open" placeholder="Write your answer here..." 
              style="width:100%;min-height:88px;padding:.5rem;border:1px solid #e0e0e0;border-radius:6px;font-size:1rem;font-family:inherit;resize:vertical"></textarea>
          </div>`;
      }

      html += `
        <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">


          <button onclick="favoriteQuestion(${i})" 
            style="padding:6px 12px; background:#ffb800; color:white; border:0; border-radius:6px; cursor:pointer;">
            Favorite ⭐ 
          </button>

          <button onclick="copyQuestion(${i})" 
            style="padding:6px 12px; background:#2196F3; color:white; border:0; border-radius:6px; cursor:pointer;">
           copy 📋 
          </button>

          <div style="display:flex; gap:5px; align-items:center;">
            <a href="#" onclick="shareQuestion(${i}, 'x'); return false;" title="Share on X">
              <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/x.svg" width="24" height="24" style="filter: invert(36%) sepia(97%) saturate(1595%) hue-rotate(176deg) brightness(93%) contrast(95%);"/>
            </a>
            <a href="#" onclick="shareQuestion(${i}, 'facebook'); return false;" title="Share on Facebook">
              <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg" width="24" height="24" style="filter: invert(29%) sepia(72%) saturate(900%) hue-rotate(182deg) brightness(90%) contrast(90%);"/>
            </a>
            <a href="#" onclick="shareQuestion(${i}, 'whatsapp'); return false;" title="Share on WhatsApp">
              <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/whatsapp.svg" width="24" height="24" style="filter: invert(49%) sepia(92%) saturate(510%) hue-rotate(95deg) brightness(93%) contrast(95%);"/>
            </a>
          </div>
        </div>
      `;

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
    checkBtn.style.marginRight = '10px';

    //Favorite button
    const favBtn = document.createElement('a');
    favBtn.className = 'btn btn-primary';
    favBtn.textContent = 'view Favorites ⭐';
    favBtn.href = 'favorites.php';
    favBtn.style.display = 'inline-block';
    favBtn.style.marginTop = '1rem';

    checkBtn.addEventListener('click', (e) => { 
      e.preventDefault(); 
      checkAnswers(); 
    });

    container.appendChild(checkBtn);
    container.appendChild(favBtn);
  }

  /* ▼▼▼ save the question as favorite ▼▼▼ */
  function favoriteQuestion(index) {
    const box = document.getElementsByClassName('feature-card')[index];
    const htmlContent = box.outerHTML;

    fetch('save_favorite.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'html=' + encodeURIComponent(htmlContent)
    })
    .then(res => res.text())
    .then(data => { showToast("The question has been added to favorites! ⭐"); })
    .catch(err => { console.error("Error saving favorite:", err); showToast("Error saving question ❌", "#F44336"); });

  }
  function deleteFavorite(favHtml) {
    if (!confirm("Are you sure you want to remove this question from favorites?")) return;

    fetch('delete_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'html=' + encodeURIComponent(favHtml)
    })
    .then(res => res.text())
    .then(msg => {
        showToast("Question removed from favorites ❌", "#F44336");
        // إعادة تحميل الصفحة لتحديث قائمة المفضلة
        setTimeout(() => { location.reload(); }, 500);
    })
    .catch(err => {
        console.error(err);
        showToast("Error removing question ❌", "#F44336");
    });
}

  /* ▲▲▲ favorite ▲▲▲ */

  /* copy */
  function copyQuestion(index) {
    const box = document.getElementsByClassName('feature-card')[index];
    const text = box.innerText;
    navigator.clipboard.writeText(text).then(() => {
    showToast("Question copied! 📋");
    }).catch(err => {
      console.error("Error copying question:", err);
      showToast("Error copying question ❌", "#F44336");
    });

  }

  /* Share */
  function shareQuestion(index, platform) {
    const box = document.getElementsByClassName('feature-card')[index];
    const text = encodeURIComponent(box.innerText + "\n" + window.location.href);
    let url = "";
    if(platform === 'x') url = `https://x.com/intent/tweet?text=${text}`;
    else if(platform === 'facebook') url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`;
    else if(platform === 'whatsapp') url = `https://api.whatsapp.com/send?text=${text}`;
    window.open(url, "_blank");
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

      //  color the question box based on correctness
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
        if (window.quizChart){ window.quizChart.destroy(); }
        window.quizChart = new Chart(ctx, {
            type: "pie",
            data: {
                labels: ["Correct", "Wrong"],
                datasets: [{ data: [score, total - score], backgroundColor: ["#4CAF50", "#F44336"] }]
            },
            options: { responsive: true, plugins: { legend: { position: "bottom" } } }
        });
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

    async function populateTypeFilter() {
      const regionSelect = document.getElementById('regionFilter');
      const typeSelect = document.getElementById('typeFilter');
      if (!typeSelect || !regionSelect) return;
      const src = regionSelect.value || 'Words';
      typeSelect.innerHTML = '';
      const optAll = document.createElement('option'); optAll.value = 'all'; optAll.textContent = 'All Types';
      typeSelect.appendChild(optAll);

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
        } catch (e) { }
      }

      if (types && types.length > 0) {
        for (const t of types) {
          const o = document.createElement('option'); o.value = t; o.textContent = t; typeSelect.appendChild(o);
        }
      } else {
        const labels = [{v:'mcq',t:'Multiple Choice'},{v:'multi',t:'Multiple Answers (Choose more than one)'},{v:'fill',t:'Fill in the Blank'}];
        for (const l of labels){ const o=document.createElement('option'); o.value=l.v; o.textContent=l.t; typeSelect.appendChild(o); }
      }
    }

    populateTypeFilter();
    const regionSelectEl = document.getElementById('regionFilter');
    if (regionSelectEl) regionSelectEl.addEventListener('change', () => populateTypeFilter());
  });
</script>

 </body>
</html>
