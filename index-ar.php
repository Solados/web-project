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
    <title>هويّة | الصفحة الرئيسية</title>
    <meta name="description" content="اكتشف العادات والتقاليد ومناطق المملكة العربية السعودية.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/styles.css">

    <style>
  /* RTL overrides */
  html, body { direction: rtl; }
  .navbar { direction: rtl;}
  .nav-links { direction: rtl; }
  .dropdown-content { text-align: right; right: 0; left: auto; }

  /* Hero video and overlays (shared with English page) */
  .video-wrap{position:relative;overflow:hidden;background:#000}
  .video-wrap video{width:100%;height:auto;display:block;max-height:60vh;object-fit:cover}
  .video-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none}
  .video-welcome{color:#c59d1f;font-size:clamp(1.6rem,3vw,3rem);font-weight:700;text-shadow:0 6px 18px rgba(0,0,0,0.45);opacity:0;transform:translateY(10px);transition:opacity .8s ease,transform .8s ease}
  .video-welcome.visible{opacity:1;transform:translateY(0)}
  #video-toggle-ar{position:absolute;right:1rem;bottom:1rem;background:rgba(20,20,20,0.6);color:#fff;border:0;padding:.5rem .6rem;border-radius:6px;cursor:pointer}

  /* Small cards and grids (reuse existing classes where possible) */
  .overview-card{background:linear-gradient(180deg,#fffafa,#fffaf0);padding:1.25rem;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06);max-width:900px;margin:0 auto}
  .overview-card{transition:transform .18s ease,box-shadow .18s ease}
  .features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1rem;margin-top:1rem}
  .feature-card{background:#fff;padding:1.25rem;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.05);transition:transform .18s ease,box-shadow .18s ease;min-height:220px;display:flex;flex-direction:column;gap:0.75rem}
  .feature-card:hover{transform:translateY(-6px);box-shadow:0 12px 30px rgba(0,0,0,0.08)}
  .feature-card{cursor:pointer}
  .stat-card{background:linear-gradient(90deg,#f7e9cd,#fff9ef);padding:1.5rem;border-radius:14px;text-align:center;font-weight:700;box-shadow:0 8px 26px rgba(0,0,0,0.06)}
  .stat-card{transition:transform .18s ease,box-shadow .18s ease}
  .regions-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-top:1rem}
  .region-card{background:#fff;padding:1.25rem;border-radius:12px;display:flex;flex-direction:column;justify-content:space-between;min-height:220px;transition:transform .18s ease,box-shadow .18s ease}
  .explore-btn{background:#c59d1f;color:#fff;padding:.5rem .75rem;border-radius:8px;border:0;cursor:pointer;text-decoration:none;display:inline-block}

  /* Golden icon box */
  .icon-box{width:72px;height:72px;background:#d4af37;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform .18s ease,box-shadow .18s ease}
  .icon-box .icon{font-size:28px}

  /* Region images: full-width, no golden background, responsive height */
  .region-card .icon-box{background:transparent;width:100%;height:auto;border-radius:8px;padding:0;align-items:center;justify-content:center}
  .region-card .icon-box .region-img{width:100%;height:120px;object-fit:cover;display:block;border-radius:8px}
  @media(min-width:992px){ .region-card .icon-box .region-img{height:140px} }
  @media(max-width:480px){ .region-card .icon-box .region-img{height:100px} }

  .sr-hidden{opacity:0;transform:translateY(12px);transition:opacity .6s ease,transform .6s ease}
  .sr-visible{opacity:1;transform:none}
  .stat-card:hover{transform:translateY(-6px);box-shadow:0 12px 30px rgba(0,0,0,0.08)}
  .region-card:hover{transform:translateY(-6px);box-shadow:0 12px 30px rgba(0,0,0,0.08)}
  .overview-card:hover{transform:translateY(-6px);box-shadow:0 12px 30px rgba(0,0,0,0.08)}

  /* Slide-in helpers (reusable) */
  .slide-in-left{transform:translateX(-24px);opacity:0}
  .slide-in-right{transform:translateX(24px);opacity:0}
  .slide-in-up{transform:translateY(18px);opacity:0}
  .slide-in-left.sr-visible,
  .slide-in-right.sr-visible,
  .slide-in-up.sr-visible{transform:none;opacity:1;transition:transform .9s cubic-bezier(.2,.9,.2,1),opacity .9s cubic-bezier(.2,.9,.2,1)}
  </style>
</head>

<body class="rtl">

<!-- Header -->
<header class="site-header">
  <nav class="navbar">

   <a class="brand" href="index-ar.php">
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

      <!-- زر تغيير اللغة -->

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

<?php if (!$LOGGED_IN): ?>
    <li><a href="/sign/Signup_LogIn_Form_ar.html">تسجيل الدخول</a></li>
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
      
      <li><a href="index.php" style="font-weight:700">English</a></li>

    </ul>

  </nav>

</header>

  <!-- Main content -->
  <main id="main">

    <!-- Hero video -->
    <section class="section video-section">
      <div class="video-wrap">
        <video id="hero-video-ar" autoplay muted loop playsinline>
          <source src="video/Video Project1111.mp4" type="video/mp4">
          متصفحك لا يدعم الفيديو.
        </video>
        <div class="video-overlay">
          <h1 class="video-welcome" id="video-welcome-ar">مرحباً بكم في السعودية</h1>
        </div>
        <button id="video-toggle-ar" aria-pressed="false" aria-label="إيقاف الفيديو">إيقاف</button>
      </div>
    </section>

    <!-- Overview card -->
    <section id="overview" class="section section-intro">
      <div class="container">
        <div class="overview-card sr-hidden">
          <h2>ماذا تقدم منصة هويّة</h2>
          <p>منصة تتيح الوصول إلى آلاف الأسئلة والاختبارات التفاعلية لاستكشاف الثقافة السعودية والتقاليد والمناطق.</p>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section id="features" class="section">
      <div class="container">
        <div class="features-grid">
          <article class="feature-card sr-hidden">
            <div class="icon-box"><div class="icon">📚</div></div>
            <h3>آلاف الأسئلة</h3>
            <p>قاعدة أسئلة واسعة تغطي اللغة واللهجات والعادات والتقاليد.</p>
          </article>
          <article class="feature-card sr-hidden">
            <div class="icon-box"><div class="icon">🧩</div></div>
            <h3>اختبارات تفاعلية</h3>
            <p>اختبارات مرنة بعدد أسئلة قابل للاختيار وتحديد نوع وفئة الاسئلة.</p>
          </article>
          <article class="feature-card sr-hidden">
            <div class="icon-box"><div class="icon">📊</div></div>
            <h3>إحصاءات تفصيلية</h3>
            <p>عرض النتائج بعد كل اختبار ومتابعة التقدم في ملف المستخدم.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- Large stat card -->
    <section class="section">
      <div class="container">
        <div class="stat-card sr-hidden">
          <div class="stat-value" id="question-count-ar">أكثر من 10,000 سؤال متاح على المنصة.</div>
        </div>
      </div>
    </section>

    <!-- Regions preview -->
    <section id="regions" class="section">
      <div class="container">
        <div class="regions-grid">
          <div class="region-card sr-hidden">
            <div>
                <div class="icon-box"><img src="image/Flag.jpg" alt="أسئلة عامة" class="region-img"></div>
              <h4>أسئلة عامة</h4>
              <p>أسئلة عامة حول الثقافة والتاريخ السعودي.</p>
            </div>
            <a class="explore-btn" href="General-ar.php">استكشف</a>
          </div>
          <div class="region-card sr-hidden">
            <div>
                <div class="icon-box"><img src="image/North.jpg" alt="المنطقة الشمالية" class="region-img"></div>
              <h4>أسئلة المنطقة الشمالية</h4>
              <p>أسئلة متعلقة بالالمنطقة الشمالية.</p>
            </div>
            <a class="explore-btn" href="North-ar.php">استكشف</a>
          </div>
          <div class="region-card sr-hidden">
            <div>
                <div class="icon-box"><img src="image/South.jpeg" alt="المنطقة الجنوبية" class="region-img"></div>
              <h4>أسئلة المنطقة الجنوبية</h4>
              <p>أسئلة متعلقة بالمنطقة الجنوبية.</p>
            </div>
            <a class="explore-btn" href="South-ar.php">استكشف</a>
          </div>
          <div class="region-card sr-hidden">
            <div>
                <div class="icon-box"><img src="image/East.jpeg" alt="المنطقة الشرقية" class="region-img"></div>
              <h4>أسئلة المنطقة الشرقية</h4>
              <p>أسئلة متعلقة بالمنطقة الشرقية.</p>
            </div>
            <a class="explore-btn" href="East-ar.php">استكشف</a>
          </div>
          <div class="region-card sr-hidden">
            <div>
                <div class="icon-box"><img src="image/West.jpeg" alt="المنطقة الغربية" class="region-img"></div>
              <h4>أسئلة المنطقة الغربية</h4>
              <p>أسئلة متعلقة بالمنطقة الغربية.</p>
            </div>
            <a class="explore-btn" href="West-ar.php">استكشف</a>
          </div>
          <div class="region-card sr-hidden">
            <div>
                <div class="icon-box"><img src="image/Central.jpeg" alt="المنطقة الوسطى" class="region-img"></div>
              <h4>أسئلة المنطقة الوسطى</h4>
              <p>أسئلة متعلقة بالمنطقة الوسطى.</p>
            </div>
            <a class="explore-btn" href="Central-ar.php">استكشف</a>
          </div>
        </div>
      </div>
    </section>

    <!-- container where question cards render (preserve for JS) -->
    <section>
      <div class="container">
        <div class="features"></div>
      </div>
    </section>

  </main>
  
<!-- Footer -->
  <footer class="site-footer" aria-label="footer">
   <div class="container footer-grid">
    <div>
     <strong>هويّة</strong>
     <p>© 2025 جميع الحقوق محفوظة</p>
        <p class="footer-sources">المصادر: <a href="https://github.com/LamaAy/SaudiCulture-Dataset" target="_blank" rel="noopener noreferrer">SaudiCulture-Dataset</a>، مصادر أبشر: <a href="https://docs.google.com/spreadsheets/d/1-O91eSIvOUJEuSIDnHoaS21OHMnVc3anpw0jAVw_krs/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (كلمات)</a>، <a href="https://docs.google.com/spreadsheets/d/1nwVsA24SzxqITv_-jVQ_rQWxQ4eqpGJmIifyxZq2jsY/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (عبارات)</a>، <a href="https://docs.google.com/spreadsheets/d/1HAUXQnbA8L4dhFNEx-XQA67OeOaO5lpwX5RUMgC3swQ/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (أمثال)</a>، <a href="https://www.absher.sa" target="_blank" rel="noopener noreferrer">موقع أبشر</a>.</p>
    </div>
    <ul class="footer-links">
     <li><a href="#main">العودة للأعلى</a></li>
    </ul>
   </div>
  </footer>

   <!-- Scripts -->
  <script src="assets/script.js"></script>

  <script>

// small video toggle + reveal logic for Arabic page
(function(){
  const vid = document.getElementById('hero-video-ar');
  const btn = document.getElementById('video-toggle-ar');
  const welcome = document.getElementById('video-welcome-ar');
  if(welcome) setTimeout(()=>welcome.classList.add('visible'),200);
  if(btn && vid){
    btn.addEventListener('click', ()=>{
      if(vid.paused){ vid.play(); btn.textContent='إيقاف'; btn.setAttribute('aria-pressed','false'); }
      else{ vid.pause(); btn.textContent='تشغيل'; btn.setAttribute('aria-pressed','true'); }
    });
  }

  // setup stagger index
  document.querySelectorAll('.sr-hidden').forEach((el, idx) => el.dataset.srIndex = idx);

  const srObserver = new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
      if(!entry.isIntersecting) return;
      const el = entry.target;
      const rect = el.getBoundingClientRect();
      const cx = rect.left + rect.width/2;
      const vw = window.innerWidth;
      const centerMargin = vw * 0.12;
      let dir = 'up';
      if (cx < vw/2 - centerMargin) dir = 'left';
      else if (cx > vw/2 + centerMargin) dir = 'right';
      el.classList.add('slide-in-' + dir);
      const baseDelay = 80;
      const idx = parseInt(el.dataset.srIndex || 0, 10);
      const delay = Math.min(8, idx) * baseDelay + (idx % 3) * 30;
      setTimeout(()=>{ el.classList.add('sr-visible'); el.classList.remove('sr-hidden'); }, delay);
      srObserver.unobserve(el);
    });
  },{threshold:0.15});
  document.querySelectorAll('.sr-hidden').forEach(el=>srObserver.observe(el));
})();
</script>

<script>
// Arabic page local: video toggle + scroll reveal
(function(){
  const vid = document.getElementById('hero-video-ar');
  const btn = document.getElementById('video-toggle-ar');
  const welcome = document.getElementById('video-welcome-ar');
  if(welcome) setTimeout(()=>welcome.classList.add('visible'),200);
  if(btn && vid){
    btn.addEventListener('click', ()=>{
      if(vid.paused){ vid.play(); btn.textContent='إيقاف'; btn.setAttribute('aria-pressed','false'); }
      else{ vid.pause(); btn.textContent='تشغيل'; btn.setAttribute('aria-pressed','true'); }
    });
  }
  const io = new IntersectionObserver((entries)=>{entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('sr-visible'); e.target.classList.remove('sr-hidden'); } });},{threshold:0.15});
  document.querySelectorAll('.sr-hidden').forEach(el=>io.observe(el));
})();
</script>

<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="PkSRl6nFY3Csgenh8koIS";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>


    </body>
</html>