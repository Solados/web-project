<?php
// Start session before ANY output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>هوية | المساهمون</title>
  <meta name="description" content="تعرف على المساهمين في بناء موقع هوية وما قام به كل شخص.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
<a class="skip-link" href="#main">تخطي إلى المحتوى</a>

<header class="site-header">
  <nav class="navbar" aria-label="التنقل الرئيسي">
    <a class="brand" href="index-ar.php" aria-label="الصفحة الرئيسية">
      <img src="image/Hawiyah.png" alt="الشعار" class="site-logo">
    </a>

    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="فتح القائمة">☰</button>

    <ul id="nav-links" class="nav-links">
      <?php include 'sign/check_login_status.php'; ?>

      <li class="nav-search">
        <button class="search-toggle" type="button" aria-label="بحث" aria-expanded="false">🔍</button>
        <form class="nav-search-form" action="Search.php" method="get" role="search">
          <input type="search" name="q" placeholder="ابحث عن كلمة..." autocomplete="off">
          <button type="submit">بحث</button>
        </form>
      </li>

      <?php if ($LOGGED_IN): ?>
        <li class="dropdown">
          <a class="dropbtn">الملف الشخصي</a>
          <ul class="dropdown-content">
            <li><a href="dashboard.php">الملف الشخصي</a></li>
            <li><a href="Favorite.php">المفضلة</a></li>
            <li><a href="sign/check_session.php?logout=true" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">تسجيل الخروج</a></li>
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
      </li>

      <li><a href="index-ar.php">الرئيسية</a></li>
      <li><a href="Contributors.php" style="font-weight:700">English</a></li>
    </ul>
  </nav>
</header>

<main id="main" class="page-content">

  <section class="section section-intro">
    <div class="container">
      <div class="team-intro">
        <h1>المساهمون في بناء الموقع</h1>
        <p>
          هذا القسم يعرض أعضاء الفريق وأبرز ما قام به كل شخص أثناء تطوير موقع «هوية».
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="team-grid" aria-label="أعضاء الفريق">

        <!-- Member 1: Hisham -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Hisham.jpg" alt="صورة العضو: هشام آل مفرح">
        </div>

        <h2 class="member-name">هشام آل مفرح</h2>
        <p class="member-role">Frontend Developer & Data Presentation</p>

        <ul class="member-tasks">
            <li>رفع الموقع على الاستضافة وتجهيز تشغيله على الهوست.</li>
            <li>تحليل البيانات وتطوير طريقة عرضها في جميع صفحات الموقع.</li>
            <li>تحسين تجربة التنقل عبر تعديل ترتيب عناصر النافبار.</li>
            <li>تطوير شريط البحث وتحسين أدائه داخل الموقع.</li>
            <li>تطوير صفحة الكويز الإنجليزية.</li>
            <li>إضافة فلاتر لعرض البيانات في الصفحات الرئيسية.</li>
            <li>إنشاء صفحة المساهمين وتوثيق مساهمة كل عضو.</li>
            <li>دمج أسئلة المستخدمين ضمن عرض الأسئلة.</li>
            <li>تطوير صفحة المنطقة الجنوبية وتحديث صور السلايدر لتبرز المدن الرئيسية.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/HishamMufarreh" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/hisham-mufarreh" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:hs.mufarreh@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 2: Muhammed -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Muhammed.jpeg" alt="صورة العضو: محمد المسعودي">
        </div>

        <h2 class="member-name">محمد المسعودي</h2>
        <p class="member-role">UI Developer & Data Visualization</p>

        <ul class="member-tasks">
            <li>تحسين إحصائيات الاختبار وتوضيح عرض البيانات والألوان.</li>
            <li>تحسين تصميم صفحة الكويز وتطوير شكل الفلاتر لتصبح أكثر احترافية.</li>
            <li>تحسين تصميم صفحة تسجيل الدخول لتطابق ثيم الموقع.</li>
            <li>تطوير صفحة المنطقة الغربية وتحديث صور السلايدر لتبرز المدن الرئيسية.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/lLweesl" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="mailto:sasfe90@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 3: Mazen -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="صورة العضو: مازن الزهراني">
        </div>

        <h2 class="member-name">مازن الزهراني</h2>
        <p class="member-role">Frontend Developer & Responsiveness</p>

        <ul class="member-tasks">
            <li>تنفيذ تحسينات عامة للواجهة (توجيه الشعار للرئيسية ومعالجة مشاكل السلايدر).</li>
            <li>المساهمة في تطوير صفحة البروفايل وتحسين توافقها مع تصميم الموقع.</li>
            <li>تنقيح وتنظيم الكود وتحسين هيكلية المشروع.</li>
            <li>بناء الصفحات والمكونات الرئيسية لواجهة المستخدم.</li>
            <li>تحسين توافق الموقع مع مختلف أحجام الشاشات (Responsive).</li>
            <li>تطوير صفحة المنطقة الشمالية وتحديث صور السلايدر لتبرز المدن الرئيسية.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/Mazen-Alzahrani" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/%D9%85%D8%A7%D8%B2%D9%86-%D8%A7%D9%84%D8%B2%D9%87%D8%B1%D8%A7%D9%86%D9%8A-850889274/" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:mazenhmz10@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 4: Mansour -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="صورة العضو: منصور المقاطي">
        </div>

        <h2 class="member-name">منصور المقاطي</h2>
        <p class="member-role">Backend Developer & Deployment</p>

        <ul class="member-tasks">
            <li>ضبط صفحة الكويز وربط فلاتره بقاعدة البيانات.</li>
            <li>تطوير شات بوت للمساعدة داخل الموقع.</li>
            <li>إنشاء صفحة البروفايل وربطها بباقي أجزاء الموقع.</li>
            <li>إنشاء وتطوير صفحة تسجيل الدخول</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/Solados" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/manssor-almuqati-5414aa387/" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:manssorralmuqati@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 5: Anas -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="صورة العضو: أنس القايدي">
        </div>

        <h2 class="member-name">أنس القايدي</h2>
        <p class="member-role">Frontend Features & UX</p>

        <ul class="member-tasks">
            <li>تطوير أزرار التفاعل مع الأسئلة (المفضلة، المشاركة، النسخ).</li>
            <li>ربط الأسئلة المفضلة بصفحة مخصصة وتحسين تصميم صفحة المفضلة.</li>
            <li>دعم تعدد اللغات وتحسين تجربة التبديل بين اللغات.</li>
            <li>إضافة إحصائيات الاختبار.</li>
            <li>تطوير ميزة مشاركة نتائج الاختبار على منصات التواصل الاجتماعي.</li>
            <li>تطوير صفحة المنطقة الشرقية وتحديث صور السلايدر لتبرز المدن الرئيسية.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="https://github.com/Anas-dev11" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="https://www.linkedin.com/in/anas-alqaidi-7177a8218/" target="_blank" rel="noopener">LinkedIn</a>
            <a class="member-link" href="mailto:anas34rt@gmail.com">Gmail</a>
        </div>
        </article>

        <!-- Member 6: Abdulrahman -->
        <article class="member-card">
        <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Personal-photo.jpg" alt="صورة العضو: عبدالرحمن الحمدان">
        </div>

        <h2 class="member-name">عبدالرحمن الحمدان</h2>
        <p class="member-role">Branding & Localization</p>

        <ul class="member-tasks">
            <li>إضافة هوية الموقع وتوحيد العناصر البصرية بما يتوافق مع التصميم العام.</li>
            <li>المساهمة في دعم تعدد اللغات وتحسين تجربة عرض المحتوى.</li>
            <li>تطوير صفحة المنطقة الوسطى وتحديث صور السلايدر لتبرز المدن الرئيسية.</li>
        </ul>

        <div class="member-links">
            <a class="member-link" href="#" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="#" target="_blank" rel="noopener">LinkedIn</a>
        </div>
        </article>

      </div>
    </div>
  </section>

</main>

<footer class="site-footer" aria-label="تذييل الصفحة">
  <div class="container footer-grid">
    <div>
      <strong>هويّة</strong>
      <p>© 2025 جميع الحقوق محفوظة</p>
      <p class="footer-sources">
        المصادر:
        <a href="https://github.com/LamaAy/SaudiCulture-Dataset" target="_blank" rel="noopener noreferrer">SaudiCulture-Dataset</a>،
        مصادر أبشر:
        <a href="https://docs.google.com/spreadsheets/d/1-O91eSIvOUJEuSIDnHoaS21OHMnVc3anpw0jAVw_krs/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (كلمات)</a>،
        <a href="https://docs.google.com/spreadsheets/d/1nwVsA24SzxqITv_-jVQ_rQWxQ4eqpGJmIifyxZq2jsY/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (عبارات)</a>،
        <a href="https://docs.google.com/spreadsheets/d/1HAUXQnbA8L4dhFNEx-XQA67OeOaO5lpwX5RUMgC3swQ/edit?usp=sharing" target="_blank" rel="noopener noreferrer">أبشر (أمثال)</a>،
        <a href="https://www.absher.sa" target="_blank" rel="noopener noreferrer">موقع أبشر</a>.
      </p>
    </div>

    <ul class="footer-links">
      <li><a href="#main">العودة للأعلى</a></li>
    </ul>

    <!-- زر جديد بالأسفل بالمنتصف -->
    <div class="footer-cta">
      <a class="footer-contributors-btn" href="Contributors-ar.php">فريق العمل</a>
    </div>
  </div>
</footer>



<script src="assets/script.js"></script>
</body>
</html>
